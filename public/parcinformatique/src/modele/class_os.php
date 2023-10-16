<?php

class Os {

    private $db;
    private $insert;
    private $select;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO parc_informatique_os(idOs, nomOs) 
									VALUES(:idOs, :nomOs)");                  
        $this->select = $db->prepare("SELECT * 
									FROM parc_informatique_os");
    }
	
    public function insert($idOs,$nomOs) { 
        $r = true;
        $this->insert->execute(array(':idOs' => $idOs, ':nomOs' => $nomOs));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $listeO = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
}
