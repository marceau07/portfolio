<?php

function actionRole($twig, $db) {
    $form = array();
    $role = new Role($db);
    
    if (isset($_POST['btAjouter'])) {
        $libelle = $_POST['inputLibelle'];
        $exec = $role->insert($libelle);
    }
    
    $liste = $role->select();
    echo $twig->render('role.html.twig', array('form'=> $form,'liste'=>$liste));
}

function actionModifRole($twig, $db) {
    $form = array();
    
    if (isset($_GET['id'])) {
        $role = new Role($db);
        $unRole = $role->selectById($_GET['id']);
        
        if ($unRole != null) {
            $form['role'] = $unRole;
        } 
        
        else {
            $form['message'] = 'Role incorrect';
        }
    }

    else {
        if (isset($_POST['btModifier'])) {
            $role = new Role($db);
            $id = $_POST['id'];
            $libelle = $_POST['libelle'];
            $exec = $role->update($id, $libelle);
               
        }
    }
    
    echo $twig->render('role-modif.html.twig', array('form' => $form));
}

?>
