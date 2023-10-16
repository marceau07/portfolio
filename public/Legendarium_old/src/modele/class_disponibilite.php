<?php

class Disponibilite{
    
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_availability(libelle) 
									VALUES(:libelle)");             
        $this->select = $db->prepare("SELECT * 
									FROM legendarium_availability d");
        $this->update = $db->prepare("UPDATE legendarium_availability 
									SET libelle=:libelle
									WHERE id=:id"); 
    }

    public function insert($libelle) {
        $r = true;
        $this->insert->execute(array(':libelle'=>$libelle));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }

    public function update($libelle){
        $r = true;
        $this->update->execute(array(':libelle'=>$libelle));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>