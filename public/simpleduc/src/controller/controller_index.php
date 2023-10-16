<?php

function actionIndex($twig) {
    $form = array();

    echo $twig->render('index.html.twig', array('form' => $form));
}

function actionAbout($twig) {
    echo $twig->render('about.html.twig');
}

function actionMentions($twig) {
    echo $twig->render('legalmentions.html.twig');
}

function actionMaintenance($twig) {
    echo $twig->render('maintenance.html.twig');
}
