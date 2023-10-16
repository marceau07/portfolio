<?php 
define("DB_SERVER", "localhost");
define("DB_NAME", 	"production");
define("DB_LOGIN", 	"login4017");
define("DB_PASSWD", "btsinfo");

try{
    $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWD);
} catch(Exception $e){
    $db = NULL;
}

?>