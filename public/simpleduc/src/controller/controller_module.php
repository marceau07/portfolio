<?php

/*
 * Created by Gromain
 */

function actionModuleList($twig, $db) {
    $form = array();
    $module = new Module($db);
    
        if (isset($_GET['idModule'])) {
        $exec = $module->delete($_GET['idModule']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table module';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Module supprimé avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $module) {
            $exec = $module->delete($idModule);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table module';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Module a été supprimé avec succès.';
            }
        }
    }
    $listeM = $module->select();
    echo $twig->render('module_list.html.twig', array('form' => $form, 'listeM' => $listeM));
}

/*
 * Created by Gromain
 */

function actionModuleAdd($twig, $db) {
    $form = array();
    $module = new Module($db);    

    if (isset($_POST['btAdd'])) {
       
        $labelModule = $_POST['labelModule'];
        $descModule = $_POST['descModule'];

        $exec = $module->insert($labelModule, $descModule);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Module';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Module a bien été ajoutée !';
        }
    }



    $listeM = $module->select();
    echo $twig->render('module_add.html.twig', array('form' => $form, 'listeM' => $listeM));
}

/*
 * Created by Gromain
 */

function actionModuleModify($twig, $db) {
    $form = array();

    if (isset($_GET['idModule'])) {
        $module = new Module($db);
        $aModule = $module->selectById($_GET['idModule']);

        if ($aModule != null) {
            $form['module'] = $aModule;
        } else {
            $form['message'] = 'Module incorrecte';
        }
    } else {        
        if (isset($_POST['btModify'])) {
            var_dump( $_POST);
            $module = new Module($db);
            $idModule = $_POST['idModule'];
            $labelModule = $_POST['labelModule'];
            $descModule = $_POST['descModule'];
            $exec = $module->update($idModule,$labelModule, $descModule);
                

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table Module';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Module non précisée';
        }
    }
    echo $twig->render('module_modify.html.twig', array('form' => $form));
}
