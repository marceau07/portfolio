<?php

class jdr {
    
    private $db;
    private $select;
    private $insert;
    private $selectById;
    private $delete;
    private $update;
    private $selectLimit;
    private $selectCount;
    private $selectQte;
    private $replaceQte;
    
    public function __construct($db) {
        $this->db = $db;
        
        $this->insert = $db->prepare("insert into jdr(idJdr, titreJdr, synopsis, date, nbJoueurs, nb, mj) values (:idJdr, :titreJdr, :synopsis, :date, :nbJoueurs, :nb, :mj)");
        
        $this->select = $db->prepare("select * from jdr j "
                                   . "inner join utilisateur on j.mj = utilisateur.id "
                                   . "order by date desc");
        
        $this->selectById = $db->prepare("select * from jdr j "
                                       . "inner join utilisateur on j.mj = utilisateur.id "
                                       . "where idJdr=:idJdr");
        
        $this->update = $db->prepare("update jdr set mj=:mj, titreJdr=:titreJdr, synopsis=:synopsis, date=:date, nbJoueurs=:nbJoueurs, nb=:nb where idJdr=:idJdr");
        
        $this->delete = $db->prepare("delete from jdr where idJdr=:idJdr");
        
        $this->selectLimit = $db->prepare("select * from jdr j "
                                        . "inner join utilisateur on j.mj = utilisateur.id "
                                        . "order by date desc limit :inf,:limite");
        
        $this->selectCount = $db->prepare("select count(*) as nb from jdr");
        
        $this->selectQte = $db->prepare("select * from jdr where idJdr=:idJdr");
        
        $this->replaceQte = $db->prepare("update jdr "
                                       . "set nb=:nb "
                                       . "where idJdr=:idJdr");
        
    }
    
    public function insert($idJdr, $titreJdr, $synopsis, $date, $nbJoueurs, $nb, $mj) {
        $r = true;
        $this->insert->execute(array(':idJdr'=>$idJdr,':titreJdr'=>$titreJdr,':synopsis'=>$synopsis,':date'=>$date,':nbJoueurs'=>$nbJoueurs,':nb'=>$nb,':mj'=>$mj));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function selectQte($idJdr) {
        $unLivre = $this->selectQte->execute(array(':idJdr' => $idJdr));
        if ($this->selectQte->errorCode() != 0) {
            print_r($this->selectQte->errorInfo());
        }
        return $this->selectQte->fetch();
    }
    
    public function replaceQte($nb, $idJdr) {
        $r = true;
        $this->replaceQte->execute(array(':nb' => $nb, ':idJdr' => $idJdr));
        if ($this->replaceQte->errorCode() != 0) {
            print_r($this->replaceQte->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }
    
    public function selectById($idJdr) {
        $this->selectById->execute(array(':idJdr'=>$idJdr));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
    
    public function update($idJdr, $mj, $titreJdr, $synopsis, $date, $nbJoueurs, $nb) {
        $r = true;
        $this->update->execute(array(':idJdr'=>$idJdr,':mj'=>$mj,':titreJdr'=>$titreJdr,':synopsis'=>$synopsis,':date'=>$date,':nbJoueurs'=>$nbJoueurs,':nb'=>$nb));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function delete($idJdr){
        $r = true;
        $this->delete->execute(array(':idJdr'=>$idJdr));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function selectLimit($inf, $limite){
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();
        if ($this->selectLimit->errorCode()!=0){
            print_r($this->selectLimit->errorInfo());
        }
        return $this->selectLimit->fetchAll();
    }
    
    public function selectCount(){
        $this->selectCount->execute();
        if ($this->selectCount->errorCode()!=0){
            print_r($this->selectCount->errorInfo());
        }
        return $this->selectCount->fetch();
    }
    
}

?>