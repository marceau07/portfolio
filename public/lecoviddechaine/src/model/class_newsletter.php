<?php

class Newsletter {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO covid_newsletters(email) 
									VALUES(:email)");
        $this->select = $db->prepare("SELECT email
                                      FROM covid_newsletters");
        $this->delete = $db->prepare("DELETE FROM covid_newsletters 
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

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
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
