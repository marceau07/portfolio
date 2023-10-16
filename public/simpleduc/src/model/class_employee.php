<?php

class Employee{
    
    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $selectTeamNull;
    private $selectTeamNotNull;
    private $update;
    private $updateTeam;
    private $delete;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_employees(lastNameEmployee, firstNameEmployee, cityEmployee, levelEmployee, idTeamEmployee) 
									VALUES(:lastNameEmployee, :firstNameEmployee, :cityEmployee, :levelEmployee, :idTeamEmployee)");    
        $this->select = $db->prepare("SELECT e.idEmployee, e.lastNameEmployee, e.firstNameEmployee, e.cityEmployee, e.levelEmployee, e.idTeamEmployee, t.idTeam, t.nameTeam 
									FROM simpleduc_employees e 
									LEFT JOIN simpleduc_teams t ON e.idTeamEmployee = t.idTeam 
									ORDER BY lastNameEmployee");
        $this->selectById = $db->prepare("SELECT idEmployee, e.lastNameEmployee, e.firstNameEmployee, e.cityEmployee, e.levelEmployee, e.idTeamEmployee, t.idTeam, t.nameTeam 
										FROM simpleduc_employees e 
										LEFT JOIN simpleduc_teams t ON e.idTeamEmployee = t.idTeam 
										WHERE idEmployee=:idEmployee 
										ORDER BY lastNameEmployee");
        $this->selectTeamNull = $db->prepare("SELECT * 
											FROM simpleduc_employees 
											WHERE idTeamEmployee IS NULL");
        $this->selectTeamNotNull = $db->prepare("SELECT * 
												FROM simpleduc_employees 
												WHERE idTeamEmployee IS NOT NULL");
        $this->update = $db->prepare("UPDATE simpleduc_employees 
									SET lastNameEmployee=:lastNameEmployee, firstNameEmployee=:firstNameEmployee, cityEmployee=:cityEmployee, levelEmployee=:levelEmployee, idTeamEmployee=:idTeamEmployee 
									WHERE idEmployee=:idEmployee");
        $this->updateTeam = $db->prepare("UPDATE simpleduc_employees 
										SET idTeamEmployee=:idTeamEmployee 
										WHERE simpleduc_employees.idEmployee=:idEmployee");
        $this->delete = $db->prepare("DELETE FROM simpleduc_employees 
									WHERE idEmployee=:idEmployee");
        }
    public function insert($lastNameEmployee, $firstNameEmployee, $cityEmployee, $levelEmployee, $idTeamEmployee){
        $r = true;
        $this->insert->execute(array(':lastNameEmployee'=>$lastNameEmployee, ':firstNameEmployee'=>$firstNameEmployee, ':cityEmployee'=>$cityEmployee, ':levelEmployee'=>$levelEmployee, ':idTeamEmployee'=>$idTeamEmployee));
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
    
     public function selectById($idEmployee){ 
        $this->selectById->execute(array(':idEmployee'=>$idEmployee)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo()); 
            
        }
        return $this->selectById->fetch(); 
    }
    
     public function selectTeamNull(){ 
        $this->selectTeamNull->execute(); 
        if ($this->selectTeamNull->errorCode()!=0){
            print_r($this->selectTeamNull->errorInfo());
        }
        return $this->selectTeamNull->fetchAll(); 
    }
    
     public function selectTeamNotNull(){ 
        $this->selectTeamNotNull->execute(); 
        if ($this->selectTeamNotNull->errorCode()!=0){
            print_r($this->selectTeamNotNull->errorInfo());
        }
        return $this->selectTeamNotNull->fetchAll(); 
    }
    
    public function update($idEmployee,$lastNameEmployee, $firstNameEmployee, $cityEmployee, $levelEmployee, $idTeamEmployee){
        $r = true;
        $this->update->execute(array(':idEmployee' => $idEmployee,':lastNameEmployee'=>$lastNameEmployee, ':firstNameEmployee'=>$firstNameEmployee, ':cityEmployee'=>$cityEmployee, ':levelEmployee'=>$levelEmployee, ':idTeamEmployee'=>$idTeamEmployee));
        if ($this->update->errorCode()!=0){
			print_r($this->update->errorInfo());  
			$r=false;
        }
        return $r;
    }
    
    public function updateTeam($idTeamEmployee, $idEmployee){
        $r = true;
        $this->updateTeam->execute(array(':idTeamEmployee'=>$idTeamEmployee, ':idEmployee'=>$idEmployee));
        if ($this->updateTeam->errorCode()!=0){
			print_r($this->updateTeam->errorInfo());  
			$r=false;
        }
        return $r;
    }
    
    public function delete($idEmployee){
        $r = true;
        $this->delete->execute(array(':idEmployee'=>$idEmployee));
        if ($this->delete->errorCode()!=0){
			print_r($this->delete->errorInfo());  
			$r=false;
        }
        return $r;
    }
    
}

?>

