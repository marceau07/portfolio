<?php

class Statut {

    private $db;
    private $insert;
    private $select;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO parc_informatique_statuts(nomStatut) 
									VALUES(:nomStatut)");                  
        $this->select = $db->prepare("SELECT * 
									FROM parc_informatique_statuts");
    }
	
    public function insert($nomStatut) { 
        $r = true;
        $this->insert->execute(array(':nomStatut'=>$nomStatut));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $listeS = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
}