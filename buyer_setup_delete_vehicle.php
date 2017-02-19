<?php
    include "db.php";
    $stmt =  $dbh->prepare("CALL sp_buyer_setup_vehicle_delete(?)");
    $stmt->bindParam(1, $_POST['vehicle_id'], PDO::PARAM_INT);
    $stmt->execute();
?>
