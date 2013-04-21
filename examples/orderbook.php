<?php
require '../rest.php';
use BitBox\Rest;

// get current orderbook
$rest = new Rest();
$response = $rest->orderbook('BTCUSD');
if (Rest::isError($response)) {
  echo 'ERROR: ' . $response->message . "\n";
} else {
  print_r($response);
}
