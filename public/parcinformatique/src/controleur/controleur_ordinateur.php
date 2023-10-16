<?php

function actionOrdinateur($twig, $db) {
    $form = array();

    $ordinateur = new Ordinateur($db);
    $employe = new Employe($db);
    $listeO = null;
    
    $listeO = $ordinateur->select();
    $listeE = $employe->select();

    echo $twig->render('parc.html.twig', array('form' => $form, 'listeO' => $listeO, 'listeE' => $listeE));
}

function actionAjouterOrdinateur($twig, $db) {
    $form = array();

    $ordinateur = new Ordinateur($db);
    $listeO = $ordinateur->select();

    if (isset($_POST['btAjoutOrdinateur'])) {
        $ip = htmlspecialchars($_POST['ip']);
        $mac = htmlspecialchars($_POST['mac']);
        $reseau = htmlspecialchars($_POST['reseau']);
        $os = htmlspecialchars($_POST['os']);
        $status = htmlspecialchars($_POST['status']);
        $employe = htmlspecialchars($_POST['employe']);

        $form['valide'] = true;

        $exec = $ordinateur->insert($ip, $mac, $reseau, $os, $status, $employe);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            header('Location: index.php?page=ordinateur');
            $form['valide'] = true;
        }
    }
    echo $twig->render('ordinateur.html.twig', array('form' => $form, 'listeO' => $listeO));
}



