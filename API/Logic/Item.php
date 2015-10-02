<?php

  class Item {
    private $db;
    private $tableName = 'item';
    public function __construct() { $this->db = new DBase(); }

    public function get($id = null) {
      $sql = "SELECT * FROM {$this->tableName} WHERE `active` = 1";
      if (!empty($id))
        $sql .= " AND `id` = " . $this->db->escape($id);
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
          'description' => array('required' => true, 'regex' => '/^.{1,100}$/'),
          'defaultCost' => array('regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/'),
          'defaultQuantity' => array('regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/'),
          'comment' => array('regex' => '/^(0|1)$/'),
          'active' => array('regex' => '/^(0|1)$/')
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
          new Error("Error inserting item record");
        }
      }
    }

    public function put($id = null) {
      if (empty($id))
        new Error("Item ID cannot be empty");
      $fields = array();
      parse_str(file_get_contents("php://input"), $fields);

      $result = RequiredFields::getFields(
        array(
          'description' => array('regex' => '/^.{1,100}$/'),
          'defaultCost' => array('regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/'),
          'defaultQuantity' => array('regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/'),
          'comment' => array('regex' => '/^(0|1)$/'),
          'active' => array('regex' => '/^(0|1)$/')
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
          new Error("Could not update item");
      } else
          new Error("No values to update");
    }
  }
