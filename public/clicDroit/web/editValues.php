<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';
require_once '../src/modele/_classes.php';

$carte = new Carte($db);
$chantier = new Chantier($db);
$prestation = new Prestation($db);

if (isset($_POST['idCarte']) && isset($_POST['isOk']) && $_POST['isOk'] == true) {
    $r = $carte->update($_POST['idChantier'], $_POST['idPrestation'], $_POST['dateJanvier'], $_POST['dateFevrier'], $_POST['dateMars'], $_POST['dateAvril'], $_POST['dateMai'], $_POST['dateJuin'], $_POST['dateJuillet'], $_POST['dateAout'], $_POST['dateSeptembre'], $_POST['dateOctobre'], $_POST['dateNovembre'], $_POST['dateDecembre'], $_POST['idCarte']);
}

if (isset($_POST['idCarte'])) {
    $editingCarte = $carte->selectById($_POST['idCarte']);
    $listeC = $chantier->select();
    $listeP = $prestation->select();

    echo '<div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edition de la prestation n°' . $editingCarte['idCarte'] . '</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">';
    echo '<form class="formulaire-custom" id="form-heures">
                                    <div class="inline-form">
                                        <div class="" id="filtreHeure" style="margin: 10px 0px; padding-right: 0px;">
                                            <div class="form-group">
                                                <label for="" class="col-sm-1">Chantier</label>
                                                <div class="col-sm-4" id="select_chantier_container">
                                                    <select class="form-control input-sm" name="heure_chantier" id="edit_heure_chantier" type="text" >';
    echo '<option id="' . $editingCarte['idChantier'] . '" disabled selected>' . $editingCarte['nameChantier'] . '</option>';
    foreach ($listeC as $c) {
        echo '<option id="' . $c['idChantier'] . '">' . $c['nameChantier'] . '</option>';
    }
    echo '</select>
                                                </div>
                                                <label for="" class="col-sm-1">Prestation</label>
                                                <div class="col-sm-4" id="select_prestation_container">
                                                    <select class="form-control input-sm" name="heure_prestation" id="edit_heure_prestation" type="text">
     <option id="' . $editingCarte['idPrestation'] . '" disabled selected>' . $editingCarte['namePrestation'] . '</option>';
    foreach ($listeP as $p) {
        echo '<option id="' . $p['idPrestation'] . '">' . $p['namePrestation'] . '</option>';
    }
    echo '</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="col-xs-12" >

                                                <hr style="margin:15px 0">
                                                <div class="form-group" id="form-heures-classiques">
                                                    <div class="col-xs-2">
                                                        <label>Janvier</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_1" name="edit_heure_mois_1" value="' . $editingCarte['dateJanvier'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Février</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_2" name="edit_heure_mois_2" value="' . $editingCarte['dateFevrier'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Mars</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_3" name="edit_heure_mois_3" value="' . $editingCarte['dateMars'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Avril</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_4" name="edit_heure_mois_4" value="' . $editingCarte['dateAvril'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Mai</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_5" name="edit_heure_mois_5" value="' . $editingCarte['dateMai'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Juin</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_6" name="edit_heure_mois_6" value="' . $editingCarte['dateJuin'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Juillet</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_7" name="edit_heure_mois_7" value="' . $editingCarte['dateJuillet'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Août</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_8" name="edit_heure_mois_8" value="' . $editingCarte['dateAout'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Septembre</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_9" name="edit_heure_mois_9" value="' . $editingCarte['dateSeptembre'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Octobre</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_10" name="edit_heure_mois_10" value="' . $editingCarte['dateOctobre'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Novembre</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_11" name="edit_heure_mois_11" value="' . $editingCarte['dateNovembre'] . '" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Décembre</label>
                                                        <input class="form-control input-sm" id="edit_heure_mois_12" name="edit_heure_mois_12" value="' . $editingCarte['dateDecembre'] . '" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>							
                                </form>';
    echo '</div>
                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="btnModifierCarte" onclick="updateValues(' . $editingCarte['idCarte']. ', true)">Modifier</button>
                    </div>';
}