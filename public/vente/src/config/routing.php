<?php

function getPage($db) {

    $lesPages['accueil'] = "actionAccueil;0";
    $lesPages['inscrire'] = "actionInscrire;0";
    $lesPages['mentions'] = "actionMentions;0";
    $lesPages['connexion'] = "actionConnexion;0";
    $lesPages['deconnexion'] = "actionDeconnexion;0";
    $lesPages['apropos'] = "actionApropos;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
    $lesPages['utilisateur'] = "actionUtilisateur;1";
    $lesPages['type'] = "actionType;1";
    $lesPages['produit'] = "actionProduit;1";
    $lesPages['modifutilisateur'] = "actionModifUtilisateur;1";
    $lesPages['modiftype'] = "actionModifType;1";
    $lesPages['modifproduit'] = "actionModifProduit;1";
    $lesPages['role'] = "actionRole;1";
    $lesPages['modifrole'] = "actionModifRole;1";
    $lesPages['listeproduitpdf'] = "actionListeProduitPdf;1";
    $lesPages['produitws'] = "actionProduitWS;0";
    
    

    if ($db!=null) {
        if(isset($_GET['page'])) {
            $page = $_GET['page']; // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
        } 
    else { // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page par défaut
        $page = 'accueil';
    }
    if (!isset($lesPages[$page])) { // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
        $page = 'accueil';
    }

    $explose = explode( ";",$lesPages[$page]); // Nous découpons la ligne du tableau sur le caractère « ; » Le résultat est stocké dans le tableau $explose
    $role = $explose[1] ; // Le rôle est dans la 2ème partie du tableau $explose

    if ($role != 0) { // Si mon rôle nécessite une vérification
        if(isset($_SESSION['login']) ){ // Si je me suis authentifié
            if(isset($_SESSION['role'])) { // Si j’ai bien un rôle
                if($role!=$_SESSION['role']) { // Si mon rôle ne correspond pas à celui qui est nécessaire pour voir la page
                    $contenu = 'actionAccueil'; // Je le redirige vers l’accueil, car il n’a pas le bon rôle
                }
                else {
                    $contenu = $explose[0]; // Je récupère le nom du contrôleur, car il a le bon rôle
                }
            }
            else {
                $contenu = 'actionAccueil';
            }
        }
        else{
            $contenu = 'actionAccueil'; // Page d’accueil, car il n’est pas authentifié
        }
    }
    else {
        $contenu = $explose[0]; // Je récupère le contrôleur, car il n’a pas besoin d’avoir un rôle
    }
    }
    else{ // Si $db est null
        $contenu = 'actionMaintenance';
    }

return $contenu;
}

?>
