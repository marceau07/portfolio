<?php

function androidViewPublishersAction($db) {    
    $publisher = new Publisher($db);
    try {
        $data = $publisher->select();
    } catch (Exception $e) {
        $data = $e->getMessage();
    }
    
    die(json_encode($data));
}