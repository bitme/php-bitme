# BitBox\Rest

Communicate with [BitBox REST API](http://inbitbox.github.io/rest/).

## Verify API Credentials

```php
<?php
require 'rest.php';
use BitBox\Rest;

$rest = new Rest('mykey', 'mysecret');
$response = $rest->verifyCredentials();
if (Rest::isError($response)) {
  echo $response->message . "\n";
} else {
  echo "credentials are valid!\n";
}
```

## Orderbook

```php
<?php
// orderbook does not require authentication
$rest = new Rest();
$response = $rest->orderbook('BTCUSD');
if (Rest::isError($response)) {
  echo $response->message . "\n";
} else {
  print_r($response);
}
```
