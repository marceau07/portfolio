<?php

function actionAjouterReglement($twig, $db) {
    $form = array();
    
    $reglement = new Reglement($db);
    $listeR = $reglement->select();
    
    if (isset($_POST['btAjouterReglement'])) {
        $typeRegle = htmlspecialchars($_POST['typeRegle']);
        
        $form['valide'] = true;
        
        $exec = $reglement->insert($typeRegle);
        
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            header('Location: index.php?page=reglement');
            $form['valide'] = true;
            $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
        }
    }
    echo $twig->render('ajouterReglement.html.twig', array('form' => $form, 'listeR' => $listeR));
}

function actionReglement($twig, $db){
    $form = array(); 
    $reglement = new Reglement($db);
    $listeR = $reglement->select();
    
    echo $twig->render('reglement.html.twig', array('form'=>$form,'listeR'=>$listeR));
}