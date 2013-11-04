<?php
require '../rest.php';
use BitMe\Rest;

// get current orderbook
$rest = new Rest();
$response = $rest->orderbook('BTCLTC');
if (Rest::isError($response)) {
  echo 'ERROR: ' . $response->message . "\n";
} else {
  print_r($response);
}
