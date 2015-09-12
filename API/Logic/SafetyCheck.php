<?php

  class SafetyCheck {
    private $db;
    private $tableName = 'safetyCheck';
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
      $this->db->prepareInsert(array('completed' => 0));
      if ($this->db->insert($this->tableName)) {
        $this->get($this->db->lastId());
      } else {
        new Error("Error inserting safety check record");
      }
    }

    public function put($id = null) {
      if (empty($id))
        new Error("Safety Check ID cannot be empty");
      $fields = array();
      parse_str(file_get_contents("php://input"), $fields);

      $result = RequiredFields::getFields(
        array(
          'completed' => array('required' => true, 'regex' => '/^(0|1)$/')
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
          new Error("Could not update safety check");
      } else
          new Error("No values to update");
    }
  }
