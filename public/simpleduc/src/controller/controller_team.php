<?php

/*
 * Created by Ludivine : Ok
 */

function actionTeamList($twig, $db) {
    $form = array();
    $team = new Team($db);

    if (isset($_GET['idTeam'])) {
        $exec = $team->delete($_GET['idTeam']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table équipe';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Equipe supprimée avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idTeam) {
            $exec = $team->delete($idTeam);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table équipe';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Equipe a été supprimée avec succès.';
            }
        }
    }
    $listeT = $team->select();
    echo $twig->render('team_list.html.twig', array('form' => $form, 'listeT' => $listeT));
}

/*
 * Created by Ludivine : Not ok
 */

function actionTeamAdd($twig, $db) {
    $form = array();
    $team = new Team($db);
    $employee = new Employee($db);

    if (isset($_POST['btAdd'])) {
        $nameTeam = $_POST['nameTeam'];
        $idEmployee = $_POST['idEmployee'];

        $exec = $team->insert($nameTeam);
        $exec2 = $team->insertEmployeeTeam($idEmployee);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Equipe';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Equipe a bien été ajoutée !';
        }
    }

    $listeT = $team->select();
    $listeE = $employee->select();
    echo $twig->render('team_add.html.twig', array('form' => $form, 'listeT' => $listeT, 'listeE' => $listeE));
}

/*
 * Created by Ludivine : Ok
 */

function actionTeamModify($twig, $db) {
    $form = array();

    if (isset($_GET['idTeam'])) {
        $team = new Team($db);
        $employee = new Employee($db);
        $employeeTeam = new EmployeeTeam($db);
        $employeeTeam = $employeeTeam->select();
        $aTeam = $team->selectById($_GET['idTeam']);

        if ($aTeam != null) {
            $form['team'] = $aTeam;
        } else {
            $form['message'] = 'Equipe incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $team = new Team($db);
            $employee = new Employee($db);
            $idTeam = $_POST['idTeam'];
            $nameTeam = $_POST['nameTeam'];
            $idEmployee = $_POST['idEmployee'];

            $exec = $team->update($idTeam, $nameTeam);
            $execEmployeeTeam = $team->updateEmployeeTeam($idTeam, $idEmployee);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table activite';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Equipe non précisée';
        }
    }
    $listeT = $team->select();
    $listeE = $employee->select();
    
    var_dump($employeeTeam);
    echo $twig->render('team_modify.html.twig', array('form' => $form, 'listeT' => $listeT, 'listeE' => $listeE));
}
