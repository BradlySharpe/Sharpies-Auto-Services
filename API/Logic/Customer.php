<?php

  class Customer {
    private $db;
    private $tableName = 'customer';
    public function __construct() { $this->db = new DBase(); }

    public function get($id = null) {
      $sql = "SELECT * FROM {$this->tableName}";
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
        if ($this->db->insert($this->tableName)) {
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
