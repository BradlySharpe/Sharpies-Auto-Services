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
        logMessage("  Passed: $testName - value '$value'");
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
    'registration' => (rand(100, 999) . 'XYZ')
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
  if ($result->error) failed("Didn't return safety check: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Car as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Create Safety Check
   */
  $result = $API->post('safetycheck', array(), "Creating Safety Check");
  if ($result->error) failed("Didn't return safety check: " . $result->message . "\n" . json_encode($result->data));
  $SafetyCheck = $result->data;
  $API->test(!empty($SafetyCheck->id), true, "ID Shouldn't be empty");

  /*
    Update Safety Check
      Completed
   */
  $SafetyCheck->completed = 1;
  $result = $API->put(
    'safetycheck/' . $SafetyCheck->id,
    array('completed' => $SafetyCheck->completed),
    "Completing Safety Check");
  if ($result->error) failed("Didn't return safety check: " . $result->message . "\n" . json_encode($result->data));
  $API->test($result->data->completed, true, "Safety Check completed");


  /*
    Create Service
   */
  $Service = array(
    'owner' => $Bradly['id'],
    'car' => $Car['id'],
    'odo' => rand(1, 999999),
    'safetyCheck' => $SafetyCheck->id
  );
  $result = $API->post('service', $Service, "Creating Service");
  if ($result->error) failed("Didn't return service: " . $result->message . "\n" . json_encode($result->data));
  $API->test((1000 <= $result->data->id), true, "Service ID should be greater than 999");
  foreach ($Service as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Service['id'] = $result->data->id;

  /*
    Get Service
   */
  $result = $API->get('service/' . $Service['id'], "Getting Service - ID: " . $Service['id']);
  if ($result->error) failed("Didn't return service: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Service as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Create Invoice
   */
  $Invoice = array(
    'service' => $Service['id']
  );
  $result = $API->post('invoice', $Invoice, "Creating Invoice");
  if ($result->error) failed("Didn't return invoice: " . $result->message . "\n" . json_encode($result->data));
  $API->test((1000 <= $result->data->id), true, "Invoice ID should be greater than 999");
  foreach ($Invoice as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Invoice['id'] = $result->data->id;

  /*
    Get Invoice
   */
  $result = $API->get('invoice/' . $Invoice['id'], "Getting Invoice - ID: " . $Invoice['id']);
  if ($result->error) failed("Didn't return invoice: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Invoice as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Create Item
   */
  $Item = array(
    'description' => 'Some part description',
    'defaultCost' => 5.95,
    'defaultQuantity' => 2.5,
    'comment' => true,
    'active' => true,
  );
  $result = $API->post('item', $Item, "Creating Item");
  if ($result->error) failed("Didn't return item: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Item as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Item['id'] = $result->data->id;

  /*
    Get Item
   */
  $result = $API->get('item/' . $Item['id'], "Getting Item - ID: " . $Item['id']);
  if ($result->error) failed("Didn't return item: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Item as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Update Item
      Cost and Quantity
   */
  $Item['defaultCost'] = 20.95;
  $Item['defaultQuantity'] = 20.95;
  $result = $API->put(
    'item/' . $Item['id'],
    array(
      'defaultCost' => $Item['defaultCost'],
      'defaultQuantity' => $Item['defaultQuantity']
    ),
    "Updating Item");
  if ($result->error) failed("Didn't return item: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Item as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Create Detail
   */
  $Detail = array(
    'invoice' => $Invoice['id'],
    'description' => 'Description about part',
    'comment' => 'Description about work done',
    'cost' => 100.05,
    'quantity' => 2.5,
  );
  $result = $API->post('detail', $Detail, "Creating Detail");
  if ($result->error) failed("Didn't return detail: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Detail as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Detail['id'] = $result->data->id;

  /*
    Get Detail
   */
  $result = $API->get('detail/' . $Detail['id'], "Getting Detail - ID: " . $Detail['id']);
  if ($result->error) failed("Didn't return detail: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Detail as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Update Detail
      Comment, Cost and Quantity
   */
  $Detail['comment'] = 'Different comment about work done';
  $Detail['cost'] = 109.95;
  $Detail['quantity'] = 3.5;
  $result = $API->put(
    'detail/' . $Detail['id'],
    array(
      'comment' => $Detail['comment'],
      'cost' => $Detail['cost'],
      'quantity' => $Detail['quantity']
    ),
    "Updating Item");
  if ($result->error) failed("Didn't return detail: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Detail as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Create Payment
   */
  $Payment = array(
    'invoice' => $Invoice['id'],
    'amount' => 200,
    'comment' => 'Description about payment',
    'date' => date("Y-m-d")
  );
  $result = $API->post('payment', $Payment, "Creating Payment");
  if ($result->error) failed("Didn't return payment: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Payment as $key => $value)
    $API->test($result->data->{$key}, $value, $key);
  $Payment['id'] = $result->data->id;

  /*
    Get Payment
   */
  $result = $API->get('payment/' . $Payment['id'], "Getting Payment - ID: " . $Payment['id']);
  if ($result->error) failed("Didn't return payment: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Payment as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  /*
    Update Payment
      Comment and Amount
   */
  $Payment['comment'] = 'Different comment about payment';
  $Payment['amount'] = 109.95;
  $result = $API->put(
    'payment/' . $Payment['id'],
    array(
      'comment' => $Payment['comment'],
      'amount' => $Payment['amount']
    ),
    "Updating Item");
  if ($result->error) failed("Didn't return payment: " . $result->message . "\n" . json_encode($result->data));
  foreach ($Payment as $key => $value)
    $API->test($result->data->{$key}, $value, $key);

  echo "\nTests Finished!\n";
