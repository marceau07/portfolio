<?php

/* Fonctions ayant un rapport avec le jeu de role */

function actionParticiper($twig, $db) {
    $form = array();
    if (isset($_POST['btParticiper'])) {
        $email = htmlspecialchars($_SESSION['email']);
        $idJeu = htmlspecialchars($_POST['idJeu']);

        $form['valide'] = true;
        $jouer = new Jouer($db);
        $exec = $jouer->insert($email, $idJeu);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Jeux ';
        }
    }
    if(isset($_GET['id'])) {
        $jeux = new Jeux($db);
        $liste = $jeux->selectById($idJeu);
        if ($liste != null) {
            $form['jdr'] = $liste;
            $form['libelleJeu'] = $liste['libelleJeu'];
        } else {
            $form['message'] = 'Jeu de rôle incorrect';
        }
    }
    if (!empty($exec)) {
        $message = "Un mail vous a été envoyé avec un resumé de votre inscription au jeu de rôle";
        $from = "Legendarium@pro.fr";
        $to = $_SESSION['email'];
        $subject = "Inscription à un jeu de rôle";
        
        $message = "<p>L'inscription de cette adresse (" . $email . ") a bien été prise en compte.</p>";
        $headers = "From: " . $from . "\n";
        $headers .= "Reply-To: " . $from . "\n";
        $headers .= "Content-Type: text/html; charset=\"utf-8\"";

        $form['message'] = $message;
        $form['resul'] = mail($to, $subject, $message, $headers);
    }
    $jeux = new Jeux($db);
    $liste = $jeux->select();

    echo $twig->render('participer.html.twig', array('form' => $form, 'liste' => $liste));
}

function actionProposer($twig, $db) {
    $form = array();
    if (isset($_POST['btProposer'])) {
        $titre = htmlspecialchars($_POST['nomJeu']);
        $synopsis = htmlspecialchars($_POST['synopsis']);
        $nbJoueurMax = htmlspecialchars($_POST['nbJoueurMax']);
        $dateEvenement = htmlspecialchars($_POST['dateEvenement']);
        $idRoleJeu = htmlspecialchars($_POST['idRoleJeu']);

        $form['valide'] = true;
        $jeux = new Jeux($db);
        $exec = $jeux->insert($titre, $synopsis, $nbJoueurMax, $dateEvenement, $idRoleJeu);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Jeux ';
        }
    }
    if (!empty($exec)) {
        $message = "Un mail vous a été envoyé avec un resumé de votre proposition";
        $from = "marceau0707@gmail.com";
        $to = $_SESSION['email'];
        $subject = "Proposition jeu de rôle";
        if ($idRoleJeu == 0) {
            $messagein = "Joueur lambda";
        } else {
            $messagein = "Maitre de jeu";
        };
        $form['titre'] = $titre;
        $form['synopsis'] = $synopsis;
        $form['nbJoueurMax'] = $nbJoueurMax;
        $form['dateEvenement'] = $dateEvenement;
        $form['idRoleJeu'] = $idRoleJeu;

        $message = "<p>Titre du jeu: " . $titre . "</p><p>Résumé de votre jeu: " . $synopsis . "</p><p>Nombre de joueurs maximum autorisés: " . $nbJoueurMax . "</p>"
                . "<p>Date de l'évènement: " . $dateEvenement . "</p><p>Votre rôle dans le jeu: " . $messagein . " </p>";
        $headers = "From: " . $from . "\n";
        $headers .= "Reply-To: " . $from . "\n";
        $headers .= "Content-Type: text/html; charset=\"utf-8\"";

        $form['message'] = $message;
        $form['resul'] = mail($to, $subject, $message, $headers);
    }
    echo $twig->render('proposer.html.twig', array('form' => $form));
}

function actionJDRA($twig, $db) {
    $form = array();

    $jeux = new Jeux($db);

    if (isset($_GET['id'])) {
        $jeux = new Jeux($db);
        $liste = $jeux->selectById($_GET['id']);
        if ($liste != null) {
            $form['jdr'] = $liste;
        } else {
            $form['message'] = 'Jeu de rôle incorrect';
        }
    }
    $liste = $jeux->select();

    echo $twig->render('manageJDR.html.twig', array('form' => $form, 'liste' => $liste));
}

function actionJDRAM($twig, $db) {
    $form = array();
    $choixAdmin = new ChoixAdmin($db);
    $liste = $choixAdmin->select();
    $form['choixAdmin'] = $liste;
    $jeux = new Jeux($db);

    if (isset($_GET['id'])) {
        $jeux = new Jeux($db);
        $liste = $jeux->selectById($_GET['id']);
        if ($liste != null) {
            $form['jdr'] = $liste;
        } else {
            $form['message'] = 'Jeu de rôle incorrect';
        }
    }
    if (isset($_POST['btModifierJDRM'])) {

        $idJeu = $_POST['idJeu'];
        $titreJeu = htmlspecialchars($_POST['nomJeu']);
        $libelleJeu = htmlspecialchars($_POST['synopsis']);
        $choixAdmin = htmlspecialchars($_POST['choixAdmin']);
        $nbJoueurMax = htmlspecialchars($_POST['nbJoueurMax']);
        $dateEvenement = htmlspecialchars($_POST['dateEvenement']);
        $idRoleJeu = htmlspecialchars($_POST['idRoleJeu']);
        $exec = $jeux->update($idJeu, $titreJeu, $libelleJeu, $choixAdmin, $nbJoueurMax, $dateEvenement, $idRoleJeu);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de mise à jour dans la table jeux';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Modification réussie';
        }
        header("Location: index.php?page=managejdr");
    }
    $liste = $jeux->select();
    echo $twig->render('manageJDRM.html.twig', array('form' => $form, 'liste' => $liste));
}
