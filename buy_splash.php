<?php
    require_once "_session.php";
    require_once "_db.php";

    $header_info = array(
        "title" => "Buy",
        "css" => array("_stylesheets/buy_splash.css"),
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
                    <p>Fans seeking game day parking: would you like to have a parking spot waiting for you on game days? Don’t miss another kickoff while you’re looking for a parking spot. Reserve your parking spot near the stadium in advance, save time and never have to drive around looking for parking again! GotIt! Parking helps you purchase a spot on someone’s lawn ahead of time with a variety of prices and locations to choose from. With our secure online payment method remove the hassle of exchanging cash by paying beforehand. With 3 easy steps create your account now so you’re ready for game day!</p>
                </div>
                <div class="col-md-4 text-center">
                    <a class="btn btn-primary btn-lg " href="buy.php">Find Parking&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-3 text-center">
                    <h1 class="step-number">1</h1>
                    <p class="step-text">Find your Stadium</p>
                </div>
                <div class="col-md-3 text-center">
                    <h1 class="step-number">2</h1>
                    <p class="step-text">Pick your Spot</p>
                </div>
                <div class="col-md-3 text-center">
                    <h1 class="step-number">3</h1>
                    <p class="step-text">Purchase!</p>
                </div>
                <div class="col-md-3 text-center">
                    <h1 class="step-number">4</h1>
                    <p class="step-text">Attend the game, hassle-free</p>
                </div>
            </div>
        </div>
    </body>

    <?php
    include "_footer.php";
?>
