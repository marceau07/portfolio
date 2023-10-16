<?php

class Avis {

    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $selectLastForPanel;
    private $selectByEmail;
    private $update;
    private $delete;
    private $deleteByUser;
    private $emailExist;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO julie_bressand_avis(message, note, email) 
									VALUES(:message, :note, :email)");
        $this->select = $db->prepare('SELECT av.id, av.message, av.note, av.email, u.prenom, u.nom 
									FROM julie_bressand_avis av 
									INNER JOIN julie_bressand_utilisateurs u ON av.id=u.idAvis ');
        $this->selectByEmail = $db->prepare('SELECT av.id, av.message, av.note, av.email 
											FROM julie_bressand_avis av 
											WHERE av.email=:email');
        $this->selectById = $db->prepare('SELECT av.id, av.message, av.note, av.email 
										FROM julie_bressand_avis av 
										WHERE av.id=:idavis');
        $this->update = $db->prepare("UPDATE julie_bressand_avis 
									SET message=:message, note=:note 
									WHERE email=:email");
        $this->delete = $db->prepare("DELETE FROM julie_bressand_avis 
									WHERE id=:id");
        $this->deleteByUser = $db->prepare("DELETE FROM julie_bressand_avis 
											WHERE email=:email");
        $this->emailExist = $db->prepare("SELECT COUNT(email) AS nb 
										FROM julie_bressand_avis 
										WHERE email=:email");
        $this->selectLastForPanel = $db->prepare("SELECT av.id, av.message, av.note, av.email, nom, prenom 
												FROM julie_bressand_avis av 
												INNER JOIN julie_bressand_utilisateurs u ON av.id=u.idAvis 
												ORDER BY id DESC 
												LIMIT 0, 3");
    }

    public function insert($message, $note, $email) {
        $r = true;
        $this->insert->execute(array(':message' => $message, ':note' => $note, ':email' => $email));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $liste = $this->select->execute();
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
    
    public function selectById($idavis) {
        $this->selectById->execute(array(':idavis' => $idavis));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
    
    public function selectLastForPanel() {
        $this->selectLastForPanel->execute();
        if ($this->selectLastForPanel->errorCode() != 0) {
            print_r($this->selectLastForPanel->errorInfo());
        }
        return $this->selectLastForPanel->fetchAll();
    }

    public function update($message, $note, $email) {
        $r = true;
        $this->update->execute(array(':message' => $message, ':note' => $note, ':email' => $email));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($id) {
        $r = true;
        $this->delete->execute(array(':id' => $id));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function deleteByUser($email) {
        $r = true;
        $this->deleteByUser->execute(array(':email' => $email));
        if ($this->deleteByUser->errorCode() != 0) {
            print_r($this->deleteByUser->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function emailExist($email) {
        $this->emailExist->execute(array(':email' => $email));
        if ($this->emailExist->errorCode() != 0) {
            print_r($this->emailExist->errorInfo());
        }
        return $this->emailExist->fetch();
    }

}

?>
