<?php

class News{
    
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_news(news_title, news_label, news_date) 
									VALUES(:news_title, :news_label, NOW()) ");             
        $this->select = $db->prepare("SELECT news_title, news_label, datePublication 
									FROM legendarium_news ");
        $this->update = $db->prepare("UPDATE legendarium_news 
									SET news_title=:news_title, news_label=:news_label, datePublication=:datePublication 
									WHERE news_id=:news_id ");
        
    }

    public function insert($news_title, $news_label) {
        $r = true;
        $this->insert->execute(array(':news_title'=>$news_title, 'news_label'=>$news_label));
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

    public function update($news_id, $news_title, $news_label){
        $r = true;
        $this->update->execute(array(':news_id'=>$news_id, ':news_title'=>$news_title, 'news_label'=>$news_label));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>