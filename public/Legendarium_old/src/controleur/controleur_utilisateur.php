<?php

function actionModifUtilisateur($twig, $db) {
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
        if(isset($_POST['btModifier'])) {
            $utilisateur = new Utilisateur($db);
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $role = $_POST['role'];
            $email = $_POST['email'];
            $mdp = $_POST['mdp'];
            $mdp2 = $_POST['mdp2'];
          //  if (empty($inputPassword) == true) {
          //      $form['message'] = 'Mot de passe non précisé';
          //  }
            if ($mdp!=$mdp2) {
                $form['valide'] = false;  
                $form['message'] = 'Les mots de passe sont différents';
            }
            else {
                $utilisateur = new Utilisateur($db); 
                $exec = $utilisateur->update($email, 
                        password_hash($mdp, PASSWORD_DEFAULT), $nom, $prenom, $role);
                if(!$exec){
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

function actionCalendar($twig){
    $form = array();
    
    echo $twig->render('calendar.html.twig', array('form'=>$form));
}