<?php

class Genre{
    
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO types_books(type_book_label)
									VALUES(:type_book_label)");             
        $this->select = $db->prepare("SELECT * 
									FROM types_books");
        $this->update = $db->prepare("UPDATE types_books 
									SET type_book_label=:type_book_label 
									WHERE type_book_id=:type_book_id"); 
    }

    public function insert($type_book_label) {
        $r = true;
        $this->insert->execute(array(':type_book_label' => $type_book_label));
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

    public function update($type_book_id, $type_book_label){
        $r = true;
        $this->update->execute(array(':type_book_id' => $type_book_id, ':type_book_label' => $type_book_label));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>