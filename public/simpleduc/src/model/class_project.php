<?php

class Project{
    
   private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_projects(nameProject, descProject, idContractProject, idModuleProject ) 
									VALUES(:nameProject, :descProject, :idContractProject, :idModuleProject)");    
        $this->select = $db->prepare("SELECT idProject, nameProject, descProject, idContractProject, idModuleProject, labelContract, labelModule 
									FROM simpleduc_projects P 
									INNER JOIN simpleduc_contracts CT ON P.idContractProject = CT.idContract 
									INNER JOIN simpleduc_modules M ON P.idModuleProject= M.idModule 
									ORDER BY nameProject");
         $this->selectById = $db->prepare("SELECT idProject, nameProject, descProject, idContractProject, idModuleProject, labelModule, labelContract 
										FROM simpleduc_projects P 
										INNER JOIN simpleduc_contracts CT ON P.idContractProject = CT.idContract 
										INNER JOIN simpleduc_modules M ON P.idModuleProject= M.idModule 
										WHERE idProject=:idProject 
										ORDER BY nameProject");
        $this->update = $db->prepare("UPDATE simpleduc_projects 
									SET nameProject=:nameProject, descProject=:descProject, idContractProject=:idContractProject, idModuleProject=:idModuleProject 
									WHERE idProject=:idProject");
        $this->delete = $db->prepare("DELETE FROM simpleduc_projects WHERE idProject=:idProject");
        }
    public function insert($nameProject, $descProject, $idContractProject, $idModuleProject){
        $r = true;
        $this->insert->execute(array(':nameProject'=>$nameProject, ':descProject'=>$descProject, ':idContractProject'=>$idContractProject, ':idModuleProject'=>$idModuleProject));
        if ($this->insert->errorCode()!=0){
             print_r($this->insert->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function select(){
        $this->select->execute();
        if ($this->select->errorCode()!=0){
             print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectById($idProject){ 
        $this->selectById->execute(array(':idProject'=>$idProject)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo()); 
            
        }
        return $this->selectById->fetch(); 
    }

    
    public function update($idProject, $nameProject, $descProject, $idContractProject, $idModuleProject){
        $r = true;
        $this->update->execute(array(':idProject'=>$idProject, ':nameProject'=>$nameProject, ':descProject'=>$descProject, ':idContractProject'=>$idContractProject, ':idModuleProject'=>$idModuleProject));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }

    public function delete($idProject){
        $r = true;
        $this->delete->execute(array(':idProject'=>$idProject));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
}

?>

