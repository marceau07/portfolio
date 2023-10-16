<?php

class EmployeeTeam {

    private $db;
    private $insert;
    private $select;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_employees_teams(idEmployee, idTeam) 
									VALUES(:idEmployee, :idTeam)");
        $this->select = $db->prepare("SELECT idEmployee, idTeam 
									FROM simpleduc_employees_teams 
									ORDER BY idEmployee");
    }

    public function insert($idEmployee, $idTeam) {
        $r = true;
        $this->insert->execute(array(':idEmployee' => $idEmployee, ':idTeam' => $idTeam));
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

