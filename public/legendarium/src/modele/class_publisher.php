<?php

class Publisher {
    private $db;
    private $select;
    private $insert;
    
    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT p.*
                                      FROM legendarium_publishers p");
        $this->insert = $db->prepare("INSERT INTO legendarium_publishers(publisher_label) 
                                    VALUES (:publisher_label)");                  
    }
    
    public function insert($publisherLabel) { 
        $r = true;
        $this->insert->execute(array(
            ':publisher_label' => $publisherLabel
        ));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function select() {
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll(PDO::FETCH_ASSOC);
    }
}