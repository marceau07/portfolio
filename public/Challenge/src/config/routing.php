<?php

function getPage($db) {
    /* PAGES DE BASE */
    $lesPages['index'] = "actionIndex;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
    $lesPages['deconnexion'] = "actionDeconnexion;0";
    $lesPages['activite'] = "actionActivite;0";
    $lesPages['prestation'] = "actionPresta;0";
    $lesPages['clients'] = "actionClient;0";
    $lesPages['priserdv'] = "actionPrendreRdv;0";
    $lesPages['ajouterPresta'] = "actionAjouterPresta;2";
    $lesPages['inscription'] = "actionInscription;0";
    $lesPages['connexion'] = "actionConnexion;0";
    $lesPages['mesrdv'] = "actionMesRdv;0";
    $lesPages['ajouterActivite'] = "actionAjouterActivite;2";
    $lesPages['materiel'] = "actionMateriel;2";
    $lesPages['ajouterMateriel'] = "actionAjouterMateriel;2";
    $lesPages['prestationWS'] = "actionPrestationWS;0";
    $lesPages['activiteWS'] = "actionActiviteWS;0";
    $lesPages['reglementWS'] = "actionReglementWS;0";
    $lesPages['materielWS'] = "actionMaterielWS;0";
    $lesPages['profile'] = "actionProfile;0";
    $lesPages['supprimer'] = "actionSupprimerCompte;0";
    $lesPages['reglement'] = "actionReglement;2";
    $lesPages['ajouterReglement'] = "actionAjouterReglement;2";
    $lesPages['pack'] = "actionPack;2";
    $lesPages['ajouterPack'] = "actionAjouterPack;2";
    $lesPages['prestationPdf'] = "actionPrestationPdf;2";



    if ($db != null) {
        if (isset($_GET['page'])) {
            $page = $_GET['page']; // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
        } else { // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page par défaut
            $page = 'index';
        }
        if (!isset($lesPages[$page])) { // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'index';
        }

        $explose = explode(";", $lesPages[$page]); // Nous découpons la ligne du tableau sur le caractère « ; » Le résultat est stocké dans le tableau $explose
        $role = $explose[1]; // Le rôle est dans la 2ème partie du tableau $explose

        if ($role != 0) { // Si mon rôle nécessite une vérification
            if (isset($_SESSION['email'])) { // Si je me suis authentifié
                if (isset($_SESSION['idRole'])) { // Si j’ai bien un rôle
                    if ($role != $_SESSION['idRole']) { // Si mon rôle ne correspond pas à celui qui est nécessaire pour voir la page
                        $contenu = 'actionIndex'; // Je le redirige vers l’accueil, car il n’a pas le bon rôle
                    } else {
                        $contenu = $explose[0]; // Je récupère le nom du contrôleur, car il a le bon rôle
                    }
                } else {
                    $contenu = 'actionIndex';
                }
            } else {
                $contenu = 'actionIndex'; // Page d’accueil, car il n’est pas authentifié
            }
        } else {
            $contenu = $explose[0]; // Je récupère le contrôleur, car il n’a pas besoin d’avoir un rôle
        }
    } else { // Si $db est null
        $contenu = 'actionMaintenance';
    }
    return $contenu;
}

?>
