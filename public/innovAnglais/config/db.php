<?php

$dns = 'mysql:host=localhost;dbname=db_innov';
$user = 'login4017';
$password = 'btsinfo';

try {
    $db = new PDO($dns, $user, $password);
} catch (PDOException $e) {
    $error = $e->getMessage();
    echo $error;
}