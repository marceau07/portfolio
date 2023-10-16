<?php

/*
 * Created by Gromain
 */

function actionFirmList($twig, $db) {
    $form = array();
    $firm = new Firm($db);

    if (isset($_GET['idFirm'])) {
        $exec = $firm->delete($_GET['idFirm']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Firm';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Entreprise supprimée avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idFirm) {
            $exec = $firm->delete($idFirm);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Firm';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Entreprise supprimée avec succès.';
            }
        }
    }
    $listeF = $firm->select();
    echo $twig->render('firm_list.html.twig', array('form' => $form, 'listeF' => $listeF));
}

/*
 * Created by Gromain
 */

function actionFirmAdd($twig, $db) {
    $form = array();
    $firm = new Firm($db);
    $contact = new Contact($db);
    $listeC = $contact->select();
    $listeF = $firm->select();

    if (isset($_POST['btAdd'])) {
        $nameFirm = htmlspecialchars($_POST['inputName']);
        $cityFirm = htmlspecialchars($_POST['inputCity']);
        $zipCodeFirm = htmlspecialchars($_POST['inputZipCode']);
        $streetFirm = htmlspecialchars($_POST['inputStreet']);
        $telFirm = htmlspecialchars($_POST['inputTel']);
        $faxFirm = htmlspecialchars($_POST['inputFax']);
        $idContactFirm = htmlspecialchars($_POST['inputIdContact']);

        $exec = $firm->insert($nameFirm, $cityFirm, $zipCodeFirm, $streetFirm, $telFirm, $faxFirm, $idContactFirm);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Firm';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }



    echo $twig->render('firm_add.html.twig', array('form' => $form, 'listeC' => $listeC, 'listeF' => $listeF));
}

/*
 * Created by Gromain
 */

function actionFirmModify($twig, $db) {
    $form = array();

    if (isset($_GET['idFirm'])) {
        $form = array();
        $firm = new Firm($db);
        $contact = new Contact($db);
        $listeC = $contact->select();
        $listeF = $firm->select();
        $aFirm = $firm->selectById($_GET['idFirm']);

        if ($aFirm != null) {
            $form['firm'] = $aFirm;
        } else {
            $form['message'] = 'Entreprise incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $firm = new Firm($db);
            $idFirm = $_POST['idFirm'];
            $nameFirm = htmlspecialchars($_POST['inputName']);
            $cityFirm = htmlspecialchars($_POST['inputCity']);
            $zipCodeFirm = htmlspecialchars($_POST['inputZipCode']);
            $streetFirm = htmlspecialchars($_POST['inputStreet']);
            $telFirm = htmlspecialchars($_POST['inputTel']);
            $faxFirm = htmlspecialchars($_POST['inputFax']);
            $idContactFirm = htmlspecialchars($_POST['inputContact']);

            $exec = $firm->update($idFirm, $nameFirm, $cityFirm, $zipCodeFirm, $streetFirm, $telFirm, $faxFirm, $idContactFirm);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table Firm';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Entreprise non précisée';
        }
    }
    echo $twig->render('firm_modify.html.twig', array('form' => $form, 'listeC' => $listeC, 'listeF' => $listeF));
}
