<?php

class Produit{
    
    private $db;
    private $insert;
    private $select;
    private $selectById;
    private $update;
    private $delete;
    private $selectLimit;
    private $selectCount;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO vente_produits(designation, description, prix, idType, photo) 
									VALUES(:designation, :description, :prix, :idType, :photo)");
        $this->select = $db->prepare("SELECT p.id, designation, description, prix, idType 
									FROM vente_produits p  
									INNER JOIN vente_types t ON t.id = p.idType
									ORDER BY designation");
        $this->selectById = $db->prepare("SELECT id, designation, description, prix, idType 
										FROM vente_produits p 
										WHERE id=:id");
        $this->update = $db->prepare("UPDATE vente_produits 
									SET designation=:designation, description=:description, prix=:prix, idType=:idType 
									WHERE id=:id");
        $this->delete = $db->prepare("DELETE FROM vente_produits 
									WHERE id=:id");
        $this->selectLimit = $db->prepare("SELECT id, designation, description, prix, idType 
										FROM vente_produits 
										ORDER BY designation 
										LIMIT :inf,:limite");
        $this->selectCount =$db->prepare("SELECT COUNT(*) as nb 
										FROM vente_produits");
    }

    public function insert($designation, $description, $prix, $idType, $photo) {
        $r = true;
        $this->insert->execute(array(':designation'=>$designation, ':description'=>$description, ':prix'=>$prix, ':idType'=>$idType, ':photo'=>$photo));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
      
    public function select() {
        $liste = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectById($id) {
        $this->selectById->execute(array(':id'=>$id));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
    
    public function update($id, $designation, $description, $prix, $idType) {
        $r = true;
        $this->update->execute(array(':id' => $id, ':designation' => $designation, ':description' => $description, ':prix' => $prix, ':idType' => $idType));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function delete($id) {
        $r = true;
        $this->delete->execute(array(':id'=>$id));
        if ($this->delete->errorCode()!=0){
            print_r($this->delete->errorInfo());
            $r=false;
        }
        return $r;
    }

    public function selectLimit($inf, $limite) {
        $this->selectLimit->bindParam(':inf', $inf, PDO::PARAM_INT);
        $this->selectLimit->bindParam(':limite', $limite, PDO::PARAM_INT);
        $this->selectLimit->execute();        
        if ($this->selectLimit->errorCode()!=0) {
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