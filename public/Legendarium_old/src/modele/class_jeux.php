<?php

class Jeux{
    
    private $db;
    private $insert; 
    private $select;
    private $selectById;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_games(game_label, game_status, game_max_players, game_date_event, id_role_game) 
									VALUES(:game_label, :game_status, :game_max_players, :game_date_event, :id_role_game)");             
        $this->select = $db->prepare("SELECT * 
									FROM legendarium_games ");
        $this->update = $db->prepare("UPDATE legendarium_games 
									SET game_label=:game_label, game_status=:game_status, choixAdmin=:choixAdmin, game_max_players=:game_max_players, game_date_event=:game_date_event, id_role_game=:id_role_game 
									WHERE game_id=:game_id"); 
        $this->selectById = $db->prepare("SELECT * 
										FROM legendarium_games 
										WHERE game_id=:game_id");
    }

    public function insert($game_label, $game_status, $game_max_players, $game_date_event, $id_role_game) {
        $r = true;
        $this->insert->execute(array(':game_label' => $game_label,':game_status' => $game_status, ':game_max_players' => $game_max_players, ':game_date_event' => $game_date_event, ':id_role_game' => $id_role_game));
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

    public function update($game_id, $game_label, $game_status, $choixAdmin, $game_max_players, $game_date_event, $id_role_game){
        $r = true;
        $this->update->execute(array(':game_id' => $game_id, ':game_label' => $game_label,':game_status' => $game_status, 
									':choixAdmin' => $choixAdmin, ':game_max_players' => $game_max_players, ':game_date_event' => $game_date_event, ':id_role_game' => $id_role_game));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
    
    public function selectById($game_id) {
        $this->selectById->execute(array(':game_id' => $game_id));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }
}

?>