<?php

class Book {   
    private $db;
    private $select;
    private $selectTypesBooks;
    private $selectById;
    private $insert;
    private $update;
    private $updatePicture;
    
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
                                    FROM legendarium_books b 
                                    LEFT JOIN legendarium_clusters_books cb ON cb.id_book = b.book_id
                                    LEFT JOIN legendarium_authors a ON a.author_id = b.id_author
                                    LEFT JOIN legendarium_publishers p ON p.publisher_id = b.id_publisher
                                    LEFT JOIN legendarium_availability av ON av.availability_id = b.id_availability
                                    LEFT JOIN legendarium_types_books tb ON tb.type_book_id = b.id_type_book 
                                    ORDER BY b.book_title ");
        $this->selectTypesBooks = $db->prepare("SELECT tb.* FROM legendarium_types_books tb");
        $this->selectById = $db->prepare("SELECT 
                                        b.*, 
                                        REPLACE(b.book_price, '.', ',') AS book_price, 
                                        availability_label, 
                                        CONCAT(author_last_name, ' ', author_first_name) AS author_label, 
                                        publisher_label, 
                                        type_book_label
                                    FROM legendarium_books b 
                                    INNER JOIN legendarium_authors a ON a.author_id = b.id_author
                                    INNER JOIN legendarium_publishers p ON p.publisher_id = b.id_publisher
                                    INNER JOIN legendarium_availability av ON av.availability_id = b.id_availability
                                    INNER JOIN legendarium_types_books tb ON tb.type_book_id = b.id_type_book 
                                    WHERE b.book_id=:book_id ");
        $this->insert = $db->prepare("INSERT INTO legendarium_books 
                                        (book_title, 
                                        book_isbn, 
                                        book_synopsis, 
                                        book_price, 
                                        book_quantity, 
                                        book_picture, 
                                        id_availability, 
                                        id_type_book, 
                                        id_author, 
                                        id_publisher) 
                                    VALUES 
                                        (:book_title, 
                                        :book_isbn, 
                                        :book_synopsis, 
                                        :book_price, 
                                        :book_quantity, 
                                        :book_picture, 
                                        :id_availability, 
                                        :id_type_book, 
                                        :id_author, 
                                        :id_publisher)");
        $this->update = $db->prepare("UPDATE legendarium_books 
                                      SET book_title=:book_title, 
                                        book_isbn=:book_isbn, 
                                        book_synopsis=:book_synopsis, 
                                        book_price=:book_price, 
                                        book_quantity=:book_quantity, 
                                        book_picture=:book_picture, 
                                        id_availability=:id_availability, 
                                        id_type_book=:id_type_book, 
                                        id_author=:id_author, 
                                        id_publisher=:id_publisher
                                      WHERE book_id=:book_id ");
        $this->updatePicture = $db->prepare('UPDATE legendarium_books 
                                            SET book_picture=:book_picture
                                            WHERE book_id=:book_id ');
    }
    
    public function insert($bookTitle, $bookIsbn, $bookSynopsis, $bookPrice, $bookQuantity, $bookPicture, $idAvailability, $idTypeBook, $idAuthor, $idPublisher) { 
        $r = true;
        $this->insert->execute(array(
            ':book_title'       => $bookTitle, 
            ':book_isbn'        => $bookIsbn, 
            ':book_synopsis'    => $bookSynopsis, 
            ':book_price'       => $bookPrice, 
            ':book_quantity'    => $bookQuantity, 
            ':book_picture'     => $bookPicture, 
            ':id_availability'  => $idAvailability, 
            ':id_type_book'     => $idTypeBook, 
            ':id_author'        => $idAuthor, 
            ':id_publisher'     => $idPublisher 
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
    
    public function selectTypesBooks() {
        $this->selectTypesBooks->execute();
        if ($this->selectTypesBooks->errorCode()!=0){
            print_r($this->selectTypesBooks->errorInfo());  
        }
        return $this->selectTypesBooks->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function selectById($idBook) {
        $this->selectById->execute(array(':book_id' => $idBook));
        if ($this->selectById->errorCode()!=0){
            print_r($this->selectById->errorInfo());  
        }
        return $this->selectById->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($bookTitle, $bookIsbn, $bookSynopsis, $bookPrice, $bookQuantity, $bookPicture, $idAvailability, $idTypeBook, $idAuthor, $idPublisher, $idBook) { 
        $r = true;
        $this->update->execute(array(
            ':book_title'       => $bookTitle, 
            ':book_isbn'        => $bookIsbn, 
            ':book_synopsis'    => $bookSynopsis, 
            ':book_price'       => $bookPrice, 
            ':book_quantity'    => $bookQuantity, 
            ':book_picture'     => $bookPicture, 
            ':id_availability'  => $idAvailability, 
            ':id_type_book'     => $idTypeBook, 
            ':id_author'        => $idAuthor, 
            ':id_publisher'     => $idPublisher, 
            ':book_id'          => $idBook
        ));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }
    
    public function updatePicture($bookPicture, $idBook) { 
        $r = true;
        $this->updatePicture->execute(array(
            ':book_picture'     => $bookPicture, 
            ':book_id'          => $idBook
        ));
        if ($this->updatePicture->errorCode()!=0){
            print_r($this->updatePicture->errorInfo());
            $r = false;
        }
        return $r;
    }
}