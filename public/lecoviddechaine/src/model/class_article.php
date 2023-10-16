<?php

class Article {

    private $db;
    private $insert;
    private $select;
    private $selectArticlesImportes;
    private $selectVisibilityY;
    private $selectVisibilityN;
    private $selectById;
    private $selectByCategory;
    private $selectByCategoryDate;
    private $selectByLast;
    private $update;
    private $visibility;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO covid_articles(titleArticle, descriptionArticle, contentArticle, dateArticle, sourceArticle, imageArticle, isActivated, idCategory, idUser, publisherArticle) 
                                    VALUES(:titleArticle, :descriptionArticle, :contentArticle, :dateArticle, :sourceArticle, :imageArticle, :isActivated, :idCategory, :idUser, :publisherArticle)");
        $this->select = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, a.idCategory, c.labelCategory, u.nicknameUser, a.publisherArticle 
                                      FROM covid_articles a 
                                      INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
                                      INNER JOIN covid_users u ON u.idUser=a.idUser 
                                      ORDER BY dateArticle ");
        $this->selectArticlesImportes = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, c.labelCategory, u.nicknameUser, a.publisherArticle 
													  FROM covid_articles a 
													  INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
													  INNER JOIN covid_users u ON u.idUser=a.idUser 
													  WHERE a.idCategory=5 
													  AND dateArticle=:dateArticle 
													  AND a.isActivated=1
													  ORDER BY dateArticle ");
        $this->selectVisibilityN = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, c.labelCategory, u.nicknameUser 
												FROM covid_articles a 
												INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
												INNER JOIN covid_users u ON u.idUser=a.idUser 
												WHERE a.isActivated=0
												ORDER BY dateArticle ");
        $this->selectVisibilityY = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, c.labelCategory, u.nicknameUser 
												FROM covid_articles a 
												INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
												INNER JOIN covid_users u ON u.idUser=a.idUser  
												WHERE a.isActivated=1
												ORDER BY dateArticle");
        $this->selectById = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, a.idCategory, c.labelCategory, u.nicknameUser, a.publisherArticle
                                          FROM covid_articles a 
                                          INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
                                          INNER JOIN covid_users u ON u.idUser=a.idUser 
                                          WHERE idArticle=:idArticle
                                          ORDER BY dateArticle");
        $this->selectByCategory = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, a.idCategory, c.labelCategory, u.nicknameUser, a.publisherArticle
												FROM covid_articles a 
												INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
												INNER JOIN covid_users u ON u.idUser=a.idUser 
												WHERE c.labelCategory=:labelCategory
												ORDER BY dateArticle DESC");
        $this->selectByCategoryDate = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, a.idCategory, c.labelCategory, u.nicknameUser, a.publisherArticle
                                                    FROM covid_articles a 
                                                    INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
                                                    INNER JOIN covid_users u ON u.idUser=a.idUser 
                                                    WHERE c.idCategory=:idCategory 
                                                    AND dateArticle=:dateSaisie 
                                                    ORDER BY dateArticle DESC");
        $this->selectByLast = $db->prepare("SELECT idArticle, titleArticle, contentArticle, dateArticle, sourceArticle, imageArticle, a.isActivated, c.labelCategory, u.nicknameUser, a.publisherArticle
											FROM covid_articles a 
											INNER JOIN covid_categories c ON c.idCategory=a.idCategory 
											INNER JOIN covid_users u ON u.idUser=a.idUser 
											ORDER BY dateArticle DESC");
        $this->update = $db->prepare("UPDATE covid_articles 
									SET titleArticle=:titleArticle, contentArticle=:contentArticle
                                    WHERE idArticle=:idArticle");
        $this->visibility = $db->prepare("UPDATE covid_articles 
										SET isActivated=:isActivated
										WHERE idArticle=:idArticle");
        $this->delete = $db->prepare("DELETE FROM covid_articles 
									WHERE idArticle=:idArticle");
    }

    public function insert($titleArticle, $descriptionArticle, $contentArticle, $dateArticle, $sourceArticle, $imageArticle, $isActivated, $idCategory, $idUser, $publisherArticle) {
        $r = true;
        $this->insert->execute(array(':titleArticle' => $titleArticle, ':descriptionArticle' => $descriptionArticle, ':contentArticle' => $contentArticle, ':dateArticle' => $dateArticle, ':sourceArticle' => $sourceArticle, ':imageArticle' => $imageArticle, ':isActivated' => $isActivated, ':idCategory' => $idCategory, ':idUser' => $idUser, ':publisherArticle' => $publisherArticle));
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

    public function selectArticlesImportes($dateArticle) {
        $this->selectArticlesImportes->execute(array(':dateArticle' => $dateArticle));
        if ($this->selectArticlesImportes->errorCode() != 0) {
            print_r($this->selectArticlesImportes->errorInfo());
        }
        return $this->selectArticlesImportes->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectVisibilityY() {
        $this->selectVisibilityY->execute();
        if ($this->selectVisibilityY->errorCode() != 0) {
            print_r($this->selectVisibilityY->errorInfo());
        }
        return $this->selectVisibilityY->fetchAll();
    }

    public function selectVisibilityN() {
        $this->selectVisibilityN->execute();
        if ($this->selectVisibilityN->errorCode() != 0) {
            print_r($this->selectVisibilityN->errorInfo());
        }
        return $this->selectVisibilityN->fetchAll();
    }

    public function selectById($idArticle) {
        $this->selectById->execute(array(':idArticle' => $idArticle));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function selectByCategory($labelCategory) {
        $this->selectByCategory->execute(array(':labelCategory' => $labelCategory));
        if ($this->selectByCategory->errorCode() != 0) {
            print_r($this->selectByCategory->errorInfo());
        }
        return $this->selectByCategory->fetchAll();
    }

    public function selectByCategoryDate($idCategory, $dateSaisie) {
        $this->selectByCategoryDate->execute(array(':idCategory' => $idCategory, ':dateSaisie' => $dateSaisie));
        if ($this->selectByCategoryDate->errorCode() != 0) {
            print_r($this->selectByCategoryDate->errorInfo());
        }
        return $this->selectByCategoryDate->fetchAll();
    }

    public function selectByLast() {
        $this->selectByLast->execute();
        if ($this->selectByLast->errorCode() != 0) {
            print_r($this->selectByLast->errorInfo());
        }
        return $this->selectByLast->fetch();
    }

    public function update($titleArticle, $contentArticle, $idArticle) {        
        $r = true;
        $this->update->execute(array(':titleArticle' => $titleArticle, ':contentArticle' => $contentArticle, ':idArticle' => $idArticle));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function visibility($isActivated, $idArticle) {
        $r = true;
        $this->visibility->execute(array(':isActivated' => $isActivated, ':idArticle' => $idArticle));
        if ($this->visibility->errorCode() != 0) {
            print_r($this->visibility->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idArticle) {
        $r = true;
        $this->delete->execute(array(':idArticle' => $idArticle));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

}
