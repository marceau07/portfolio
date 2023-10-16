<?php

function getPage($db) {
    $lesPages['webIndex'] = "webIndexAction;0";
    $lesPages['webLogin'] = "webLoginAction;0";

    $lesPages['indexFiltersAndroid'] = "androidFiltersIndex;0";
    $lesPages['indexClustersAndroid'] = "androidIndexClustersAction;0";
    $lesPages['indexBooksAndroid'] = "androidIndexBooksAction;0";
    $lesPages['loginAndroid'] = "androidLoginAction;0";
    $lesPages['signinAndroid'] = "androidSigninAction;0";
    $lesPages['viewBookAndroid'] = "androidViewBookAction;0";
    $lesPages['viewAuthorsAndroid'] = "androidViewAuthorsAction;0";
    $lesPages['viewPublishersAndroid'] = "androidViewPublishersAction;0";
    $lesPages['viewAvailabilitiesAndroid'] = "androidViewAvailabilitiesAction;0";
    $lesPages['viewTypesBookAndroid'] = "androidViewTypesBookAction;0";
    $lesPages['updateBookAndroid'] = "androidUpdateBookAction;0";
    $lesPages['updateCoverBookWeb'] = "webUpdateCoverBookAction;0";
    
    if ($db != null) {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'webIndex';
        }
        if (!isset($lesPages[$page])) {
            $page = 'webIndex';
        }
        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];
        if ($role != 0) {
            if (isset($_SESSION['email'])) {  // Si je me suis authentifié
                if (isset($_SESSION['role'])) {  // Si j’ai bien un rôle  
                    if ($role != $_SESSION['role']) { // Si mon rôle ne correspond pas à celui qui est nécessaire 
                        //pour voir la page
                        $contenu = 'webIndexAction';  // Je le redirige vers l’accueil, car il n’a pas le bon rôle
                    } else {
                        $contenu = $explose[0];  // Je récupère le nom du contrôleur, car il a le bon rôle
                    }
                } else {
                    $contenu = 'webIndexAction';
                }
            } else {
                $contenu = 'webIndexAction';  // Page d’accueil, car il n’est pas authentifié
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