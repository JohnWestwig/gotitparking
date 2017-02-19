<?php
    require_once "_session.php";
    require_once "_db.php";

    $status = $dbconn->sp_execute("sp_home_verify_buyer_seller_existence", [$_SESSION['id']])[0][0];

    if ($status['is_buyer'] == 0 && $status['is_seller'] == 0) {
        //header("Location: welcome.php");
    }

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
            <?php if (isset($_GET['login'])): ?>
                <!--<div id="welcome_message_wrapper" class="text-center alert alert-success">
                    <span id="welcome_message" class="text-center"></span>
                </div>-->
                <?php endif; ?>
                    <div class="row">
                        <div class="col-md-1">
                            <img src="_images/logo.png" height="80" width="80" />
                        </div>
                        <div class="col-md-8">
                            <h1 class="text-primary" id="welcome_message"><strong></strong></h1>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 id="transactions_header"><i class="fa fa-shopping-bag"></i>&ensp;<span class="text-primary">My Purchases</span></h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="transactions_table" class="table no-border">
                                        <tbody id="transactions_tbody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 id="transactions_header"><i class="fa fa-users"></i>&ensp;<span class="text-primary">Community Log</span></h3>
                                    <div id="recent_transactions" class="well well-lg">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" id="football_menu_wrapper">
                            <h3 id="transactions_header"><i class="fa fa-binoculars"></i>&ensp;<span class="text-primary">Explore</span>&ensp;<small><span id="football_menu_help">Hover to View&ensp;<i class="fa fa-arrow-down"></i></span></small></h3>
                            <nav id="football_menu" class="circular-menu">
                                <div class="circle">
                                    <a href="sell.php" class="btn-circle-expand btn-circle-expand-lg btn-sell vcenter" data-toggle="tooltip" data-placement="top" title="Manage Spots"><i class="fa fa-wrench fa-2x"></i></a>
                                    <a href="sell.php?archived=true" class="btn-circle-expand btn-circle-expand-lg btn-sell vcenter" data-toggle="tooltip" data-placement="top" title="Selling History"><i class="fa fa-book fa-2x"></i></a>
                                    <a href="seller_setup.php" class="btn-circle-expand btn-circle-expand-lg btn-sell vcenter" data-toggle="tooltip" data-placement="top" title="Seller Settings"><i class="fa fa-gear fa-2x"></i></a>
                                    <a></a>
                                    <a href="buyer_setup.php" class="btn-circle-expand btn-circle-expand-lg btn-buy vcenter" data-toggle="tooltip" data-placement="top" title="Buyer Settings"><i class="fa fa-gear fa-2x"></i></a>
                                    <a href="buy.php?archived=true" class="btn-circle-expand btn-circle-expand-lg btn-buy vcenter" data-toggle="tooltip" data-placement="top" title="Buying History"><i class="fa fa-history fa-2x"></i></a>
                                    <a href="buy.php" class="btn-circle-expand btn-circle-expand-lg btn-buy vcenter" data-toggle="tooltip" data-placement="top" title="Buy a Spot"><i class="fa fa-search fa-2x"></i></a>
                                    <a></a>
                                </div>
                                <a class="menu-button" href=""><img height="100%" src="_images/football_icon.png" /></a>
                            </nav>
                        </div>
                    </div>
                    <hr>
        </div>
    </body>

    <?php
    include "_footer.php";
?>
