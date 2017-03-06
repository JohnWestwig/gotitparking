<?php
    session_start();
    unset($_SESSION["id"]);
    if (isset($_GET['sender'])) {
        header("Location: " . $_GET['sender']);
    } else {
        header("Location: home.php");
    }
?>
