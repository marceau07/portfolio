<?php

class Client {

    private $db;
    private $insert;
    private $select;
    private $selectByEmail;
    private $update;

    public function __construct($db) { 
        $this->db = $db;
        $this->connect = $db->prepare("SELECT idCli, email, mdp, idRole FROM challenge_client WHERE email=:email");
        $this->insert = $db->prepare("INSERT INTO challenge_client(email, mdp, nom, prenom, dateNaiss, cp, ville, rue, tel, idRole) 
									VALUES(:email, :mdp, :nom, :prenom, :dateNaiss, :cp, :ville, :rue, :tel, :idRole)");
        $this->select = $db->prepare("SELECT idCli, nom, prenom, dateNaiss, cp, ville, rue, tel, idRole FROM challenge_client c");
        $this->selectByEmail = $db->prepare("SELECT * FROM challenge_client c WHERE email=:email");
        $this->update = $db->prepare("UPDATE challenge_client SET nom=:nom, prenom=:prenom, dateNaiss=:dateNaiss, cp=:cp, ville=:ville, rue=:rue, tel=:tel WHERE email=:email");
        $this->delete = $db->prepare("DELETE FROM challenge_client WHERE email=:email");
    }

    public function connect($email) {
        $this->connect->execute(array(':email' => $email));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function insert($email, $mdp, $nom, $prenom, $dateNaiss, $cp, $ville, $rue, $tel, $idRole) { // Étape 3
        $r = true;
        $this->insert->execute(array(':email' => $email, ':mdp' => $mdp, ':nom' => $nom, ':prenom' => $prenom, ':dateNaiss' => $dateNaiss, ':cp' => $cp, ':ville' => $ville, ':rue' => $rue, ':tel' => $tel, ':idRole' => $idRole));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $listeC = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectByEmail($email) {
        $this->selectByEmail->execute(array(':email' => $email));
        if ($this->selectByEmail->errorCode() != 0) {
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }

    public function update($nom, $prenom, $dateNaiss, $cp, $ville, $rue, $tel, $email) {
        $r = true;
        $this->update->execute(array(':nom' => $nom, ':prenom' => $prenom, ':dateNaiss' => $dateNaiss, ':cp' => $cp, ':ville' => $ville, ':rue' => $rue, ':tel' => $tel, ':email' => $email));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($email) {
        $r = true;
        $this->delete->execute(array(':email' => $email));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
