<?php
    include "_db.php";

    $error = false;
    if (isset($_POST['register'])) {
        $salt = openssl_random_pseudo_bytes(4);
        try {
            $dbconn->sp_execute("sp_register_create_user", [$_POST['email'], $_POST['password'], $salt, $_POST['first_name'], $_POST['last_name']]);
            header("Location: login.php");
        } catch (PDOException $e) {
            $error = true;
        }
    }

    $header_info = array(
        "title" => "Register",
        "css" => array("_stylesheets/register.css"),
        "scripts" => array("_scripts/register.js"),
        "bootstrap_validator" => true
    );
    include "_header.php";
?>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">Register</h3>
                        </div>
                        <div class="panel-body">
                            <form id="registration" method="post">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>
                                    <input name="first_name" type="text" class="form-control" id="first_name" placeholder="John" required>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input name="last_name" type="text" class="form-control" id="last_name" placeholder="Doe" required>
                                </div>
                                <div class="form-group">
                                    <label for="email_address">Email Address</label>
                                    <input name="email" type="email" class="form-control" id="email_address" placeholder="john.doe@example.com" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input name="password" type="password" class="form-control" id="password">
                                </div>
                                <div class="form-group">
                                    <label for="password">Confirm Password</label>
                                    <input name="confirm_password" type="password" class="form-control" id="confirm_password">
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-5 text-center">
                                        <p class="text-success">Already a member? Login <a href="./login.php?sender=<?php echo $_GET['sender'];?>"><strong>here</strong>.</a></p>
                                    </div>
                                    <div class="col-md-6 col-md-offset-1 text-right">
                                        <button name="register" type="submit" class="btn btn-primary btn-lg">Finish&nbsp;<i class="glyphicon glyphicon-arrow-right"></i></button>
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
