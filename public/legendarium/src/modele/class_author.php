<?php

class Author {
    private $db;
    private $select;
    private $insert;
    
    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT a.*
                                      FROM legendarium_authors a");
        $this->insert = $db->prepare("INSERT INTO legendarium_authors (author_last_name, author_first_name, author_biography) 
                                    VALUES(:author_last_name, :author_first_name, :author_biography)");
    }
    
    public function insert($authorLastName, $authorFirstName, $authorBiography) { 
        $r = true;
        $this->insert->execute(array(
            ':author_last_name' => $authorLastName,
            ':author_first_name' => $authorFirstName,
            ':author_biography' => $authorBiography
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