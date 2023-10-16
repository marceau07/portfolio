<?php

class Utilisateur{
    
    private $db;
    private $insert; // Étape 1
    private $connect;
    private $select;
    private $selectByEmail;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("insert  into  utilisateur(email, mdp, nom, prenom, idRole) values (:email, :mdp, :nom, :prenom, :role)"); // Étape 2                    
        $this->connect = $db->prepare("select email, idRole, mdp from utilisateur where email=:email");
        $this->select = $db->prepare("select email, idRole, nom, prenom, r.libelle as libellerole from utilisateur u, role r where u.idRole = r.id order by nom");
        $this->selectByEmail = $db->prepare("select email, nom, prenom, idRole from utilisateur where email=:email");
        $this->update = $db->prepare("update utilisateur set nom=:nom, prenom=:prenom, idRole=:role, mdp=:mdp where email=:email");        
    }

    public function insert($email, $mdp, $role, $nom, $prenom) { // Étape 3
        $r = true;
        $this->insert->execute(array(':email'=>$email, ':mdp'=>$mdp, ':role'=>$role, ':nom'=>$nom, ':prenom'=>$prenom));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function connect($email) {
        $this->connect->execute(array(':email'=>$email));
        if ($this->connect->errorCode()!=0){
            print_r($this->connect->errorInfo());  
        }
        return $this->connect->fetch();
    } 
    
    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectByEmail($email) {
        $this->selectByEmail->execute(array(':email'=>$email));
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }

    public function update($email, $mdp, $nom, $prenom, $role){
        $r = true;
        $this->update->execute(array(':email'=>$email,':mdp'=>$mdp,':nom'=>$nom,':prenom'=>$prenom,':role'=>$role));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
        $r=false;
        }
        return $r;
    }
    
}

?>