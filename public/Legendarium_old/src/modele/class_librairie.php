<?php

class Librairie{
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_library(library_picture, library_title, library_label) 
									VALUES(:library_picture, :library_title, :library_label) ");             
        $this->select = $db->prepare("SELECT library_picture, library_title, library_label 
									FROM legendarium_library ");
        $this->update = $db->prepare("UPDATE legendarium_library 
									SET library_picture=:library_picture, library_title=:library_title, library_label=:library_label 
									WHERE id=:id ");
        
    }

    public function insert($library_picture, $library_title, $library_label) {
        $r = true;
        $this->insert->execute(array(':library_picture'=>$library_picture, ':library_title'=>$library_title, 'library_label'=>$library_label));
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

    public function update($library_picture, $library_title, $library_label){
        $r = true;
        $this->update->execute(array(':library_picture'=>$library_picture, ':library_title'=>$library_title, 'library_label'=>$library_label));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>