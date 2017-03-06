<?php
    session_start();
    include "_db.php";

    $error = false;
    if (isset($_POST['login'])) {
        $verification = $dbconn->sp_execute("sp_login_auth_verify", [$_POST['email'], $_POST['password']])[0][0];
        
        if ($verification['verified']) {
            $_SESSION['id'] = $verification['user_id'];
            if (isset($_GET['sender'])) {
                header("Location: " . $_GET['sender']);
            } else {
                header("Location: home.php?login=true");
            }
        } else {
            $error = true;
        }
    }

    $header_info = array(
        "title" => "Log In",
        "css" => array("_stylesheets/login.css")
    );
    include "_header.php";
?>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Log In</h3>
                        </div>
                        <div class="panel-body">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="email-icon"><i class="glyphicon glyphicon-envelope"></i></span>
                                            <input name="email" type="text" class="form-control" placeholder="E-Mail" aria-describedby="email-icon">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="input-group">
                                            <span class="input-group-addon" id="password-icon"><i class="glyphicon glyphicon-lock"></i></span>
                                            <input name="password" type="password" class="form-control" placeholder="Password" aria-describedby="password-icon">
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5 text-center">
                                        <p class="text-success">New to ParkU? Register <a href="./register.php?sender=<?php echo $_GET['sender'];?>"><strong>here</strong>.</a></p>
                                    </div>
                                    <div class="col-md-6 col-md-offset-1 text-center">
                                        <button type="submit" name="login" class="btn btn-primary btn-lg">Continue&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <?php
    include "_footer.php";
?>
