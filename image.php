<?php
    header('Content-Type: image/png');
    readfile("../img/" . $_GET['img']);
?>