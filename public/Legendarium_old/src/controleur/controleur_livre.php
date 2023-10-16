<?php

/* Fonctions ayant un rapport avec le catalogue ou les livres */

function actionAjouterLivre($twig, $db) {
    $form = array();
    $auteur = new Auteur($db);
    $liste = $auteur->select();
    $form['auteur'] = $liste;
    $disponibilite = new Disponibilite($db);
    $liste = $disponibilite->select();
    $form['disponibilite'] = $liste;
    $genre = new Genre($db);
    $liste = $genre->select();
    $form['genre'] = $liste;
    $editeur = new Editeur($db);
    $liste = $editeur->select();
    $form['editeur'] = $liste;
    if (isset($_POST['btAjouter'])) {
        $titre = htmlspecialchars($_POST['titre']);
        $isbn = htmlspecialchars($_POST['isbn']);
        $synopsis = htmlspecialchars($_POST['synopsis']);
        $prix = htmlspecialchars($_POST['prix']);
        $quantite = htmlspecialchars($_POST['quantite']);
        $idDisponibilite = htmlspecialchars($_POST['idDisponibilite']);
        $idGenre = htmlspecialchars($_POST['idGenre']);
        $idAuteur = htmlspecialchars($_POST['idAuteur']);
        $idEditeur = htmlspecialchars($_POST['idEditeur']);
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
        $form['valide'] = true;
        $livre = new Livre($db);
        $exec = $livre->insert($titre, $isbn, $synopsis, $prix, $quantite, $photo, $idDisponibilite, $idGenre, $idAuteur, $idEditeur);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table livre ';
        }
    }
    echo $twig->render('ajouter-livre.html.twig', array('form' => $form));
}

function actionLivre($twig, $db) {
    $form = array();
    $livre = new Livre($db);
    $listeL = $livre->select();
    echo $twig->render('index.html.twig', array('form' => $form, 'listeL' => $listeL));
}

function actionCatalogue($twig, $db) {
    $form = array();
    $catalogue = new Livre($db);
    $liste = $catalogue->catalogue();

    echo $twig->render('catalogue.html.twig', array('form' => $form, 'liste' => $liste));
}

function actionCatalogueA($twig, $db) {
    $form = array();
    $catalogue = new Livre($db);
    
    if (isset($_POST['btModifierCat'])) {
        $catalogue = new Catalogue($db);
        $idLivre = $_GET['id'];
        $titre = htmlspecialchars($_POST['titre']);
        $isbn = htmlspecialchars($_POST['isbn']);
        $synopsis = htmlspecialchars($_POST['synopsis']);
        $prix = htmlspecialchars($_POST['prix']);
        $quantite = htmlspecialchars($_POST['quantite']);
        $photo = htmlspecialchars($_POST['photo']);
        $coupDeCoeur = htmlspecialchars($_POST['photo']);
        $idDisponibilite = htmlspecialchars($_POST['idDisponibilite']);
        $idGenre = htmlspecialchars($_POST['idGenre']);
        $idAuteur = htmlspecialchars($_POST['idAuteur']);
        $idEditeur = htmlspecialchars($_POST['idEditeur']);

        $exec = $catalogue->update($idLivre, $titre, $auteur, $synopsis, $etat, $prix, $quantite, $photo, $idDisponibilite, $idGenre, $idAuteur, $idEditeur);
        
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de mise à jour dans la table livres';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Modification réussie';
        }
    }
    if (isset($_GET['id'])) {
        $jeux = new Jeux($db);
        $liste = $catalogue->selectById($_GET['id']);
        if ($liste != null) {
            $form['catalogue'] = $liste;
        } else {
            $form['message'] = 'Livre incorrect';
        }
    }
$liste = $catalogue->catalogue();
    echo $twig->render('manageCatalogue.html.twig', array('form' => $form, 'liste' => $liste));
}
