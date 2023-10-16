<?php

class Auteur{
    
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_authors(author_last_name, author_first_name, author_biography)
									VALUES(:author_last_name, :author_first_name, :author_biography)");             
        $this->select = $db->prepare("SELECT * 
									FROM legendarium_authors");
        $this->update = $db->prepare("UPDATE legendarium_authors 
									SET author_last_name=:author_last_name, author_first_name=:author_first_name, author_biography=:author_biography 
									WHERE id=:id"); 
    }

    public function insert($author_last_name, $author_first_name, $author_biography) {
        $r = true;
        $this->insert->execute(array(':author_last_name'=>$author_last_name, ':author_first_name'=>$author_first_name, ':author_biography'=>$author_biography));
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

    public function update($author_last_name, $author_first_name, $author_biography){
        $r = true;
        $this->update->execute(array(':author_last_name'=>$author_last_name, ':author_first_name'=>$author_first_name, ':author_biography'=>$author_biography));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>