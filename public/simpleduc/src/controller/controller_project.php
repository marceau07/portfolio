<?php

/*
 * Created by Gromain
 */

function actionProjectList($twig, $db) {
    $form = array();
    $project = new Project($db);

    if (isset($_GET['idProject'])) {
        $exec = $project->delete($_GET['idProject']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table project';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Projet supprimé avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        var_dump($_POST);
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idProject) {
            $exec = $project->delete($idProject);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table projet';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Projet a été supprimé avec succès.';
            }
        }
    }

    $listeP = $project->select();
    echo $twig->render('project_list.html.twig', array('form' => $form, 'listeP' => $listeP));
}

/*
 * Created by Gromain
 */

function actionProjectAdd($twig, $db) {
    $form = array();
    $project = new Project($db);
    $contrat = new Contract($db);
    $module = new Module($db);

    if (isset($_POST['btAdd'])) {
        $nameProject = htmlspecialchars($_POST['nameProject']);
        $descProject = htmlspecialchars($_POST['descProject']);
        $idContractProject = htmlspecialchars($_POST['idContractProject']);
        $idModuleProject = htmlspecialchars($_POST['idModuleProject']);

        $exec = $project->insert($nameProject, $descProject, $idContractProject, $idModuleProject);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table project';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }
    

    $listeP = $project->select();
    $listeCT = $contrat->select();
    $listeM = $module->select();
    echo $twig->render('project_add.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeCT' => $listeCT, 'listeM' => $listeM));
}

/*
 * Created by Gromain
 */

function actionProjectModify($twig, $db) {
    $form = array();

    if (isset($_GET['idProject'])) {
        $project = new Project($db);
        $contrat = new Contract($db);
        $module = new Module($db);

        $listeM = $module->select();
        $listeC = $contrat->select();
        
        $aProject = $project->selectById($_GET['idProject']);

        if ($aProject != null) {
            $form['project'] = $aProject;
        } else {
            $form['message'] = 'Projet incorrect';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $project = new Project($db);
            $idProject = $_POST['idProject'];
            $nameProject = htmlspecialchars($_POST['nameProject']);
            $descProject = htmlspecialchars($_POST['descProject']);
            $idContractProject = htmlspecialchars($_POST['idContractProject']);
            $idModuleProject = htmlspecialchars($_POST['idModuleProject']);

            $exec = $project->update($idProject, $nameProject, $descProject, $idContractProject, $idModuleProject);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table project';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Projet non précisé';
        }
    }

    echo $twig->render('project_modify.html.twig', array('form' => $form, 'listeC' => $listeC, 'listeM' => $listeM));
}
