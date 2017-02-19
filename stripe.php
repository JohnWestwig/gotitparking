<?php
  require_once("vendor/autoload.php");

    define('STRIPE_API_KEY', "sk_test_fgOQkTkWOf882RxCP1lmvBP8"); 
    define('STRIPE_CLIENT_ID', "ca_9P7KGBHs72aGbD4AX2vPsWK81Z1Xh0CG");
    define('STRIPE_TOKEN_URI', 'https://connect.stripe.com/oauth/token');
    define('STRIPE_AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');

    Stripe\Stripe::setApiKey(STRIPE_API_KEY);
?>
