var map = null;
var driveway_markers = [];

make_request_url = "seller_setup_action.php";

/* Load */
function load_map() {
    map = new google.maps.Map($("#map").get(0), {
        center: new google.maps.LatLng(0, 0),
        zoom: 6
    });
    load_driveways();
}

function load_driveways() {
    make_request("driveway_load", null, function (data) {
        display_driveways(data[0]);
    });
}

function load_stripe() {
    make_request("stripe_load", null, function (data) {
        display_stripe(data[0][0]);
    });
}

/* Display */
function display_driveways(driveways) {
    $("#driveway_tbody tr").remove();
    for (var i = 0; i < driveways.length; i++) {
        var driveway = driveways[i];
        driveway_markers[i] = new google.maps.Marker({
            position: new google.maps.LatLng(driveway.lat, driveway.lng),
            map: map
        });

        var row = $("<tr></tr>");
        row.attr({
            "data-id": driveway.id,
            "data-line1": driveway.line1,
            "data-line2": driveway.line2,
            "data-city": driveway.city,
            "data-state": driveway.state,
            "data-zipcode": driveway.zipcode,
            "data-lat": driveway.lat,
            "data-lng": driveway.lng,
            "data-num_spots": driveway.num_spots,
            "data-image_path": driveway.image_path
        });

        var image_col = "<td><img class='img-rounded' height='100px' width='100px' src='" + ((driveway.image_path == null) ? "_images/driveway_default.png" : driveway.image_path) + "'/></td>";
        var address_col = '<td class="address-col"><h3><strong class="text-primary">' + driveway['line1'] + " " + driveway['line2'] + '</strong></h3><h3><small>' + driveway['city'] + ", " + driveway['state'] + " " + driveway['zipcode'] + '</small></h3>';
        var delete_button_col = "<td><button class='btn btn-danger delete-driveway'><i class='fa fa-trash'></i></button></td>";
        var edit_button_col = "<td><button class='btn btn-primary edit-driveway'><i class='fa fa-pencil'></i></button></td>";

        row.append(image_col).append(address_col).append(edit_button_col).append(delete_button_col);
        $("#driveway_tbody").append(row);
    }

    //Adjust map to make all markers visible:
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < driveway_markers.length; i++) {
        bounds.extend(driveway_markers[i].getPosition());
    }
    map.fitBounds(bounds);
}

function display_stripe(stripe) {
    if (stripe) {
        console.log(stripe);
        $("#createStripeAccountButton").hide();
        $("#stripe_account_id").text(stripe.stripe_account_id);
    }
}

/* Events */
function on_click_driveway_add_button() {
    $("#driveway_add_modal input").val("");
    $("#driveway_add_modal").modal("show");
}

function on_click_driveway_add_confirm_button() {
    $("#driveway_add_modal").modal("hide");
    make_request("driveway_create", {
        lat: $("#driveway_add_lat").val(),
        lng: $("#driveway_add_lng").val(),
        line1: $("#driveway_add_line1").val(),
        line2: $("#driveway_add_line2").val(),
        city: $("#driveway_add_city").val(),
        state: $("#driveway_add_state").val(),
        zipcode: $("#driveway_add_zipcode").val(),
        num_spots: $("#driveway_add_num_spots").val()
    }, function () {
        load_driveways();
    });
}

function on_click_driveway_edit_button() {
    var $row = $(this).closest("tr");
    $("#driveway_edit_id").val($row.data("id"));
    $("#driveway_edit_num_spots").val($row.data("num_spots"));

    var image_path = $row.data("image_path");
    if (image_path == null) {
        image_path = "_images/driveway_default.png";
    }

    $("#driveway_edit_image_display").attr("src", image_path);
    $("#driveway_edit_modal").modal("show");
}

function on_click_driveway_edit_confirm_button() {
    $("#driveway_edit_modal").modal("hide");
    make_request("driveway_update", {
        driveway_id: $("#driveway_edit_id").val(),
        num_spots: $("#driveway_edit_num_spots").val(),
    }, function (data) {
        load_driveways();
    });
}

function on_click_delete_driveway_button() {
    make_request("driveway_delete", {
        driveway_id: $(this).closest("tr").data("id")
    }, function () {
        load_driveways();
    });
}

function on_click_edit_driveway_upload_image_button() {
    $("#driveway_edit_image").focus().trigger("click");
}

function on_driveway_edit_image_upload_complete() {
    var driveway_image_upload_form = new FormData($('#driveway_edit_image_upload_form')[0]);
    driveway_image_upload_form.append("driveway_id", $("#driveway_edit_id").val());
    make_request_file_upload("driveway_image_upload", driveway_image_upload_form, function (data) {
        $("#driveway_edit_image_display").attr("src", data[0][0].image_path);
    }, function (data) {
        alert("nope");
    });
}

function onStripeAccountButtonClicked() {
    make_request("stripe_create", null, function(data) {
        display_stripe(data[0][0]); 
    });
}

$(document).ready(function () {
    //Bind event handlers:
    $("#add_driveway").on("click", on_click_driveway_add_button);
    $("#driveway_add_confirm_button").on("click", on_click_driveway_add_confirm_button);

    $("#driveway_tbody").on("click", ".edit-driveway", on_click_driveway_edit_button);
    $("#driveway_edit_confirm_button").on("click", on_click_driveway_edit_confirm_button);

    $("#driveway_tbody").on("click", ".delete-driveway", on_click_delete_driveway_button);

    $("#driveway_edit_image_upload_button").on("click", on_click_edit_driveway_upload_image_button);
    $("#driveway_edit_image").on("change", on_driveway_edit_image_upload_complete);

    $("#createStripeAccountButton").on("click", onStripeAccountButtonClicked);

    //Initial Load:
    load_stripe();
});
