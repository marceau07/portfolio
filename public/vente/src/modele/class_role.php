<?php

class Role {

    private $db;
    private $select;
    private $insert;
    private $update;
    private $selectById;

    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("select id, libelle from role r");
        $this->insert = $db->prepare("insert into role (libelle) values (:libelle)");
        $this->update = $db->prepare("update role set libelle=:libelle where id=:id");
        $this->selectById = $db->prepare("select id, libelle from role where id=:id");
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
    
    public function insert($libelle) { //Etape 3
        $t = true;
        $this->insert->execute(array(':libelle' => $libelle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $t = false;
        }
        return $t;
    }
    
    public function selectById($id) {
        $this->selectById->execute(array(':id' => $id));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $libelle) {
        $r = true;
        $this->update->execute(array(':id' => $id, ':libelle' => $libelle));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

}

?>