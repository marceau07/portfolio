<?php

function getPage($db) {
    /* PAGES INDEX */
    $lesPages['accueil'] = "actionAccueil;0";
    $lesPages['googleAuth'] = "actionGoogleAuth;0";
    $lesPages['connexion'] = "actionConnexion;0";
    $lesPages['deconnexion'] = "actionDeconnexion;0";
    $lesPages['inscription'] = "actionInscription;0";
    $lesPages['maintenance'] = "actionMaintenance;0";

    /* PAGES Ajout */
    $lesPages['ajouterScript'] = "actionAjouterScript;0";
    $lesPages['ajouterOrdinateur'] = "actionAjouterOrdinateur;0";
    $lesPages['ajouterOs'] = "actionAjouterOs;0";
    $lesPages['ajouterUtilisateur'] = "actionAjouterUtilisateur;0";
    $lesPages['ajouterFonction'] = "actionAjouterFonction;0";

    /* PAGES DE BASE */
    $lesPages['script'] = "actionScript;0";
    $lesPages['ordinateur'] = "actionOrdinateur;0";
    $lesPages['utilisateur'] = "actionUtilisateur;0";
    $lesPages['employe'] = "actionEmploye;0";
    $lesPages['os'] = "actionOs;0";
    $lesPages['modal'] = "actionModalIndex;0";
    $lesPages['installationScript'] = "actionInstallationScript;0";
    $lesPages['monCompte'] = "actionMonCompte;0";
    $lesPages['supprimer'] = "actionSupprimerCompte;0";


    /* PAGES PDF */
    $lesPages['listeOrdinateurPdf'] = "actionListeOrdinateurPdf;0";

    /* PAGES WS */
    $lesPages['employeWS'] = "actionEmployeWS;0";
    $lesPages['scriptWS'] = "actionScriptWS;0";
    $lesPages['parcWS'] = "actionParcWS;0";
    $lesPages['canvas'] = "actionCanvas;0";
    $lesPages['ssh'] = "actionSsh;0";
    $lesPages['cmd'] = "actionCmd;0";
    $lesPages['sshTest'] = "adminSshAccess;0";

    /* PAGES Android */
    $lesPages['inscriptionAndroid'] = "actionInscriptionAndroid;0";
    $lesPages['connexionAndroid'] = "actionConnexionAndroid;0";
    $lesPages['employeAndroid'] = "actionEmployeAndroid;0";
    
    /* PAGE Android w/ Flutter */
    $lesPages['scriptsJSON'] = "actionScriptsJson;0";
    $lesPages['OSJSON'] = "actionOSJson;0";
    $lesPages['AddOSJSON'] = "actionAddOSJson;0";
    $lesPages['usersJSON'] = "actionUsersJson;0";
    $lesPages['functionsJSON'] = "actionFunctionsJson;0";
    $lesPages['employeesJSON'] = "actionEmployeesJson;0";
    $lesPages['computersJSON'] = "actionComputersJson;0";
    $lesPages['networksJSON'] = "actionNetworksJson;0";
    $lesPages['deleteAccount'] = "actionDeleteAccount;0";
    $lesPages['signIn'] = "actionSignIn;0";
    $lesPages['retrieveAccountData'] = "actionRetrieveAccountData;0";
    
    /* Pages de test */
    $lesPages['facebookAuth'] = "actionFacebookAuth;0";
    $lesPages['facebookAuthCallback'] = "actionFacebookAuthCallback;0";
    
    /* Récupération des PC */
    $lesPages['scan'] = "actionScan;0";


    if ($db != null) {
        if (isset($_GET['page'])) {
            $page = $_GET['page']; // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
        } else { // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page par défaut
            $page = 'accueil';
        }
        if (!isset($lesPages[$page])) { // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'accueil';
        }

        $explose = explode(";", $lesPages[$page]); // Nous découpons la ligne du tableau sur le caractère « ; » Le résultat est stocké dans le tableau $explose
        $role = $explose[1]; // Le rôle est dans la 2ème partie du tableau $explose

        if ($role != 0) { // Si mon rôle nécessite une vérification
            if (isset($_SESSION['login'])) { // Si je me suis authentifié
                if (isset($_SESSION['role'])) { // Si j’ai bien un rôle
                    if ($role != $_SESSION['role']) { // Si mon rôle ne correspond pas à celui qui est nécessaire pour voir la page
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
    } else { // Si $db est null
        $contenu = 'actionMaintenance';
    }

    return $contenu;
}

?>
