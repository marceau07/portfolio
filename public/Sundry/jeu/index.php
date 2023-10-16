<?php

session_start();

require_once 'lib/vendor/autoload.php';
require_once 'config/routing.php';
require_once 'model/_class.php';
require_once 'controller/_controllers.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(28);

$loader = new Twig_Loader_Filesystem('view/');
$twig = new Twig_Environment($loader, array());
$twig->addGlobal('session', $_SESSION);

$contenu = getPage($db);

$contenu($twig, $db);

?>
