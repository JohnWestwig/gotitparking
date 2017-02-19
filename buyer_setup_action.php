<?php
    require_once "_session.php";
    require_once "_db.php";

    $id = $_SESSION['id'];

    switch ($_POST['action']) {
        case "vehicle_create":
            reply($dbconn->sp_execute("sp_buyer_setup_vehicle_insert", [$id, $_POST['plate'], $_POST['make'], $_POST['model'], $_POST['color']]));
            break;
        case "vehicle_load":
            reply($dbconn->sp_execute("sp_buyer_setup_vehicle_select", [$id]));
            break;
        case "vehicle_delete":
            reply($dbconn->sp_execute("sp_buyer_setup_vehicle_delete", [$_POST['vehicle_id']]));
            break;
        default:
            echo "Unsupported action";
    }

    function reply($result) {
        echo json_encode($result);
    }
?>
