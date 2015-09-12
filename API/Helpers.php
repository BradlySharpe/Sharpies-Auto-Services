<?php

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
