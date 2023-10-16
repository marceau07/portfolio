<?php

function actionOs($twig, $db) {
    $form = array();

    $os = new Os($db);
    $listeOS = $os->select();
    
    echo $twig->render('os.html.twig', array('form' => $form, 'listeOS' => $listeOS));
}

function actionAjouterOS($twig, $db) {
    $form = array();

    $os = new Os($db);
    $listeOS = $os->select();

    if (isset($_POST['btAjouterOs'])) {
       
        $nomOs = htmlspecialchars($_POST['nomOs']);

        $form['valide'] = true;

        $exec = $os->insert($idOs, $nomOs);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            header('Location: index.php?page=os');
            $form['valide'] = true;
        }
    }
    echo $twig->render('ajouterOs.html.twig', array('form' => $form, 'listeOS' => $listeOS));
}
