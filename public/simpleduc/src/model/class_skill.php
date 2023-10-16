<?php

class Skill{
    
    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;
    
    public function __construct($db){
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_skills(nameSkill, descSkill, versionSkill) VALUES(:nameSkill, :descSkill, :versionSkill)");    
        $this->select = $db->prepare("SELECT idSkill, nameSkill, descSkill, versionSkill 
									FROM simpleduc_skills");
        $this->selectById = $db->prepare("SELECT idSkill, nameSkill, descSkill, versionSkill 
										FROM simpleduc_skills 
										WHERE idSkill=:idSkill 
										ORDER BY nameSkill");
        $this->update = $db->prepare("UPDATE simpleduc_skills SET nameSkill=:nameSkill, descSkill=:descSkill, versionSkill=:versionSkill 
									WHERE idSkill=:idSkill");
        $this->delete = $db->prepare("DELETE FROM simpleduc_skills 
									WHERE idSkill=:idSkill");
        }
    public function insert($nameSkill, $descSkill, $versionSkill){
        $r = true;
        $this->insert->execute(array(':nameSkill'=>$nameSkill, ':descSkill'=>$descSkill, ':versionSkill'=>$versionSkill));
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
    
    public function selectById($idSkill){ 
        $this->selectById->execute(array(':idSkill'=>$idSkill)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo()); 
            
        }
        return $this->selectById->fetch(); 
    }
    
    public function update($idSkill, $nameSkill, $descSkill, $versionSkill){
        $r = true;
        $this->update->execute(array(':idSkill'=>$idSkill, ':nameSkill'=>$nameSkill, ':descSkill'=>$descSkill, ':versionSkill'=>$versionSkill));
        if ($this->update->errorCode()!=0){
             print_r($this->update->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
    public function delete($idSkill){
        $r = true;
        $this->delete->execute(array(':idSkill'=>$idSkill));
        if ($this->delete->errorCode()!=0){
             print_r($this->delete->errorInfo());  
             $r=false;
        }
        return $r;
    }
    
}

?>

