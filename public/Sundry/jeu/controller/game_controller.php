<?php

function actionLeaderboard($twig, $db) {
    $form = array();
    echo $twig->render('leaderboard.html.twig', array('form' => $form));
}

function actionGame($twig, $db) {
    $form = array();

    $detect = new Mobile_Detect;

    if ($detect->isMobile() || $detect->isTablet()) {
        $form['isMobile'] = "isMobile";
        if ($detect->isiOS()) {
            $form['isMobile'] = "isiOS";
        } elseif ($detect->isAndroid()) {
            $form['isMobile'] = "isAndroid";
        }
    } else {
        $form['isMobile'] = "isNonMobile";
    }

    echo $twig->render('game.html.twig', array('form' => $form));
}
