<?php

class User{
    
    private $db;
    private $insert;
    private $connect;
    private $select;
    private $selectByEmail;
    private $update;
    private $updateMdp;
    private $delete;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_users(emailUser, passwordUser, lastNameUser, firstNameUser, idRoleUser) 
									VALUES(:emailUser, :passwordUser, :lastNameUser, :firstNameUser, :idRoleUser)");    
        $this->connect = $db->prepare("SELECT emailUser, idRoleUser, passwordUser 
									FROM simpleduc_users 
									WHERE emailUser=:emailUser");
        $this->select = $db->prepare("SELECT emailUser, idRoleUser, lastNameUser, firstNameUser, idRoleUser, labelRole 
									FROM simpleduc_users u 
									INNER JOIN simpleduc_roles r ON r.idRole = u.idRoleUser 
									ORDER BY lastNameUser");
        $this->selectByEmail = $db->prepare("SELECT emailUser, lastNameUser, firstNameUser, idRoleUser 
											FROM simpleduc_users 
											WHERE emailUser=:emailUser");
        $this->update = $db->prepare("UPDATE simpleduc_users 
									SET lastNameUser=:lastNameUser, firstNameUser=:firstNameUser, idRoleUser=:idRoleUser 
									WHERE emailUser=:emailUser");
        $this->updateMdp = $db->prepare("UPDATE simpleduc_users 
										SET passwordUser=:passwordUser 
										WHERE emailUser=:emailUser");
        $this->delete = $db->prepare("DELETE FROM simpleduc_users 
									WHERE emailUser=:idUser");
        }
        
    public function insert($emailUser, $passwordUser, $lastNameUser, $firstNameUser, $idRoleUser){
        $r = true;
        $this->insert->execute(array(':emailUser'=>$emailUser, ':passwordUser'=>$passwordUser, ':lastNameUser'=>$lastNameUser, ':firstNameUser'=>$firstNameUser, ':idRoleUser'=>$idRoleUser));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function connect($emailUser){  
        $aUser = $this->connect->execute(array(':emailUser'=>$emailUser));
        if ($this->connect->errorCode()!=0){
             print_r($this->connect->errorInfo());  
        }
        return $this->connect->fetch();
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectByEmail($emailUser){ 
        $this->selectByEmail->execute(array(':emailUser'=>$emailUser)); 
        if ($this->selectByEmail->errorCode()!=0){
            print_r($this->selectByEmail->errorInfo()); 
            
        }
        return $this->selectByEmail->fetch(); 
    }
    
    public function update( $lastNameUser, $firstNameUser,$idRoleUser,$emailUser){
        $r = true;
        $this->update->execute(array(':lastNameUser'=>$lastNameUser, ':firstNameUser'=>$firstNameUser, ':idRoleUser'=>$idRoleUser, ':emailUser'=>$emailUser));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function updateMdp($emailUser, $passwordUser){
        $r = true;
        $this->updateMdp->execute(array(':emailUser'=>$emailUser, ':passwordUser'=>$passwordUser));
        if ($this->update->errorCode()!=0){
             print_r($this->updateMdp->errorInfo());  
             $r=false;
        }
        return $r;
    }
    public function delete($idUser){
        $r = true;
        $this->delete->execute(array(':idUser'=>$idUser));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
}

?>

