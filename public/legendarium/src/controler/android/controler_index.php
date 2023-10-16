<?php

function androidFiltersIndex($db) {
    $book = new Book($db);
    $filters = $book->selectTypesBooks();
    $nbFilters = sizeof($filters);
    die(json_encode(array(
        "filters" => $filters, 
        "nbFilters" => $nbFilters, 
    )));
}

function androidIndexClustersAction($db) {    
    $cluster = new ClusterBooks($db);
    try {
        $clusters = $cluster->select();
    } catch (Exception $e) {
        $clusters = $e->getMessage();
    }
    
    die(json_encode($clusters));
}

function androidIndexBooksAction($db) {    
    $book = new Book($db);
    try {
        $books = $book->select();
    } catch (Exception $e) {
        $books = $e->getMessage();
    }
    
    die(json_encode($books));
}

function androidSigninAction($db) {
    $data = $_POST;
    
    $user = new User($db);
    try {
        $exec = $user->insert($data['user_email'], $data['user_last_name'], $data['user_first_name'], $data['user_nickname'], $data['user_phone'], password_hash($data['user_password'], PASSWORD_DEFAULT));
    } catch (Exception $e) {
        $data = false . " " . $e->getMessage();
    }
    
    die(json_encode(array(
        "POST" => $data
    )));
}

function androidLoginAction($db) {
    $result = array();
    
    $data = $_POST;
    
    $user = new User($db);
    $aUser = $user->connect($data['user_nickname_email']);
    
    if ($aUser != null) {
        if (!password_verify($data['user_password'], $aUser['user_password'])) {
            $result['message'] = 'Mot de passe incorrect';
            $result['field'] = 'password';
        } else {
            $aUser = $user->selectByNicknameOrEmail($data['user_nickname_email']);
            die(json_encode(array(
                "aUser" => $aUser
            )));
        }
    } elseif ($aUser == false) {
        $result['message'] = 'Login incorrect';
        $result['field'] = 'login';
    }
    
    die(json_encode(array(
        "result" => $result
    )));
}