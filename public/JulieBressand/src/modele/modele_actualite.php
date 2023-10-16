<?php

class actualite {

    private $db;
    private $insert;
    private $select;
    private $selectLastForPanel;
    private $selectById;
    private $delete;
    private $update;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO julie_bressand_actualites(titre, message, date) 
									VALUES(:titre, :message, :date)");
        $this->select = $db->prepare("SELECT titre, message, idactu, concat(day(date),'-',month(date),'-',year(date)) AS date 
									FROM julie_bressand_actualites a 
									ORDER BY idactu DESC");
        $this->selectById = $db->prepare("SELECT titre, message, idactu, date 
										FROM julie_bressand_actualites 
										WHERE idactu=:idactu");
        $this->delete = $db->prepare("DELETE FROM julie_bressand_actualites 
									WHERE idactu=:idactu");
        $this->update = $db->prepare("UPDATE julie_bressand_actualites 
									SET titre=:titre, message=:message, date=:date 
									WHERE idactu=:idactu");
        $this->SelectLastActu = $db->prepare("SELECT * 
											FROM julie_bressand_actualites a 
											ORDER BY idactu DESC 
											LIMIT 0, 1");
        $this->selectLastForPanel = $db->prepare("SELECT * 
												FROM julie_bressand_actualites 
												ORDER BY idactu DESC 
												LIMIT 0, 3");
    }

    public function insert($titre, $message, $date) {
        $r = true;
        $this->insert->execute(array(':titre' => $titre, ':message' => $message, ':date' => $date));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

//Supprime une actualité en fonction de son idactu
    public function delete($idactu) {
        $r = true;
        $this->delete->execute(array(':idactu' => $idactu));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function update($idactu, $titre, $message, $date) {
        $r = true;
        $this->update->execute(array(':idactu' => $idactu, ':titre' => $titre, ':message' => $message, ':date' => $date));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

//Selectionne toute les actualités
    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($idactu) {
        $this->selectById->execute(array(':idactu' => $idactu));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function selectLastForPanel() {
        $this->selectLastForPanel->execute(array());
        if ($this->selectLastForPanel->errorCode() != 0) {
            print_r($this->selectLastForPanel->errorInfo());
        }
        return $this->selectLastForPanel->fetchAll();
    }

//Selectionné la dernière actualité
    public function SelectLastActu() {
        $listelast = $this->SelectLastActu->execute();
        if ($this->SelectLastActu->errorCode() != 0) {
            print_r($this->SelectLastActu->errorInfo());
        }
        return $this->SelectLastActu->fetchAll();
    }

}

?>
