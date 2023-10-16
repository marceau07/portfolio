<?php

/*
 * Created by Gromain
 */

function actionContactList($twig, $db) {
    $form = array();
    $contact = new Contact($db);
    
     if (isset($_GET['idContact'])) {
        $exec = $contact->delete($_GET['idContact']);
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
        foreach ($cocher as $idContact) {
            $exec = $contact->delete($idContact);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table Skill';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Compétence supprimée avec succès.';
            }
        }
    }
    
    $listeC = $contact->select();
    echo $twig->render('contact_list.html.twig', array('form' => $form, 'listeC' => $listeC));
}

/*
 * Created by Gromain
 */

function actionContactAdd($twig, $db) {
    $form = array();
    $contact = new Contact($db);

    if (isset($_POST['btAdd'])) {
        $lastNameContact = htmlspecialchars($_POST['inputLastName']);
        $firstNameContact = htmlspecialchars($_POST['inputFirstName']);
        $telContact = htmlspecialchars($_POST['inputTel']);
        $mailContact = htmlspecialchars($_POST['inputMail']);

        $exec = $contact->insert($lastNameContact, $firstNameContact, $telContact, $mailContact);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table contact';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }
    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idFirm) {
            $exec = $contact->delete($idContact);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table contact';
            } else {
                $form['valide'] = true;
                $form['message'] = 'L\'équipe a été supprimée avec succès.';
            }
        }
    }

    $listeC = $contact->select();
    echo $twig->render('contact_add.html.twig', array('form' => $form, 'listeC' => $listeC));
}

/*
 * Created by Gromain
 */

function actionContactModify($twig, $db) {
    $form = array();

    if (isset($_GET['idContact'])) {
        $contact = new Contact($db);
        $aContact = $contact->selectById($_GET['idContact']);

        if ($aContact != null) {
            $form['contact'] = $aContact;
        } else {
            $form['message'] = 'Contact incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $contact = new Contact($db);
            $idContact = $_POST['idContact'];
            $lastNameContact = htmlspecialchars($_POST['inputLastName']);
            $firstNameContact = htmlspecialchars($_POST['inputFirstName']);
            $telContact = htmlspecialchars($_POST['inputTel']);
            $mailContact = htmlspecialchars($_POST['inputMail']);

            $exec = $contact->update($idContact, $lastNameContact, $firstNameContact, $telContact, $mailContact);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table contact';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Contact non précisée';
        }
    }

    echo $twig->render('contact_modify.html.twig', array('form' => $form));
}
