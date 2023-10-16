<?php

function actionMesRdv($twig, $db) {
    $form = array();
    $rdv = new Demander($db);
    $listeR = $rdv->selectByEmail($_SESSION['email']);
    
    echo $twig->render('mesRdv.html.twig', array('form' => $form, 'listeR' => $listeR));
}
