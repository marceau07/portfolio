<?php
class Materiel{
    
    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO challenge_materiel(nomMateriel, commentaire) 
									VALUES(:nomMateriel, :commentaire)");
        $this->select = $db->prepare("SELECT * FROM challenge_materiel M");
        $this->update = $db->prepare("UPDATE challenge_materiel SET nomMateriel=:nomMateriel, commentaire=:commentaire WHERE codeMateriel=:codeMateriel");
        $this->delete = $db->prepare("DELETE FROM challenge_materiel WHERE codeMateriel=:codeMateriel");
    }

    public function insert($nomMateriel, $commentaire) {
        $r = true;
        $this->insert->execute(array(':nomMateriel'=>$nomMateriel, ':commentaire'=>$commentaire));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $listeM = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function update($nomMateriel, $commentaire, $codeMateriel) {
        $r = true;
        $this->update->execute(array(':nomMateriel' => $nomMateriel, ':commentaire' => $commentaire, ':codeMateriel' => $codeMateriel));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($codeMateriel) {
        $r = true;
        $this->delete->execute(array(':codeMateriel' => $codeMateriel));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}