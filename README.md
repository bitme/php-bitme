# BitMe\Rest

Communicate with [BitMe REST API](http://bitme.github.com/rest/).

## Verify API Credentials

```php
<?php
require 'rest.php';
use BitMe\Rest;

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

## Testing

You may use the [testnet](https://test.bitme.com) API for development/testing 
purposes.

```php
<?php
$rest = new Rest('testnetkey', 'testnetsecret', true);
```
