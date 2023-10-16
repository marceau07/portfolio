<?php

session_start();
/* initialisation des fichiers TWIG */
require_once '../src/lib/vendor/autoload.php';
require_once '../src/config/routing.php';
require_once '../src/model/_classes.php';
require_once '../src/controller/_controllers.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(3);

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$acceptLang = ['fr', 'es', 'en'];
$lang = in_array($lang, $acceptLang) ? $lang : 'en';

$module = new Module($db);
$game = new Game($db);
$modules = $module->select();

$serialNumber = rand(0, 9);

if(empty($_COOKIE['PHPSESSID'])) {
    header('Location: ./');
} else {
    $newGame = $game->insert($_COOKIE['PHPSESSID'], 0, 0, 0, 0, "SN " . $serialNumber, 2);
    $currentGame = $game->selectCurrentGame($_COOKIE['PHPSESSID']);
}

$loader = new Twig_Loader_Filesystem('../src/view/');
$twig = new Twig_Environment($loader, array());
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->addGlobal('session', $_SESSION);
$twig->addGlobal('modules', $modules);
$twig->addGlobal('currentGame', $currentGame);
$twig->addGlobal('acceptLang', $acceptLang);

$contenu = getPage($db);

// Exécution de la fonction souhaitée
$contenu($twig, $lang, $db);

?>