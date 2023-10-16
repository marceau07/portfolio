<?php

class Carte {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $lastInsert;
    private $selectByPrestation;
    private $selectByChantier;
    private $selectByChantierPrestation;
    private $selectByMonth;
    private $selectByMonthChantier;
    private $selectByMonthChantierPrestation;
    private $selectByMonthPrestation;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO clic_droit_cartes(idChantier, idPrestation, dateJanvier, dateFevrier, dateMars, dateAvril, dateMai, dateJuin, dateJuillet, dateAout, dateSeptembre, dateOctobre, dateNovembre, dateDecembre) 
									VALUES(:idChantier, :idPrestation, :dateJanvier, :dateFevrier, :dateMars, :dateAvril, :dateMai, :dateJuin, :dateJuillet, :dateAout, :dateSeptembre, :dateOctobre, :dateNovembre, :dateDecembre)");
        $this->select = $db->prepare("SELECT *, SUM(`dateJanvier`+`dateFevrier`+`dateMars`+`dateAvril`+`dateMai`+`dateJuin`+`dateJuillet`+`dateAout`+`dateSeptembre`+`dateOctobre`+`dateNovembre`+`dateDecembre`) AS 'total' 
									FROM clic_droit_cartes
									INNER JOIN clic_droit_prestations ON clic_droit_prestations.idPrestation=clic_droit_cartes.idPrestation
									INNER JOIN clic_droit_chantiers ON clic_droit_chantiers.idChantier=clic_droit_cartes.idChantier
									GROUP BY clic_droit_cartes.idCarte, clic_droit_cartes.idChantier, clic_droit_cartes.idPrestation");
        $this->selectById = $db->prepare("SELECT *, SUM(`dateJanvier`+`dateFevrier`+`dateMars`+`dateAvril`+`dateMai`+`dateJuin`+`dateJuillet`+`dateAout`+`dateSeptembre`+`dateOctobre`+`dateNovembre`+`dateDecembre`) AS 'total' 
										FROM clic_droit_cartes
										INNER JOIN clic_droit_prestations ON clic_droit_prestations.idPrestation=clic_droit_cartes.idPrestation
										INNER JOIN clic_droit_chantiers ON clic_droit_chantiers.idChantier=clic_droit_cartes.idChantier
										WHERE clic_droit_cartes.idCarte=:idCarte
										GROUP BY clic_droit_cartes.idCarte, clic_droit_cartes.idChantier, clic_droit_cartes.idPrestation");
        $this->lastInsert = $db->prepare("SELECT *, SUM(`dateJanvier`+`dateFevrier`+`dateMars`+`dateAvril`+`dateMai`+`dateJuin`+`dateJuillet`+`dateAout`+`dateSeptembre`+`dateOctobre`+`dateNovembre`+`dateDecembre`) AS 'total' 
										FROM clic_droit_cartes 
										INNER JOIN clic_droit_prestations ON clic_droit_prestations.idPrestation=clic_droit_cartes.idPrestation 
										INNER JOIN clic_droit_chantiers ON clic_droit_chantiers.idChantier=clic_droit_cartes.idChantier 
										WHERE idCarte=(SELECT MAX(idCarte) 
														FROM clic_droit_cartes
										)");
        $this->selectByPrestation = $db->prepare("SELECT *, SUM(`dateJanvier`+`dateFevrier`+`dateMars`+`dateAvril`+`dateMai`+`dateJuin`+`dateJuillet`+`dateAout`+`dateSeptembre`+`dateOctobre`+`dateNovembre`+`dateDecembre`) AS 'total' 
												FROM `clic_droit_cartes`
												INNER JOIN clic_droit_prestations ON clic_droit_prestations.idPrestation=clic_droit_cartes.idPrestation
												INNER JOIN clic_droit_chantiers ON clic_droit_chantiers.idChantier=clic_droit_cartes.idChantier
												WHERE clic_droit_cartes.idPrestation=:idPrestation
												GROUP BY clic_droit_cartes.idCarte, clic_droit_cartes.idChantier, clic_droit_cartes.idPrestation");
        $this->selectByChantier = $db->prepare("SELECT *, SUM(`dateJanvier`+`dateFevrier`+`dateMars`+`dateAvril`+`dateMai`+`dateJuin`+`dateJuillet`+`dateAout`+`dateSeptembre`+`dateOctobre`+`dateNovembre`+`dateDecembre`) AS 'total' 
												FROM clic_droit_cartes
												INNER JOIN clic_droit_prestations ON clic_droit_prestations.idPrestation=clic_droit_cartes.idPrestation
												INNER JOIN clic_droit_chantiers ON clic_droit_chantiers.idChantier=clic_droit_cartes.idChantier
												WHERE clic_droit_cartes.idChantier=:idChantier
												GROUP BY clic_droit_cartes.idCarte, clic_droit_cartes.idChantier, clic_droit_cartes.idPrestation");
        $this->selectByChantierPrestation = $db->prepare("SELECT *, SUM(`dateJanvier`+`dateFevrier`+`dateMars`+`dateAvril`+`dateMai`+`dateJuin`+`dateJuillet`+`dateAout`+`dateSeptembre`+`dateOctobre`+`dateNovembre`+`dateDecembre`) AS 'total' 
														FROM clic_droit_cartes
														INNER JOIN clic_droit_prestations ON clic_droit_prestations.idPrestation=clic_droit_cartes.idPrestation
														INNER JOIN clic_droit_chantiers ON clic_droit_chantiers.idChantier=clic_droit_cartes.idChantier
														WHERE clic_droit_cartes.idChantier=:idChantier AND clic_droit_cartes.idPrestation=:idPrestation
														GROUP BY clic_droit_cartes.idCarte, clic_droit_cartes.idChantier, clic_droit_cartes.idPrestation");
        $this->selectByMonth = $db->prepare("SELECT SUM(`dateJanvier`) AS 'janvier', SUM(`dateFevrier`) AS 'fevrier', SUM(`dateMars`) AS 'mars', SUM(`dateAvril`) AS 'avril', SUM(`dateMai`) AS 'mai', SUM(`dateJuin`) AS 'juin', SUM(`dateJuillet`) AS 'juillet', SUM(`dateAout`) AS 'aout', SUM(`dateSeptembre`) AS 'septembre', SUM(`dateOctobre`) AS 'octobre', SUM(`dateNovembre`) AS 'novembre', SUM(`dateDecembre`) AS 'decembre'
											FROM clic_droit_cartes");
        $this->selectByMonthChantierPrestation = $db->prepare("SELECT
																	SUM(`dateJanvier`) AS 'janvier',
																	SUM(`dateFevrier`) AS 'fevrier',
																	SUM(`dateMars`) AS 'mars',
																	SUM(`dateAvril`) AS 'avril',
																	SUM(`dateMai`) AS 'mai',
																	SUM(`dateJuin`) AS 'juin',
																	SUM(`dateJuillet`) AS 'juillet',
																	SUM(`dateAout`) AS 'aout',
																	SUM(`dateSeptembre`) AS 'septembre',
																	SUM(`dateOctobre`) AS 'octobre',
																	SUM(`dateNovembre`) AS 'novembre',
																	SUM(`dateDecembre`) AS 'decembre'
																FROM clic_droit_cartes
																WHERE clic_droit_cartes.idChantier=:idChantier 
																AND clic_droit_cartes.idPrestation=:idPrestation");
        $this->selectByMonthChantier = $db->prepare("SELECT
														SUM(`dateJanvier`) AS 'janvier',
														SUM(`dateFevrier`) AS 'fevrier',
														SUM(`dateMars`) AS 'mars',
														SUM(`dateAvril`) AS 'avril',
														SUM(`dateMai`) AS 'mai',
														SUM(`dateJuin`) AS 'juin',
														SUM(`dateJuillet`) AS 'juillet',
														SUM(`dateAout`) AS 'aout',
														SUM(`dateSeptembre`) AS 'septembre',
														SUM(`dateOctobre`) AS 'octobre',
														SUM(`dateNovembre`) AS 'novembre',
														SUM(`dateDecembre`) AS 'decembre'
													FROM clic_droit_cartes
													WHERE clic_droit_cartes.idChantier=:idChantier");
        $this->selectByMonthPrestation = $db->prepare("SELECT
															SUM(`dateJanvier`) AS 'janvier',
															SUM(`dateFevrier`) AS 'fevrier',
															SUM(`dateMars`) AS 'mars',
															SUM(`dateAvril`) AS 'avril',
															SUM(`dateMai`) AS 'mai',
															SUM(`dateJuin`) AS 'juin',
															SUM(`dateJuillet`) AS 'juillet',
															SUM(`dateAout`) AS 'aout',
															SUM(`dateSeptembre`) AS 'septembre',
															SUM(`dateOctobre`) AS 'octobre',
															SUM(`dateNovembre`) AS 'novembre',
															SUM(`dateDecembre`) AS 'decembre'
														FROM clic_droit_cartes
														WHERE clic_droit_cartes.idPrestation=:idPrestation");
        $this->update = $db->prepare("UPDATE clic_droit_cartes SET idChantier=:idChantier, idPrestation=:idPrestation, dateJanvier=:dateJanvier, dateFevrier=:dateFevrier, dateMars=:dateMars, dateAvril=:dateAvril, dateMai=:dateMai, dateJuin=:dateJuin, dateJuillet=:dateJuillet, dateAout=:dateAout, dateSeptembre=:dateSeptembre, dateOctobre=:dateOctobre, dateNovembre=:dateNovembre, dateDecembre=:dateDecembre WHERE idCarte=:idCarte");
        $this->delete = $db->prepare("DELETE FROM clic_droit_cartes WHERE idCarte=:idCarte");
    }

    public function insert($idChantier, $idPrestation, $dateJanvier, $dateFevrier, $dateMars, $dateAvril, $dateMai, $dateJuin, $dateJuillet, $dateAout, $dateSeptembre, $dateOctobre, $dateNovembre, $dateDecembre) {
        $r = true;
        $this->insert->execute(array(':idChantier' => $idChantier, ':idPrestation' => $idPrestation, ':dateJanvier' => $dateJanvier, ':dateFevrier' => $dateFevrier, ':dateMars' => $dateMars, ':dateAvril' => $dateAvril, ':dateMai' => $dateMai, ':dateJuin' => $dateJuin, ':dateJuillet' => $dateJuillet, ':dateAout' => $dateAout, ':dateSeptembre' => $dateSeptembre, ':dateOctobre' => $dateOctobre, ':dateNovembre' => $dateNovembre, ':dateDecembre' => $dateDecembre));
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

    public function selectById($idCarte) {
        $this->selectById->execute(array(':idCarte' => $idCarte));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function selectByPrestation($idPrestation) {
        $this->selectByPrestation->execute(array(':idPrestation' => $idPrestation));
        if ($this->selectByPrestation->errorCode() != 0) {
            print_r($this->selectByPrestation->errorInfo());
        }
        return $this->selectByPrestation->fetchAll();
    }

    public function selectByChantier($idChantier) {
        $this->selectByChantier->execute(array(':idChantier' => $idChantier));
        if ($this->selectByChantier->errorCode() != 0) {
            print_r($this->selectByChantier->errorInfo());
        }
        return $this->selectByChantier->fetchAll();
    }

    public function selectByChantierPrestation($idChantier, $idPrestation) {
        $this->selectByChantierPrestation->execute(array(':idChantier' => $idChantier, ':idPrestation' => $idPrestation));
        if ($this->selectByChantierPrestation->errorCode() != 0) {
            print_r($this->selectByChantierPrestation->errorInfo());
        }
        return $this->selectByChantierPrestation->fetchAll();
    }

    public function selectByMonth() {
        $this->selectByMonth->execute();
        if ($this->selectByMonth->errorCode() != 0) {
            print_r($this->selectByMonth->errorInfo());
        }
        return $this->selectByMonth->fetch();
    }
    
    public function selectByMonthChantierPrestation($idChantier, $idPrestation) {
        $this->selectByMonthChantierPrestation->execute(array(':idChantier' => $idChantier, ':idPrestation' => $idPrestation));
        if ($this->selectByMonthChantierPrestation->errorCode() != 0) {
            print_r($this->selectByMonthChantierPrestation->errorInfo());
        }
        return $this->selectByMonthChantierPrestation->fetch();
    }

    public function selectByMonthChantier($idChantier) {
        $this->selectByMonthChantier->execute(array(':idChantier' => $idChantier));
        if ($this->selectByMonthChantier->errorCode() != 0) {
            print_r($this->selectByMonthChantier->errorInfo());
        }
        return $this->selectByMonthChantier->fetch();
    }

    public function selectByMonthPrestation($idPrestation) {
        $this->selectByMonthPrestation->execute(array(':idPrestation' => $idPrestation));
        if ($this->selectByMonthPrestation->errorCode() != 0) {
            print_r($this->selectByMonthPrestation->errorInfo());
        }
        return $this->selectByMonthPrestation->fetch();
    }

    public function update($idChantier, $idPrestation, $dateJanvier, $dateFevrier, $dateMars, $dateAvril, $dateMai, $dateJuin, $dateJuillet, $dateAout, $dateSeptembre, $dateOctobre, $dateNovembre, $dateDecembre, $idCarte) {
        $r = true;
        $this->update->execute(array(':idChantier' => $idChantier, ':idPrestation' => $idPrestation, ':dateJanvier' => $dateJanvier, ':dateFevrier' => $dateFevrier, ':dateMars' => $dateMars, ':dateAvril' => $dateAvril, ':dateMai' => $dateMai, ':dateJuin' => $dateJuin, ':dateJuillet' => $dateJuillet, ':dateAout' => $dateAout, ':dateSeptembre' => $dateSeptembre, ':dateOctobre' => $dateOctobre, ':dateNovembre' => $dateNovembre, ':dateDecembre' => $dateDecembre, ':idCarte' => $idCarte));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idCarte) {
        $r = true;
        $this->delete->execute(array(':idCarte' => $idCarte));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function lastInsert() {
        $this->lastInsert->execute();
        if ($this->lastInsert->errorCode() != 0) {
            print_r($this->lastInsert->errorInfo());
        }
        return $this->lastInsert->fetch();
    }

}
