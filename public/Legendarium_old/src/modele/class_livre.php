<?php

class Livre{
    private $db;
    private $insert; 
    private $select;
    private $selectByAuteur;
    private $update;
    private $carousel;
    private $catalogue;
    private $recherche;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_books(titre, isbn, synopsis, prix, quantite, photo, idDisponibilite, idGenre, idAuteur, idEditeur) 
									VALUES(:titre, :isbn, :synopsis, :prix, :quantite, :photo, :idDisponibilite, :idGenre, :idAuteur, :idEditeur)");             
        $this->select = $db->prepare("SELECT titre, isbn, synopsis, prix, prix, quantite 
									FROM legendarium_books");
        $this->selectByAuteur = $db->prepare("SELECT a.nom, a.prenom, titre, prix, quantite, photo 
											FROM legendarium_books l 
											INNER JOIN legendarium_authors a ON l.idAuteur=a.id 
											WHERE a.nom=:auteur");
        $this->update = $db->prepare("UPDATE legendarium_books 
									SET titre=:titre, isbn=:isbn, synopsis:synopsis, prix=:prix, quantite=:quantite, photo=:photo, idDisponibilite=:idDisponibilite, idGenre=:idGenre, idAuteur=:idAuteur, idEditeur=:idEditeur 
									WHERE idLivre=:idLivre");
        $this->carousel = $db->prepare("SELECT titre, synopsis, photo 
										FROM legendarium_books ");
        $this->catalogue = $db->prepare("SELECT titre, synopsis, photo 
										FROM legendarium_books ");
        $this->recherche = $db->prepare("SELECT titre, synopsis, photo 
										FROM legendarium_books 
										WHERE titre LIKE '%".":recherche"."%'
										ORDER BY titre DESC ");
    }

    public function insert($titre, $isbn, $synopsis, $prix, $quantite, $photo, $idDisponibilite, $idGenre, $idAuteur, $idEditeur) {
        $r = true;
        $this->insert->execute(array(':titre'=>$titre, ':isbn'=>$isbn, ':synopsis'=>$synopsis, ':prix'=>$prix, ':quantite'=>$quantite, ':photo'=>$photo, ':idDisponibilite'=>$idDisponibilite, ':idGenre'=>$idGenre, ':idAuteur'=>$idAuteur, ':idEditeur'=>$idEditeur));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $listeL = $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
    
    public function selectByAuteur($auteur) {
        $this->selectByAuteur->execute(array(':auteur'=>$auteur));
        if ($this->selectByAuteur->errorCode()!=0){
            print_r($this->selectByAuteur->errorInfo());
        }
        return $this->selectByAuteur->fetch();
    }

    public function update($idLivre, $titre, $auteur, $synopsis, $etat, $prix, $quantite, $photo, $idDisponibilite, $idGenre, $idAuteur, $idEditeur){
        $r = true;
        $this->update->execute(array(':idLivre'=>$idLivre, ':titre'=>$titre, ':auteur'=>$auteur, ':syopsis'=>$synopsis, ':etat'=>$etat, ':prix'=>$prix, ':quantite'=>$quantite, ':photo'=>$photo, ':idDisponibilite'=>$idDisponibilite, ':idGenre'=>$idGenre, ':idAuteur'=>$idAuteur, ':idEditeur'=>$idEditeur));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function carousel(){
        $carousel = $this->carousel->execute();
        if ($this->carousel->errorCode()!=0){
            print_r($this->carousel->errorInfo());  
        }
        return $this->carousel->fetchAll();
    }
    
    public function catalogue(){
        $catalogue = $this->catalogue->execute();
        if ($this->catalogue->errorCode()!=0){
            print_r($this->catalogue->errorInfo());
        }
        return $this->catalogue->fetchAll();
    }
    
    public function recherche($recherche){
        $this->recherche->execute(array(':recherche'=>$recherche));
        if($this->recherche->errorCode()!=0){
            print_r($this->recherche->errorInfo());
        }
        return $this->recherche->fetch();
    }
}

?>