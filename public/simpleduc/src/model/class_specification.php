<?php

class Specification {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_specifications(idContractSpecification, idFirmSpecification, fileSpecification) 
									VALUES(:idContractSpecification, :idFirmSpecification, :fileSpecification)");
        $this->select = $db->prepare("SELECT idContractSpecification, idFirmSpecification, fileSpecification, C.idContract, C.labelContract 
									FROM simpleduc_specifications S 
									INNER JOIN simpleduc_contracts C ON C.idContract = idContractSpecification 
									ORDER BY fileSpecification");
        $this->selectById = $db->prepare("SELECT idContractSpecification, idFirmSpecification, fileSpecification, C.idContract, C.labelContract, nameFirm 
										FROM simpleduc_specifications S 
										INNER JOIN simpleduc_contracts C ON C.idContract = idContractSpecification 
										INNER JOIN simpleduc_firms F ON F.idFirm=S.idFirmSpecification 
										WHERE idContractSpecification=:idContractSpecification 
										AND idFirmSpecification=:idFirmSpecification");
        $this->update = $db->prepare("UPDATE simpleduc_specifications 
									SET fileSpecification=:fileSpecification 
									WHERE idContractSpecification=:idContracstSpecification 
									AND idFirmSpecification=:idFirmSpecification");
        $this->delete = $db->prepare("DELETE FROM simpleduc_specifications 
									WHERE idContratSpecification=:idContratSpecification 
									AND idFirmSpecification=:idFirmSpecification");
    }

    public function insert($idContractSpecification, $idFirmSpecification, $fileSpecification) {
        $r = true;
        $this->insert->execute(array(':idContractSpecification' => $idContractSpecification, ':idFirmSpecification' => $idFirmSpecification, ':fileSpecification' => $fileSpecification));
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

    public function selectById($idContractSpecification, $idFirmSpecification) {
        $this->selectById->execute(array(':idContractSpecification' => $idContractSpecification, ':idFirmSpecification' => $idFirmSpecification));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($fileSpecification, $idContractSpecification, $idFirmSpecification) {
        $r = true;
        $this->update->execute(array(':fileSpecification' => $fileSpecification, ':idContractSpecification' => $idContractSpecification, ':idFirmSpecification' => $idFirmSpecification));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idContractSpecification, $idFirmSpecification) {
        $r = true;
        $this->delete->execute(array(':idContractSpecification' => $idContractSpecification, ':idFirmSpecification' => $idFirmSpecification));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
?>

