<?php

/*
 * Created by Ludivine
 */

function actionEmployeeList($twig, $db) {
    $form = array();
    $employee = new Employee($db);

    if (isset($_GET['idEmployee'])) {
        $exec = $employee->delete($_GET['idEmployee']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Employés';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Employé supprimé avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idEmployee) {
            $exec = $employee->delete($idEmployee);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Skill';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Compétence supprimée avec succès.';
            }
        }
    }
    $liste = $employee->select();

    echo $twig->render('employee_list.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Ludivine
 */

function actionEmployeeAdd($twig, $db) {
    $form = array();
    $employee = new Employee($db);
    $team = new Team($db);

    if (isset($_POST['btAdd'])) {

        $lastNameEmployee = $_POST['lastNameEmployee'];
        $firstNameEmployee = $_POST['firstNameEmployee'];
        $cityEmployee = $_POST['cityEmployee'];
        $levelEmployee = $_POST['levelEmployee'];
        $idTeamEmployee = $_POST['idTeamEmployee'];


        $exec = $employee->insert($lastNameEmployee, $firstNameEmployee, $cityEmployee, $levelEmployee, $idTeamEmployee);


        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Equipe';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Equipe a bien été ajoutée !';
        }
    }

    $liste = $employee->select();
    $listeT = $team->select();
    echo $twig->render('employee_add.html.twig', array('form' => $form, 'liste' => $liste, 'listeT' => $listeT));
}

/*
 * Created by Ludivine
 */

function actionEmployeeModify($twig, $db) {
    $form = array();
    $employee = new Employee($db);
    $team = new Team($db);
    $listeE = $employee->select();
    $listeT = $team->select2();
    if (isset($_GET['idEmployee'])) {
        $form = array();

        $anEmployee = $employee->selectById($_GET['idEmployee']);
        if ($anEmployee != null) {
            $form['employee'] = $anEmployee;
        } else {
            $form['message'] = 'Employé incorrect';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $employee = new Employee($db);

            $idEmployee = $_POST['idEmployee'];
            $lastNameEmployee = $_POST['lastNameEmployee'];
            $firstNameEmployee = $_POST['firstNameEmployee'];
            $cityEmployee = $_POST['cityEmployee'];
            $levelEmployee = $_POST['levelEmployee'];
            $idTeamEmployee = $_POST['idTeamEmployee'];


            $exec = $employee->update($idEmployee, $lastNameEmployee, $firstNameEmployee, $cityEmployee, $levelEmployee, $idTeamEmployee);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table employé';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Employé non précisée';
        }
    }

    echo $twig->render('employee_modify.html.twig', array('form' => $form, 'listeT' => $listeT, 'listeE' => $listeE));
}

/*
 * Created by Marceau 
 */

function actionTaskEmployeeList($twig, $db) {
    $form = array();
    $taskEmployee = new TaskEmployee($db);

    if (isset($_GET['id'])) {
        $taskEmployee = new TaskEmployee($db);
        $aSkill = $taskEmployee->select($_GET['id']);

        if ($aSkill != null) {
            $form['skill'] = $aSkill;
        } else {
            $form['message'] = 'Compétence incorrecte';
        }
    }

    if (isset($_GET['idSkill'])) {
        $exec = $skill->delete($_GET['idSkill']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Skill';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Compétence supprimée avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idSkill) {
            $exec = $skill->delete($idSkill);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Skill';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Compétence supprimée avec succès.';
            }
        }
    }
    $liste = $skill->select();
    echo $twig->render('skill_list.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Marceau
 */

function actionTaskEmployeeAdd($twig, $db) {
    $form = array();
    $skill = new Skill($db);

    if (isset($_POST['btAdd'])) {
        $nameSkill = htmlspecialchars($_POST['nameSkill']);
        $descSkill = htmlspecialchars($_POST['descSkill']);
        $versionSkill = htmlspecialchars($_POST['versionSkill']);

        $exec = $skill->insert($nameSkill, $descSkill, $versionSkill);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Skill';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La compétence a bien été ajoutée !';
        }
    }



    $liste = $skill->select();
    echo $twig->render('skill_add.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Marceau
 */

function actionTaskEmployeeModify($twig, $db) {
    $form = array();

    if (isset($_GET['idSkill'])) {
        $skill = new Skill($db);
        $aSkill = $skill->selectById($_GET['idSkill']);

        if ($aSkill != null) {
            $form['skill'] = $aSkill;
        } else {
            $form['message'] = 'Compétence incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $skill = new Skill($db);
            $nameSkill = htmlspecialchars($_POST['nameSkill']);
            $descSkill = htmlspecialchars($_POST['descSkill']);
            $versionSkill = htmlspecialchars($_POST['versionSkill']);

            $exec = $skill->update($idSkill, $nameSkill, $descSkill, $versionSkill);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table Skill';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Compétence non précisée';
        }
    }

    echo $twig->render('skill_modify.html.twig', array('form' => $form));
}

/*
 * Created by Marceau : Ok
 */

function actionAssignATeamPage($twig, $db) {
    $form = array();

    $team = new Team($db);
    $employee = new Employee($db);

    $listeT = $team->select();
    $listeE = $employee->selectTeamNull();
    $listeEGotATeam = $employee->selectTeamNotNull();

    echo $twig->render('assign_a_team.html.twig', array('form' => $form, 'listeT' => $listeT, 'listeE' => $listeE, 'listeEGotATeam' => $listeEGotATeam));
}

/*
 * Created by Marceau : Ok
 */

function actionAssignATeam($twig, $db) {
    $employee = new Employee($db);

    if (!empty($_POST['idTeam']) && !empty($_POST['idEmployee'])) {
        $idTeamEmployee = $_POST['idTeam'];
        $idEmployee = $_POST['idEmployee'];
        if ($idTeamEmployee == "NULL") {
            $exec = $employee->updateTeam(null, $idEmployee);
        } else {
            $exec = $employee->updateTeam($idTeamEmployee, $idEmployee);
        }
        if ($exec) {
            echo "OK";
        } else {
            echo '!OK';
        }
    }
}

/*
 * Created by Marceau : Ok
 */

function actionAssignATaskPage($twig, $db) {
    $form = array();

    $task = new Task($db);
    $module = new Module($db);

    $listeTKWithoutM = $task->select();
    $listeM = $module->select();

    echo $twig->render('assign_a_task.html.twig', array('form' => $form, 'listeTK' => $listeTKWithoutM, 'listeM' => $listeM));
}

/*
 * Created by Marceau : Ok
 */

function actionAssignATask($twig, $db) {
    
}

/*
 * Created by Marceau : Ok
 */

function actionListEmployee($twig, $db) {
    $employee = new Employee($db);
    $liste = $unProduit = $employee->select();
    $html = $twig->render('employee_list_pdf.html.twig', array('liste' => $liste));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des employes.pdf');
    } catch (Html2PdfException $e) {
        echo 'erreur ' . $e;
    }
}
