<?php

class Team {

    private $db;
    private $insert;
    private $select;
    private $select2;
    private $delete;
    private $update;
    private $selectById;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_teams(nameTeam) 
									VALUES(:nameTeam)");
        $this->insertEmployeeTeam = $db->prepare("INSERT INTO simpleduc_employees_teams(idTeam, idEmployee) 
												VALUES(LAST_INSERT_ID(), :idEmployee)");
        $this->select = $db->prepare("SELECT * 
									FROM simpleduc_teams T
								   LEFT JOIN simpleduc_employees_teams ET ON T.idTeam = ET.idTeam
								   LEFT JOIN simpleduc_employees E ON ET.idEmployee = E.idEmployee");
        $this->select2 = $db->prepare("SELECT T.idTeam, T.nameTeam, ET.idEmployee, ET.idEmployee, E.lastNameEmployee, E.firstNameEmployee 
									FROM simpleduc_teams T
									INNER JOIN simpleduc_employees_teams ET ON T.idTeam = ET.idTeam
									INNER JOIN simpleduc_employees E ON ET.idEmployee = E.idEmployee");
        $this->delete = $db->prepare("DELETE FROM simpleduc_teams 
									where idTeam=:idTeam");
        $this->update = $db->prepare("UPDATE simpleduc_teams 
									SET nameTeam=:nameTeam 
									WHERE idTeam=:idTeam");
        $this->updateEmployeeTeam = $db->prepare("UPDATE simpleduc_employees_teams 
												SET idTeam=:idTeam, idEmployee=:idEmployee 
												WHERE idTeam=:idTeam");
        $this->selectById = $db->prepare("SELECT idTeam, nameTeam 
										FROM simpleduc_teams 
										WHERE idTeam=:idTeam 
										ORDER BY nameTeam");
    }

    public function insert($nameTeam) {
        $r = true;
        $this->insert->execute(array(':nameTeam' => $nameTeam));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function insertEmployeeTeam($idEmployee) {
        $r = true;
        $this->insertEmployeeTeam->execute(array(':idEmployee' => $idEmployee));
        if ($this->insertEmployeeTeam->errorCode() != 0) {
            print_r($this->insertEmployeeTeam->errorInfo());
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

    public function select2() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function update($idTeam, $nameTeam) {
        $r = true;
        $this->update->execute(array(':idTeam' => $idTeam, ':nameTeam' => $nameTeam));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateEmployeeTeam($idTeam, $idEmployee) {
        $r = true;
        $this->updateEmployeeTeam->execute(array(':idTeam' => $idTeam, ':idEmployee' => $idEmployee));
        if ($this->updateEmployeeTeam->errorCode() != 0) {
            print_r($this->updateEmployeeTeam->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectById($idTeam) {
        $this->selectById->execute(array(':idTeam' => $idTeam));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function delete($idTeam) {
        $r = true;
        $this->delete->execute(array(':idTeam' => $idTeam));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
?>



