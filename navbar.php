<?php 
    require_once "_session.php";
    require_once "_db.php";

    $page = basename($_SERVER['PHP_SELF']);
    $logged_in = isset($_SESSION["id"]);
    $name = $dbconn->sp_execute("sp_navbar_name_select", [$_SESSION['id']])[0][0]['name'];
?>
    <script src="_scripts/navbar.js"></script>
    <link type="text/css" rel="stylesheet" href="_stylesheets/navbar.css" />
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a id="navbar_logo" href="home.php" class="navbar-left"><img src="_images/logo.png" height="32"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class='animated <?php if ($page == "home.php" ) {echo "active";} ?>'>
                        <a href="home.php">Home</a>
                    </li>
                    <li class='animated <?php if ($page == "buy_splash.php" ) {echo "active";} ?>'>
                        <a href="buy_splash.php">Park with Us!</a>
                    </li>
                    <li class='animated <?php if ($page == "sell.php" ) {echo "active";} ?>'>
                        <a href="sell.php">Rent a Spot</a>
                    </li>

                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class='animated <?php if ($page == "about.php" ) {echo "active";} ?>'>
                        <a href="about.php">About</a>
                    </li>
                    <li class="dropdown animated">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Settings <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li <?php if ($page == "buyer_setup.php") {echo "class='active'";} ?> ><a href="buyer_setup.php">Buyer Setup</a></li>
                            <li <?php if ($page == "seller_setup.php") {echo "class='active'";} ?> ><a href="seller_setup.php">Seller Setup</a></li>
                        </ul>
                    </li>
                    <?php if ($logged_in): ?>
                    <li class="dropdown animated">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $name;?>&ensp;<i class='fa fa-user'></i>&ensp;<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li <?php if ($page == "profile.php") {echo "class='active'";} ?> ><a href="profile.php">My Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php">Log Out</a></li>
                        </ul>
                    </li>
                    <?php else:?>
                    <button class="btn btn-default navbar-btn">Register</button>
                    <?php endif;?>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
        <!--/.container-fluid -->
    </nav>
