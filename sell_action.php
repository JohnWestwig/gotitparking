<?php
    require_once "_session.php";
    require_once "_db.php";

    $id = $_SESSION['id'];

    switch ($_POST['action']) {
        case "load_driveways":
            reply($dbconn->sp_execute("sp_sell_driveways_select", [$id]));
            break;
        case "load_stadiums":
            reply($dbconn->sp_execute("sp_sell_stadiums_select", [$id, $_POST['driveway_id']]));
            break;
        case "load_events":
            reply($dbconn->sp_execute("sp_sell_events_select", [$id, $_POST['driveway_id'], $_POST['stadium_id'], $_POST['is_upcoming']]));
            break;
        case "load_event_details":
            reply($dbconn->sp_execute("sp_sell_offering_group_details_select", [$id, $_POST['group_id']]));
            break;
        case "update_offering_availability":
            reply($dbconn->sp_execute("sp_sell_update_offering_group", [$_POST['event_id'], $_POST['driveway_id'], $_POST['available']]));
            break;
        default:
            echo "Unsupported action";
    }

    function reply($result) {
        echo json_encode($result);
    }

?>