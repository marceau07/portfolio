<?php

class Jouer{
    
    private $db;
    private $insert; 
    private $select;
    private $selectById;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO users_game(id_user, id_game) 
									VALUES(:id_user, :id_game)");             
        $this->select = $db->prepare("SELECT * 
									FROM users_game ");
        $this->update = $db->prepare("UPDATE users_game 
									SET id_user=:id_user, id_game=:id_game
									WHERE user_game_id=:user_game_id"); 
        $this->selectById = $db->prepare("SELECT * 
										FROM users_game 
										WHERE id_user=:id_user");
    }

    public function insert($id_user, $id_game) {
        $r = true;
        $this->insert->execute(array(':id_user' => $id_user, ':id_game' => $id_game));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }

    public function update($user_game_id, $id_user, $id_game){
        $r = true;
        $this->update->execute(array(':user_game_id' => $user_game_id, ':id_user' => $id_user, ':id_game' => $id_game));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function selectById($id_user) {
        $this->selectById->execute(array(':id_user' => $id_user));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
}

?>