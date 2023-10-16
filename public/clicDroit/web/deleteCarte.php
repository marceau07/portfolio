<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';
require_once '../src/modele/_classes.php';

$carte = new Carte($db);
$carte->delete($_POST['idCarte']);