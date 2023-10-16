<?php

function actionUtilisateur($twig, $db) {
    $form = array();

    $utilisateur = new Utilisateur($db);
    $listeU = $utilisateur->select();

    echo $twig->render('utilisateur.html.twig', array('form' => $form, 'listeU' => $listeU));
}

function actionAjouterUtilisateur($twig, $db) {
    $form = array();

    if (isset($_POST['btAjoutUtilisateur'])) {

        $mdp1 = htmlspecialchars($_POST['mdp1']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $fonction = htmlspecialchars($_POST['fonction']);
        $form['valide'] = true;

        $utilisateur = new Utilisateur($db);

        if ($mdp1 != $mdp2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } elseif (strlen($mdp1) < 8) {
            $form['valide'] = false;
            $form['message'] = 'Votre mot de passe est trop court il doit contenir au minimum 8 caractères';
        } else {
            $_SESSION['$pseudo'] = $pseudo;
            $utilisateur = new Utilisateur($db);
            $exec = $utilisateur->insert($pseudo, $fonction, password_hash($mdp1, PASSWORD_DEFAULT));

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
                actionDeconnexion($twig);
            }
        }
        $form['pseudo'] = $pseudo;
    }
    echo $twig->render('ajouterUtilisateur.html.twig', array('form' => $form, 'session' => $_SESSION));
}

function actionAjouterFonction($twig, $db) {
    $form = array();

    if (isset($_POST['btAjouterFonction'])) {

        $titre = htmlspecialchars($_POST['titre']);
        $form['valide'] = true;

        $fonction = new Fonction($db);

        $exec = $fonction->insert($titre);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Ajout Reussi.';
        }
    }

    echo $twig->render('ajouterFonction.html.twig', array('form' => $form));
}
