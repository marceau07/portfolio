<?php

class Type {
    
    private $db;
    private $insert; // Étape 1
    private $connect;
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("insert  into  type(libelle) values (:libelle)"); // Étape 2                    
        $this->connect = $db->prepare("select libelle from type where libelle=:libelle");
        $this->select = $db->prepare("select libelle, id from type t");
        $this->selectById = $db->prepare("select id, libelle from type where id=:id");
        $this->update = $db->prepare("update type set libelle=:libelle where id=:id");
    }

    public function insert($libelle) { // Étape 3
        $t = true;
        $this->insert->execute(array(':libelle'=>$libelle));
        if ($this->insert->errorCode()!=0){
            print_t($this->insert->errorInfo());  
            $t=false;
        }
        return $t;
    }
    
    public function connect($libelle) {
        $this->connect->execute(array(':libelle'=>$libelle));
        if ($this->connect->errorCode()!=0){
            print_t($this->connect->errorInfo());  
        }
        return $this->connect->fetch();
    } 
    
    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_t($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectById($id) {
        $this->selectById->execute(array(':id'=>$id));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
    
    public function update($libelle, $id){
        $r = true;
        $this->update->execute(array(':libelle'=>$libelle, ':id'=>$id));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
        $r=false;
        }
        return $r;
    }
}

?>