/*TODO
    Update price, num_spots
*/

make_request_url = "sell_action.php";

var EVENT_FADE_SPEED = 1000;

/* Load */
function load_all() {
    load_driveways(function (data) {
        var driveways = data[0];
        for (var i = 0; i < driveways.length; i++) {
            load_stadiums(driveways[i].id, function (data) {
                var driveway_id = data[0][0].driveway_id;
                var stadiums = data[1];
                for (var i = 0; i < stadiums.length; i++) {
                    load_events(driveway_id, stadiums[i].id);
                }
            });
        }
    });
}

function load_driveways(success, error) {
    make_request("load_driveways", null, function (data) {
        display_driveways(data[0]);
        if (success) {
            success(data);
        }
    }, function (data) {
        if (error) {
            error(data);
        }
    });
}

function load_stadiums(driveway_id, success, error) {
    make_request("load_stadiums", {
        driveway_id: driveway_id
    }, function (data) {
        display_stadiums(data[0][0].driveway_id, data[1]);
        if (success) {
            success(data);
        }
    }, function (data) {
        if (error) {
            error(data);
        }
    });
}

function load_events(driveway_id, stadium_id, success, error) {
    make_request("load_events", {
        stadium_id: stadium_id,
        driveway_id: driveway_id,
        is_upcoming: ($("#archive_toggle").prop("checked")) ? 1 : 0
    }, function (data) {
        display_events(data[0][0].driveway_id, data[0][0].stadium_id, data[1]);
        if (success) {
            success(data);
        }
    }, function (data) {
        if (error) {
            error(data);
        }
    });
}

function load_event_details(group_id) {
    make_request("load_event_details", {
        group_id: group_id
    }, function (data) {
        display_event_details(data[0][0], data[1]);
    });
}

/* Display */
function display_driveways(driveways) {
    if (driveways.length == 0) {
        $("#property_warning").show();
    }

    $("#offerings_tbody tr").remove();
    $.each(driveways, function (index, driveway) {
        $("#offerings_tbody").append(
            $("<tr></tr>").append(
                $("<td></td>", {
                    "class": "text-center"
                }).append(
                    $("<h3></h3>", {
                        "class": "text-primary text-bold",
                        "html": driveway.line1 + " " + driveway.line2 + "<small>" + driveway.city + ", " + driveway.state + " " + driveway.zipcode + "</small>"
                    }).append()
                ).append(
                    $("<img/>", {
                        "class": "img-rounded top-padded",
                        "height": 180,
                        "width": 180,
                        "src": (driveway.image_path == null) ? "_images/driveway_default.png" : driveway.image_path
                    })
                )
            ).append(
                $("<td></td>", {
                    "class": "stadium-col"
                }).append(
                    $("<table></table>", {
                        "class": "table table-responsive"
                    }).append(
                        $("<tbody></tbody>").attr({
                            "data-driveway_id": driveway.id
                        })
                    )
                )
            )
        );
    });
}

function display_stadiums(driveway_id, stadiums) {
    $("tbody[data-driveway_id=" + driveway_id + "] tr").remove();
    if (stadiums.length == 0) {
        $("tbody[data-driveway_id=" + driveway_id + "]").append("<tr><td><h3 class='text-danger'>No stadiums with events near this address</h3></td></tr>");
    }
    $.each(stadiums, function (index, stadium) {
        $("<tr></tr>").attr({
            "data-stadium_id": stadium.id,
            "data-driveway_id": driveway_id
        }).append(
            $("<td></td>").append(
                $("<div></div>", {
                    "class": "col-md-2"
                }).append(
                    $("<i></i>", {
                        "class": "fa fa-map-marker fa-4x"
                    })
                )
            ).append(
                $("<div></div>", {
                    "class": "col-md-8 col-md-offset-1"
                }).append(
                    $("<h4></h4>", {
                        "text": stadium.name
                    })
                )
            )
        ).appendTo($("tbody[data-driveway_id=" + driveway_id + "]"));
    });
}

function display_events(driveway_id, stadium_id, events) {
    var $event_row;
    if (events.length == 0) {
        $event_row = $("<h3 class='text-danger'>No events near this stadium</h3></td>");
    } else {
        $event_row = $("<div></div>", {
            "class": "row"
        });

        if (events.length > 4) {
            $event_row.append(
                $("<div></div>", {
                    "class": "col-md-1 text-right"
                }).append(
                    $("<button></button>", {
                        "class": "btn btn-primary btn-xs get-prev-event"
                    }).attr({
                        "data-toggle": "tooltip",
                        "data-placement": "left",
                        "title": "Previous"
                    }).append(
                        $("<i></i>", {
                            "class": "fa fa-arrow-left"
                        })
                    )
                )
            ).append(
                $("<div></div>", {
                    "class": "col-md-10"
                }).append(
                    $("<div></div>", {
                        "class": "progress progress-thin"
                    }).append(
                        $("<div></div>", {
                            "class": "progress-bar progress-bar-hidden",
                            "role": "progressbar"
                        }).css("width", $(this).parent().width() / events.length) //TODO Fix.
                    ).append(
                        $("<div></div>", {
                            "class": "progress-bar",
                            "role": "progressbar"
                        }).css("width", (100 * 4 / events.length) + "%")
                    )
                )
            ).append(
                $("<div></div>", {
                    "class": "col-md-1 text-left"
                }).append(
                    $("<button></button>", {
                        "class": "btn btn-primary btn-xs get-next-event"
                    }).attr({
                        "data-toggle": "tooltip",
                        "data-placement": "right",
                        "title": "Next"
                    }).append(
                        $("<i></i>", {
                            "class": "fa fa-arrow-right"
                        })
                    )
                )
            );
        }

        $.each(events, function (index, event) {
            $event_item = $("<div></div>");
            display_event_cell($event_item, event);
        }
            

    var $event_list_col = $("tr[data-driveway_id='" + driveway_id + "'][data-stadium_id='" + stadium_id + "']");
    $event_list_col.find(".event-list-col").remove();
    $event_list_col.append(
        $("<td></td>", {
            "class": "event-list-col"
        }).attr({
            "data-num_events": events.length
        }).append($event_row)
    );
}

function display_event_cell($event_cell, event) {
, {
                "class": "col-md-3 text-center event-cell"
            }).attr({
                "data-event_id": event.id,
                "data-driveway_id": driveway_id,
                "data-stadium_id": stadium_id
            }).append(
                $("<div></div>").append(
                    $("<h5></h5>", {
                        "class": "text-center text-primary text-bold",
                        "text": event.name
                    })
                ).append(
                    $("<p></p>", {
                        "text": event.start_time + " - " + event.end_time
                    })
                )
            ).append(
                $("<input type='checkbox'>").attr({
                    "class": "offering-availability " + ((index < 4) ? "show-on-start" : ""),
                    "disabled": event.transaction_count > 0 || !$("#archive_toggle").prop("checked"),
                    "checked": event.offering_count > 0,
                    "data-toggle": "toggle",
                    "data-on": "Available",
                    "data-off": "Unavailable",
                    "data-onstyle": "success",
                    "data-offstyle": "danger",
                    "data-driveway_id": driveway_id,
                    "data-stadium_id": stadium_id,
                    "data-event_id": event.id,
                })
            );
            if (event.group_id != null) {
                console.log("Group id: " + event.group_id);
                $event_item.append(
                    $("<div></div").append(
                        $("<a></a>", {
                            "class": "text-right text-small text-info offering-availability-details",
                            "text": "View details",
                            "href": "#"
                        }).attr({
                            "data-group_id": event.group_id
                        })
                    )
                );
            }
            if (index >= 4) {
                $event_item.hide();
            }
            $event_row.append($event_item);
        });
    }
}

function display_event_scroll_bar() {
    
}

function display_event_details(offering, transactions) {
    $("#offering_availability_details_modal_title").text(offering.name);
    $("#offering_availability_details_modal_subtitle").text(offering.start_time + " - " + offering.end_time);
    $("#offering_availability_details_tbody tr").remove();

    var valid_transaction_count = 0;
    $.each(transactions, function (index, transaction) {
        if (transaction.transaction_id != null) {
            $("#offering_availability_details_tbody").append(
                $("<tr></tr>").append(
                    $("<td></td>", {
                        "class": "text-center",
                        "text": transaction.first_name + " " + transaction.last_name
                    })
                ).append(
                    $("<td></td>", {
                        "class": "text-center",
                        "text": transaction.transaction_date_created
                    })
                ).append(
                    $("<td></td>", {
                        "class": "text-center",
                        "text": "$" + (offering.price / 100)
                    })
                )
            );
            valid_transaction_count++;
        }
    });

    if (valid_transaction_count == 0) {
        $("#offering_availability_details_tbody").append("<tr><td class='text-center' colspan='3'>No transactions yet...</td></tr>");
    }

    $("#offering_availability_details_price").val(offering.price / 100);
    $("#offering_availability_details_num_spots").val(transactions.length);
    $("#offering_availability_details_price").attr("disabled", valid_transaction_count != 0);
    $(".num-spots-adjust").attr("disabled", valid_transaction_count != 0);
    $(".price-adjust").attr("disabled", valid_transaction_count != 0);
}

/* Events */
function on_offering_availability_change(e) {
    e.preventDefault();
    
    var $event_cell = $(this).closest(".event-cell");
    var stadium_id = $event_cell.data("stadium_id");
    var driveway_id = $event_cell.data("driveway_id");
    var event_id = $event_cell.data("event_id");
    var available = ($(this).is(":checked")) ? 1 : 0;

    make_request("update_offering_availability", {
        driveway_id: driveway_id,
        event_id: event_id,
        available: available
    }, function (data) {
        display_event_cell($event_cell, data[0][0]);
    });
}

function on_archive_toggle_change() {
    if ($(this).prop("checked")) {
        load_all();
    } else {
        load_all();
    }
}

function on_offering_availability_loaded(e) {
    $(e.target).find(".offering-availability.show-on-start").bootstrapToggle();
}

function on_offering_availability_details_click(e) {
    e.preventDefault();
    load_event_details($(this).data("group_id"));
    $("#offering_availability_details_modal").modal("show");
}

function on_adjust_up() {
    $(this).parent().siblings("input").val(function (i, oldval) {
        $(this).trigger("change");
        return ++oldval;
    });
}

function on_adjust_down() {
    $(this).parent().siblings("input").val(function (i, oldval) {
        $(this).trigger("change");
        return --oldval;
    });
}

function on_offering_availability_details_edit() {
    $(".show-on-offering-availability-details-edit").show();
}

function on_get_next_event() {
    var $event_list_col = $(this).closest(".event-list-col");
    var $progress_bar = $event_list_col.find(".progress");
    var $event_cells = $event_list_col.find(".event-cell");
    var $hidden_after = $event_cells.filter(":visible").nextAll(":hidden");

    //If there are no next events, do nothing:
    if ($hidden_after.length == 0) {
        return;
    } else {
        $event_list_col.find(".progress-bar-hidden").css("width", function (index, value) {
            value = parseFloat(value);
            return value + $(this).parent().width() / $event_list_col.data("num_events");
        });
        $event_cells.filter(":visible").first().fadeOut(0);
        $event_cells.filter(":visible").last().next(":hidden").fadeIn({
            "duration": EVENT_FADE_SPEED,
            "start": function () {
                $(this).find(".offering-availability").bootstrapToggle();
            }
        });
    }
}

function on_get_prev_event() {
    var $event_list_col = $(this).closest(".event-list-col");
    var $progress_bar_hidden = $event_list_col.find(".progress-bar-hidden");
    var $event_cells = $event_list_col.find(".event-cell");
    var $hidden_before = $event_cells.filter(":visible").prevAll(":hidden");

    //If there are no previous events, do nothing:
    if ($hidden_before.length == 0) {
        return;
    } else {
        $event_list_col.find(".progress-bar-hidden").css("width", function (index, value) {
            value = parseFloat(value);
            return value - $(this).parent().width() / $event_list_col.data("num_events");
        });
        $event_cells.filter(":visible").last().fadeOut(0);
        $event_cells.filter(":visible").first().prev(":hidden").fadeIn({
            duration: EVENT_FADE_SPEED,
            start: function () {
                $(this).find(".offering-availability").bootstrapToggle();
            }
        });
    }
}

$(window).bind('beforeunload', function () {
    //return 'Are you sure you want to leave?';
});

$(document).ready(function () {
    load_all();
    $(".show-on-offering-availability-details-edit").hide();

    //Bind events:
    $("#offerings_tbody").on("change", ".offering-availability", on_offering_availability_change);
    $("#archive_toggle").on("change", on_archive_toggle_change);
    $("#offerings_tbody").on("DOMNodeInserted", ".event-list-col", on_offering_availability_loaded);
    $("#offerings_tbody").on("click", ".offering-availability-details", on_offering_availability_details_click);
    $("#offering_availability_details_modal").on("click", ".adjust-up", on_adjust_up);
    $("#offering_availability_details_modal").on("click", ".adjust-down", on_adjust_down);
    $("#offering_availability_details_modal").on("change", ".offering-availability-details-edit", on_offering_availability_details_edit);
    $("#offerings_tbody").on("click", ".get-next-event", on_get_next_event);
    $("#offerings_tbody").on("click", ".get-prev-event", on_get_prev_event);

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        delay: {
            "show": 1000,
            "hide": 100
        }
    });
});
