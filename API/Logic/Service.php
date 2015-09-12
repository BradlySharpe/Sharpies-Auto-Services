<?php

  class Service {
    private $db;
    private $tableName = 'service';
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
          'owner' => array('required' => true, 'regex' => '/^\d+$/'),
          'car' => array('required' => true, 'regex' => '/^\d+$/'),
          'odo' => array('required' => true, 'regex' => '/^\d{1,6}$/'),
          'safetyCheck' => array('required' => true, 'regex' => '/^\d+$/')
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
          new Error("Error inserting service record");
        }
      }
    }

    /*
      //Cannot change service once created
    public function put($id = null) {
      if (empty($id))
        new Error("Car ID cannot be empty");
      $fields = array();
      parse_str(file_get_contents("php://input"), $fields);

      $result = RequiredFields::getFields(
        array(
          'owner' => array('regex' => '/^\d+$/'),
          'car' => array('regex' => '/^\d+$/'),
          'odo' => array('regex' => '/^\d{1,6}$/'),
          'safetyCheck' => array('regex' => '/^\d+$/')
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
          new Error("Could not update service");
      } else
          new Error("No values to update");
    }
    */
  }
