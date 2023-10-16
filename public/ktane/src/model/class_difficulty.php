<?php

class Difficulty {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO ktane_difficulty(labelDifficulty, descriptionDifficulty, timer) 
									VALUES(:labelDifficulty, :descriptionDifficulty, :timer)");
        $this->select = $db->prepare("SELECT * 
									FROM ktane_difficulty");
        $this->delete = $db->prepare("DELETE FROM ktane_difficulty 
									WHERE idDifficulty=:idDifficulty");
    }

    public function insert($labelDifficulty, $descriptionDifficulty, $timer) {
        $r = true;
        $this->insert->execute(array(':labelDifficulty' => $labelDifficulty, ':descriptionDifficulty' => $descriptionDifficulty, ':timer' => $timer));
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

    public function delete($idDifficulty) {
        $r = true;
        $this->delete->execute(array(':idDifficulty' => $idDifficulty));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}
