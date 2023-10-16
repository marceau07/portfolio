<?php
/* Actions sur l'utilisateur */

function actionUtilisateursA($twig, $db){
    $form = array();
    
    $utilisateur = new Utilisateur($db);
    $liste = $utilisateur->select();
    
    echo $twig->render('utilisateursA.html.twig', array('form'=>$form, 'liste'=>$liste));
}

function actionModifUtilisateursA($twig, $db){
    $form = array();
    
    if(isset($_GET['email'])) {
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->selectByEmail($_GET['email']);
        if ($unUtilisateur!=null){
            $form['utilisateur'] = $unUtilisateur;
            $role = new Role($db);
            $liste = $role->select();
            $form['roles']=$liste;
        }
        else{
            $form['message'] = 'Utilisateur incorrect';
        }
    }    
    else{
        if(isset($_POST['btModifierUtilisateur'])) {
            $utilisateur = new Utilisateur($db);
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $pseudo = $_POST['pseudo'];
            $telephone = $_POST['telephone'];
            $inputPassword = $_POST['inputPassword'];
            $inputPassword2 = $_POST['inputPassword2'];
            $role = $_POST['role'];
           
            if (empty($inputPassword) == true) {
                $form['message'] = 'Mot de passe non précisé';
            }
            
            if ($inputPassword!=$inputPassword2) {
                $form['valide'] = false;  
                $form['message'] = 'Les mots de passe sont différents';
            }
            
            else {
                $utilisateur = new Utilisateur($db); 
                $exec = $utilisateur->update($nom, $prenom, $pseudo, $telephone, password_hash($inputPassword, PASSWORD_DEFAULT), $role);
                if (!$exec){
                    $form['valide'] = false;  
                    $form['message'] = 'Problème d\'insertion dans la table utilisateur';
                }
                
                else {
                    $form['valide'] = true;  
                    $form['message'] = 'Modification réussie';                  
                }
            }    
        
        }
    else {
        $form['message'] = 'Utilisateur non précisé';
    }
    }
    echo $twig->render('utilisateur-modif.html.twig', array('form'=>$form));
}


/*
 * *******************************
 */

/* Actions sur les livre */


/*
 * *******************************
 */