<?php
require '../rest.php';
use BitBox\Rest;

define('KEY', 'mykey');
define('SECRET', 'mysecret');

// BUY 2 BTC @ 6 USD/each
$currencyPair = 'BTCUSD';
$orderTypeCd = 'BID';
$quantity = 2;
$rate = 6;

// create a new order
$rest = new Rest(KEY, SECRET);
$response = $rest->orderCreate($currencyPair, $orderTypeCd, $quantity, $rate);
if (Rest::isError($response)) {
  echo 'ERROR: ' . $response->message . "\n";
} else {
  print_r($response);
}
