<?php

class Module {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO ktane_module(nameModule, redirect, logoModule) 
									VALUES(:nameModule, :redirect, :logoModule)");
        $this->select = $db->prepare("SELECT * 
									FROM ktane_module
									ORDER BY nameModule DESC");
        $this->delete = $db->prepare("DELETE FROM ktane_module 
									WHERE idModule=:idModule");
    }

    public function insert($nameModule, $redirect, $logoModule) {
        $r = true;
        $this->insert->execute(array(':nameModule' => $nameModule, ':redirect' => $redirect, ':logoModule' => $logoModule));
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

    public function delete($idModule) {
        $r = true;
        $this->delete->execute(array(':idModule' => $idModule));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}
