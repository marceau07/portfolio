<?php
session_start();
require_once '../src/lib/vendor/autoload.php';
require_once '../src/config/routing.php';
require_once '../src/controleur/controleur_index.php';
require_once '../src/modele/modele_index.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(20);

$loader = new Twig_Loader_Filesystem('../src/vue/');

$twig = new Twig_Environment($loader, array());

$twig->addGlobal('session', $_SESSION);

$contenu = getPage($db);
$contenu($twig,$db);

?>
