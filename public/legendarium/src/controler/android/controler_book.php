<?php

function androidViewBookAction($db) {    
    $book = new Book($db);
    try {
        $data = $book->selectById($_GET['idBook']);
    } catch (Exception $e) {
        $data = $e->getMessage();
    }
    
    die(json_encode($data));
}

function androidViewTypesBookAction($db) {    
    $type = new TypeBook($db);
    try {
        $data = $type->select();
    } catch (Exception $e) {
        $data = $e->getMessage();
    }
    
    die(json_encode($data));
}

function androidUpdateBookAction($db) {
    $result = array();

    $data = $_POST;

    $book = new Book($db);

    try {
        $exec = $book->update(
                $data['book_title'], 
                $data['book_isbn'], 
                $data['book_synopsis'], 
                $data['book_price'], 
                $data['book_quantity'], 
                $data['id_availability'], 
                $data['id_type_book'], 
                $data['id_author'], 
                $data['id_publisher'], 
                $data['book_id']);
        if($exec) { $result['message'] = "Le livre a bien été mis à jour"; }
        else { $result['message'] = "Une erreur s'est produite lors de la mise à jour du livre"; }
    } catch (Exception $e) {
        $result['message'] = $e->getMessage();
    }
    
    die(json_encode($result));
}
