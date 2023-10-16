<?php

function actionActivite($twig, $db) {
    $form = array();
    $activite = new Activite($db);
    $listeA = $activite->select();
    $posseder = new Posseder($db);
    $listeP = $posseder->select();

    echo $twig->render('activite.html.twig', array('form' => $form, 'listeA' => $listeA, 'listeP' => $listeP));
}

function actionAjouterActivite($twig, $db) {
    $form = array();
    $activite = new Activite($db);
    $listeA = $activite->select();
    $materiel = new Materiel($db);
    $listeM = $materiel->select();
    $posseder = new Posseder($db);

    if (isset($_POST['btAjouterActivite'])) {
        $codeAct = htmlspecialchars($_POST['codeAct']);
        $descAct = htmlspecialchars($_POST['descAct']);
        $nomAct = htmlspecialchars($_POST['nomAct']);
        $codeMat = htmlspecialchars($_POST['idMateriel']);

        $form['valide'] = true;

        $exec = $activite->insert($nomAct, $descAct);
        $codeAct = $activite->select();
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            header('Location: index.php?page=activite');
            $form['valide'] = true;
            $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
        }
    }
    echo $twig->render('ajouterActivite.html.twig', array('form' => $form, 'listeM' => $listeM, 'listeA' => $listeA));
}
