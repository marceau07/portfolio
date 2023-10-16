<?php

class History {

    private $db;
    private $insert;
    private $select;
    private $selectByNickname;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO ktane_history(nickname, score, date, idDifficulty) 
									VALUES(:nickname, :score, :date, :idDifficulty)");
        $this->select = $db->prepare("SELECT nickname, score, date, labelDifficulty, timer
                                    FROM ktane_history h
                                    INNER JOIN ktane_difficulty d ON h.idDifficulty=d.idDifficulty");
        $this->selectByNickname = $db->prepare("SELECT nickname, score, date, labelDifficulty, timer
                                                FROM ktane_history h
                                                INNER JOIN ktane_difficulty d ON h.idDifficulty=d.idDifficulty
                                                WHERE nickname=:nickname");
        $this->delete = $db->prepare("DELETE FROM ktane_history 
									WHERE idHistory=:idHistory");
    }

    public function insert($nickname, $score, $date, $idDifficulty) {
        $r = true;
        $this->insert->execute(array(':nickname' => $nickname, ':score' => $score, ':date' => $date, ':idDifficulty' => $idDifficulty));
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

    public function selectByNickname($nickname) {
        $this->selectByNickname->execute(array(':nickname' => $nickname));
        if ($this->selectByNickname->errorCode() != 0) {
            print_r($this->selectByNickname->errorInfo());
        }
        return $this->selectByNickname->fetchAll();
    }

    public function delete($idHistory) {
        $r = true;
        $this->delete->execute(array(':idHistory' => $idHistory));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}
