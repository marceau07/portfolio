<?php

class Activite {

    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO challenge_activite(nomAct, descAct) values (:nomAct, :descAct)");
        $this->select = $db->prepare("SELECT a.codeAct, a.nomAct, a.descAct FROM challenge_activite a");
        $this->update = $db->prepare("UPDATE challenge_activite SET nomAct=:nomAct, descAct=:descAct WHERE codeAct=:codeAct");
        $this->delete = $db->prepare("DELETE FROM challenge_activite WHERE codeAct=:codeAct");
    }

    public function insert($nomAct, $descAct) {
        $r = true;
        $this->insert->execute(array(':nomAct' => $nomAct, ':descAct' => $descAct));
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

    public function update($nomAct, $descAct, $codeAct) {
        $r = true;
        $this->update->execute(array(':nomAct' => $nomAct, ':descAct' => $descAct, ':codeAct' => $codeAct));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($codeAct) {
        $r = true;
        $this->delete->execute(array(':codeAct' => $codeAct));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
