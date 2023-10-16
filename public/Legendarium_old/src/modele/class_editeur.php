<?php

class Editeur{
    
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO publishers(publisher_label) 
									VALUES(:publisher_label)");             
        $this->select = $db->prepare("SELECT * 
									FROM publishers");
        $this->update = $db->prepare("UPDATE publishers 
									SET publisher_label=:publisher_label 
									WHERE publisher_id=:publisher_id"); 
    }

    public function insert($publisher_label) {
        $r = true;
        $this->insert->execute(array(':publisher_label' => $publisher_label));
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

    public function update($publisher_id, $publisher_label){
        $r = true;
        $this->update->execute(array(':publisher_id' => $publisher_id, ':publisher_label' => $publisher_label));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>