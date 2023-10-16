<?php

/*
 * Created by Ludivine : Ok
 */

function actionSkillList($twig, $db) {
    $form = array();
    $skill = new Skill($db);
    
    
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
 * Created by Ludivine : Ok
 */

function actionSkillAdd($twig, $db) {
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
 * Created by Ludivine : Ok
 */

function actionSkillModify($twig, $db) {
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
            $idSkill = $_POST['idSkill'];
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