<?php
    require_once "_session.php";
    require_once "_db.php";

    $header_info = array(
        "title" => "Sell",
        "css" => array("_stylesheets/sell_splash.css"),
        "social" => true
    );
    require_once "_header.php";
    require_once "navbar.php";
?>

    <body>
        <div class="container">
            <div class="flex-row">
                <div class="col-md-8">
                    <h2>Why GotIt! Parking?</h2>
                    <p>Want to make some easy cash on game day?  GotIt! Parking takes the hassle out of finding a parking spot for fans by helping property owners near the stadium easily turn their lawn or driveway into a parking lot. Once you’re on board with us, you choose how many parking spaces you have available to sell, how much each one will cost, and what days they will be available. We take care of the rest. You get paid when customers purchase their parking space from you, using our secure online payment platform.</p>
                </div>
                <div class="col-md-4 text-center">
                    <a class="btn btn-primary btn-lg " href="sell.php">Start Selling&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 text-center">
                    <h1 class="step-number">1</h1>
                    <p class="step-text">Enter your Address</p>
                </div>
                <div class="col-md-3 text-center">
                    <h1 class="step-number">2</h1>
                    <p class="step-text">Set your Preferences</p>
                </div>
                <div class="col-md-3 text-center">
                    <h1 class="step-number">3</h1>
                    <p class="step-text">Enter the Market</p>
                </div>
                <div class="col-md-3 text-center">
                    <h1 class="step-number">4</h1>
                    <p class="step-text">Start Earning!</p>
                </div>
            </div>
        </div>
    </body>

    <?php
    include "_footer.php";
?>
