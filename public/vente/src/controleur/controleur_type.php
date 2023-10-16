<?php

function actionType($twig, $db) {
    $form = array(); 
    $type = new Type($db);
   
    if (isset($_POST['btAjouter'])) {
        $inputLibelle = $_POST['inputLibelle']; 
        $exec = $type->insert($inputLibelle);
    }
   
    $liste = $type->select();
    echo $twig->render('type.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionModifType($twig, $db) {
    $form = array();
    
    if (isset($_GET['id'])) {
        $type = new Type($db);
        $unType = $type->selectById($_GET['id']);

            if ($unType!=null) {
                $form['type'] = $unType;
            }
            
            else {
                $form['message'] = 'Type incorrect';
            }
    }
    
    else {
        if(isset($_POST['btModifier'])) {
            $type = new Type($db);
            $libelle = $_POST['libelle'];
            $id = $_POST['id'];
            $exec = $type->update($libelle,$id);
        }
    }
    
   echo $twig->render('type-modif.html.twig', array('form'=>$form));
                                      
}
                              
?>