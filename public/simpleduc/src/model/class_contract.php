<?php

class Contract {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_contracts(labelContract, dateSignatureContract, dateBegProject, dateEndProject, costProject) 
									VALUES(:labelContract, :dateSignatureContract, :dateBegProject, :dateEndProject, :costProject)");
        $this->select = $db->prepare("SELECT idContract, labelContract, dateSignatureContract, dateBegProject, dateEndProject, costProject 
									FROM simpleduc_contracts 
									ORDER BY labelContract");
        $this->selectById = $db->prepare("SELECT idContract, labelContract, dateSignatureContract, dateBegProject, dateEndProject, costProject 
										FROM simpleduc_contracts
										WHERE idContract=:idContract 
										ORDER BY labelContract");
        $this->update = $db->prepare("UPDATE simpleduc_contracts 
									SET labelContract=:labelContract, dateSignatureContract=:dateSignatureContract, dateBegProject=:dateBegProject, dateEndProject=:dateEndProject, costProject=:costProject
									WHERE idContract=:idContract");
        $this->delete = $db->prepare("DELETE FROM simpleduc_contracts 
									WHERE idContract=:idContract");
    }

    public function insert($labelContract, $dateSignatureContract, $dateBegProject, $dateEndProject, $costProject) {
        $r = true;
        $this->insert->execute(array(':labelContract' => $labelContract, ':dateSignatureContract' => $dateSignatureContract, ':dateBegProject' => $dateBegProject,
            ':dateEndProject' => $dateEndProject, ':costProject' => $costProject));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
    
     public function selectById($idContract){ 
        $this->selectById->execute(array(':idContract'=>$idContract)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo()); 
            
        }
        return $this->selectById->fetch(); 
    }

    public function update($idContract, $labelContract, $dateSignatureContract, $dateBegProject, $dateEndProject, $costProject) {
        $r = true;
        $this->update->execute(array(':idContract' => $idContract, ':labelContract' => $labelContract, ':dateSignatureContract' => $dateSignatureContract,
            ':dateBegProject' => $dateBegProject, ':dateEndProject' => $dateEndProject, ':costProject' => $costProject));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idContract) {
        $r = true;
        $this->delete->execute(array(':idContract' => $idContract));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
?>

