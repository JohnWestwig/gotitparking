<?php
    require_once "_session.php";
    require_once "_db.php";

    $header_info = array(
        "title" => "Home",
        "scripts" => array("_scripts/home.js"),
        "css" => array("_stylesheets/home.css", "_stylesheets/radial_menu.css"),
        "social" => true
    );
    require_once "_header.php";
    require_once "navbar.php";
?>
    <body>
        <div class="container">
            <!-- Heading Row -->
            <div class="row">
                <div class="col-md-8">
                    <img class="img-responsive img-rounded" src="_images/ku_action.jpg" alt="">
                </div>
                <!-- /.col-md-8 -->
                <div class="col-md-4">
                    <h1><img src="_images/logo.png" height="64"/>&nbsp;<strong>Parking</strong></h1>
                    <hr>
                    <p><strong class='text-primary'>Welcome to the future.</strong>
                        <br>Park where you want, when you want, hassle-free. GotIt! Parking provides a simple, intuitive platform for parkers and owners alike.</p>
                    <div class="row">
                        <div class="col-md-11 text-right">
                            <a class="btn btn-primary btn-lg " href="about.php">Learn More&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-4 -->
            </div>
            <!-- /.row -->

            <hr>

            <!-- Call to Action Well -->
            <!--<div class="row">
            <div class="col-lg-12">
                <div class="well text-center">
                    Simple - Secure - GotIt! Parking
                </div>
            </div>
        </div>-->
            <!-- /.row -->

            <div class="row">
                <div class="col-md-4">
                    <h2><i class="fa fa-car"></i>&nbsp;&nbsp;Looking to park?</h2>
                    <p>Parking for the big game is tough. GotIt! Parking lets you find the perfect spot to tailgate and hang with friends, hassle-free. Learn to love the simplicity of GotIt! Parking and get parking!</p>
                </div>
                <div class="col-md-4">
                    <h2><i class="fa fa-home"></i>&nbsp;&nbsp;Have a spot to sell?</h2>
                    <p>Do you own a home near the stadium? Want to earn easy money at the touch of a button? GotIt! Parking's simple, intuitive interface and flexible platform allows you to manage gameday from the comfort of your own home.</p>
                </div>
                <div class="col-md-4">
                    <h2><i class="fa fa-lightbulb-o"></i>&nbsp;&nbsp;Questions?</h2>
                    <p>Visit our FAQ page to get answers to common questions about GotIt! Parking, or get in contact with us via e-mail or phone.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <a class="pull-right btn btn-primary btn-md" href="buy.php">Buy Now&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                </div>
                <div class="col-md-4">
                    <a class="pull-right btn btn-primary btn-md" href="sell.php">Sell Now&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                </div>
                <div class="col-md-4">
                    <a class="pull-right btn btn-primary btn-md" href="faq.php">Ask us Anything&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></a>
                </div>
            </div>

            <!-- Footer -->
            <footer>
                <div class="row">
                    <div class="col-lg-12">
                        <p></p>
                    </div>
                </div>
            </footer>
        </div>
    </body>

<?php
    include "_footer.php";
?>
