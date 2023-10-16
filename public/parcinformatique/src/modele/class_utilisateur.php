<?php

class Utilisateur {

    private $db;
    private $insert;
    private $select;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO parc_informatique_utilisateurs(id, pseudo, fonction, mdpUtilisateur) 
									VALUES(:id, :pseudo, :fonction, :mdpUtilisateur)");
        $this->select = $db->prepare("SELECT * 
									FROM parc_informatique_utilisateurs u 
									INNER JOIN fonction f ON f.idFonction=u.fonction");
    }
    
    public function insert($id,$pseudo,$fonction,$mdpUtilisateur) { 
        $r = true;
        $this->insert->execute(array(':id'=>$id, ':pseudo'=>$pseudo, ':fonction'=>$fonction, ':mdpUtilisateur'=>$mdpUtilisateur));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $listeU = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
}