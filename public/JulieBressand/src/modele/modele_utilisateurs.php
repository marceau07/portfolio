
<?php

class Utilisateur {

    private $db;
    private $insert;
    private $connect;
    private $select;
    private $selectCount;
    private $selectForPanel;
    private $update;
    private $updateMdp;
    private $updateForAvis;
    private $selectLimit;
    private $selectByEmail;
    private $emailExist;
    private $telephoneExist;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO julie_bressand_utilisateurs(nom, prenom,  email, pass ,idrole, telephone) 
									VALUES(:nom, :prenom, :email, :pass , :idrole , :telephone)");
        $this->connect = $db->prepare("SELECT * 
									FROM julie_bressand_utilisateurs 
									WHERE email=:email ");
        $this->select = $db->prepare("SELECT * 
									FROM julie_bressand_utilisateurs u ");
        $this->selectLimit = $db->prepare("SELECT * 
										FROM julie_bressand_utilisateurs 
										LIMIT :inf, :limite");
        $this->selectCount = $db->prepare("SELECT COUNT(*) as nb 
										FROM julie_bressand_utilisateurs");
        $this->selectForPanel = $db->prepare("SELECT * 
											FROM julie_bressand_utilisateurs u 
											ORDER BY nom DESC");
        $this->selectByEmail = $db->prepare("SELECT * 
											FROM julie_bressand_utilisateurs u 
											WHERE email=:email");
        $this->update = $db->prepare("UPDATE julie_bressand_utilisateurs 
									SET nom=:nom, prenom=:prenom, telephone=:telephone, email=:email, pass=:pass 
									WHERE email=:email");
        $this->updateMdp = $db->prepare("UPDATE julie_bressand_utilisateurs 
										SET pass=:pass 
										WHERE email=:email");
        $this->updateForAvis = $db->prepare("UPDATE julie_bressand_utilisateurs 
											SET idAvis=:idAvis 
											WHERE email=:email");
        $this->emailExist = $db->prepare("SELECT COUNT(email) 
										FROM julie_bressand_utilisateurs 
										WHERE email=:email");
        $this->telephoneExist = $db->prepare("SELECT COUNT(telephone) 
											FROM julie_bressand_utilisateurs 
											WHERE telephone=:telephone");
        $this->delete = $db->prepare("DELETE FROM julie_bressand_utilisateurs 
									WHERE email=:email");
    }

    public function selectByEmail($email) {
        $this->selectByEmail->execute(array(':email' => $email));
        if ($this->selectByEmail->errorCode() != 0) {
            print_r($this->selectByEmail->errorInfo());
        }
        return $this->selectByEmail->fetch();
    }

    public function selectLimit($inf, $limite) {
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode() != 0) {
            print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }

    public function selectCount() {
        $this->selectCount->execute();
        if ($this->selectCount->errorCode() != 0) {
            print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }

    public function selectForPanel() {
        $this->selectForPanel->execute(array());
        if ($this->selectForPanel->errorCode() != 0) {
            print_r($this->selectForPanel->errorInfo());
        }
        return $this->selectForPanel->fetchAll();
    }
    
    public function insert($nom, $prenom, $email, $pass, $idrole, $telephone) {
        $r = true;
        $this->insert->execute(array(':nom' => $nom, ':prenom' => $prenom, ':email' => $email, ':pass' => $pass, ':idrole' => $idrole, ':telephone' => $telephone));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function update($nom, $prenom, $telephone, $email, $pass) {
        $r = true;
        $this->update->execute(array(':nom' => $nom, ':prenom' => $prenom, ':telephone' => $telephone, ':email' => $email, ':pass' => $pass));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateMdp($pass, $email) {
        $r = true;
        $this->updateMdp->execute(array(':pass' => $pass, ':email' => $email));
        if ($this->updateMdp->errorCode() != 0) {
            print_r($this->updateMdp->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateForAvis($idAvis, $email) {
        $r = true;
        $this->updateForAvis->execute(array(':idAvis' => $idAvis, ':email' => $email));
        if ($this->updateForAvis->errorCode() != 0) {
            print_r($this->updateForAvis->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function connect($email) {
        $unUtilisateur = $this->connect->execute(array(':email' => $email));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function emailExist($email) {
        $this->emailExist->execute(array(':email' => $email));
        if ($this->emailExist->errorCode() != 0) {
            print_r($this->emailExist->errorInfo());
        }
        return $this->emailExist->fetch();
    }

    public function telephoneExist($telephone) {
        $this->telephoneExist->execute(array(':telephone' => $telephone));
        if ($this->telephoneExist->errorCode() != 0) {
            print_r($this->telephoneExist->errorInfo());
        }
        return $this->telephoneExist->fetch();
    }

    function delete($email) {
        $r = true;
        $this->delete->execute(array(':email' => $email));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}

?>
