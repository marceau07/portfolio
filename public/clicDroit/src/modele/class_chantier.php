<?php

class Chantier {
    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO clic_droit_chantiers(nameChantier) 
									VALUES(:nameChantier)");
        $this->select = $db->prepare("SELECT * 
									FROM clic_droit_chantiers");
        $this->update = $db->prepare("UPDATE clic_droit_chantiers 
									SET nameChantier=:nameChantier 
									WHERE idChantier=:idChantier");
        $this->delete = $db->prepare("DELETE FROM clic_droit_chantiers 
									WHERE idChantier=:idChantier");
    }

    public function insert($nameChantier) {
        $r = true;
        $this->insert->execute(array(':nameChantier' => $nameChantier));
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

    public function update($nameChantier, $idChantier) {
        $r = true;
        $this->update->execute(array(':namePrestation' => $nameChantier, ':idChantier' => $idChantier));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idChantier) {
        $r = true;
        $this->delete->execute(array(':idChantier' => $idChantier));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}