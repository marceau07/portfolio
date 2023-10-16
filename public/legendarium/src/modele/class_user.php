<?php

class User {
    private $db;
    private $insert;
    private $selectByNicknameOrEmail;
    private $select;
    
    public function __construct($db) {
        $this->db = $db;
        $this->connect = $db->prepare('SELECT u.* 
									FROM legendarium_users u 
									WHERE user_email=:saisie OR user_nickname=:saisie');
        $this->insert = $db->prepare("INSERT INTO legendarium_users 
                                        (user_email, 
                                        user_last_name, 
                                        user_first_name, 
                                        user_nickname, 
                                        user_phone, 
                                        user_password, 
                                        user_picture, 
                                        id_role) 
                                    VALUES (:user_email, 
                                        :user_last_name, 
                                        :user_first_name, 
                                        :user_nickname, 
                                        :user_phone, 
                                        :user_password, 
                                        NULL, 
                                        0)");                  
        $this->selectByNicknameOrEmail = $db->prepare('SELECT u.* 
													FROM legendarium_users u 
													WHERE user_email=:saisie OR user_nickname=:saisie');
        $this->select = $db->prepare("");
    }
   
    public function connect($saisie) {
        $this->connect->execute(array(':saisie' => $saisie));
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }
    
    public function insert($user_email, $user_last_name, $user_first_name, $user_nickname, $user_phone, $user_password) { 
        $r = true;
        $this->insert->execute(array(
            ':user_email'           => $user_email, 
            ':user_last_name'       => $user_last_name, 
            ':user_first_name'      => $user_first_name, 
            ':user_nickname'        => $user_nickname, 
            ':user_phone'           => $user_phone, 
            ':user_password'        => $user_password, 
        ));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function selectByNicknameOrEmail($saisie) {
        $this->selectByNicknameOrEmail->execute(array(':saisie' => $saisie));
        if ($this->selectByNicknameOrEmail->errorCode() != 0){
            print_r($this->selectByNicknameOrEmail->errorInfo());  
        }
        return $this->selectByNicknameOrEmail->fetch(PDO::FETCH_ASSOC);
    }
    
    public function select() {
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }
}