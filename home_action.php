<?php
    require_once "_session.php";
    require_once "_db.php";

    $id = $_SESSION['id'];

    switch ($_POST['action']) {
        case "load_user_info":
            reply($dbconn->sp_execute("sp_home_user_select", [$id]));
            break;
        case "select_transactions":
            reply($dbconn->sp_execute("sp_home_transactions_select", [$id]));
            break;
        case "select_recent_transactions":
            reply($dbconn->sp_execute("sp_home_transactions_recent_select", []));
            break;
        default:
            echo "Unsupported action";
    }

    function reply($result) {
        echo json_encode($result);
    }

?>