<?php

function getPage($db) {
    $lesPages['accueil'] = "actionAccueil;0";
    $lesPages['propos'] = "actionPropos;0";
    $lesPages['mentions'] = "actionMentions;0";
    $lesPages['connexion'] = "actionConnexion;0";
    $lesPages['forgetmdp'] = "actionMdpOublie;0";
    $lesPages['forgetmdpsecond'] = "actionMdpOublieSecondStep;0";
    $lesPages['inscrire'] = "actionInscrire;0";
    $lesPages['deconnexion'] = "actionDeconnexion;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
    $lesPages['contact'] = "actionContact;0";
    $lesPages['moncompte'] = "actionMonCompte;0";
    $lesPages['participer'] = "actionParticiper;0";
    $lesPages['proposer'] = "actionProposer;0";
    $lesPages['ajouterlivre'] = "actionAjouterLivre;1";
    $lesPages['admin'] = "actionAdmin;1";
    $lesPages['mesdonnees'] = "actionMesDonnees;0";
    $lesPages['modifutilisateur'] = "actionModifUtilisateursA;1";
    $lesPages['catalogue'] = "actionCatalogue;0";
    $lesPages['manageutilisateurs'] = "actionUtilisateursA;1";
    $lesPages['managejdr'] = "actionJDRA;1";
    $lesPages['managejdrmodif'] = "actionJDRAM;1";
    $lesPages['managecarousel'] = "actionCarouselA;1";
    $lesPages['managecatalogue'] = "actionCatalogueA;1";
    $lesPages['livreA'] = "actionLivresA;1";
    $lesPages['essai'] = "actionEssai;1";
    $lesPages['calendar'] = "actionCalendar;0";

    if ($db != null) {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'accueil';
        }
        if (!isset($lesPages[$page])) {
            $page = 'accueil';
        }
        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];
        if ($role != 0) {
            if (isset($_SESSION['email'])) {  // Si je me suis authentifié
                if (isset($_SESSION['role'])) {  // Si j’ai bien un rôle  
                    if ($role != $_SESSION['role']) { // Si mon rôle ne correspond pas à celui qui est nécessaire 
                        //pour voir la page
                        $contenu = 'actionAccueil';  // Je le redirige vers l’accueil, car il n’a pas le bon rôle
                    } else {
                        $contenu = $explose[0];  // Je récupère le nom du contrôleur, car il a le bon rôle
                    }
                } else {
                    $contenu = 'actionAccueil';
                }
            } else {
                $contenu = 'actionAccueil';  // Page d’accueil, car il n’est pas authentifié
            }
        } else {
            $contenu = $explose[0]; //  Je récupère le contrôleur, car il n’a pas besoin d’avoir un rôle
        }
    } else {
        $contenu = $lesPages['maintenance'];
    }
    // La fonction envoie le contenu
    return $contenu;
}

?>