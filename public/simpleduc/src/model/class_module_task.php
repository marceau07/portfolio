<?php

class ModuleTask{
    
    private $db;
    private $insert;
    private $select;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_modules_tasks (idTask) 
									VALUES(:idTask)");    
        $this->select = $db->prepare("SELECT idModule, idTask 
									FROM simpleduc_modules_tasks mt 
									ORDER BY nameUser");
         }
    public function insert($idTask){
        $r = true;
        $this->insert->execute(array(':idTask'=>$idTask));
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
  
    
}

?>

