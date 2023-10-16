<?php

class Prestation {
    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO clic_droit_prestations(namePrestation) 
									VALUES(:namePrestation)");
        $this->select = $db->prepare("SELECT * 
									FROM clic_droit_prestations");
        $this->update = $db->prepare("UPDATE clic_droit_prestations 
									SET namePrestation=:namePrestation 
									WHERE idPrestation=:idPrestation");
        $this->delete = $db->prepare("DELETE FROM clic_droit_prestations 
									WHERE idPrestation=:idPrestation");
    }

    public function insert($namePrestation) {
        $r = true;
        $this->insert->execute(array(':namePrestation' => $namePrestation));
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

    public function update($namePrestation, $idPrestation) {
        $r = true;
        $this->update->execute(array(':namePrestation' => $namePrestation, ':idPrestation' => $idPrestation));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idPrestation) {
        $r = true;
        $this->delete->execute(array(':idPrestation' => $idPrestation));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}