<?php

class TypeBook {
    private $db;
    private $select;
    private $insert;
    
    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT tb.*
                                      FROM legendarium_types_books tb");
        $this->insert = $db->prepare("INSERT INTO legendarium_types_books(type_book_label) 
                                    VALUES(:type_book_label)");                  
    }
    
    public function insert($typeBookLabel) { 
        $r = true;
        $this->insert->execute(array(
            ':type_book_label' => $typeBookLabel
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