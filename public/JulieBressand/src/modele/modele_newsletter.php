<?php

class Newsletter {

    private $db;
    private $insert;
    private $select;
    private $emailExist;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO julie_bressand_newsletters(email) 
									VALUES(:email)");
        $this->select = $db->prepare('SELECT * 
									FROM julie_bressand_newsletters 
									ORDER BY id DESC');        
        $this->emailExist = $db->prepare("SELECT COUNT(email) 
										FROM julie_bressand_newsletters 
										WHERE email=:email");
        $this->delete = $db->prepare("DELETE FROM julie_bressand_newsletters 
									WHERE email=:email");
    }

    public function insert($email) {
        $r = true;
        $this->insert->execute(array(':email' => $email));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
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

    public function select() {
        $this->select->execute();
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

}

?>
