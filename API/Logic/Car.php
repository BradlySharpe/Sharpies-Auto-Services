<?php


  class Car {
    private $db;
    private $tableName = 'car';
    public function __construct() { $this->db = new DBase(); }

    public function get($id = null) {
      $sql = "SELECT * FROM {$this->tableName}";
      if (!empty($id))
        $sql .= " WHERE `id` = " . $this->db->escape($id);
      $result = array();
      if (!empty($id)) {
        $result = $this->db->fetchOne($sql);

      } else {
        $result = $this->db->fetchAll($sql);
        $customer = new Customer(false);
        foreach ($result as $index => $car) {
          $owner = $customer->get($car['owner']);
          if (!empty($owner))
            $result[$index]['owner'] = $owner;
        }
      }
      new Respond($result);
    }

    public function post() {
      $result = RequiredFields::getFields(
        array(
          'owner' => array('required' => true, 'regex' => '/^\d+$/'),
          'make' => array('required' => true, 'regex' => '/^.{1,100}$/'),
          'model' => array('required' => true, 'regex' => '/^.{1,100}$/'),
          'registration' => array('required' => true, 'regex' => '/^[A-Z0-9]{1,6}$/')
        ),
        $_POST
      );

      if ($result['error']) {
        new Error("Invalid values passed", $result['data']);
      } else {
        $this->db->prepareInsert($result['data']);
        if ($this->db->insert($this->tableName)) {
          $this->get($this->db->lastId());
        } else {
          new Error("Error inserting car record");
        }
      }
    }

    public function put($id = null) {
      if (empty($id))
        new Error("Car ID cannot be empty");
      $fields = array();
      parse_str(file_get_contents("php://input"), $fields);

      $result = RequiredFields::getFields(
        array(
          'owner' => array('regex' => '/^\d+$/'),
          'make' => array('regex' => '/^.{1,100}$/'),
          'model' => array('regex' => '/^.{1,100}$/'),
          'registration' => array('regex' => '/^[A-Z0-9]{1,6}$/')
        ),
        $fields
      );

      $fields = array();
      foreach ($result['data'] as $key => $value)
        if (!empty($key) && !empty($value)) $fields[$key] = $value;

      if (0 < count($fields)) {
        $this->db->prepareUpdate($fields);
        if ($this->db->update($this->tableName, $id)) {
          $this->get($id);
        } else
          new Error("Could not update customer");
      } else
          new Error("No values to update");
    }
  }
