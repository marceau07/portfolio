<?php

class Employe {

    private $db;
    private $insert;
    private $select;
    private $selectByEmail;
    private $update;
    private $delete;
    private $deleteByEmail;

    public function __construct($db) {
        $this->db = $db;
        $this->connect = $db->prepare("SELECT idEmploye, email, mdp, nomEmploye, prenomEmploye, idRole 
									FROM parc_informatique_employes 
									WHERE email=:email");
        $this->selectByEmail = $db->prepare("SELECT idEmploye, email, mdp, nomEmploye, prenomEmploye, idRole, CONCAT(prenomEmploye, ' ', nomEmploye) AS full_name 
											FROM parc_informatique_employes e 
											WHERE email=:email");
        $this->insert = $db->prepare("INSERT INTO parc_informatique_employes(email, mdp, nomEmploye, prenomEmploye, idRole) 
									VALUES(:email, :mdp, :nomEmploye, :prenomEmploye, :idRole)");
        $this->select = $db->prepare("SELECT idEmploye, email, mdp, nomEmploye, prenomEmploye, descriptionRole 
									FROM parc_informatique_employes e 
									INNER JOIN parc_informatique_roles r ON e.idRole=r.idRole");
        $this->update = $db->prepare("UPDATE parc_informatique_employes 
									SET email=:email, nomEmploye=:nomEmploye, prenomEmploye=:prenomEmploye 
									WHERE idEmploye=:idEmploye");
        $this->delete = $db->prepare("DELETE FROM parc_informatique_employes 
									WHERE idEmploye=:idEmploye");
        $this->deleteByEmail = $db->prepare("DELETE FROM parc_informatique_employes 
											WHERE email=:email");
    }

    public function insert($email, $mdp, $nomEmploye, $prenomEmploye, $idRole) {
        $r = true;
        $this->insert->execute(array(':email' => $email, ':mdp' => $mdp, ':nomEmploye' => $nomEmploye, ':prenomEmploye' => $prenomEmploye, ':idRole' => $idRole));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function connect($email) {
        $this->connect->execute(array(':email' => $email));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function select() {
        $listeE = $this->select->execute();
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

    public function update($email, $nomEmploye, $prenomEmploye, $idEmploye) {
        $r = true;
        $this->update->execute(array(':email' => $email, ':nomEmploye' => $nomEmploye, ':prenomEmploye' => $prenomEmploye, ':idEmploye' => $idEmploye));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idEmploye) {
        $r = true;
        $this->delete->execute(array(':idEmploye' => $idEmploye));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function deleteByEmail($email) {
        $r = true;
        $this->deleteByEmail->execute(array(':email' => $email));
        if ($this->deleteByEmail->errorCode() != 0) {
            print_r($this->deleteByEmail->errorInfo());
            $r = false;
        }
        return $r;
    }

}
