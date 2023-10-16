<?php

class Category {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO covid_categories(labelCategory) 
									VALUES(:labelCategory)");
        $this->select = $db->prepare("SELECT idCategory, labelCategory 
									FROM covid_categories 
									ORDER BY idCategory DESC, labelCategory ASC");
        $this->delete = $db->prepare("DELETE FROM covid_categories 
									WHERE idCategory=:idCategory");
    }

    public function insert($labelCategory) {
        $r = true;
        $this->insert->execute(array(':labelCategory' => $labelCategory));
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

    public function delete($idCategory) {
        $r = true;
        $this->delete->execute(array(':idCategory' => $idCategory));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}
