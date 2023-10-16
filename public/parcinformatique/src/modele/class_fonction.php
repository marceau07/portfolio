<?php

class Fonction {

    private $db;
    private $insert;
    private $select;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO parc_informatique_fonctions(titre) 
									VALUES(:titre)");                  
        $this->select = $db->prepare("SELECT * 
									FROM parc_informatique_fonctions");
    }
    
    public function insert($titre) { 
        $r = true;
        $this->insert->execute(array(':titre'=>$titre));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    
    public function select() {
        $listeF = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
	
}