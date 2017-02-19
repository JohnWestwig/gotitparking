make_request_url = "buyer_setup_action.php";

/* Load */
function load_vehicles() {
    make_request("vehicle_load", null, function (data) {
        display_vehicles(data[0]);
    });
}

/* Display */
function display_vehicles(vehicles) {
    $("#vehicle_tbody tr").remove();

    if (vehicles.length == 0) {
        $("#vehicle_tbody").append("<tr><td colspan='2'>No vehicles yet...</td></tr>");
    }

    for (var i = 0; i < vehicles.length; i++) {
        var vehicle = vehicles[i];
        var vehicle_col = '<td><h3 class="text-primary">' + vehicle.plate + '&nbsp;<small>' + vehicle.color + ' ' + vehicle.make + ' ' + vehicle.model + '</small></h3></td>';
        $("#vehicle_tbody").append("<tr><input type='hidden' value='" + vehicle.id + "'/>" + vehicle_col + "<td><button class='btn btn-danger'><i class='delete_vehicle fa fa-trash'></i></button></td></tr>");
    }
}

/* Events */
function on_click_delete_vehicle_button() {
    make_request("vehicle_delete", {
        vehicle_id: $(this).closest("tr").find("input").val()
    }, load_vehicles);
}

function on_click_create_vehicle_button() {
    $("#vehicle_create").modal('hide');
    make_request("vehicle_create", {
        make: $("#vehicle_make").val(),
        model: $("#vehicle_model").val(),
        plate: $("#vehicle_plate").val(),
        color: $("#vehicle_color").val()
    }, load_vehicles);
}


$(document).ready(function () {
    //Bind event handlers:
    $("#vehicle_create_button").on("click", on_click_create_vehicle_button);
    $("#vehicle_tbody").on("click", ".delete_vehicle", on_click_delete_vehicle_button);

    //Load page data:
    load_vehicles();
});
