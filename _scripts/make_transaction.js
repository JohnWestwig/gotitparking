var time_remaining, timer_id;
$(document).ready(function () {
    Stripe.setPublishableKey('pk_test_nzqfl6dOeGibJ4aYKPeY6BdX');
    start_timer($("#time_remaining").text());

    $("#payment_form").submit(function (event) {
        var payment_form = $("#payment_form");
        $("#payment_form_submit").prop('disabled', true);
        Stripe.card.createToken(payment_form, stripe_response_handler);
        return false;
    });
    
    $("#payment_form_error_alert").hide();

});

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

function stripe_response_handler(status, response) {
    var payment_form = $("#payment_form");
    if (response.error) {
        $("#payment_form_error_alert").show();
        $("#payment_form_error_alert_text").text(response.error.message);
        $("#payment_form_submit").prop('disabled', false);
    } else {
        var token = response.id;
        payment_form.append($('<input type="hidden" name="stripe_token" />').val(token));
        payment_form.get(0).submit();
    }
}
