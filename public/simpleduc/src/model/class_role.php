<?php

class Role{
    
    private $db;
    private $select;
    private $insert;
    private $update;
    private $selectById;
    
    public function __construct($db){
        $this->db = $db;  
        $this->select = $db->prepare("SELECT idRole, labelRole 
									FROM simpleduc_roles 
									ORDER BY labelRole DESC");
        $this->insert = $db->prepare("INSERT INTO simpleduc_roles(labelRole) 
									VALUES(:labelRole)");
        $this->update = $db->prepare("UPDATE simpleduc_roles 
									SET labelRole=:labelRole 
									WHERE idRole=:idRole");
        $this->selectById = $db->prepare("SELECT idRole, labelRole 
										FROM simpleduc_roles 
										WHERE idRole=:idRole");
    }
      
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectById($idRole){
        $this->selectById->execute(array(':idRole'=>$idRole));
        if ($this->selectById->errorCode()!=0){
             print_r($this->selectById->errorInfo());  
        }
        return $this->selectById->fetch();
    }
    
    public function insert($labelRole){
        $r = true;
        $this->insert->execute(array(':labelRole'=>$labelRole));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    public function update($idRole, $labelRole){
        $r = true;
        $this->update->execute(array(':idRole'=>$idRole, ':labelRole'=>$labelRole));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
}

?>
