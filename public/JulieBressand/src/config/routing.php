<?php

function getPage($db) {

//Pages de base
    $lesPages['accueil']        = "actionAccueil;0";
    $lesPages['connexion']      = "actionConnexion;0";
    $lesPages['deconnexion']    = "actionDeconnexion;0";
    $lesPages['inscription']    = "actionInscription;0";
    $lesPages['maintenance']    = "actionMaintenance;0";
    $lesPages['actualites']     = "actionActualite;0";
    $lesPages['cookies']        = "actionCookies;0";
    $lesPages['cgu']            = "actionCGU;0";
    $lesPages['calendar']       = "actionCalendar;0";

//Pages erreurs
    $lesPages['error400'] = "actionErreur400;0";
    $lesPages['error401'] = "actionErreur401;0";
    $lesPages['error402'] = "actionErreur402;0";
    $lesPages['error403'] = "actionErreur403;0";
    $lesPages['error404'] = "actionErreur404;0";
    $lesPages['error405'] = "actionErreur405;0";
    $lesPages['error406'] = "actionErreur406;0";
    $lesPages['error407'] = "actionErreur407;0";
    $lesPages['error408'] = "actionErreur408;0";
    $lesPages['error409'] = "actionErreur409;0";
    $lesPages['error410'] = "actionErreur410;0";
    $lesPages['error411'] = "actionErreur411;0";
    $lesPages['error412'] = "actionErreur412;0";
    $lesPages['error413'] = "actionErreur413;0";
    $lesPages['error414'] = "actionErreur414;0";
    $lesPages['error415'] = "actionErreur415;0";
    $lesPages['error416'] = "actionErreur416;0";
    $lesPages['error417'] = "actionErreur417;0";
    $lesPages['error418'] = "actionErreur418;0";
    $lesPages['error421'] = "actionErreur421;0";
    $lesPages['error422'] = "actionErreur422;0";
    $lesPages['error423'] = "actionErreur423;0";
    $lesPages['error424'] = "actionErreur424;0";
    $lesPages['error425'] = "actionErreur425;0";
    $lesPages['error426'] = "actionErreur426;0";
    $lesPages['error428'] = "actionErreur428;0";
    $lesPages['error429'] = "actionErreur429;0";
    $lesPages['error431'] = "actionErreur431;0";
    $lesPages['error449'] = "actionErreur449;0";
    $lesPages['error450'] = "actionErreur450;0";
    $lesPages['error451'] = "actionErreur451;0";
    $lesPages['error456'] = "actionErreur456;0";
    $lesPages['error444'] = "actionErreur444;0";
    $lesPages['error495'] = "actionErreur495;0";
    $lesPages['error496'] = "actionErreur496;0";
    $lesPages['error497'] = "actionErreur497;0";
    $lesPages['error498'] = "actionErreur498;0";
    $lesPages['error499'] = "actionErreur499;0";

//Pages MEMBRES
    $lesPages['espacemembres']  = "actionEspaceMembres;0";
    $lesPages['priserdv']       = "actionPriseRdv;0";
    $lesPages['gestioncompte']  = "actionGestionCompte;0";
    $lesPages['faq']            = "actionFaq;0";
    $lesPages['mdpOublie']      = "actionMdpOublie;0";
    $lesPages['mesrdv']         = "actionMesRendezvous;0";
    $lesPages['ajouterAvis']    = "actionAjouterAvis;0";
    $lesPages['gestionAvis']    = "actionGestionAvis;0";
    $lesPages['mesdonnees']     = "actionRecupererInfo;0";
// Pages Admin
    $lesPages['envoimail']          = "actionMail;2";
    $lesPages['gestionActualite']   = "actionGestionActualite;2";
    $lesPages['gestionrdv']         = "actionGestionrdv;2";
    $lesPages['panel']              = "actionPanel;2";
    $lesPages['Ajoutactualite']     = "actionAjoutActualite;2";
    
    if ($db != null) {
        if (isset($_GET['page'])) {
            // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
            $page = $_GET['page'];
        } else {
            // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page
            //par défaut
            $page = 'accueil';
        }
        if (!isset($lesPages[$page])) {
            // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'accueil';
        }
        $explose = explode(";", $lesPages[$page]); // Nous découpons la ligne du tableau sur le
        //caractère « ; » Le résultat est stocké dans le tableau $explose
        $role = $explose[1]; // Le rôle est dans la 2ème partie du tableau $explose
        if ($role != 0) { // Si mon rôle nécessite une vérification
            if (isset($_SESSION['login'])) { // Si je me suis authentifié
                if (isset($_SESSION['role'])) { // Si j’ai bien un rôle
                    if ($role != $_SESSION['role']) { // Si mon rôle ne correspond pas à celui qui est nécessaire
                        //pour voir la page
                        $contenu = 'actionAccueil'; // Je le redirige vers l’accueil, car il n’a pas le bon rôle
                    } else {
                        $contenu = $explose[0]; // Je récupère le nom du contrôleur, car il a le bon rôle
                    }
                } else {
                    $contenu = 'actionAccueil';
                }
            } else {
                $contenu = 'actionAccueil'; // Page d’accueil, car il n’est pas authentifié
            }
        } else {
            $contenu = $explose[0]; // Je récupère le contrôleur, car il n’a pas besoin d’avoir un rôle
        }
    } else {
        // Si $db est null
        $contenu = 'actionMaintenance';
    }
    // La fonction envoie le contenu
    return $contenu;
}
?>