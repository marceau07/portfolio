<?php

function actionEmploye($twig, $db) {
    $form = array();

    $employe = new Employe($db);
    $listeE = $employe->select();

    echo $twig->render('employe.html.twig', array('form' => $form, 'listeE' => $listeE));
}

function actionMonCompte($twig, $db) {

    $form = array();
    $employe = new Employe($db);

    $form['profile'] = $employe->selectByEmail($_SESSION['email']);

    if (isset($_POST['btModifier'])) {
        $prenomEmploye = htmlspecialchars($_POST['prenomEmploye']);
        $nomEmploye = htmlspecialchars($_POST['nomEmploye']);
        $idEmploye = htmlspecialchars($_POST['idEmploye']);

        $exec = $employe->update($_SESSION['email'], $nomEmploye, $prenomEmploye, $idEmploye);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Modification non effectuée.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Modification effectuée.';
        }
    }

    echo $twig->render('monCompte.html.twig', array('form' => $form));
}
function actionSupprimerCompte($twig, $db) {
    $employe = new Employe($db);

    $exec = $employe->delete($_SESSION['idEmploye']);
    
    actionDeconnexion();
    
    header("Location: index.php");
}