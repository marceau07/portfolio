<?php
session_start();
require_once '../src/lib/vendor/autoload.php';
require_once '../src/config/routing.php';
require_once '../src/modele/_classes.php';
require_once '../src/controler/_controlers.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(10);

$loader = new Twig_Loader_Filesystem('../src/view/');
$twig = new Twig_Environment($loader, array());
$twig->addGlobal('session', $_SESSION);
$contenu = getPage($db);
$contenu($db, $twig);
?>