<?php

class Firm {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_firms(nameFirm, cityFirm, zipCodeFirm, streetFirm, telFirm, faxFirm, idContactFirm) VALUES (:nameFirm, :cityFirm, :zipCodeFirm, :streetFirm, :telFirm, :faxFirm, :idContactFirm)");
        $this->select = $db->prepare("SELECT idFirm, nameFirm, cityFirm, zipCodeFirm, streetFirm, telFirm, faxFirm, idContactFirm, C.idContact, C.lastNameContact, C.firstNameContact, C.telContact, C.mailContact 
									FROM simpleduc_firms F 
									INNER JOIN simpleduc_contacts C ON F.idContactFirm = C.idContact 
									ORDER BY idFirm");
        $this->selectById = $db->prepare("SELECT idFirm, nameFirm, cityFirm, zipCodeFirm, streetFirm, telFirm, faxFirm, idContactFirm, C.idContact, C.lastNameContact, C.firstNameContact, C.telContact, C.mailContact 
										FROM simpleduc_firms F 
										INNER JOIN simpleduc_contacts C ON F.idContactFirm = C.idContact
										WHERE idFirm =:idFirm
										ORDER BY cityFirm");
        $this->update = $db->prepare("UPDATE simpleduc_firms 
									SET nameFirm=:nameFirm, cityFirm=:cityFirm, zipCodeFirm=:zipCodeFirm, streetFirm=:streetFirm, telFirm=:telFirm, faxFirm=:faxFirm, idContactFirm=:idContactFirm 
									WHERE idFirm=:idFirm");
        $this->delete = $db->prepare("DELETE FROM simpleduc_firms 
									WHERE idFirm=:idFirm");
        $this->selectContractSignature = $db->prepare("SELECT F.idFirm, F.nameFirm, C.idContract, C.labelContract, C.dateSignatureContract 
													FROM simpleduc_firms F 
													INNER JOIN simpleduc_specifications S ON F.idFirm = S.idFirmSpecification
													INNER JOIN simpleduc_contracts C ON S.idContractSpecification = C.idContract 
													ORDER BY idFirm");
        $this->selectCostProject = $db->prepare("SELECT idProject, nameProject, C.costProject, SUM(T.costTask), T.idTask 
												FROM PROJECT P
												INNER JOIN simpleduc_contracts C ON P.idProject = C.idContract
												INNER JOIN simpleduc_modules M ON P.idModuleProject = M.idModule
												INNER JOIN simpleduc_modules_tasks MT ON M.idModule = MT.idModule
												INNER JOIN simpleduc_tasks T ON MT.idTask = T.idTask");
    }

    public function insert($nameFirm, $cityFirm, $zipCodeFirm, $streetFirm, $telFirm, $faxFirm, $idContactFirm) {
        $r = true;
        $this->insert->execute(array(':nameFirm' => $nameFirm, ':cityFirm' => $cityFirm, ':zipCodeFirm' => $zipCodeFirm, ':streetFirm' => $streetFirm, ':telFirm' => $telFirm, ':faxFirm' => $faxFirm, ':idContactFirm' => $idContactFirm));
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

    public function selectById($idFirm) {
        $this->selectById->execute(array(':idFirm' => $idFirm));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($idFirm, $nameFirm, $cityFirm, $zipCodeFirm, $streetFirm, $telFirm, $faxFirm, $idContactFirm) {
        $r = true;
        $this->update->execute(array(':idFirm' => $idFirm, ':nameFirm' => $nameFirm, ':cityFirm' => $cityFirm, ':zipCodeFirm' => $zipCodeFirm, ':streetFirm' => $streetFirm, ':telFirm' => $telFirm, ':faxFirm' => $faxFirm, ':idContactFirm' => $idContactFirm));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idFirm) {
        $r = true;
        $this->delete->execute(array(':idFirm' => $idFirm));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
?>

