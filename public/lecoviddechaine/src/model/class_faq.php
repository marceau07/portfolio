<?php

class Faq {

    private $db;
    private $insert;
    private $select;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO covid_faqs(titleFaq, answerFaq, idCategory) VALUES(:titleFaq, :answerFaq, :idCategory)");
        $this->select = $db->prepare("SELECT titleFaq, answerFaq, c.labelCategory 
                                      FROM covid_faqs f
                                      INNER JOIN covid_categories c ON f.idCategory=c.idCategory ");
        $this->delete = $db->prepare("DELETE FROM covid_faqs WHERE idFaq=:idFaq");
    }

    public function insert($titleFaq, $answerFaq, $idCategory) {
        $r = true;
        $this->insert->execute(array(':titleFaq' => $titleFaq, ':answerFaq' => $answerFaq, ':idCategory' => $idCategory));
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

    public function delete($idFaq) {
        $r = true;
        $this->delete->execute(array(':idFaq' => $idFaq));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
