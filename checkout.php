<?php
    require_once "_db.php";
    require_once "_session.php";

    if (!isset($_GET['offering_id'])) {
        header("Location: buy.php?error=invalid_offering");
    }

    $reservation = $dbconn->sp_execute("sp_make_transaction_reservation_select", [$_GET['offering_id']])[0][0];
    if ($reservation == false || $reservation['time_remaining'] <= 0) {
        header("Location: buy.php?offering_id=" . $_GET['offering_id'] . "&reservation=" . json_encode($reservation));
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
        "title" => "Checkout",
        "css" => array("_stylesheets/checkout.css"),
        "scripts" => array("_scripts/checkout.js"),
        "stripe" => true,
        "data_table" => true
    );
    require_once "_header.php";
    require_once "navbar.php";
?>

    <body>
        <input type="hidden" id="logged-in" value="<?php if (isset($_SESSION['id'])) {echo 'true';} else {echo 'false';} ?>" />
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <strong><?php echo $error; ?>Thanks!</strong>&nbsp;You have temporarily reserved this spot. You have <strong id="time_remaining"><?php echo $reservation['time_remaining'];?></strong> until this page times out.
                    </div>
                    <div id="payment_form_error_alert" class="alert alert-danger" role="alert" hidden>
                        <strong>Uh Oh!</strong>&nbsp;<span id="payment_form_error_alert_text"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    <h3 class="text-danger">
                    <?php echo "<small>Purchasing 1 spot for </small>$" . ($offering['price_in_cents'] / 100)  . " <small>from " . $offering['first_name'] . " " . $offering['last_name'] . " at</small> " . $offering['line1'] . " " . $offering['line2'] . "<small>" . $offering['city'] . ", " . $offering['state'] . " " . $offering['zipcode'] . "</small>"?></h3>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-1">
                    <a id="step-return" class="btn btn-danger"><i class="fa fa-arrow-left"></i>&nbsp;Back</a>
                </div>
                <div class="col-md-10 text-center checkout-step" id="step-0-content" hidden>
                    <?php if (!isset($_SESSION["id"])): ?>
                        <h5>Here at GotIt! Parking, we like to build relationships with our customers.  To continue, please sign up with us for easy, reliable access to parking!</h5>
                        <h3><a class="btn btn-primary btn-lg" href="/login.php?sender=<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'];?>">Log In</a> &nbsp;or&nbsp; <a class="btn btn-primary btn-lg" href="/register.php?sender=checkout.php">Register</a></h3>
                        <?php else: ?>
                            <h3>You're logged in as <?php echo $_SESSION['name'];?>!</h3>
                            <a href="/login.php?sender=<?php echo $_SERVER['PHP_SELF'] . " ? " . $_SERVER['QUERY_STRING'];?>">Continue as someone else <i class="fa fa-arrow-right"></i></a>
                            <?php endif; ?>
                </div>
                <div class="col-md-10 checkout-step" id="step-1-content" hidden>
                    <?php if (isset($_SESSION["id"])): ?>
                        <div class="row">
                            <div class="col-md-8 text-center">
                                <h5>Please select which vehicle you will be taking to the game.</h5>
                            </div>
                            <div class="col-md-4 text-center">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#vehicle_create">Add a Vehicle&nbsp;<i class="glyphicon glyphicon-plus"></i></button>
                            </div>
                        </div>
                        <table class="table table-hover" id="vehicle_table">
                            <thead class="thead-inverse">
                                <tr>
                                    <th></th>
                                    <th class="text-center"></th>
                                    <th class="text-center">Vehicle</th>
                                </tr>
                            </thead>
                            <tbody id="vehicle_tbody">
                            </tbody>
                        </table>
                        <?php endif; ?>
                </div>
                <div class="col-md-10 checkout-step" id="step-2-content" hidden></div>
                <div class="col-md-10 checkout-step" id="step-3-content" hidden>
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
                    </form>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button id="verifyCardButton" class="btn btn-lg btn-success">Verify Card</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 checkout-step text-center" id="step-4-content" hidden>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 text-center">
                            <button id="finishCheckoutButton" class="btn btn-lg btn-success">Finish Checkout</button>
                            <hr>
                            <p>Please make sure all information is correct before proceeding.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <a id="step-continue" class="btn btn-success">Next <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>

            <hr>

            <div class="row form-group">
                <div class="col-md-12 col-xs-12">
                    <ul class="nav nav-pills nav-stacked nav-justified thumbnail setup-panel" id="step-headers">
                        <li class="active" id="step-0-header">
                            <a href="#" data-step-num="0">
                                <h4 class="list-group-item-heading">Step 1</h4>
                                <p class="list-group-item-text">Account</p>
                            </a>
                        </li>
                        <li id="step-1-header">
                            <a href="#" data-step-num="1">
                                <h4 class="list-group-item-heading">Step 2</h4>
                                <p class="list-group-item-text">Vehicles</p>
                            </a>
                        </li>
                        <li id="step-2-header">
                            <a href="#" data-step-num="2">
                                <h4 class="list-group-item-heading">Step 3</h4>
                                <p class="list-group-item-text">Extras</p>
                            </a>
                        </li>
                        <li id="step-3-header">
                            <a href="#" data-step-num="3">
                                <h4 class="list-group-item-heading">Step 4</h4>
                                <p class="list-group-item-text">Payment</p>
                            </a>
                        </li>
                        <li id="step-4-header">
                            <a href="#" data-step-num="4">
                                <h4 class="list-group-item-heading">Step 5</h4>
                                <p class="list-group-item-text">Finish</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="vehicle_create" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add a Vehicle</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group row">
                                <label for="vehicle_plate" class="col-md-2 col-form-label">Plate No.</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="vehicle_plate" placeholder="ABC-1234">
                                </div>
                                <label for="vehicle_color" class="col-md-2 col-form-label">Color</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="vehicle_color" placeholder="Blue">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="vehicle_make" class="col-md-2 col-form-label">Make</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="vehicle_make" placeholder="Honda">
                                </div>
                                <label for="vehicle_model" class="col-md-2 col-form-label">Model</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="vehicle_model" placeholder="Accord">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <button id="vehicle_create_button" type="button" class="btn btn-success">Confirm</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
    </body>
