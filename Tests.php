<?php

  function logMessage($message) {
    echo "  --  $message\n";
  }

  function failed($message) {
    die("  --    Test Failed: $message");
  }

  class API {
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

  $API = new API();
  echo "\nStarting Tests...\n";

  $Warren = array(
    'firstname' => 'Warren',
    'lastname' => 'Sharpe',
    'address' => '890 Fake Street',
    'city' => 'Dennington',
    'state' => 'Victoria',
    'postcode' => 3280,
  );

  $Bradly = array(
    'firstname' => 'Bradly',
    'lastname' => 'Sharpe',
    'address' => '123 Fake Street',
    'city' => 'Dennington',
    'state' => 'Victoria',
    'postcode' => 3280,
  );

  $Car = array(
    'owner' => null,
    'make' => 'Holden Commodore',
    'model' => 'VE Wagon',
    'registration' => '123XYZ'
  );

  /*
    Create Customer - Warren
   */
  $result = $API->post('customer', $Warren, "Creating Warren");
  if ($result->error) failed("Didn't return customer: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Warren as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Warren['id'] = $result->data->id;

  /*
    Create Customer - Bradly
   */
  $result = $API->post('customer', $Bradly, "Creating Bradly");
  if ($result->error) failed("Didn't return customer: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Bradly as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Bradly['id'] = $result->data->id;
  logMessage("Bradly ID: " . $Bradly['id']);

  /*
    Get Customer - Warren
   */
  $result = $API->get('customer/' . $Warren['id'], "Getting Warren - ID: " . $Warren['id']);
  if ($result->error) failed("Didn't return customer: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Warren as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Update Customer - Warren
      Set City to Warrnambool
   */
  $Warren['city'] = 'Warrnambool';
  $result = $API->put(
    'customer/' . $Warren['id'],
    array('city' => $Warren['city']),
    "Updating Warren - City: " . $Warren['city']);
  if ($result->error) failed("Didn't return customer: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Warren as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Create Car
   */
  $Car['owner'] = $Warren['id'];
  $result = $API->post('car', $Car, "Creating Car");
  if ($result->error) failed("Didn't return car: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Car as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Car['id'] = $result->data->id;

  /*
    Create Duplicate Car
      Should fail
   */
  $result = $API->post('car', $Car, "Creating Duplicate Car");
  if (!$result->error) failed("Should have returned error - Cannot create duplicate cars");
  logMessage("  Couldn't create duplicate car - that's good!");

  /*
    Get Car
   */
  $result = $API->get('car/' . $Car['id'], "Getting Car - ID: " . $Car['id']);
  if ($result->error) failed("Didn't return car: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Car as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Update Car
      Change Owner
   */
  $Car['owner'] = $Bradly['id'];
  $result = $API->put(
    'car/' . $Car['id'],
    array('owner' => $Car['owner']),
    "Updating Car - Owner: " . $Car['owner']);
  if ($result->error) failed("Didn't return car: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Car as $key => $value)
    $API->test($result->data->{$key}, $value, $key);


  echo "\nTests Finished!";
