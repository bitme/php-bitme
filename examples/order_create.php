<?php
require '../rest.php';
use BitMe\Rest;

define('KEY', 'mykey');
define('SECRET', 'mysecret');

// BUY 2 BTC @ 75 LTC/each
$currencyPair = 'BTCLTC';
$orderTypeCd = 'BID';
$quantity = 2;
$rate = 75;

// create a new order
$rest = new Rest(KEY, SECRET);
$response = $rest->orderCreate($currencyPair, $orderTypeCd, $quantity, $rate);
if (Rest::isError($response)) {
  echo 'ERROR: ' . $response->message . "\n";
} else {
  print_r($response);
}
