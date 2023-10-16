<?php

function actionIndex($twig, $lang) {
    $form = array();
    echo $twig->render("${lang}/index.html.twig", array('form' => $form));
}

function actionGetModulesComplete($twig, $lang, $db) {
    $game = new Game($db);
    
    $currentGame = $game->selectCurrentGame($_COOKIE['PHPSESSID']);
        
    echo json_encode($currentGame);
}

function actionSettings($twig, $lang, $db) {
    $form = array();

    $game = new Game($db);
    $difficulty = new Difficulty($db);

    $form['difficulties'] = $difficulty->select();
    //$form['game'] = $game->selectCurrentGame('mrodrigues');

    for ($i = 0; $i < sizeof($form['difficulties']); $i++) {
        $form['difficulties'][$i]['timer'] = gmdate("i\m s\s", $form['difficulties'][$i]['timer']);
    }

    if (!empty($_POST['nickname']) && isset($_POST['nickname']) && !empty($_POST['idDifficulty']) && isset($_POST['idDifficulty'])) {
        $game->insert($_POST['nickname'], $_POST['idDifficulty']);
        $_SESSION['nickname'] = $_POST['nickname'];
    }
    echo $twig->render("${lang}/settings.html.twig", array('form' => $form));
}

function actionHistory($twig, $lang, $db) {
    $form = array();

    $history = new History($db);

    $form['history'] = $history->selectByNickname($_COOKIE['PHPSESSID']);
    for ($i = 0; $i < sizeof($form['history']); $i++) {
        $form['history'][$i]['score'] = gmdate("i\m s\s", $form['history'][$i]['score']);
        $form['history'][$i]['timer'] = gmdate("i\m s\s", $form['history'][$i]['timer']);
    }

    echo $twig->render("${lang}/history.html.twig", array('form' => $form));
}
