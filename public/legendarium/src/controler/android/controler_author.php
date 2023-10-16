<?php

function androidViewAuthorsAction($db) {    
    $author = new Author($db);
    try {
        $data = $author->select();
    } catch (Exception $e) {
        $data = $e->getMessage();
    }
    
    die(json_encode($data));
}