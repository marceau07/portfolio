<?php

function actionIndex($twig, $db) {
    echo $twig->render('index.html.twig', array());
}

function actionMaintenance($twig, $db) {
    echo $twig->render('maintenance.html.twig', array());
}

function actionSignIn($twig, $db) {
    echo $twig->render('signin.html.twig', array());
}

function actionLogout($twig, $db) {
    echo $twig->render('logout.html.twig', array());
}

function actionCgu($twig, $db) {
    echo $twig->render('cgu.html.twig', array());
}

function actionPrivacy($twig, $db) {
    echo $twig->render('privacy.html.twig', array());
}

function actionAbout($twig, $db) {
    echo $twig->render('about.html.twig', array());
}
