var WALK_MIN_PER_KM = 12;

var map,
    current_info_window,
    offerings_markers = [],
    stadium_marker,
    user_marker;

var price_filter, distance_filter;

var offerings_table;

make_request_url = "buy_action.php";

/* Load */
function load_map() {
    map = new google.maps.Map($("#map").get(0), {
        zoom: 12
    });

    current_info_window = new google.maps.InfoWindow({
        content: "Nothing to show..."
    });

    stadium_marker = new google.maps.Marker({
        icon: "_images/stadium_icon.png",
        map: map
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            load_closest_stadium(position.coords.latitude, position.coords.longitude);

            user_marker = new google.maps.Marker({
                position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                icon: "_images/marker_blue.png",
                map: map
            });
            map.setCenter(user_marker.position);
            $("#geolocation_message").css("visibility", "visible");
            $("#map_loader").hide();

        }, function () {
            $("#map_loader").hide();
            //TODO: set message when geolocation is disabled.
        });
    } else {
        $("#map_loader").hide();
    }
}

function load_closest_stadium(lat, lng) {
    make_request("load_closest_stadium", {
        user_lat: lat,
        user_lng: lng
    }, function (data) {
        $("#stadium_search").val(data[0][0].name);
        on_change_stadium(data[0][0]);
    }, function () {
        console.log("Couldn't load closest stadium");
    });
}

function load_events(stadium_id, success = null, error = null) {
    make_request("load_events", {
        stadium_id: stadium_id
    }, function (data) {
        display_events(data[0]);
        if (success) {
            success(data);
        }
        load_offerings(data[0][0].id);
    }, function () {
        if (error) {
            error(data);
        }
    });
}

function load_offerings(event_id, max_distance, max_price, success, error) {
    "use strict";
    make_request("load_offerings", {
        event_id: event_id,
        max_distance: max_distance,
        max_price: max_price
    }, function (data) {
        display_offerings(data[0]);
        if (success) {
            success(data);
        }
    }, function () {
        if (error) {
            error(data);
        }
    });
}

function display_events(events) {
    "use strict";
    $("#event_select option").remove();
    for (var i = 0; i < events.length; i += 1) {
        var event = events[i];
        $("#event_select").append("<option value='" + event.id + "'>" + event.name + "</option>");
    }
}

function display_offerings(offerings) {
    offerings_table.clear();
    $("#offerings_tbody tr").remove();

    for (var i = 0; i < offerings_markers.length; i++) {
        offerings_markers[i].setMap(null);
    }
    offerings_markers = [];

    var stadium_lat = $("#stadium_select option:selected").data("lat"),
        stadium_lng = $("#stadium_select option:selected").data("lng");

    for (var i = 0; i < offerings.length; i++) {
        var offering = offerings[i];
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(offering.lat, offering.lng),
            map: map
        });

        var driveway_image_path = (offering.driveway_image_path == null) ? "_images/driveway_default.png" : offering.driveway_image_path;

        marker.info = new google.maps.InfoWindow({
            content: '<div class="info-window-no-scroll">' +
                '<div class="row">' +
                '<div class="col-md-3">' +
                '<img class="img-rounded" height="100" width="100" src="' + driveway_image_path + '"/>' +
                '</div>' +
                '<div class="text-center col-md-7 col-md-offset-2">' +
                '<h4 class="text-primary"><strong>' + offering.line1 + '</strong></h4>' +
                '<h5>' + Math.floor(WALK_MIN_PER_KM * offering.distance_to_stadium) + ' min. walk | $' + Math.floor(offering.price / 100) + '</h5>' +
                '<h6 class="info-window-owner"><i class="fa fa-user"></i>&ensp;<span class="text-info">' + offering.name + '</span></h6>' +
                '</div>' +
                '</div>' +
                '</div>'
        });
        marker.addListener('click', display_info_window);
        offerings_markers.push(marker);

        //var time_col = "<td>" + offering.start_time + " to " + offering.end_time + "</td>";v
        var map_link_col = "<td><a href='#' class='map-link' data-marker-index=" + i + "><i class='fa fa-2x fa-map-marker show-on-hover'></i></a></td>";
        var address_col = "<td>" + offering.line1 + "</td>";
        var owner_col = "<td>" + offering.name + "</td>";
        var price_col = "<td><span class='label label-success'>" + "$" + Math.floor(offering.price / 100) + "</span></td>";
        var distance_to_stadium_col = "<td><span class='label label-primary'>" + offering.distance_to_stadium + " km </span></td>";
        var buy_button_col = "<td><button class='btn btn-sm btn-primary show-on-hover make_transaction_btn' data-group-id='" + offering.id + "'>BUY&nbsp;<i class='glyphicon glyphicon-arrow-right'></i></button></td>";
        offerings_table.row.add([
            map_link_col,
            address_col,
            owner_col,
            distance_to_stadium_col,
            price_col,
            buy_button_col
        ]);
    }
    offerings_table.draw(false);
}

function display_info_window() {
    "use strict";
    if (current_info_window) {
        current_info_window.close();
    }
    current_info_window = this.info;
    current_info_window.open(map, this);
}

/* Events */
function on_change_stadium(stadium) {
    load_events(stadium.id);
    var new_latlng = new google.maps.LatLng(stadium.lat, stadium.lng);
    stadium_marker.setPosition(new_latlng);
    stadium_marker.setTitle(stadium.name);
    map.setCenter(new_latlng);
}

function on_change_event() {
    load_offerings($("#event_select").val());
}

function on_click_make_transaction_button() {
    var group_id = $(this).data("group-id");
    make_request("make_reservation", {
        group_id: group_id
    }, function (data) {
        var reservation_info = data[0][0];
        if (reservation_info.reserve_successful != "false") {
            window.location.href = "make_transaction.php?offering_id=" + reservation_info.offering_id;
        } else {
            $("#reservation_failed_message").text("Reservation unsuccessful");
            $("#reservation_failed_alert").show();
            load_offerings($("#event_select option:selected").val(), distance_filter.slider('getValue'), 100 * price_filter.slider('getValue'));
        }
    });
}

function on_slide_stop_filter() {
    load_offerings($("#event_select option:selected").val(), distance_filter.slider('getValue'), 100 * price_filter.slider('getValue'));
}

function on_offerings_table_search_keyup() {
    var search_string = $(this).val();
    offerings_table.search(search_string).draw();
}

function on_click_map_link(e) {
    e.preventDefault();
    var marker = offerings_markers[$(this).data("marker-index")];
    var trigger = new google.maps.event.trigger(marker, 'click');
}

function on_offerings_table_draw() {
    for (var i = 0; i < offerings_markers.length; i++) {
        offerings_markers[i].setMap(null);
    }

    offerings_table.rows({
        "filter": "applied"
    }).nodes().to$().each(function (index) {
        offerings_markers[parseInt($(this).find(".map-link").data("marker-index"))].setMap(map);
    });
}

/* Page load */
$(document).ready(function () {

    /* Set up data table */
    offerings_table = $('#offerings_table').DataTable({
        "info": false,
        "paging": false,
        "dom": "lrtip"
    });


    /* Set up sliders */
    price_filter = $('#price_filter').slider({
        formatter: function (value) {
            return "$" + value + " or less";
        }
    });
    distance_filter = $('#distance_filter').slider({
        formatter: function (value) {
            return value + " km. or less";
        }
    });


    /* Bind event handlers */
    $("#stadium_select").on("change", on_change_stadium); //TODO respond to search select event.

    $("#stadium_search").autocomplete({
        lookup: function (query, done) {
            make_request("load_stadiums", {
                filter: query
            }, function (data) {
                var stadiums = data[0];
                var result = {
                    suggestions: []
                };
                for (var i = 0; i < stadiums.length; i++) {
                    result.suggestions.push({
                        value: stadiums[i].name,
                        data: stadiums[i]
                    });
                }
                console.log(result);
                done(result);
            });
        },
        onSelect: function (suggestion) {
            on_change_stadium(suggestion.data);
        }
    });

    $("#event_select").on("change", on_change_event);

    $("#offerings_tbody").on("click", ".map-link", on_click_map_link);
    $("#offerings_tbody").on("click", ".make_transaction_btn", on_click_make_transaction_button);

    price_filter.on('slideStop', on_slide_stop_filter);
    distance_filter.on('slideStop', on_slide_stop_filter);
    $("#offerings_table_search").on('keyup', on_offerings_table_search_keyup);

    offerings_table.on('draw.dt', on_offerings_table_draw);
});
