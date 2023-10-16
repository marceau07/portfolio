<?php

class horaires {

	private $db;
	private $insert;
	private $select;

	public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO julie_bressand_horaires(horaireDeb, horaireFin) 
									VALUES(:horaireDeb, :horaireFin)");
        $this->select = $db->prepare("SELECT * 
									FROM julie_bressand_horaires ");
    }

    public function insert($horaireDeb, $horaireFin) {
        $r = true;
        $this->insert->execute(array(':horaireDeb' => $horaireDeb, ':horaireFin' => $horaireFin));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

}

?>
