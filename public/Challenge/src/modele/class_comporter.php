<?php

class Comporter {

    private $db;
    private $insert;
    private $select;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO challenge_comporter(idAct, idPrest) 
									VALUES(:idAct, :idPrest)");
        $this->select = $db->prepare("SELECT * FROM challenge_comporter");
    }

    public function insert($idAct, $idPrest) {
        $r = true;
        $this->insert->execute(array(':idAct' => $idAct, ':idPrest' => $idPrest));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $listeM = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

}
