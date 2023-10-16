<?php

class Utilisateur{
    
    private $db;
    private $insert; 
    private $connect;
    private $select;
    private $selectByEmail;
    private $update;
    private $updateMdp;
    private $selectMonCompte;
    private $delete;
    private $mailSender;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_users(email, nom, prenom, pseudo, telephone, mdp, photo, idRole) 
									VALUES(:email, UPPER(:nom), :prenom, :pseudo, :telephone, :mdp, :photo, :role)");             
        $this->connect = $db->prepare("SELECT * 
									FROM legendarium_users 
									WHERE email=:email");
        $this->select = $db->prepare("SELECT id, email, nom, prenom, pseudo, telephone, photo, u.idRole 
									FROM legendarium_users u");
        $this->selectByEmail = $db->prepare("SELECT * 
											FROM legendarium_users 
											WHERE email=:email");
        $this->update = $db->prepare("UPDATE legendarium_users 
									SET nom=:nom, prenom=:prenom, pseudo=:pseudo, telephone=:telephone, mdp=:mdp, idRole=:role 
									WHERE email=:email");
        $this->updateMdp = $db->prepare("UPDATE legendarium_users 
										SET mdp=:mdp 
										WHERE email=:email");
        $this->selectMonCompte = $db->prepare("SELECT email, nom, prenom, pseudo, telephone, r.libelleRole, photo 
											FROM legendarium_users u, 
											INNER JOIN legendarium_roles r ON role_id = idRole
											WHERE email=:email ");
        $this->delete = $db->prepare("DELETE FROM legendarium_users 
									WHERE id=:id");
        $this->mailSender = $db->prepare("SELECT email 
										FROM legendarium_users");
    }

    public function insert($email, $nom, $prenom, $pseudo, $telephone, $mdp, $photo, $role) {
        $r = true;
        $this->insert->execute(array(':email'=>$email, ':nom'=>$nom, ':prenom'=>$prenom, ':pseudo'=>$pseudo, ':telephone'=>$telephone, ':mdp'=>$mdp, ':photo'=>$photo, ':role'=>$role));
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
    
    public function mailSender() {
        $liste = $this->mailSender->execute();
        if ($this->mailSender->errorCode()!=0){
            print_r($this->mailSender->errorInfo());  
        }
        return $this->mailSender->fetchAll();
    }
    
    public function selectByEmail($email) {
        $this->selectByEmail->execute(array(':email'=>$email));
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }

    public function update($nom, $prenom, $pseudo, $telephone, $mdp, $role){
        $r = true;
        $this->update->execute(array(':nom'=>$nom, ':prenom'=>$prenom, ':pseudo'=>$pseudo, ':telephone'=>$telephone, ':mdp'=>$mdp, ':role'=>$role));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
        $r=false;
        }
        return $r;
    }
    
    public function updateMdp($mdp){
        $r = true;
        $this->updateMdp->execute(array(':mdp'=>$mdp));
        if ($this->updateMdp->errorCode()!=0){
            print_r($this->updateMdp->errorInfo());
        $r=false;
        }
        return $r;
    }
    
    public function selectMonCompte($email) {
        $this->selectMonCompte->execute(array(':email'=>$email));
        if ($this->selectMonCompte->errorCode()!=0){
            print_r($this->selectMonCompte->errorInfo());
        }
        return $this->selectMonCompte->fetch();
    }
    public function delete($id){
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>