<?php

function actionPack($twig, $db) {
    $form = array();

    $posseder = new Posseder($db);
    $listeP = $posseder->select();

    echo $twig->render('pack.html.twig', array('form' => $form, 'listeP' => $listeP));
}

function actionAjouterPack($twig, $db) {
    $form = array();
    $presta = new Presta($db);
    $listeP = $presta->select();
    $activite = new Activite($db);
    $listeA = $activite->select();
    $comporter = new Comporter($db);
    $materiel = new Materiel($db);
    $listeM = $materiel->select();

    if (isset($_POST['btAjouterPresta'])) {
        $typePrest = htmlspecialchars($_POST['typePrest']);
        $montant = htmlspecialchars($_POST['montant']);
        $idAct = htmlspecialchars($_POST['activite']);
        $form['valide'] = true;

        $exec = $presta->insert($typePrest, $montant, $idAct);
        $liste = $presta->select();
        $idPrest = $liste['idPrest'];
        $exec1 = $comporter->insert($idAct, $idPrest);
    }
    echo $twig->render('ajouterPack.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeA' => $listeA, 'listeM' => $listeM));
}
