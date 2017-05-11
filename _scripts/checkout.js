make_request_url = "checkout_action.php";

/* JQuery Handles */
var $returnButton, $continueButton;
var $verifyCardButton;
var $paymentFormErrorAlert, $paymentFormErrorAlertText;
var $cardVerifiedIndicator, $cardVerifiedErrorIndicator;
var $finishCheckoutButton;

var vehicle_table;

/* Checkout Steps */
var currentStepIndex = 0;

var stripeCardToken = false;

var steps = [];

/* Step functions */

function gotoStep(stepIndex) {
    var currentStep = steps[currentStepIndex];
    if (currentStep.canGotoStep(stepIndex)) {
        displayStep(currentStepIndex, stepIndex);
        currentStepIndex = stepIndex;
    } else {
        //Do Nothing?
    }
}


/* Load Data */
function load_vehicles() {
    make_request("vehicle_load", null, function (data) {
        display_vehicles(data[0]);
    });
}

/* Display Data */
function display_vehicles(vehicles) {
    vehicle_table.clear();

    if (vehicles.length == 0) {
        //$("#vehicle_tbody").append("<tr><td colspan='1'>No vehicles yet...</td></tr>");
    }

    for (var i = 0; i < vehicles.length; i++) {
        var vehicle = vehicles[i];
        var vehicle_col = '<td><h4 class="text-primary">' + vehicle.plate + '&nbsp;<small>' + vehicle.color + ' ' + vehicle.make + ' ' + vehicle.model + '</small></h4></td>';
        vehicle_table.row.add([
            vehicle.id,
            "<td></td>",
            vehicle_col
        ]);
    }
    vehicle_table.draw(false);
}

function displayStep(oldStepIndex, newStepIndex) {

    console.log("From " + oldStepIndex + " to " + newStepIndex);

    var oldStep = steps[oldStepIndex];
    var newStep = steps[newStepIndex];

    oldStep.$content.hide();
    oldStep.$label.removeClass("active");
    console.log(newStep);
    newStep.$content.show();
    newStep.$label.addClass("active");

    $continueButton.toggleClass('disabled', !newStep.canGotoStep(newStepIndex + 1));
    $returnButton.toggleClass('disabled', !newStep.canGotoStep(newStepIndex - 1));
}

/* Handle Events */

function onClickContinueButton() {
    var currentStep = steps[currentStepIndex];

    if (currentStep.canGotoStep(currentStepIndex + 1)) {
        gotoStep(currentStepIndex + 1);
    }
}

function onClickReturnButton() {
    var currentStep = steps[currentStepIndex];

    if (currentStep.canGotoStep(currentStepIndex - 1)) {
        gotoStep(currentStepIndex - 1);
    }
}

function onClickStepLabel() {
    var stepIndex = $(this).data("stepNum");
    var currentStep = steps[currentStepIndex];
    if (currentStep.canGotoStep(stepIndex)) {
        gotoStep(stepIndex);
    }
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

function onVerifyCardButtonClicked() {
    verifyCard();
}

function verifyCard() {
    stripeCardToken = false;
    Stripe.card.createToken($("#payment_form"), onVerifyCardResponse);
}

function onVerifyCardResponse(status, response) {
    if (response.error) { // Problem!
        console.log(response.error);
        $paymentFormErrorAlertText.text(response.error.message);
        $paymentFormErrorAlert.show();

        $cardVerifiedIndicator.hide();
        $cardVerifiedErrorIndicator.show();
    } else {
        $paymentFormErrorAlert.hide();

        var token = response.id;
        stripeCardToken = token;
        console.log(token);
        $("#make_transaction_info input[name=stripe_token]").val(token);

        $cardVerifiedIndicator.show();
        $cardVerifiedErrorIndicator.hide();
    }
    console.log("refreshing");
    //Refresh step
    gotoStep(currentStepIndex);
}

function onfinishCheckoutButtonClicked() {
    make_request("make_transaction", {
        offering_id: $("#make_transaction_info input[name=offering_id]").val(),
        stripe_token: $("#make_transaction_info input[name=stripe_token]").val(),
        vehicle_id: $("#make_transaction_info input[name=vehicle_id]").val()
    }, function (data) {
        window.location.href = "/checkout_completed.php";
    }, function () {
        console.log("error");
    });
}

function canGotoInBetweenSteps(start, end) {
    for (var i = start + 1; i < end; i++) {
        if (!steps[i].canGotoStep(end)) {
            return false;
        }
    }
    return true;
}

function start_timer(num_seconds) {
    time_remaining = parseInt(num_seconds);
    timer_id = setInterval(function () {
        if (time_remaining <= 0) {
            clearInterval(timer_id);
            window.location.href = "buy.php?error=timeout";
        } else {
            $("#time_remaining").text(parse_time(time_remaining));
            time_remaining--;
        }
    }, 1000);
}

function parse_time(num_seconds) {
    return Math.floor(num_seconds / 60) + "m " + (num_seconds - 60 * Math.floor(num_seconds / 60)) + "s";
}

$(document).ready(function () {

    start_timer($("#time_remaining").text());

    steps = [{
        name: "account",
        $label: $("#step-0-header"),
        $content: $("#step-0-content"),
        canGotoStep: function (stepIndex) {
            if (stepIndex <= 0 && stepIndex >= 0) {
                return true;
            } else if (stepIndex < 0 || stepIndex >= 5) {
                return false;
            } else if ($("#logged-in").val() == 'true') {
                return canGotoInBetweenSteps(0, stepIndex);
            } else {
                return false;
            }
        }
    }, {
        name: "vehicles",
        $label: $("#step-1-header"),
        $content: $("#step-1-content"),
        canGotoStep: function (stepIndex) {
            if (stepIndex <= 1 && stepIndex >= 0) {
                return true
            } else if (stepIndex < 0 || stepIndex >= 5) {
                return false;
            } else if (vehicle_table.rows('.selected').any()) {
                return canGotoInBetweenSteps(1, stepIndex);
            } else {
                return false;
            }
        }
    }, {
        name: "extras",
        $label: $("#step-2-header"),
        $content: $("#step-2-content"),
        canGotoStep: function (stepIndex) {
            if (stepIndex < 0 || stepIndex >= 5) {
                return false;
            }
            //Check if logged in.
            return true;
        }
    }, {
        name: "payment",
        $label: $("#step-3-header"),
        $content: $("#step-3-content"),
        canGotoStep: function (stepIndex) {
            if (stepIndex > 0 && stepIndex <= 3) {
                return true;
            } else if (stepIndex < 0 || stepIndex >= 5) {
                return false;
            } else if (stripeCardToken) {
                return true;
            } else {
                console.log("Stripe token is false")
                return false;
            }
        }
    }, {
        name: "finish",
        $label: $("#step-4-header"),
        $content: $("#step-4-content"),
        canGotoStep: function (stepIndex) {
            if (stepIndex < 0 || stepIndex >= 5) {
                return false;
            }
            //Check if logged in.
            return true;
        }
    }];

    $returnButton = $("#step-return");
    $continueButton = $("#step-continue");

    $returnButton.on("click", onClickReturnButton);
    $continueButton.on("click", onClickContinueButton);

    $verifyCardButton = $("#verifyCardButton");
    $verifyCardButton.on("click", onVerifyCardButtonClicked);

    $paymentFormErrorAlert = $("#payment_form_error_alert");
    $paymentFormErrorAlertText = $("#payment_form_error_alert_text");

    $cardVerifiedIndicator = $("#cardVerifiedIndicator");
    $cardVerifiedErrorIndicator = $("#cardVerifiedErrorIndicator");

    $finishCheckoutButton = $("#finishCheckoutButton");
    $finishCheckoutButton.on("click", onfinishCheckoutButtonClicked);

    $("#step-headers").on("click", "a", onClickStepLabel);

    gotoStep(currentStepIndex);

    vehicle_table = $('#vehicle_table').DataTable({
        "info": false,
        "paging": false,
        bFilter: false,
        columnDefs: [{
            targets: "_all",
            orderable: false
        }, {
            targets: [0],
            visible: false,
            orderable: false,
            searchable: false
        }, {
            orderable: false,
            className: "select-checkbox",
            targets: 1
        }],
        select: {
            style: 'os',
            selector: 'td:first-child'
        },
        order: [[2, 'asc']]
    }).on('select', function (e, dt, type, indexes) {
        var vehicle_id = vehicle_table.rows(indexes).data()[0][0];
        $("#make_transaction_info input[name=vehicle_id]").val(vehicle_id);

        //Refresh step
        gotoStep(currentStepIndex);
    }).on('deselect', function (e, dt, type, indexes) {
        $("#make_transaction_info input[name=vehicle_id]").val("");

        //Refresh step
        gotoStep(currentStepIndex);
    });

    $("#vehicle_table").css("width", "100%");

    load_vehicles();

    Stripe.setPublishableKey('pk_test_5zl9yd55M80ALYHdFGmp5wzV');

    $("#vehicle_create_button").on("click", on_click_create_vehicle_button);
});
