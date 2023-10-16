<?php

class ClusterBooks {
    private $db;
    private $select;
    private $insert;
    
    public function __construct($db) {
        $this->db = $db;
        $this->select = $db->prepare("SELECT 
                                        cb.*, 
                                        b.*, 
                                        REPLACE(b.book_price, '.', ',') AS book_price, 
                                        availability_label, 
                                        CONCAT(author_last_name, ' ', author_first_name) AS author_label, 
                                        publisher_label, 
                                        type_book_label
                                    FROM legendarium_clusters_books cb 
                                    INNER JOIN legendarium_books b ON b.book_id = cb.id_book
                                    INNER JOIN legendarium_authors a ON a.author_id = b.id_author
                                    INNER JOIN legendarium_publishers p ON p.publisher_id = b.id_publisher
                                    INNER JOIN legendarium_availability av ON av.availability_id = b.id_availability
                                    INNER JOIN legendarium_types_books tb ON tb.type_book_id = b.id_type_book
                                    WHERE cb_end_date IS NULL ");
        $this->insert = $db->prepare("INSERT INTO legendarium_clusters_books(id_book, cb_start_date, cb_end_date) 
                                    VALUES (:id_book, NOW(), NULL)");                  
    }
    
    public function insert($id_book) { 
        $r = true;
        $this->insert->execute(array(
            ':id_book' => $id_book,
        ));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function select() {
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll(PDO::FETCH_ASSOC);
    }
}