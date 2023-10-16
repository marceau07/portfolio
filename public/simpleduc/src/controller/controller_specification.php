<?php

/*
 * Created by Marceau
 */

function actionSpecificationList($twig, $db) {
    $form = array();
    $specification = new Specification($db);

    if (isset($_GET['idContractSpecification']) && isset($_GET['idFirmSpecification'])) {
        $exec = $specification->delete($idContractSpecification, $idFirmSpecification);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table des cahiers des charges.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Cahier des charges supprimé avec succès.';
        }
    }

    if (isset($_POST['btDelete'])) {
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ($cocher as $idSpecification) {
            $exec = $specification->delete($idContractSpecification, $idFirmSpecification);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table des cahiers des charges.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Les cahiers ont été supprimés avec succès.';
            }
        }
    }

    $liste = $specification->select();
    echo $twig->render('specification_list.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Marceau : ok
 */

function actionSpecificationAdd($twig, $db) {
    $form = array();
    $specification = new Specification($db);
    $firm = new Firm($db);
    $contract = new Contract($db);


    if (isset($_POST['btAdd'])) {
        $idContractSpecification = htmlspecialchars($_POST['idContract']);
        $idFirmSpecification = htmlspecialchars($_POST['idFirm']);
        $fileSpecification = NULL;
        if (isset($_FILES['fileSpecification'])) {
            if (!empty($_FILES['fileSpecification']['name'])) {

                $ok_extensions = array('pdf', 'PDF', 'doc', 'DOC', 'docx', 'DOCX', 'odt', 'ODT', 'txt');
                $max_size = 500000;
                $dest_folder = '/var/www/html/symfony4-4020/public/simpleduc/src/specifications/';
                if (!in_array(substr(strrchr($_FILES['fileSpecification']['name'], '.'), 1), $ok_extensions)) {
                    echo 'Veuillez sélectionner un fichier autorisé !';
                } else {
                    if (file_exists($_FILES['fileSpecification']['tmp_name']) && (filesize($_FILES['fileSpecification']['tmp_name'])) > $max_size) {
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    } else {
                        $fileSpecification = basename($_FILES['fileSpecification']['name']);
                        $fileSpecification = strtr($fileSpecification, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        $fileSpecification = preg_replace('/([^.a-z0-9]+)/i', '_', $fileSpecification);
                        move_uploaded_file($_FILES['fileSpecification']['tmp_name'], $dest_folder . $fileSpecification);
                    }
                }
            }
        }

        $exec = $specification->insert($idContractSpecification, $idFirmSpecification, $fileSpecification);
        var_dump($_FILES['fileSpecification']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème d\'insertion dans la table Specification';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'insertion s\'est bien déroulée !';
        }
    }

    if (isset($_GET['idContract']) && isset($_GET['idFirm'])) {
        $exec = $specification->delete($_GET['idContract'], $_GET['idFirm']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table Specification';
        } else {
            $form['valide'] = true;
            $form['message'] = 'La specification a été supprimée avec succès.';
        }
    }



    $liste = $specification->select();
    $listeC = $contract->select();
    $listeF = $firm->select();
    echo $twig->render('specification_add.html.twig', array('form' => $form, 'liste' => $liste, 'listeC' => $listeC, 'listeF' => $listeF));
}