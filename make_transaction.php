<?php
    require_once "_db.php";
    require_once "_session.php";

    if (!isset($_GET['offering_id'])) {
        header("Location: buy.php");
    }

    $reservation = $dbconn->sp_execute("sp_make_transaction_reservation_select", [$_SESSION['id'], $_GET['offering_id']])[0][0];
    if ($reservation == false || $reservation['time_remaining'] <= 0) {
        header("Location: buy.php?offering_id=" . $_GET['offering_id'] . "&reservation=" . json_encode($reservation));
        //header("Location: https://chess.com");
    }

    $offering = $dbconn->sp_execute("sp_make_transaction_offering_details", [$_GET['offering_id']])[0][0];
    
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
        "title" => "Make Transaction",
        "css" => array("_stylesheets/make_transaction.css"),
        "scripts" => array("_scripts/make_transaction.js"),
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
                    <div id="payment_form_error_alert" class="alert alert-danger" role="alert">
                        <strong>Uh Oh!</strong>&nbsp;<span id="payment_form_error_alert_text"></span>
                    </div>
                </div>
            </div>
            <hr>
            <h3 class="text-danger"><?php echo "<small>Purchase 1 spot for </small>$" . ($offering['price_in_cents'] / 100)  . " <small>from " . $offering['first_name'] . " " . $offering['last_name'] . " at</small> " . $offering['line1'] . " " . $offering['line2'] . "<small>" . $offering['city'] . ", " . $offering['state'] . " " . $offering['zipcode'] . "?</small>" ?></h3>
            <hr>
            <form id="payment_form" role="form" method="post">
                <input type="hidden" name="offering_id" value="<?php echo $_GET['offering_id'];?>" />
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="card_number">CARD NUMBER</label>
                                    <div class="input-group">
                                        <input class="form-control" id="card_number" type="text" size="20" data-stripe="number" required autofocus>
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exp_month">EXPIRY DATE (MM/YY)</label>
                                    <div class="input-group">
                                        <input id="exp_month" class="form-control" type="text" data-stripe="exp_month">
                                        <span class="input-group-addon">/</span>
                                        <input id="exp_year" class="form-control" type="text" data-stripe="exp_year">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cardCVC">CV CODE</label>
                                    <input type="tel" class="form-control" placeholder="CVC" data-stripe="cvc" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_line1">LINE 1</label>
                                    <input type="text" class="form-control" id="address_line1" data-stripe="address_line1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address_line2">LINE 2</label>
                                    <input type="text" class="form-control" id="address_line2" data-stripe="address_line2">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address_city">CITY</label>
                                    <input type="text" class="form-control" id="address_city" data-stripe="address_city">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address_state">STATE</label>
                                    <input type="text" class="form-control" id="address_state" data-stripe="address_state">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="address_zip">ZIP CODE</label>
                                    <input type="text" class="form-control" id="address_zip" data-stripe="address_zip">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <button id="payment_form_submit" class="btn btn-success btn-lg btn-block" type="submit" name="payment_form_submit">Buy this spot!</button>
                    </div>
                </div>
            </form>
        </div>
    </body>
