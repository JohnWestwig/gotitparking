<?php
    require_once "_db.php";
    require_once "_session.php";

    /*if (!isset($_GET['offering_id'])) {
        header("Location: buy.php?error=invalid_offering");
    }

    $reservation = $dbconn->sp_execute("sp_make_transaction_reservation_select", [$_GET['offering_id']])[0][0];
    if ($reservation == false || $reservation['time_remaining'] <= 0) {
        header("Location: buy.php?offering_id=" . $_GET['offering_id'] . "&reservation=" . json_encode($reservation));
    }

    $offering = $dbconn->sp_execute("sp_make_transaction_offering_details", [$_GET['offering_id']])[0][0];*/
    
    require_once "stripe.php";

    if (isset($_POST["stripe_token"])) {
        $stripe_token = $_POST['stripe_token'];
        $stripe_seller = $dbconn->sp_execute("sp_make_transaction_stripe_seller_select", [$_POST['offering_id']])[0][0];
        try {
            $stripe_charge = \Stripe\Charge::create(
                array(
                    "amount" => $offering["price_in_cents"], // amount in cents
                    "currency" => "usd",
                    "source" => $stripe_token,
                    "description" => "Purchased a spot",
                    "application_fee" => 100
                ),
                array("stripe_account" => $stripe_seller["account_id"])
            );
            
            if ($stripe_charge) {
                $dbconn->sp_execute("sp_make_transaction_transaction_insert", [$_SESSION['id'], $_POST['offering_id'], $stripe_charge['id']]);
            }
            
            header("Location: transaction_completed.php");
        } catch(\Stripe\Error\Card $e) {
            $error = $e->getMessage();
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
        
    }

    $header_info = array(
        "title" => "Checkout",
        "css" => array("_stylesheets/checkout.css"),
        "scripts" => array("_scripts/checkout.js"),
        "stripe" => true
    );
    require_once "_header.php";
    require_once "navbar.php";
?>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <strong><?php echo $error; ?>Thanks!</strong>&nbsp;You have temporarily reserved this spot. You have <strong id="time_remaining"><?php echo $reservation['time_remaining'];?></strong> until this page times out.
                    </div>
                    <!--<div id="payment_form_error_alert" class="alert alert-danger" role="alert">
                        <strong>Uh Oh!</strong>&nbsp;<span id="payment_form_error_alert_text"></span>
                    </div>-->
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-12 col-xs-12">
                    <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                        <li class="active">
                            <a href="#step-1">
                                <h4 class="list-group-item-heading">Step 1</h4>
                                <p class="list-group-item-text">Account</p>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#step-2">
                                <h4 class="list-group-item-heading">Step 2</h4>
                                <p class="list-group-item-text">Vehicles</p>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#step-3">
                                <h4 class="list-group-item-heading">Step 3</h4>
                                <p class="list-group-item-text">Extras</p>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#step-4">
                                <h4 class="list-group-item-heading">Step 4</h4>
                                <p class="list-group-item-text">Payment</p>
                            </a>
                        </li>
                        <li class="disabled">
                            <a href="#step-5">
                                <h4 class="list-group-item-heading">Step 5</h4>
                                <p class="list-group-item-text">Finish</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 text-center" id="step-1">
                    <?php if (isset($_SESSION['id'])): ?>
                        <h2>Continue as (person who is logged in)</h2>
                    <?php else: ?>
                        <h3>We encourage you to sign up for GotIt! Parking!</h3>
                    <?php endif; ?>
                </div>
                <div class="col-md-6" id="step-2" hidden>

                </div>
                <div class="col-md-6" id="step-3" hidden>

                </div>
                <div class="col-md-6" id="step-4" hidden>

                </div>
                <div class="col-md-6" id="step-5" hidden>

                </div>
                <div class="col-md-4 text-right">
                    <button class="btn btn-lg btn-success">Save &amp; Continue <i class="fa fa-arrow-right"></i></button>
                </div>
            </div>
        </div>
    </body>
