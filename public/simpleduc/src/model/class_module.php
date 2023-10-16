<?php

class Module {

    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;
    private $selectById;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_modules(labelModule, descModule) 
									VALUES(:labelModule, :descModule)");
        $this->select = $db->prepare("SELECT idModule, labelModule, descModule 
									FROM simpleduc_modules 
									ORDER BY labelModule");
        $this->update = $db->prepare("UPDATE simpleduc_modules 
									SET labelModule=:labelModule, descModule=:descModule 
									WHERE idModule=:idModule");
        $this->delete = $db->prepare("DELETE FROM simpleduc_modules 
									WHERE idModule=:idModule");
        $this->selectById = $db->prepare("SELECT idModule, labelModule, descModule 
										FROM simpleduc_modules 
										WHERE idModule=:idModule 
										ORDER BY idModule");
    }

    public function insert($labelModule, $descModule) {
        $r = true;
        $this->insert->execute(array(':labelModule' => $labelModule, ':descModule' => $descModule));
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

    public function update($idModule, $labelModule, $descModule ) {
        $r = true;
        $this->update->execute(array(':idModule' => $idModule, ':labelModule' => $labelModule, ':descModule' => $descModule));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idModule) {
        $r = true;
        $this->delete->execute(array(':idModule' => $idModule));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
    
        public function selectById($idModule){ 
        $this->selectById->execute(array(':idModule'=>$idModule)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo()); 
            
        }
        return $this->selectById->fetch(); 
    }

}
?>

