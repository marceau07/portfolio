<?php

/* fonction des pages principales */

function actionAccueil($twig, $db) {
    $livre = new Livre($db);
    $liste = $livre->select();

    $horaire = new Horaire($db);
    $infos['horaire'] = $horaire->select();

    $librairie = new Librairie($db);
    $carousel = $librairie->select();
    echo $twig->render('index.html.twig', array('infos' => $infos, 'liste' => $liste, 'carousel' => $carousel));
}

function actionMaintenance($twig){
    echo $twig->render('maintenance.html.twig');
}

function actionPropos($twig) {
    echo $twig->render('propos.html.twig', array());
}

function actionContact($twig) {
    echo $twig->render('contact.html.twig', array());
}

function actionMentions($twig) {
    echo $twig->render('mentions.html.twig', array());
}

/* fonction de l'administration */

function actionAdmin($twig, $db) {
    $livre = new Livre($db);
    $listeL = $livre->select();
    $formL = array();
    $auteur = new Auteur($db);
    $listeL = $auteur->select();
    $formL['auteur'] = $listeL;
    $disponibilite = new Disponibilite($db);
    $listeL = $disponibilite->select();
    $formL['disponibilite'] = $listeL;
    $genre = new Genre($db);
    $listeL = $genre->select();
    $formL['genre'] = $listeL;
    $editeur = new Editeur($db);
    $listeL = $editeur->select();
    $formL['editeur'] = $listeL;
    if (isset($_POST['btAjouterLivre'])) {
        $titre = htmlspecialchars($_POST['inputTitreLivre']);
        $isbn = htmlspecialchars($_POST['inputIsbnLivre']);
        $synopsis = htmlspecialchars($_POST['inputSynopsisLivre']);
        $prix = htmlspecialchars($_POST['inputPrixLivre']);
        $quantite = htmlspecialchars($_POST['inputQuantiteLivre']);
        $idDisponibilite = htmlspecialchars($_POST['inputIdDisponibiliteLivre']);
        $idGenre = htmlspecialchars($_POST['inputIdGenreLivre']);
        $idAuteur = htmlspecialchars($_POST['inputIdAuteurLivre']);
        $idEditeur = htmlspecialchars($_POST['inputIdEditeurLivre']);
        $photo = NULL;
        if (isset($_FILES['photo'])) {
            if (!empty($_FILES['photo']['name'])) {
                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '/data/si62019/mrodrigues/Legendarium/web/images/livres/couvertures/';
                if (!in_array(substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok)) {
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg !';
                } else {
                    if (file_exists($_FILES['photo']['tmp_name']) && (filesize($_FILES['photo']['tmp_name'])) > $taille_max) {
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    } else {
                        $photo = basename($_FILES['photo']['name']);
                        // enlever les accents
                        $photo = strtr($photo, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAA
                                         AAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        // remplacer les caractères autres que lettres, chiffres et point par _
                        $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                        // copie du fichier

                        move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier . $photo);
                    }
                }
            }
        }
        $formL['valide'] = true;
        $unLivre = new Livre($db);
        $exec = $unLivre->insert($titre, $isbn, $synopsis, $prix, $quantite, $idDisponibilite, $idGenre, $idAuteur, $idEditeur, $photo);
        if (!$exec) {
            $formL['valide'] = false;
            $formL['message'] = 'Problème d\'insertion dans la table livre ';
        }
    }

    $utilisateur = new Utilisateur($db);
    $listeU = $utilisateur->select();

    $nouveaute = new News($db);
    $listeN = $nouveaute->select();
    if (isset($_POST['btAjouterNews'])) {
        $titre = htmlspecialchars($_POST['inputTitreNews']);
        $libelle = htmlspecialchars($_POST['inputLibelleNews']);

        $formN['valide'] = true;
        $news = new News($db);
        $exec = $news->insert($titre, $libelle);
        if (!$exec) {
            $formN['valide'] = false;
            $formN['message'] = 'Problème d\'insertion dans la table news ';
        }
    }

    $jdr = new Jeux($db);
    $listeJDR = $jdr->select();

    $carousel = new Librairie($db);
    $listeCarousel = $carousel->select();
    if (isset($_POST['btAjouterCarousel'])) {
        $titre = htmlspecialchars($_POST['inputTitreCarousel']);
        $libelle = htmlspecialchars($_POST['inputLibelleCarousel']);
        $photo = htmlspecialchars($_POST['inputPhotoCarousel']);
        if (isset($_FILES['photo'])) {
            if (!empty($_FILES['photo']['name'])) {
                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '/data/si62019/mrodrigues/Legendarium/web/images/carousel/';
                if (!in_array(substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok)) {
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg !';
                } else {
                    if (file_exists($_FILES['photo']['tmp_name']) && (filesize($_FILES['photo']['tmp_name'])) > $taille_max) {
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    } else {
                        $photo = basename($_FILES['photo']['name']);
                        // enlever les accents
                        $photo = strtr($photo, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAA
                                         AAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        // remplacer les caractères autres que lettres, chiffres et point par _
                        $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                        // copie du fichier

                        move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier . $photo);
                    }
                }
            }
        }
        $formC['valide'] = true;
        $carousel = new Librairie($db);
        $exec = $carousel->insert($photo, $titre, $libelle);
        if (!$exec) {
            $formC['valide'] = false;
            $formC['message'] = 'Problème d\'insertion dans la table librairie';
        }
    }
    echo $twig->render('administration.html.twig', array('formL' => $formL, 'listeL' => $listeL, 'listeU' => $listeU,
        'formN' => $formN, 'listeN' => $listeN, 'listeJDR' => $listeJDR,
        'formC' => $formC, 'listeCarousel' => $listeCarousel));
}

function actionMesDonnees($twig, $db) {
    $utilisateur = new Utilisateur($db);
    $unUtilisateur = $utilisateur->selectMonCompte($_SESSION['email']);

    $infosMail = $_SESSION['email'];
    $infosNom = $_SESSION['nom'];
    $infosPrenom = $_SESSION['prenom'];
    $infosPseudo = $_SESSION['pseudo'];
    $infosTelephone = $_SESSION['telephone'];
    $infosPhoto = $_SESSION['photo'];

    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $from = "marceau0707@gmail.com";
    $to = $_SESSION['email'];
    $subject = "Vos données personnelles";
    $message = "<p>Mail: " . $infosMail . "</p><p>Nom: " . $infosNom . "</p><p>Prenom: " . $infosPrenom . "</p><p>Pseudo: " . $infosPseudo . "</p><p>Téléphone: " . $infosTelephone . '</p><br><p>Photo:<img src="http://si6.arras-sio.com/mrodrigues/Legendarium/web/images/utilisateurs/' . $infosPhoto . '" alt="Votre photo"/></p>';
    $headers = "From: " . $from . "\n";
    $headers .= "Reply-To: " . $from . "\n";
    $headers .= "Content-Type: text/html; charset=\"utf-8\"";

    if (mail($to, $subject, $message, $headers)) {
        echo 'reussi';
        echo $message;
    } else {
        echo 'Erreur';
    }
}
