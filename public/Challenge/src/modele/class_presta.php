<?php
class Presta{
    
    private $db;
    private $insert;
    private $select;
    private $selectJournee;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO challenge_prestation(typePrest, montant, idAct) 
									VALUES(:typePrest, :montant, :idAct)");                  
        $this->select = $db->prepare("SELECT * FROM challenge_prestation P");
        $this->selectJournee = $db->prepare("SELECT p.typePrest, p.montant, a.nomAct, m.nomMateriel 
											FROM challenge_prestation p 
											INNER JOIN challenge_comporter c ON c.idPrest = p.idPrest 
											INNER JOIN challenge_activite a ON a.codeAct = c.idAct 
											INNER JOIN challenge_posseder po ON po.codeAct = a.codeAct 
											INNER JOIN challenge_materiel m ON m.codeMateriel = po.codeMat 
											INNER JOIN challenge_demander d ON d.idPrest = p.idPrest 
											WHERE date_format(dateDeb, '%Y-%m-%d') =:date");
        $this->update = $db->prepare("UPDATE challenge_prestation 
									SET typePrest=:typePrest, montant=:montant, idAct=:idAct 
									WHERE idPrest=:idPrest");
        $this->delete = $db->prepare("DELETE FROM challenge_prestation 
									WHERE idPrest=:idPrest");
    }

    public function insert($typePrest, $montant, $idAct) {
        $r = true;
        $this->insert->execute(array(':typePrest'=>$typePrest, ':montant'=>$montant,':idAct'=>$idAct));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $listeP = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectJournee($date) {
        $listeP = $this->selectJournee->execute(array(':date' => $date));
        if ($this->selectJournee->errorCode()!=0){
            print_r($this->selectJournee->errorInfo());  
        }
        return $this->selectJournee->fetchAll();
    }
    
    public function update($typePrest, $montant, $idAct, $idPrest) {
        $r = true;
        $this->update->execute(array(':typePrest'=>$typePrest, ':montant'=>$montant, ':idAct'=>$idAct, ':idPrest'=>$idPrest));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idPrest) {
        $r = true;
        $this->delete->execute(array(':idPrest'=>$idPrest));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }
}