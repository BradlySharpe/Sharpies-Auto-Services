<?php

  function logMessage($message) {
    echo "  --  $message\n";
  }

  function failed($message) {
    die("  --    Test Failed: $message");
  }

  class makeCall {
    private $ch;
    private $base = "http://localhost/Sharpies-Auto-Services/";

    public function __construct() {
      $this->ch = curl_init();
      curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    public function get($url, $message) {
      if (empty($url))
        return null;

      logMessage($message);
      curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($this->ch, CURLOPT_POST, 0);
      curl_setopt($this->ch, CURLOPT_URL, $this->base . $url);
      $result = curl_exec($this->ch);
      if ($result)
        return json_decode($result);
      failed("URL didn't return JSON: $url");
    }

    public function post($url, $data, $message) {
      if (empty($url))
        return null;

      logMessage($message);
      curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($this->ch, CURLOPT_POST, 1);
      curl_setopt($this->ch, CURLOPT_URL, $this->base . $url);
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
      $result = curl_exec($this->ch);
      if ($result)
        return json_decode($result);
      failed("URL didn't return JSON: $url");
    }

    public function put($url, $data, $message) {
      if (empty($url))
        return null;

      logMessage($message);
      curl_setopt($this->ch, CURLOPT_POST, 0);
      curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($this->ch, CURLOPT_URL, $this->base . $url);
      curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
      $result = curl_exec($this->ch);
      if ($result)
        return json_decode($result);
      failed("URL didn't return JSON: $url");
    }

    public function test($value, $expected, $testName) {
      if ($value != $expected)
        failed("$testName - Expected '$expected', Got: '$value'");
      else
        logMessage("  Passed: $testName");
    }
  }

  $curl = new makeCall();
  echo "\nStarting Tests...\n";

  $data = array(
    'firstname' => 'Michelle',
    'lastname' => 'Sharpe',
    'address' => '890 Fake Street',
    'city' => 'Dennington',
    'state' => 'Victoria',
    'postcode' => 1235,
  );
  $result = $curl->post('customer', $data, "Testing New Customer");
  if ($result->error) failed("Didn't return customer: " . $result->message);
  foreach ($data as $key => $value) {
    $curl->test($result->data->{$key}, $value, $key);
  }

  $result = $curl->get('customer/' . $result->data->id, "Testing Customer " . $result->data->id);
  if ($result->error) failed("Didn't return customer: " . $result->message);
  foreach ($data as $key => $value) {
    $curl->test($result->data->{$key}, $value, $key);
  }

  $data['firstname'] = 'Brooke';
  $result = $curl->put('customer/' . $result->data->id, array('firstname' => $data['firstname']), "Testing Update Customer " . $result->data->id);
  if ($result->error) failed("Didn't return customer: " . $result->message);

  foreach ($data as $key => $value) {
    $curl->test($result->data->{$key}, $value, $key);
  }

  echo "\nTests Finished!";
