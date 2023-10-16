<?php

/**
 * Created by Marceau
 */
function actionUserPDFWS($twig, $db) {
    $user = new User($db);
    $list = $anUser = $user->select();
    $html = $twig->render('user_list_pdf.html.twig', array('listeU' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des utilisateurs.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionTeamPDFWS($twig, $db) {
    $team = new Team($db);
    $list = $aTeam = $team->select();
    $html = $twig->render('team_list_pdf.html.twig', array('listeT' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des équipes.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionContractPDFWS($twig, $db) {
    $contract = new Contract($db);
    $list = $aContract = $contract->select();
    $html = $twig->render('contract_list_pdf.html.twig', array('listeCT' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des contrats.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionEmployeePDFWS($twig, $db) {
    $employee = new Employee($db);
    $list = $anEmployee = $employee->select();
    $html = $twig->render('employee_list_pdf.html.twig', array('listeE' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des employés.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionProjectPDFWS($twig, $db) {
    $project = new Project($db);
    $list = $aProject = $project->select();
    $html = $twig->render('project_list_pdf.html.twig', array('listeP' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des projets.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionTaskPDFWS($twig, $db) {
    $task = new Task($db);
    $list = $aTask = $task->select();
    $html = $twig->render('task_list_pdf.html.twig', array('listeTK' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des tâches.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionSkillPDFWS($twig, $db) {
    $skill = new Skill($db);
    $list = $aSkill = $skill->select();
    $html = $twig->render('skill_list_pdf.html.twig', array('listeS' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des compétences.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionFirmPDFWS($twig, $db) {
    $firm = new Firm($db);
    $list = $aFirm = $firm->select();
    $html = $twig->render('firm_list_pdf.html.twig', array('listeF' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des entreprises.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionContactPDFWS($twig, $db) {
    $contact = new Contact($db);
    $list = $aContact = $contact->select();
    $html = $twig->render('contact_list_pdf.html.twig', array('listeC' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des contacts.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}

/**
 * Created by Marceau
 */
function actionModulePDFWS($twig, $db) {
    $module = new Module($db);
    $list = $aModule = $module->select();
    $html = $twig->render('module_list_pdf.html.twig', array('listeM' => $list));
    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('Liste des modules.pdf');
    } catch (Exception $ex) {
        echo 'erreur '.$ex;
    }
}