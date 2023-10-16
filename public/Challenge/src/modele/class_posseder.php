<?php

class Posseder {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO challenge_posseder(codeAct, codeMat, descPack) 
									VALUES(:codeAct, :codeMat, :descPack)");
        $this->select = $db->prepare("SELECT * FROM challenge_posseder p 
									INNER JOIN challenge_activite a ON a.codeAct = p.codeAct 
									INNER JOIN challenge_materiel m ON m.codeMateriel = p.codeMat ");
        $this->delete = $db->prepare("DELETE FROM challenge_posseder 
									WHERE codeAct=:codeAct 
									AND codeMat=:codeMat");
    }

    public function insert($codeAct, $codeMat, $descPack) {
        $r = true;
        $this->insert->execute(array(':codeAct' => $codeAct, ':codeMat' => $codeMat, ':descPack' => $descPack));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $listeA = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function delete($codeAct, $codeMat) {
        $r = true;
        $this->delete->execute(array(':codeAct' => $codeAct, ':codeMat' => $codeMat));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
