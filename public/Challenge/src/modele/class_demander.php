<?php

class Demander {

    private $db;
    private $insert;
    private $select;
    private $selectByEmail;

    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT date_format(dateDeb, '%T') AS heure, date_format(dateDeb, '%d-%m-%Y') 
									FROM challenge_demander");
        $this->selectByEmail = $db->prepare("SELECT date_format(dateDeb, '%T') AS heure, date_format(dateDeb, '%d-%m-%Y') AS jour 
											FROM challenge_demander D 
											INNER JOIN challenge_client C ON C.idCli=D.idCli 
											WHERE email=:email");
        $this->insert = $db->prepare("INSERT INTO challenge_demander(dateDeb, idCli, idPrest, dateFin, idRegle) 
									VALUES(:dateDeb, :idCli, :idPrest, :dateFin, :idRegle)");
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function insert($dateDeb, $idCli, $idPrest, $dateFin, $idRegle) {
        $r = true;
        $this->insert->execute(array(':dateDeb' => $dateDeb, ':idCli'=>$idCli, ':idPrest'=>$idPrest, ':dateFin'=>$dateFin, ':idRegle'=>$idRegle));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function selectByEmail($email) {
        $this->selectByEmail->execute(array(':email' => $email));
        if ($this->selectByEmail->errorCode() != 0) {
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }

}
