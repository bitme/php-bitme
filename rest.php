<?php
namespace BitMe;

class Rest
{
  protected $_key;
  protected $_secret;
  protected $_baseUri;
  protected $_nonce;
  
  public static function isError($response)
  {
    return is_object($response) && isset($response->http_code);
  }
  
  public function __construct($key = null, $secret = null)
  {
    if ($key) $this->_key = $key;
    if ($secret) $this->_secret = $secret;
    $this->_baseUri = 'https://bitme.com/rest/';
    $this->_nonce = time();
  }
  
  /*
   * Auth Not Required
   */
  
  public function orderbook($currencyPair)
  {
    $currencyPair = urlencode($currencyPair);
    return $this->_doRequest('get', 'orderbook/' . $currencyPair);
  }
  
  public function compatOrderbook($currencyPair) 
  {
    $currencyPair = urlencode($currencyPair);
    return $this->_doRequest('get', 'compat/orderbook/' . $currencyPair);
  }
  
  public function compatTrades($currencyPair, $since = null) 
  {
    $currencyPair = urlencode($currencyPair);
    $data = array();
    if ($since !== null) {
      $data['since'] = $since;
    }
    return $this->_doRequest('get', 'compat/trades/' . $currencyPair, $data);
  }
  
  /*
   * Auth Required
   */
   
  public function verifyCredentials()
  {
    $data = array('nonce' => $this->_nonce++);
    return $this->_doRequest('get', 'verify-credentials', $data);
  }
  
  public function accounts()
  {
    $data = array('nonce' => $this->_nonce++);
    return $this->_doRequest('get', 'accounts', $data);
  }
  
  public function bitcoinAddress()
  {
    $data = array('nonce' => $this->_nonce++);
    return $this->_doRequest('get', 'bitcoin-address', $data);
  }
  
  public function ordersOpen()
  {
    $data = array('nonce' => $this->_nonce++);
    return $this->_doRequest('get', 'orders/open', $data);
  }
  
  public function orderCreate($currencyPair, $orderTypeCd, $quantity, $rate)
  {
    $data = array(
      'nonce' => $this->_nonce++,
      'currency_pair' => $currencyPair,
      'order_type_cd' => $orderTypeCd,
      'quantity' => $quantity,
      'rate' => $rate
    );
    return $this->_doRequest('post', 'order/create', $data);
  }
  
  public function orderCancel($uuid)
  {
    $data = array(
      'nonce' => $this->_nonce++,
      'uuid' => $uuid
    );
    return $this->_doRequest('post', 'order/cancel', $data);
  }
  
  public function orderGet($uuid)
  {
    $data = array('nonce' => $this->_nonce++);
    $uuid = urlencode($uuid);
    return $this->_doRequest('get', 'order/' . $uuid, $data);
  }
  
  public function couponCreate($currencyCd, $amount)
  {
    $data = array(
      'nonce' => $this->_nonce++,
      'currency_cd' => $currencyCd,
      'amount' => $amount
    );
    return $this->_doRequest('post', 'coupon/create', $data);
  }
  
  public function couponRedeem($code)
  {
    $data = array(
      'nonce' => $this->_nonce++,
      'code' => $code
    );
    return $this->_doRequest('post', 'coupon/redeem', $data);
  }
  
  public function couponCancel($code)
  {
    $data = array(
      'nonce' => $this->_nonce++,
      'code' => $code
    );
    return $this->_doRequest('post', 'coupon/cancel', $data);
  }
  
  public function transferUser($toUserUuid, $currencyCd, $amount)
  {
    $data = array(
      'nonce' => $this->_nonce++,
      'to_user_uuid' => $toUserUuid,
      'currency_cd' => $currencyCd,
      'amount' => $amount
    );
    return $this->_doRequest('post', 'transfer/user', $data);
  }
  
  protected function _doRequest($method, $call, $data = null)
  {
    $dataString = $data ? http_build_query($data) : null;
    $options = array(
      CURLOPT_RETURNTRANSFER => true
    );
    
    // set URL/data for request depending on whether it's a GET or POST
    $url = $this->_baseUri . $call;
    if ($method == 'post') {
      $options[CURLOPT_POSTFIELDS] = $dataString;
    } elseif ($dataString) {
      $url .= '?' . $dataString;
    }
    $options[CURLOPT_URL] = $url;
    
    // send signed request if key and secret has been set
    // some requests do not require authentication
    if ($this->_key && $this->_secret) {
      $options[CURLOPT_HTTPHEADER] = array(
        'Rest-Key: ' . $this->_key,
        'Rest-Sign: ' . $this->_getReqHash($dataString)
      );
    }
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $body = curl_exec($ch);
    $json = json_decode($body);
    
    if (isset($json->error)) {
      return $json->error;
    }
    
    return $json;
  }
  
  protected function _getReqHash($data)
  {
    $hash = hash_hmac('sha512', $data, base64_decode($this->_secret), true);
    return base64_encode($hash);
  }
  
}
