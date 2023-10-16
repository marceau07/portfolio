<?php

class rdv {

    private $db;
    private $insert;
    private $selectByDisp;
    private $select;
    private $selectByLast;
    private $emailExist;
    private $selectByTelephone;

    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT date, method, nom, prenom, date_format(horaireDeb, '%T') AS debut, date_format(horaireFin, '%T') AS fin, pseudoskype, adresse, telephone, message 
									FROM julie_bressand_rendez_vous rv 
									INNER JOIN julie_bressand_horaires ON heure=id order ");
        $this->selectByLast = $db->prepare("SELECT date, method, nom,prenom, horaireDeb, horaireFin,pseudoskype,adresse,telephone,message 
											FROM julie_bressand_rendez_vous rv 
											INNER JOIN julie_bressand_horaires ON heure=id 
											ORDER BY date DESC, horaireDeb DESC ");
        $this->insert = $db->prepare("REPLACE INTO julie_bressand_rendez_vous(date, method, heure, message, adresse, pseudoskype, nom , prenom, telephone, email) 
									VALUES(:date, :method, :heure, :message, :adresse , :pseudoskype , :nom , :prenom, :telephone, :email)");
        $this->selectByDisp = $db->prepare("(SELECT id, date_format(horaireDeb, '%T') AS horaireDeb, date_format(horaireFin, '%T') AS horaireFin 
											FROM julie_bressand_horaires h 
											WHERE h.id NOT IN(SELECT h.id 
															FROM julie_bressand_horaires h 
															INNER JOIN julie_bressand_rendez_vous rdv ON rdv.heure=h.id 
															WHERE rdv.date=:date)
											)");
        $this->emailExist = $db->prepare("SELECT COUNT(email) AS nb 
										FROM julie_bressand_rendez_vous 
										WHERE email=:email");
        $this->selectByTelephone = $db->prepare("SELECT *, horaireDeb 
												FROM julie_bressand_rendez_vous rv 
												INNER JOIN julie_bressand_horaires h ON h.id=rv.heure 
												WHERE telephone=:telephone");
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function insert($date, $method, $heure, $message, $adresse, $pseudoskype, $nom, $prenom, $telephone, $email) {
        $r = true;
        $this->insert->execute(array(':date' => $date, ':method' => $method, ':heure' => $heure, ':message' => $message, ':adresse' => $adresse, ':pseudoskype' => $pseudoskype, ':nom' => $nom, ':prenom' => $prenom, ':telephone' => $telephone,
            ':email' => $email));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectByTelephone($telephone) {
        $this->selectByTelephone->execute(array(':telephone' => $telephone));
        if ($this->selectByTelephone->errorCode() != 0) {
            print_r($this->selectByTelephone->errorInfo());
        }
        return $this->selectByTelephone->fetchAll();
    }

    public function selectByDisp($date) {
        $this->selectByDisp->execute(array(':date' => $date));
        if ($this->selectByDisp->errorCode() != 0) {
            print_r($this->selectByDisp->errorInfo());
        }
        return $this->selectByDisp->fetchAll();
    }
    
    public function emailExist($email) {
        $this->emailExist->execute(array(':email' => $email));
        if ($this->emailExist->errorCode() != 0) {
            print_r($this->emailExist->errorInfo());
        }
        return $this->emailExist->fetch();
    }

    public function selectByLast() {
        $liste = $this->selectByLast->execute();
        if ($this->selectByLast->errorCode() != 0) {
            print_r($this->selectByLast->errorInfo());
        }
        return $this->selectByLast->fetchAll();
    }

}

?>
