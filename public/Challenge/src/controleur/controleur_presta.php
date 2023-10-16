<?php

function actionPresta($twig, $db) {
    $form = array();

    $presta = new Presta($db);
    $listeP = $presta->select();

    echo $twig->render('prestation.html.twig', array('form' => $form, 'listeP' => $listeP));
}

function actionAjouterPresta($twig, $db) {
    $form = array();
    $presta = new Presta($db);
    $listeP = $presta->select();
    $activite = new Activite($db);
    $listeA = $activite->select();
    $comporter = new Comporter($db);

    if (isset($_POST['btAjouterPresta'])) {
        $typePrest = htmlspecialchars($_POST['typePrest']);
        $montant = htmlspecialchars($_POST['montant']);
        $idAct = htmlspecialchars($_POST['activite']);
        $form['valide'] = true;

        $exec = $presta->insert($typePrest, $montant, $idAct);
        
        if($exec){
            header('Location: index.php?page=prestation');
        }
    }
    echo $twig->render('ajouterPresta.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeA' => $listeA));
}
