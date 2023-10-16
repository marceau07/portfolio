<?php

class EmployeeSkill {

    private $db;
    private $insert;
    private $select;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_employees_skills(idEmployee, idSkill) 
									VALUES(:idEmployee, :idSkill)");
        $this->select = $db->prepare("SELECT idEmployee, idSkill 
									FROM simpleduc_employees_skills 
									ORDER BY idEmployee");
    }

    public function insert($idEmployee, $idSkill) {
        $r = true;
        $this->insert->execute(array(':idEmployee' => $idEmployee, ':idSkill' => $idSkill));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

}
?>

