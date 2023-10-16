<?php

function actionPrestationWS($twig, $db) {
    $v = array();
    foreach ($_GET as $uneValeur) {
        $v['valeur'] = $v['valeur'] . $uneValeur;
    }
    $idPrest = $_GET['id'];
    $typePrest = $_GET['typePrest'];
    $montant = $_GET['montant'];
    $idAct = $_GET['idAct'];

    $prestation = new Presta($db);

    if ($_GET['action'] == 'edit') {
        $exec = $prestation->update($typePrest, $montant, $idAct, $idPrest);
    } else if ($_GET['action'] == 'delete') {
        $exec = $prestation->delete($idPrest);
    }
    $json = json_encode($v);
    echo $json;
}

function actionActiviteWS($twig, $db) {
    $v = array();
    foreach ($_GET as $uneValeur) {
        $v['valeur'] = $v['valeur'] . $uneValeur;
    }
    $codeAct = $_GET['codeAct'];
    $nomAct = $_GET['nomAct'];
    $descAct = $_GET['descAct'];

    $activite = new Activite($db);

    if ($_GET['action'] == 'edit') {
        $exec = $activite->update($nomAct, $descAct, $codeAct);
    } else if ($_GET['action'] == 'delete') {
        $exec = $activite->delete($codeAct);
    }
    $json = json_encode($v);
    echo $json;
}

function actionMaterielWS($twig, $db) {
    $v = array();
    foreach ($_GET as $uneValeur) {
        $v['valeur'] = $v['valeur'] . $uneValeur;
    }
    $codeMateriel = $_GET['codeMateriel'];
    $nomMateriel = $_GET['nomMateriel'];
    $commentaire = $_GET['commentaire'];

    $materiel = new Materiel($db);

    if ($_GET['action'] == 'edit') {
        $exec = $materiel->update($nomMateriel, $commentaire, $codeMateriel);
    } else if ($_GET['action'] == 'delete') {
        $exec = $materiel->delete($codeMateriel);
    }
    $json = json_encode($v);
    echo $json;
}

function actionReglementWS($twig, $db) {
    $v = array();
    foreach ($_GET as $uneValeur) {
        $v['valeur'] = $v['valeur'] . $uneValeur;
    }
    $idRegle = $_GET['idRegle'];
    $typeRegle = $_GET['typeRegle'];

    $reglement = new Reglement($db);

    if ($_GET['action'] == 'edit') {
        $exec = $reglement->update($typeRegle, $idRegle);
    } else if ($_GET['action'] == 'delete') {
        $exec = $reglement->delete($idRegle);
    }
    $json = json_encode($v);
    echo $json;
}

