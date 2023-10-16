<?php

function getPage($db) {

    //Pages de base
    $lesPages['index'] = "actionIndex;0";
    $lesPages['mentions'] = "actionMentions;0";
    $lesPages['cgu'] = "actionCgu;0";
    $lesPages['faq'] = "actionFaq;0";
    $lesPages['contact'] = "actionContact;0";
    
    // Pages d'erreur
    $lesPages['error404'] = "actionError404;0";
    
    // Pages utilisateurs
    $lesPages['signin'] = "actionSignIn;0";
    $lesPages['login'] = "actionLogIn;0";
    $lesPages['logout'] = "actionLogOut;0";
    $lesPages['deleteaccount'] = "actionDeleteAccount;0";
    $lesPages['myaccount'] = "actionMyAccount;0";
    $lesPages['article'] = "actionArticle;0";
    $lesPages['fullarticle'] = "actionFullArticle;0";
    $lesPages['fullarticlepdf'] = "actionFullArticleToPdf;0";
    $lesPages['resultsearch'] = "actionResultSearch;0";
    $lesPages['newsletter'] = "actionNewsletter;0";
    $lesPages['articlecategory'] = "actionArticleCategory;0";
    
    // Page d'administration
    $lesPages['panel'] = "actionPanel;2";
    $lesPages['addarticle'] = "actionAddArticle;2";
    $lesPages['addcategory'] = "actionAddCategory;2";
    $lesPages['addfaq'] = "actionAddFaq;2";
    $lesPages['updatearticle'] = "actionUpdateArticle;2";
    $lesPages['deletearticle'] = "actionDeleteArticle;2";
    $lesPages['updatevisibilityarticle'] = "actionUpdateVisibilityArticle;2";
    $lesPages['getdaynews'] = "actionGetLastNewsCovid;2";
    
    $lesPages['getdaynewsauto'] = "actionGetLastNewsCovid;0";
    
    if ($db != null) {
        if (isset($_GET['page'])) {
            // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
            $page = $_GET['page'];
        } else {
            // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page
            //par défaut
            $page = 'index';
        }

        if (!isset($lesPages[$page])) {
            // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'index';
        }

        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];

        // Si le rôle nécessite de contrôler les droits
        if ($role != 0) {
            // Nous vérifions que la personne est connectée
            if (isset($_SESSION['nicknameUser'])) {
                //Nous vérifions qu'elle a un rôle
                if (isset($_SESSION['idRoleUser'])) {

                    if ($role != $_SESSION['idRoleUser']) {
                        //Nous redigeons la personne vers la page d'acccueil car elle n'a pas le bon rôle 
                        $contenu = 'actionIndex';
                    } else {
                        // La personne est autorisée à récupérer  
                        $contenu = $explose[0];
                    }
                } else {
                    // Dans la session le rôle n'existe pas donc on va sur la page d'accueil 
                    $contenu = 'actionIndex';
                }
            } else {
                // La personne n'est pas connectée, donc on va sur la page d'accueil  
                $contenu = 'actionIndex';
            }
        } else {
            // Nous donnons du contenu non protégé  
            $contenu = $explose[0];
        }
    } else {
        // La base de données n'est pas accessible
        $contenu = 'actionMaintenance';
    }
// La fonction envoie le contenu
    return $contenu;
}

?>