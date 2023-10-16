<?php

/*
 * Created by Ludivine : Ok
 */

function actionContractList($twig, $db) {
    $form = array();
    $contract = new Contract($db);

    if (isset($_GET['idContract'])) {
        $exec = $contract->delete($_GET['idContract']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Contrat';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Contrat supprimé avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idContract) {
            $exec = $contract->delete($idContract);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Contrat';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Contrat a été supprimé avec succès.';
            }
        }
    }
    $liste = $contract->select();
    echo $twig->render('contract_list.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Ludivine : Ok
 */

function actionContractAdd($twig, $db) {
    $form = array();
    $contract = new Contract($db);

    if (isset($_POST['btAdd'])) {

        $labelContract = $_POST['labelContract'];
        $dateSignatureContract = $_POST['dateSignatureContract'];
        $dateBegProject = $_POST['dateBegProject'];
        $dateEndProject = $_POST['dateEndProject'];
        $costProject = $_POST['costProject'];


        $exec = $contract->insert($labelContract, $dateSignatureContract, $dateBegProject, $dateEndProject, $costProject);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Contrat';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Contrat a bien été ajouté !';
        }
    }

    $liste = $contract->select();
    echo $twig->render('contract_add.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Ludivine : Ok
 */

function actionContractModify($twig, $db) {
    $form = array();

    if (isset($_GET['idContract'])) {
        $contract = new Contract($db);
        $aContract = $contract->selectById($_GET['idContract']);

        if ($aContract != null) {
            $form['contract'] = $aContract;
        } else {
            $form['message'] = 'Contrat incorrect';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $contract = new Contract($db);

            $idContract = $_POST['idContract'];
            $labelContract = $_POST['labelContract'];
            $dateSignatureContract = $_POST['dateSignatureContract'];
            $dateBegProject = $_POST['dateBegProject'];
            $dateEndProject = $_POST['dateEndProject'];
            $costProject = $_POST['costProject'];

            $exec = $contract->update($idContract, $labelContract, $dateSignatureContract, $dateBegProject, $dateEndProject, $costProject);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table Contrat';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Contrat non précisé';
        }
    }

    echo $twig->render('contract_modify.html.twig', array('form' => $form));
}
