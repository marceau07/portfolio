<?php

class Game {

    private $db;
    private $insert;
    private $select;
    private $selectCurrentGame;
    private $updateWires;
    private $updateMemory;
    private $updateSymbols;
    private $updateMorseCode;
    private $delete;
    private $deleteByNickname;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO ktane_game(nickname, validatedWiresModule, validatedMemoryModule, validatedSymbolsModule, validatedMorseCodeModule, serialNumber, idDifficulty) 
									VALUES(:nickname, :validatedWiresModule, :validatedMemoryModule, :validatedSymbolsModule, :validatedMorseCodeModule, :serialNumber, :idDifficulty)");
        $this->select = $db->prepare("SELECT * 
									FROM ktane_game");
        $this->selectCurrentGame = $db->prepare("SELECT * 
                                                FROM ktane_game
                                                WHERE nickname=:nickname");
        $this->updateWires = $db->prepare("UPDATE ktane_game 
										SET validatedWiresModule=:valeur 
										WHERE nickname=:nickname");
        $this->updateMemory = $db->prepare("UPDATE ktane_game 
											SET validatedMemoryModule=:valeur 
											WHERE nickname=:nickname");
        $this->updateSymbols = $db->prepare("UPDATE ktane_game 
											SET validatedSymbolsModule=:valeur 
											WHERE nickname=:nickname");
        $this->updateMorseCode = $db->prepare("UPDATE ktane_game 
											SET validatedMorseCodeModule=:valeur 
											WHERE nickname=:nickname");
        $this->delete = $db->prepare("DELETE FROM ktane_game 
									WHERE idGame=:idGame");
        $this->deleteByNickname = $db->prepare("DELETE FROM ktane_game 
												WHERE nickname=:nickname");
    }

    public function insert($nickname, $validatedWiresModule, $validatedMemoryModule, $validatedSymbolsModule, $validatedMorseCodeModule, $serialNumber, $idDifficulty) {
        $r = true;
        $this->insert->execute(array(':nickname' => $nickname, ':validatedWiresModule' => $validatedWiresModule, ':validatedMemoryModule' => $validatedMemoryModule, ':validatedSymbolsModule' => $validatedSymbolsModule, ':validatedMorseCodeModule' => $validatedMorseCodeModule, ':serialNumber' => $serialNumber, ':idDifficulty' => $idDifficulty));
        if ($this->insert->errorCode() != 0) {
            //print_r($this->insert->errorInfo());
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

    public function selectCurrentGame($nickname) {
        $this->selectCurrentGame->execute(array(':nickname' => $nickname));
        if ($this->selectCurrentGame->errorCode() != 0) {
            print_r($this->selectCurrentGame->errorInfo());
        }
        return $this->selectCurrentGame->fetch();
    }

    public function updateWires($valeur, $nickname) {
        $r = true;
        $this->updateWires->execute(array(':valeur' => $valeur, ':nickname' => $nickname));
        if ($this->updateWires->errorCode() != 0) {
            print_r($this->updateWires->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateMemory($valeur, $nickname) {
        $r = true;
        $this->updateMemory->execute(array(':valeur' => $valeur, ':nickname' => $nickname));
        if ($this->updateMemory->errorCode() != 0) {
            print_r($this->updateMemory->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateSymbols($valeur, $nickname) {
        $r = true;
        $this->updateSymbols->execute(array(':valeur' => $valeur, ':nickname' => $nickname));
        if ($this->updateSymbols->errorCode() != 0) {
            print_r($this->updateSymbols->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function updateMorseCode($valeur, $nickname) {
        $r = true;
        $this->updateMorseCode->execute(array(':valeur' => $valeur, ':nickname' => $nickname));
        if ($this->updateMorseCode->errorCode() != 0) {
            print_r($this->updateMorseCode->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function delete($idGame) {
        $r = true;
        $this->delete->execute(array(':idGame' => $idGame));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function deleteByNickname($nickname) {
        $r = true;
        $this->deleteByNickname->execute(array(':nickname' => $nickname));
        if ($this->deleteByNickname->errorCode() != 0) {
            print_r($this->deleteByNickname->errorInfo());
            $r = false;
        }
        return $r;
    }

}
