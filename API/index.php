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
              $errors[] = array('field' => $key, 'reason' => 'regex');
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

  class CustomerHandler {
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
          'firstname' => array('required' => true),
          'lastname' => array('required' => true),
          'address' => array('required' => true),
          'city' => array('required' => true),
          'state' => array('required' => true),
          'postcode' => array('required' => true, 'regex' => '/^\d{4}$/'),
        ),
        $_POST
      );

      if ($result['error']) {
        new Error("Invalid values passed", $result['data']);
      } else {
        new Respond($result);
      }
    }
  }

  ToroHook::add("404",  function() {
    new Error("Route not found");
  });

  Toro::serve(array(
    '/customer' => "CustomerHandler",
    '/customer/:number' => "CustomerHandler"
  ));
?>
