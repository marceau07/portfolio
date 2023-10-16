<?php

class Availability {
    private $db;
    private $select;
    private $insert;
    
    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT a.*
                                      FROM legendarium_availability a");
        $this->insert = $db->prepare("INSERT INTO legendarium_availability(availability_label) 
                                    VALUES(:availability_label)");                  
    }
    
    public function insert($availabilityLabel) { 
        $r = true;
        $this->insert->execute(array(
            ':availability_label' => $availabilityLabel
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