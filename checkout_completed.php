<?php
    require_once "_session.php";
    require_once "_db.php";

    $header_info = array(
        "title" => "Checkout completed",
        "css" => array(),
        "social" => true
    );

    require_once "_header.php";
    include_once "navbar.php";
?>

    <body>
        <div class="container">
            <h1>Checkout completed.</h1>
        </div>
    </body>
