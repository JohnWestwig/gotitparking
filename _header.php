<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        <?php 
            echo "ParkU" . (array_key_exists('title', $header_info) ? " | " . $header_info['title'] : "" );
        ?>
    </title>
    
    <link rel="icon" type="image/png" href="_images/logo.png">

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    
    <!-- Bootstrap -->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">-->
    <script src="bootstrap-assets/javascripts/bootstrap.min.js"></script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet">    
    <!-- Font Awesome Icons -->
    <link href="_stylesheets/social_links.css" type="text/css" rel="stylesheet">

    <!-- Base CSS -->
    <link href="_stylesheets/base.css" rel="stylesheet" type="text/css">
    
    <?php if (isset($header_info) && is_array($header_info)): ?>
        <!-- External scripts and stylesheets -->
        <?php if (array_key_exists('stripe', $header_info)): ?>
            <!-- Stripe -->
            <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
        <?php endif; ?>

        <?php if (array_key_exists('bootstrap_validator', $header_info)): ?>
            <!-- Bootstrap Validator -->
            <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/css/bootstrapValidator.min.css"/>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
        <?php endif; ?>
    
        <?php if (array_key_exists('data_table', $header_info)): ?>
            <!-- DataTables -->
            <link rel="stylesheet" type="text/css" src="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <?php endif; ?>
    
        <?php if (array_key_exists('bootstrap_toggle', $header_info)): ?>
            <!-- Bootstrap Toggle -->
            <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet" type="text/css">
            <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <?php endif; ?>
    
        <?php if (array_key_exists('slider', $header_info)): ?>
            <!-- Bootstrap Slider -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.2.0/bootstrap-slider.min.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.2.0/css/bootstrap-slider.min.css" type="text/css"/>
        <?php endif; ?>
    
        <?php if (array_key_exists('css', $header_info)): ?>
            <!-- Page Stylesheets -->
            <?php foreach($header_info['css'] as $css): ?>
                <link rel="stylesheet" href="<?php echo $css; ?>" type="text/css">
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Page Scripts -->
        <script src="_scripts/make_request.js"></script>
        <?php if (array_key_exists('scripts', $header_info)): ?>
            <?php foreach($header_info['scripts'] as $script): ?>
                <script src="<?php echo $script; ?>"></script>
            <?php endforeach; ?>
        <?php endif; ?>
    
        <?php if (array_key_exists('social', $header_info)): ?>
            <!-- Social Links -->
            <?php include "social_links.php"; ?>
        <?php endif; ?>
    <?php endif; ?>
</head>
