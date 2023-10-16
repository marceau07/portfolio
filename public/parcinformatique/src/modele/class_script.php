<?php

class Script {

    private $db;
    private $insert;
    private $update;
    private $disableForeignKeyChecks;
    private $delete;
    private $select;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO parc_informatique_scripts(nomScript, version, descScript, idOs, fichierScript) 
									VALUES(:nomScript, :version, :descScript, :idOs, :fichierScript)");
        $this->update = $db->prepare("UPDATE parc_informatique_scripts 
									SET nomScript=:nomScript, version=:version, descScript=:descScript, idOs=:idOs 
									WHERE idScript=:idScript");
        $this->disableForeignKeyChecks = $db->prepare("SET FOREIGN_KEY_CHECKS = 0;");
        $this->delete = $db->prepare("DELETE FROM parc_informatique_scripts 
									WHERE idScript=:idScript");
        $this->select = $db->prepare("SELECT * FROM parc_informatique_scripts script
									INNER JOIN parc_informatique_os os ON os.idOs=script.idOs");
    }

    public function insert($nomScript, $version, $descScript, $idOs, $fichierScript) {
        $r = true;
        $this->insert->execute(array(':nomScript' => $nomScript, ':version' => $version, ':descScript' => $descScript, ':idOs' => $idOs, ':fichierScript' => $fichierScript));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function update($nomScript, $version, $descScript, $idOs, $idScript) {
        $r = true;
        $this->disableForeignKeyChecks->execute();
        $this->update->execute(array(':nomScript' => $nomScript, ':version' => $version, ':descScript' => $descScript, ':idOs' => $idOs, ':idScript' => $idScript));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idScript) {
        $r = true;
        $this->delete->execute(array(':idScript' => $idScript));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $listeS = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

}
