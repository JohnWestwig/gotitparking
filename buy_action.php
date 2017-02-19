<?php
    require_once "_session.php";
    require_once "_db.php";

    $id = $_SESSION['id'];

    switch ($_POST['action']) {
        case "load_events":
            reply($dbconn->sp_execute("sp_buy_event_select", [$_POST['stadium_id']]));
            break;
        case "load_offerings":
            reply($dbconn->sp_execute("sp_buy_offerings_select", [$_POST['event_id'], $_POST['max_distance'], $_POST['max_price']]));
            break;
        case "load_stadiums":
            reply($dbconn->sp_execute("sp_buy_stadium_select", []));
            break;
        case "make_reservation":
            reply($dbconn->sp_execute("sp_buy_reservation_insert", [$id, $_POST['group_id']]));
            break;
        default:
            echo "Unsupported action";
    }

    function reply($result) {
        echo json_encode($result);
    }

?>