<?php
session_start();
/* initialisation des fichiers TWIG */
require_once '../src/lib/vendor/autoload.php';
require_once '../src/config/routing.php';
require_once '../src/controller/_controllers.php';
require_once '../src/model/_classes.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(6);

$loader = new Twig_Loader_Filesystem('../src/view/');
$twig = new Twig_Environment($loader, array());
$twig->addGlobal('session', $_SESSION);

$contenu = getPage($db);
// Exécution de la fonction souhaitée
$contenu($twig,$db);


?>
