<?php

function actionMateriel($twig, $db) {
    $form = array();
    $materiel = new Materiel($db);
    $listeM = $materiel->select();

    echo $twig->render('materiel.html.twig', array('form' => $form, 'listeM' => $listeM));
}

function actionAjouterMateriel($twig, $db) {
    $form = array();

    $materiel = new Materiel($db);
    $listeM = $materiel->select();

    if (isset($_POST['btAjouterMateriel'])) {
        $nomMateriel = htmlspecialchars($_POST['nomMateriel']);
        $commentaire = htmlspecialchars($_POST['commentaire']);

        $form['valide'] = true;

        $exec = $materiel->insert($nomMateriel, $commentaire);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            header('Location: index.php?page=materiel');
            $form['valide'] = true;
            $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
        }
    }
    echo $twig->render('ajouterMateriel.html.twig', array('form' => $form, 'listeM' => $listeM));
}
