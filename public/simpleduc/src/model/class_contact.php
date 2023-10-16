<?php

class Contact {

    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_contacts(lastNameContact, firstNameContact, telContact, mailContact) 
									VALUES(:lastNameContact, :firstNameContact, :telContact, :mailContact)");
        $this->select = $db->prepare("SELECT idContact, lastNameContact, firstNameContact, telContact, mailContact 
									FROM simpleduc_contacts 
									ORDER BY lastNameContact");
        $this->selectById = $db->prepare("SELECT idContact, lastNameContact, firstNameContact, telContact, mailContact 
										FROM simpleduc_contacts 
										WHERE idContact=:idContact 
										ORDER BY lastNameContact");
        $this->update = $db->prepare("UPDATE simpleduc_contacts 
									SET lastNameContact=:lastNameContact, firstNameContact=:firstNameContact, telContact=:telContact, mailContact=:mailContact 
									WHERE idContact=:idContact");
        $this->delete = $db->prepare("DELETE FROM simpleduc_contacts 
									WHERE idContact=:idContact");
    }

    public function insert($lastNameContact, $firstNameContact, $telContact, $mailContact) {
        $r = true;
        $this->insert->execute(array(':lastNameContact' => $lastNameContact, ':firstNameContact' => $firstNameContact, ':telContact' => $telContact, ':mailContact' => $mailContact));
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
    
    public function selectById($idContact){ 
        $this->selectById->execute(array(':idContact'=>$idContact)); 
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo()); 
            
        }
        return $this->selectById->fetch(); 
    }

    public function update($idContact, $lastNameContact, $firstNameContact, $telContact, $mailContact) {
        $r = true;
        $this->update->execute(array(':idContact' => $idContact, ':lastNameContact' => $lastNameContact, ':firstNameContact' => $firstNameContact, ':telContact' => $telContact, ':mailContact' => $mailContact));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idContact) {
        $r = true;
        $this->delete->execute(array(':idContact' => $idContact));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
?>

