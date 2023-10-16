<?php

class User {

    private $db;
    private $insert;
    private $connect;
    private $select;
    private $selectByNickname;
    private $selectAllSubscribers;
    private $updateMdp;
    private $updateSubscription;
    private $updateSubscriptionEmail;
    private $suspendAccount;
    private $delete;
    private $search;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO covid_users(nicknameUser, passwordUser, idRole) 
									VALUES(:nicknameUser, :passwordUser, :idRole)");
        $this->connect = $db->prepare("SELECT idUser, nicknameUser, idRole, passwordUser, isSubscribed, isActivated 
									FROM covid_users 
									WHERE nicknameUser=:nicknameUser");
        $this->select = $db->prepare("SELECT idUser, nicknameUser, isSubscribed, isActivated, labelRole 
                                      FROM covid_users u 
                                      INNER JOIN covid_roles r ON r.idRole = u.idRole 
                                      ORDER BY nicknameUser");
        $this->selectByNickname = $db->prepare("SELECT idUser, nicknameUser, passwordUser, isSubscribed, isActivated, u.idRole, labelRole, email
                                                FROM covid_users u
                                                INNER JOIN covid_roles r ON r.idRole=u.idRole 
                                                WHERE nicknameUser=:nicknameUser");
        $this->selectAllSubscribers = $db->prepare("SELECT nicknameUser, email
                                                    FROM covid_users u
                                                    WHERE isSubscribed=true");
        $this->updateMdp = $db->prepare("UPDATE covid_users SET passwordUser=:passwordUser WHERE nicknameUser=:nicknameUser");
        $this->updateSubscription = $db->prepare("UPDATE covid_users SET isSubscribed=:isSubscribed WHERE nicknameUser=:nicknameUser");
        $this->updateSubscriptionEmail = $db->prepare("UPDATE covid_users SET email=:emailToSub WHERE nicknameUser=:nicknameUser");
        $this->suspendAccount = $db->prepare("UPDATE covid_users SET isActivated=0 WHERE nicknameUser=:nicknameUser");
        $this->delete = $db->prepare("DELETE FROM covid_users WHERE nicknameUser=:nicknameUser");
        $this->search = $db->prepare("SELECT '' AS id, titleFaq AS label, 'FAQ' AS nameTable
                                      FROM covid_faqs
                                      WHERE titleFaq LIKE(CONCAT('%', :saisie, '%'))
									  
                                      UNION
									  
                                      SELECT idArticle AS id, titleArticle AS label, 'ARTICLE' AS nameTable
                                      FROM covid_articles
                                      WHERE titleArticle LIKE(CONCAT('%', :saisie, '%'))
                                      ORDER BY label ASC");
    }

    public function insert($nicknameUser, $passwordUser, $idRole) {
        $r = true;
        $this->insert->execute(array(':nicknameUser' => $nicknameUser, ':passwordUser' => $passwordUser, ':idRole' => $idRole));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function connect($nicknameUser) {
        $aUser = $this->connect->execute(array(':nicknameUser' => $nicknameUser));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectByNickname($nicknameUser) {
        $this->selectByNickname->execute(array(':nicknameUser' => $nicknameUser));
        if ($this->selectByNickname->errorCode() != 0) {
            print_r($this->selectByNickname->errorInfo());
        }
        return $this->selectByNickname->fetch();
    }

    public function selectAllSubscribers() {
        $this->selectAllSubscribers->execute();
        if ($this->selectAllSubscribers->errorCode() != 0) {
            print_r($this->selectAllSubscribers->errorInfo());
        }
        return $this->selectAllSubscribers->fetchAll();
    }
    
    public function updateMdp($nicknameUser, $passwordUser) {
        $r = true;
        $this->updateMdp->execute(array(':nicknameUser' => $nicknameUser, ':passwordUser' => $passwordUser));
        if ($this->updateMdp->errorCode() != 0) {
            print_r($this->updateMdp->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateSubscription($nicknameUser, $isSubscribed) {
        $r = true;
        $this->updateSubscription->execute(array(':nicknameUser' => $nicknameUser, ':isSubscribed' => $isSubscribed));
        if ($this->updateSubscription->errorCode() != 0) {
            print_r($this->updateSubscription->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateSubscriptionEmail($nicknameUser, $emailToSub) {
        $r = true;
        $this->updateSubscriptionEmail->execute(array(':nicknameUser' => $nicknameUser, ':emailToSub' => $emailToSub));
        if ($this->updateSubscriptionEmail->errorCode() != 0) {
            print_r($this->updateSubscriptionEmail->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function suspendAccount($nicknameUser) {
        $r = true;
        $this->suspendAccount->execute(array(':nicknameUser' => $nicknameUser));
        if ($this->suspendAccount->errorCode() != 0) {
            print_r($this->suspendAccount->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($nicknameUser) {
        $r = true;
        $this->delete->execute(array(':nicknameUser' => $nicknameUser));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function search($saisie) {
        $this->search->execute(array(':saisie' => $saisie));
        if ($this->search->errorCode() != 0) {
            print_r($this->search->errorInfo());
        }
        $response = array();
        while ($row = $this->search->fetch(PDO::FETCH_ASSOC)) {
            if($row['nameTable'] === "ARTICLE") {
                $response[] = array("label" => strip_tags($row['label']), "table" => $row['nameTable'], "lien" => "fullarticle&idArticle=".$row['id']);
            } elseif ($row['nameTable'] === "FAQ") {
                $response[] = array("label" => strip_tags($row['label']), "table" => $row['nameTable'], "lien" => "faq");
            }
        }
        echo json_encode($response);
    }

}
?>

