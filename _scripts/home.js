var recent_transactions = [],
    current_recent_transaction_index;

make_request_url = "home_action.php";

function load_transactions() {
    make_request("select_transactions", null, function (data) {
        display_transactions(data[0]);
    });
}

function load_recent_transactions() {
    make_request("select_recent_transactions", null, function (data) {
        display_recent_transactions(data[0]);
    });
}

function load_user_info() {
    make_request("load_user_info", null, function (data) {
        display_user_info(data[0][0]);
    });
}

function display_user_info(user_info) {
    $("#welcome_message").html("<strong>Hi, " + user_info.first_name + "!</strong> <span class='gray-text'> &nbsp;Welcome to ParkU Central</span>");
}

function display_transactions(transactions) {
    $("#transactions_tbody tr").remove();

    if (transactions.length == 0) {
        $("#transactions_tbody").append("No purchases to show...");
    }

    for (var i = 0; i < transactions.length; i++) {
        var transaction = transactions[i];
        var transaction_row = "<tr>";
        transaction_row += "<td class='vcenter'><i class='fa fa-3x fa-car'/></td>";
        transaction_row += "<td class='vcenter'><h4><strong class='text-primary'>" + transaction.line1 + "</strong><small>&ensp;for&ensp;</small><strong class='text-primary'>" + transaction.event_name + "  &nbsp;" + "</strong><small>" + transaction.event_start_time + " - " + transaction.event_end_time + "</small></h4></td>";
        transaction_row += "<td class='vcenter'><a class='btn-circle-expand btn-directions' data-toggle='tooltip' title='Get Directions' data-lat='" + transaction.lat + "' data-lng='" + transaction.lng + "' href='#'><i class='fa fa-lg fa-map'/></a></td>";
        transaction_row += "<td class='vcenter'><a class='btn-circle-expand btn-share' data-toggle='tooltip' title='Share with Friends!' href='#'><i class='fa fa-lg fa-share-alt'/></a></td>";
        $("#transactions_tbody").append(transaction_row);
    }
}

function display_recent_transactions(transactions) {
    $("#recent_transactions *").remove();
    recent_transactions = transactions;

    if (recent_transactions.length > 0) {
        current_recent_transaction_index = 0;
        $("#recent_transactions").append("<span>Loading... <i class='fa fa-spinner fa-pulse fa-fw'></i></span>");

        window.setInterval(function () {
            $("#recent_transactions *").fadeOut(1000, function () {
                $("#recent_transactions > span").remove();
                var row = generate_recent_transaction_row(current_recent_transaction_index);
                console.log(row);
                $("#recent_transactions").append(row);
                current_recent_transaction_index = (current_recent_transaction_index + 1) % recent_transactions.length;
            });
        }, 5000);
    }
}

function display_radial_menu() {
    var l = $('.circle a').length;
    $('.circle a').each(function (i) {
        $(this).css("left", (40 - 50 * Math.cos(-0.5 * Math.PI - 2 * (1 / l) * i * Math.PI)).toFixed(4) + "%");
        $(this).css("top", (45 + 50 * Math.sin(-0.5 * Math.PI - 2 * (1 / l) * i * Math.PI)).toFixed(4) + "%");
    });
}

function generate_recent_transaction_row(index) {
    var transaction = recent_transactions[index];
    var row = "<span>";
    row += "<i class='fa fa-lg fa-clock-o'/>&emsp;";
    row += "<span class='text-primary'>" + transaction.first_name + " " + transaction.last_name + "</span>";
    row += " purchased a spot for ";
    row += "<span class='text-primary'>" + transaction.event_name + "</span>";
    row += "<span class='pull-right transaction-date'>" + transaction.date_created + "</span>";
    row += "</span>";
    return row;
}

function on_directions_button_clicked() {
    var lat = $(this).data("lat");
    var lng = $(this).data("lng");

    window.open("https://maps.google.com?saddr=Current+Location&daddr=" + (lat + "," + lng), "_blank");
}

function on_radial_menu_button_mouse_entered() {
    $('.circle').addClass('open');
}

function on_radial_menu_button_mouse_left() {
    $('.circle').removeClass('open');
}

function on_radial_menu_button_click(e) {
    e.preventDefault();
    $('.circle').addClass('open');
}

$(document).ready(function () {
    load_user_info();
    load_transactions();
    load_recent_transactions();

    display_radial_menu();

    //Bind events:
    $("#transactions_tbody").on("click", ".btn-directions", on_directions_button_clicked);
    $("#football_menu").on("mouseenter", on_radial_menu_button_mouse_entered);
    $("#football_menu_wrapper").on("mouseleave", on_radial_menu_button_mouse_left);
    $("#football_menu").on("click", ".menu-button", on_radial_menu_button_click);
    
    $("#football_menu_help").fadeOut(10000);
    //$("#welcome_message_wrapper").fadeOut(10000);

    //Bind all tooltips -> even dynamically generated ones
    $("body").tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    
});
