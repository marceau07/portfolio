<?php


function actionSkillWS($twig, $db) {
    
    $idSkill = $_GET['idSkill'];
    $nameSkill = $_GET['nameSkill'];
 $descSkill = $_GET['descSkill'];
    $versionSkill = $_GET['versionSkill']; 
    $skill = new Skill($db);

    if ($_GET['action'] == 'edit') {
        $exec = $skill->update($idSkill, $nameSkill, $descSkill, $versionSkill );
    } else if ($_GET['action'] == 'delete') {
        $exec = $skill->delete($idSkill, $nameSkill, $descSkill, $versionSkill);
    }
}

function actionModuleWS($twig, $db) {
    
    $idModule = $_GET['idModule'];
    $labelModule = $_GET['labelModule'];
 $descModule = $_GET['descModule']; 
    $module = new Module($db);

    if ($_GET['action'] == 'edit') {
        $exec = $module->update($idModule, $labelModule, $descModule);
    } else if ($_GET['action'] == 'delete') {
        $exec = $module->delete($idModule, $labelModule, $descModule);
    }
}