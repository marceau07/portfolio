<?php

/*
 * Created by Gromain
 */

function actionTaskList($twig, $db) {
    $form = array();
    $task = new Task($db);

    if (isset($_GET['idTask'])) {
        $exec = $task->delete($_GET['idTask']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table task';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Tâche supprimée avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idTask) {
            $exec = $task->delete($idTask);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table task';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Tâche a été supprimée avec succès.';
            }
        }
    }
    $listeTK = $task->select();
    echo $twig->render('task_list.html.twig', array('form' => $form, 'listeTK' => $listeTK));
}

/*
 * Created by Gromain
 */

function actionTaskAdd($twig, $db) {
    $form = array();
    $task = new Task($db);
    $team = new Team($db);

    if (isset($_POST['btAdd'])) {

        $labelTask = $_POST['labelTask'];
        $descTask = $_POST['descTask'];
        $idTeamTask = $_POST['idTeamTask'];
        $costTask = $_POST['costTask'];

        $exec = $task->insert($labelTask, $descTask, $idTeamTask, $costTask);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Task';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Tâche a bien été ajoutée !';
        }
    }



    $listeTK = $task->select();
    $listeT = $team->select();

    echo $twig->render('task_add.html.twig', array('form' => $form, 'listeTK' => $listeTK, 'listeT' => $listeT));
}

/*
 * Created by Gromain
 */

function actionTaskModify($twig, $db) {
    $form = array();
            $task = new Task($db);

        $listeTK = $task->select();

    if (isset($_GET['idTask'])) {
        $aTask = $task->selectById($_GET['idTask']);

        if ($aTask != null) {
            $form['task'] = $aTask;
        } else {
            $form['message'] = 'Tâche incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $task = new Task($db);
            $idTask = $_POST['idTask'];
            $labelTask = $_POST['labelTask'];
            $descTask = $_POST['descTask'];
            $idTeamTask = $_POST['idTeamTask'];
            $costTask = $_POST['costTask'];

            $exec = $task->update($idTask, $labelTask, $descTask, $idTeamTask, $costTask);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table task';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Tâche non précisée';
        }
    }

    echo $twig->render('task_modify.html.twig', array('form' => $form, 'listeTK' => $listeTK));
}
