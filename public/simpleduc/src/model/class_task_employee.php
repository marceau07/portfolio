<?php

class TaskEmployee{
    
    private $db;
    private $insert;
    private $select;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_tasks_employees(idTask, idEmployee, dateBeg, dateEnd) 
									VALUES(:idTask, :idEmployee, :dateBeg, :dateEnd)");    
        $this->select = $db->prepare("SELECT * 
									FROM simpleduc_tasks_employees");
       }
    public function insert($idTask, $idEmployee, $dateBeg, $dateEnd){
        $r = true;
        $this->insert->execute(array(':idTask'=>$idTask, ':idEmployee'=>$idEmployee, ':dateBeg'=>$dateBeg, ':dateEnd'=>$dateEnd));
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

