<?php
    require_once "_session.php";
    require_once "_db.php";
    require_once "stripe.php";

    $id = $_SESSION['id'];

    switch ($_POST['action']) {
        case "driveway_create":
            reply($dbconn->sp_execute("sp_seller_setup_driveway_insert", 
                [$id, $_POST['lat'], $_POST['lng'], $_POST['line1'], $_POST['line2'], $_POST['city'], $_POST['state'], $_POST['zipcode'], $_POST['num_spots']]));
            break;
        case "driveway_delete":
            reply($dbconn->sp_execute("sp_seller_setup_driveway_delete", [$_POST['driveway_id']]));
            break;
        case "driveway_load":
            reply($dbconn->sp_execute("sp_seller_setup_driveway_select", [$id]));
            break;
        case "driveway_update":
            reply($dbconn->sp_execute("sp_seller_setup_driveway_update", [$_POST['driveway_id'], $_POST['num_spots']]));
            break;
        case "driveway_image_upload":
            $result = prepare_image();
            
            if (array_key_exists("error", $result)) {
                reply($result);
            } else {
                reply($dbconn->sp_execute("sp_seller_setup_driveway_image_insert", [$_POST['driveway_id'], $result['image_path']]));
            }
            break;
        case "stripe_load":
            reply($dbconn->sp_execute("sp_seller_setup_stripe_select", [$id]));
            break;
        case "stripe_create":
            $stripe_account = \Stripe\Account::create(
                array(
                    "country" => "US",
                    "managed" => true
                )
            );
            $dbconn->sp_execute("sp_seller_setup_stripe_seller_insert", 
                                [$id, $stripe_account['id'], $stripe_account['keys']['publishable'], $stripe_account['keys']['secret']]);
            reply($dbconn->sp_execute("sp_seller_setup_stripe_select", [$id]));
            break;
        default:
            reply(array("error" => "Unsupported action", "attempted_action" => $_POST['action']));
    }

    function reply($result) {
        echo json_encode($result);
    }

    function prepare_image() {
        define("UPLOAD_DIR", "./_driveway_images/");

        if (!empty($_FILES["image"])) {
            $driveway_image = $_FILES["image"];

            if ($driveway_image["error"] !== UPLOAD_ERR_OK) {
                //Handle image upload errors:
                return array("error" => "Image upload error");
            }

            $extension = pathinfo($driveway_image["name"])["extension"];
            $name = "d_" + base64_encode(uniqid()) . "." . $extension;
            while (file_exists(UPLOAD_DIR . $name)) {
                $name = "d_" . base64_encode(uniqid()) . "." . $extension;
            }

            $success = move_uploaded_file($driveway_image["tmp_name"], UPLOAD_DIR . $name);
            if (!$success) { 
                //Handle image saving errors:
                return array("error" => "Image save error", "name" => UPLOAD_DIR . $name);
            }

            chmod(UPLOAD_DIR . $name, 0644);
            return array("image_path" => (UPLOAD_DIR . $name));
        } else {
            return array("error" => "No image uploaded");
        }
    }

?>
