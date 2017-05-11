<?php
    require_once "_session.php";
    require_once "_db.php";
    require_once "stripe.php";


    $id = $_SESSION['id'];

    switch ($_POST['action']) {
        case "vehicle_create":
            reply($dbconn->sp_execute("sp_buyer_setup_vehicle_insert", [$id, $_POST['plate'], $_POST['make'], $_POST['model'], $_POST['color']]));
            break;
        case "vehicle_load":
            reply($dbconn->sp_execute("sp_buyer_setup_vehicle_select", [$id]));
            break;
        case "make_transaction":
            $offering = $dbconn->sp_execute("sp_make_transaction_offering_details", [$_POST['offering_id']])[0][0];
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
                    $dbconn->sp_execute("sp_make_transaction_transaction_insert", [$_SESSION['id'], $_POST['offering_id'], $stripe_charge['id'], $_POST['vehicle_id']]);
                }

                header("Location: transaction_completed.php");
            } catch(\Stripe\Error\Card $e) {
                $error = $e->getMessage();
                reply($error);
            } catch (PDOException $e) {
                $error = $e->getMessage();
                reply($error);
            }            
            break;
        default:
            echo "Unsupported action";
    }

    function reply($result) {
        echo json_encode($result);
    }

?>
