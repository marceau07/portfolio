<?php

class Reglement {

    private $db;
    private $insert;
    private $select;
    private $delete;
    private $restore;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO challenge_reglement(typeRegle) 
									VALUES(:typeRegle)");
        $this->select = $db->prepare("SELECT * FROM challenge_reglement");
        $this->update = $db->prepare("UPDATE challenge_reglement 
									SET typeRegle=:typeRegle 
									WHERE idRegle=:idRegle");
        $this->delete = $db->prepare("DELETE FROM challenge_reglement 
									WHERE idRegle=:idRegle");
        $this->restore = $db->prepare("INSERT INTO challenge_reglement (typeRegle) 
									WHERE idRegle=:idRegle");
    }

    public function insert($typeRegle) {
        $r = true;
        $this->insert->execute(array(':typeRegle' => $typeRegle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $listeR = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
    
    public function update($typeRegle, $idRegle) {
        $r = true;
        $this->update->execute(array(':typeRegle'=>$typeRegle, ':idRegle'=>$idRegle));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idRegle) {
        $r = true;
        $this->delete->execute(array(':idRegle' => $idRegle));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function restore($typeRegle, $idRegle) {
        $r = true;
        $this->restore->execute(array(':typeRegle'=>$typeRegle,':idRegle'=>$idRegle));
        if ($this->restore->errorCode() != 0) {
            print_r($this->restore->errorInfo());
            $r = false;
        }
        return $r;
    }

}
