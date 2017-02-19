<?php
    require_once "_session.php";

    $header_info = array(
        "title" => "Welcome",
        "css" => array("_stylesheets/welcome.css")
    );
    require_once "_header.php";
?>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <img src="_images/logo.png" height="50" width="50" />
                </div>
                <div class="col-md-8 text-center">
                    <h3 class="text-primary text-center">Looks like you're new to ParkU. Welcome to the community!</h3>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 text-center">
                    <p><a class="btn btn-danger btn-lg" href="buyer_setup.php" role="button">I need a parking spot for the game!</a></p>
                </div>
                <div class="col-md-6 text-center">
                    <p><a class="btn btn-success btn-lg" href="seller_setup.php" role="button">I've got parking spots to rent!</a></p>
                </div>
            </div>
        </div>
    </body>
