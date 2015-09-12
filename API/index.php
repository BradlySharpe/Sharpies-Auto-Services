<?php
  require('DBase.php');
  require('Toro.php');

  class Respond {
    public function __construct($data, $error = false, $message = null) {
      echo json_encode(
        array(
          'error' => false || $error,
          'message' => $message,
          'data' => $data
        )
      );
    }
  }

  class Error {
    public function __construct($message, $data = null) {
      new Respond($data, true, "Error: $message");
    }
  }

  class RequiredFields {
    public static function getFields($needles, $haystack) {
      $errors = array();
      $out = array();

      foreach ($needles as $key => $options) {
        $valid = true;
        if ($options) {
          if (array_key_exists('required', $options) && true == $options['required'] &&
            (!array_key_exists($key, $haystack) || empty($haystack[$key]))) {
            $errors[] = array('field' => $key, 'reason' => 'empty');
            $valid = false;
          }
          if ($valid && array_key_exists('regex', $options) && array_key_exists($key, $haystack)) {
            $matches = array();
            preg_match($options['regex'], $haystack[$key], $matches);
            if (0 >= count($matches)) {
              $valid = false;
              $errors[] = array('field' => $key, 'reason' => 'regex', 'expression' => $options['regex']);
            }
          }
        }
        if ($valid)
          $out[$key] = (array_key_exists($key, $haystack) ? $haystack[$key] : null);
      }

      return array(
        'error' => (0 < count($errors)),
        'data' => (0 < count($errors) ? $errors : $out)
      );
    }
  }

  class Customer {
    private $db;
    public function __construct() { $this->db = new DBase(); }

    public function get($id = null) {
      $sql = "SELECT * FROM customer";
      if (!empty($id))
        $sql .= " WHERE `id` = " . $this->db->escape($id);
      $result = array();
      if (!empty($id))
        $result = $this->db->fetchOne($sql);
      else
        $result = $this->db->fetchAll($sql);
      new Respond($result);
    }

    public function post() {
      $result = RequiredFields::getFields(
        array(
          'firstname' => array('required' => true, 'regex' => '/^.{1,30}$/'),
          'lastname' => array('required' => true, 'regex' => '/^.{1,30}$/'),
          'address' => array('required' => true, 'regex' => '/^.{1,100}$/'),
          'city' => array('required' => true, 'regex' => '/^.{1,50}$/'),
          'state' => array('required' => true, 'regex' => '/^.{1,20}$/'),
          'postcode' => array('required' => true, 'regex' => '/^\d{4}$/'),
        ),
        $_POST
      );

      if ($result['error']) {
        new Error("Invalid values passed", $result['data']);
      } else {
        $this->db->prepareInsert($result['data']);
        if ($this->db->insert('customer')) {
          $this->get($this->db->lastId());
          //new Respond(array('id' => $this->db->lastId()));
        } else {
          new Error("Error inserting customer record");
        }
      }
    }

    public function put($id = null) {
      if (empty($id))
        new Error("Customer ID cannot be empty");
      $fields = array();
      parse_str(file_get_contents("php://input"), $fields);

      $result = RequiredFields::getFields(
        array(
          'firstname' => array('regex' => '/^.{1,30}$/'),
          'lastname' => array('regex' => '/^.{1,30}$/'),
          'address' => array('regex' => '/^.{1,100}$/'),
          'city' => array('regex' => '/^.{1,50}$/'),
          'state' => array('regex' => '/^.{1,20}$/'),
          'postcode' => array('regex' => '/^\d{4}$/'),
        ),
        $fields
      );

      $fields = array();
      foreach ($result['data'] as $key => $value)
        if (!empty($value)) $fields[$key] = $value;

      if (0 < count($fields)) {
        $this->db->prepareUpdate($fields);
        if ($this->db->update('customer', $id)) {
          $this->get($id);
        } else
          new Error("Could not update customer");
      } else
          new Error("No values to update");
    }
  }

  ToroHook::add("404",  function() {
    new Error("Route not found");
  });

  Toro::serve(array(
    '/customer' => "Customer",
    '/customer/:number' => "Customer"
  ));
?>
