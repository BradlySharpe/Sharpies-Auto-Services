<?php


  class Car {
    private $db;
    private $tableName = 'car';
    public function __construct() { $this->db = new DBase(); }

    public function get($customerId = null, $carId = null) {
      $sql = "SELECT * FROM {$this->tableName} WHERE 1=1";
      if (!empty($customerId))
        $sql .= " AND `owner` = " . $this->db->escape($customerId);
      if (!empty($carId))
        $sql .= " AND `id` = " . $this->db->escape($carId);
      $result = array();

      $service = new Service();

      if (!empty($carId)) {
        $result = $this->db->fetchOne($sql);
        $customer = new Customer(false);
        $owner = $customer->get($result['owner']);
        if (!empty($owner))
          $result['owner'] = $owner;
        $result['services'] = $service->getForCar($carId);
      } else {
        $result = $this->db->fetchAll($sql);
        $customer = new Customer(false);
        foreach ($result as $index => $car) {
          $owner = $customer->get($car['owner']);
          if (!empty($owner))
            $result[$index]['owner'] = $owner;
          $result['services'] = $service->getForCar($car['id']);
        }
      }
      new Respond($result);
    }

    public function post($customerId = null) {
      if (empty($customerId)) new Error("Customer ID cannot be empty");

      $result = RequiredFields::getFields(
        array(
          'make' => array('required' => true, 'regex' => '/^.{1,100}$/'),
          'model' => array('required' => true, 'regex' => '/^.{1,100}$/'),
          'registration' => array('required' => true, 'regex' => '/^[A-Z0-9]{1,6}$/')
        ),
        $_POST
      );

      if ($result['error']) {
        new Error("Invalid values passed", $result['data']);
      } else {
        $result['data']['owner'] = $customerId;
        $this->db->prepareInsert($result['data']);
        if ($this->db->insert($this->tableName)) {
          $this->get($customerId, $this->db->lastId());
        } else {
          new Error("Error inserting car record");
        }
      }
    }

    public function put($customerId = null, $carId = null) {
      if (empty($carId))
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
        if ($this->db->update($this->tableName, $carId)) {
          $this->get((array_key_exists('owner', $fields) ? $fields['owner'] : $customerId), $carId);
        } else
          new Error("Could not update customer");
      } else
          new Error("No values to update");
    }
  }
