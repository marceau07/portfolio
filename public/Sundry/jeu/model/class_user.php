<?php

class Utilisateur {

    private $db;
    private $insert;
    private $select;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO utilisateurs(email, mdp, pseudo, idRole) VALUES(:email, :mdpUtilisateur, :pseudo, :idRole)");                  
        $this->select = $db->prepare("SELECT * FROM utilisateurs");
    }

    public function insert($email, $mdp, $pseudo, $idRole) { 
        $r = true;
        $this->insert->execute(array(':email'=>$email, ':mdp'=>$mdp, ':pseudo'=>$pseudo, ':idRole'=>$idRole));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
}