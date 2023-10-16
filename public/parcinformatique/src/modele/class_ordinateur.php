<?php

class Ordinateur {

    private $db;
    private $insert;
    private $select;
    private $selectByNetwork;
    private $selectById;
    private $selectCount;
    private $selectDistinctReseau;
    private $update;
    private $updatePcOnOff;
    private $selectCarousel;
    private $delete;
    private $clearScan;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO parc_informatique_ordinateurs(ip, mac, reseau, os, statut) 
									VALUES(:ip, :mac, :reseau, :os, :statut)");
        $this->select = $db->prepare("SELECT idOrdinateur, ip, mac, reseau, nomOs, nomStatut, CONCAT(nomEmploye, ' ', prenomEmploye) AS unEmploye 
									FROM parc_informatique_ordinateurs o 
									INNER JOIN parc_informatique_statuts s ON s.idStatut = o.statut 
									LEFT JOIN parc_informatique_employes e ON e.idEmploye = o.employe 
									INNER JOIN parc_informatique_os os ON os.idOs = o.os 
									ORDER BY idOrdinateur ");
        $this->selectByNetwork = $db->prepare("SELECT idOrdinateur, ip, mac, reseau, nomOs, nomStatut, CONCAT(nomEmploye, ' ', prenomEmploye) AS unEmploye 
											FROM parc_informatique_ordinateurs o 
											INNER JOIN parc_informatique_statuts s ON s.idStatut = o.statut 
											LEFT JOIN parc_informatique_employes e ON e.idEmploye = o.employe 
											INNER JOIN parc_informatique_os os ON os.idOs = o.os 
											WHERE reseau=:idReseau 
											ORDER BY idOrdinateur ");
        $this->selectById = $db->prepare("SELECT idOrdinateur, ip, mac, nomOs, nomStatut, CONCAT(nomEmploye, ' ', prenomEmploye) AS unEmploye 
										FROM parc_informatique_ordinateurs o 
										INNER JOIN parc_informatique_statuts s ON s.idStatut = o.statut 
										LEFT JOIN parc_informatique_employes e ON e.idEmploye = o.employe 
										INNER JOIN parc_informatique_os os ON os.idOs = o.os 
										WHERE idOrdinateur=:idOrdinateur");
        $this->selectCount = $db->prepare("SELECT COUNT(idOrdinateur) AS nb 
										FROM parc_informatique_ordinateurs");
        $this->selectDistinctReseau = $db->prepare("SELECT DISTINCT reseau 
													FROM parc_informatique_ordinateurs");
        $this->selectCarousel = $db->prepare("SELECT idOrdinateur, ip, mac, reseau, nomOs, nomStatut, CONCAT(nomEmploye, ' ', prenomEmploye) AS unEmploye 
											FROM parc_informatique_ordinateurs o 
											INNER JOIN parc_informatique_statuts s ON s.idStatut = o.statut 
											LEFT JOIN parc_informatique_employes e ON e.idEmploye = o.employe 
											INNER JOIN parc_informatique_os os ON os.idOs = o.os 
											WHERE ip LIKE CONCAT('10.239.', :nb, '.%') 
											ORDER BY idOrdinateur ");
        $this->update = $db->prepare("UPDATE parc_informatique_ordinateurs 
									SET ip=:ip, mac=:mac, reseau=:reseau, os=:os, statut=:statut, employe=:employe 
									WHERE idOrdinateur=:idOrdinateur");
        $this->updatePcOnOff = $db->prepare("UPDATE parc_informatique_ordinateurs 
											SET statut=:statut 
											WHERE reseau=:reseau");
        $this->delete = $db->prepare("DELETE FROM parc_informatique_ordinateurs 
									WHERE idOrdinateur=:idOrdinateur");
        $this->clearScan = $db->prepare("DELETE FROM parc_informatique_ordinateurs");
    }

    public function insert($ip, $mac, $reseau, $os, $status) {
        $r = true;
        echo $ip . " ";
        $t = $this->insert->execute(array(':ip' => $ip, ':mac' => $mac, ':reseau' => $reseau, ':os' => $os, ':statut' => $status));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            //print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectByNetwork($idReseau) {
        $this->selectByNetwork->execute(array(':idReseau' => $idReseau));
        if ($this->selectByNetwork->errorCode() != 0) {
            //print_r($this->selectByNetwork->errorInfo());
        }
        return $this->selectByNetwork->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectById($idOrdinateur) {
        $this->selectById->execute(array(':idOrdinateur' => $idOrdinateur));
        if ($this->selectById->errorCode() != 0) {
            //print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function selectCount() {
        $this->selectCount->execute();
        if ($this->selectCount->errorCode() != 0) {
            //print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }

    public function selectDistinctReseau() {
        $this->selectDistinctReseau->execute();
        if ($this->selectDistinctReseau->errorCode() != 0) {
            print_r($this->selectDistinctReseau->errorInfo());
        }
        return $this->selectDistinctReseau->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectCarousel($nb) {
        $this->selectCarousel->execute(array(':nb' => $nb));
        if ($this->selectCarousel->errorCode() != 0) {
            print_r($this->selectCarousel->errorInfo());
        }
        return $this->selectCarousel->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($ip, $mac, $reseau, $os, $statut, $employe) {
        $r = true;
        $this->update->execute(array(':ip' => $ip, ':mac' => $mac, ':reseau' => $reseau, ':os' => $os, ':statut' => $statut, ':employe' => $employe));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updatePcOnOff($statut, $reseau) {
        $r = true;
        $this->updatePcOnOff->execute(array(':statut' => $statut,':reseau' => $reseau));
        if ($this->updatePcOnOff->errorCode() != 0) {
            print_r($this->updatePcOnOff->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idOrdinateur) {
        $r = true;
        $this->delete->execute(array(':idOrdinateur' => $idOrdinateur));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function clearScan() {
        $r = true;
        $this->clearScan->execute();
        if ($this->clearScan->errorCode() != 0) {
            print_r($this->clearScan->errorInfo());
            $r = false;
        }
        return $r;
    }

}
