<?php

  class Detail {
    private $db;
    private $tableName = 'detail';
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
          'invoice' => array('required' => true, 'regex' => '/^\d+$/'),
          'description' => array('required' => true),
          'comment' => array(),
          'cost' => array('required' => true, 'regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/'),
          'quantity' => array('required' => true, 'regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/')
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
          new Error("Error inserting detail record");
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
          'invoice' => array('regex' => '/^\d+$/'),
          'description' => array(),
          'comment' => array(),
          'cost' => array('regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/'),
          'quantity' => array('regex' => '/^[-+]?([\d]*\.[\d]+|[\d]+)$/')
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
          new Error("Could not update detail");
      } else
          new Error("No values to update");
    }
  }
