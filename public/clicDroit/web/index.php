<?php
session_start();
require_once '../src/modele/_classes.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(29);
$chantier = new Chantier($db);
$prestation = new Prestation($db);
$carte = new Carte($db);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Liste des prestations</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">
                    <h1>Liste des prestations</h1>
                </div>
            </div>
            <div class="col-xs-2">
                Filtre Chantier :
            </div>
            <div class="col-xs-4">
                <select class="form-control" id="select_chantier">
                    <option id="0">Tous</option>
                    <?php
                    $listeC = $chantier->select();
                    foreach ($listeC as $c) {
                        echo '<option id=' . $c['idChantier'] . '>' . $c['nameChantier'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-xs-2">
                Filtre Prestation :
            </div>
            <div class="col-xs-4">
                <select class="form-control" id="select_prestation">
                    <option id="0">Toutes</option>
                    <?php
                    $listeP = $prestation->select();
                    foreach ($listeP as $p) {
                        echo '<option id=' . $p['idPrestation'] . '>' . $p['namePrestation'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <table class="table table-striped table-responsive table-long" id="myTable">
                <thead>
                    <tr>
                        <th style="width:30px">
                            <a href="#" class="" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-circle fa-2x"></i></a>
                        </th>
                        <th>Chantier</th>
                        <th>Prestation</th>
                        <th>Janv.</th>
                        <th>Fév.</th>
                        <th>Mars</th>
                        <th>Avril</th>
                        <th>Mai</th>
                        <th>Juin</th>
                        <th>Juil.</th>
                        <th>Août</th>
                        <th>Sept</th>
                        <th>Oct</th>
                        <th>Nov.</th>
                        <th>Déc.</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $listeCarte = $carte->select();
                    foreach ($listeCarte as $car) {
                        echo '<tr style="font-style:normal" onclick=editValues(' . $car['idCarte'] . ') data-toggle="modal" data-target="#editModal">';
                        echo '<td></td>';
                        echo '<td><b>' . $car['nameChantier'] . '&nbsp;(' . $car['codeChantier'] . ')</b></td>';
                        echo '<td><b>' . $car['namePrestation'] . '</b></td>';
                        echo '<td><b>' . $car['dateJanvier'] . '</b></td>';
                        echo '<td><b>' . $car['dateFevrier'] . '</b></td>';
                        echo '<td><b>' . $car['dateMars'] . '</b></td>';
                        echo '<td><b>' . $car['dateAvril'] . '</b></td>';
                        echo '<td><b>' . $car['dateMai'] . '</b></td>';
                        echo '<td><b>' . $car['dateJuin'] . '</b></td>';
                        echo '<td><b>' . $car['dateJuillet'] . '</b></td>';
                        echo '<td><b>' . $car['dateAout'] . '</b></td>';
                        echo '<td><b>' . $car['dateSeptembre'] . '</b></td>';
                        echo '<td><b>' . $car['dateOctobre'] . '</b></td>';
                        echo '<td><b>' . $car['dateNovembre'] . '</b></td>';
                        echo '<td><b>' . $car['dateDecembre'] . '</b></td>';
                        echo '<td><b>' . round($car['total'], 2) . '</b></td>';
                        echo '<td>
                                <a href="#"  class="btn btn-xs btn-danger"><i data-id="' . $car['idCarte'] . '" class="fa fa-trash btnDelete"></i></a>
                              </td>
                            </tr>';
                    }
                    ?>

                    <tr>
                        <td></td>
                        <td><b>TOTAL Heures</b></td>
                        <td></td>
                        <?php
                        $hours = $carte->selectByMonth();
                        echo '<td><b>' . round($hours['janvier'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['fevrier'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['mars'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['avril'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['mai'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['juin'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['juillet'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['aout'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['septembre'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['octobre'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['novembre'], 2) . '</b></td>';
                        echo '<td><b>' . round($hours['decembre'], 2) . '</b></td>';
                        ?>   
                        <td><b><?php
                                $tot = $hours['janvier'] + $hours['fevrier'] + $hours['mars'] + $hours['avril'] + $hours['mai'] + $hours['juin'] + $hours['juillet'] + $hours['aout'] + $hours['septembre'] + $hours['octobre'] + $hours['novembre'] + $hours['decembre'];
                                echo round($tot, 2);
                                ?>
                            </b></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!--main-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Gestion des prestations</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <form class="formulaire-custom" id="form-heures">
                                    <div class="inline-form">
                                        <div class="" id="filtreHeure" style="margin: 10px 0px; padding-right: 0px;">
                                            <div class="form-group">
                                                <label for="" class="col-sm-1">Chantier</label>
                                                <div class="col-sm-4" id="select_chantier_container">
                                                    <select class="form-control input-sm" name="heure_chantier" id="heure_chantier" type="text" >
                                                        <?php
                                                        foreach ($listeC as $c) {
                                                            echo '<option id="' . $c['idChantier'] . '">' . $c['nameChantier'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <label for="" class="col-sm-1">Prestation</label>
                                                <div class="col-sm-4" id="select_prestation_container">
                                                    <select class="form-control input-sm" name="heure_prestation" id="heure_prestation" type="text">
                                                        <?php
                                                        foreach ($listeP as $p) {
                                                            echo '<option id="' . $p['idPrestation'] . '">' . $p['namePrestation'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="col-xs-12" >

                                                <hr style="margin:15px 0">
                                                <div class="form-group" id="form-heures-classiques">
                                                    <div class="col-xs-2">
                                                        <label>Janvier</label>
                                                        <input class="form-control input-sm" id="heure_mois_1" name="heure_mois_1" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Février</label>
                                                        <input class="form-control input-sm" id="heure_mois_2" name="heure_mois_2" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Mars</label>
                                                        <input class="form-control input-sm" id="heure_mois_3" name="heure_mois_3" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Avril</label>
                                                        <input class="form-control input-sm" id="heure_mois_4" name="heure_mois_4" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Mai</label>
                                                        <input class="form-control input-sm" id="heure_mois_5" name="heure_mois_5" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Juin</label>
                                                        <input class="form-control input-sm" id="heure_mois_6" name="heure_mois_6" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Juillet</label>
                                                        <input class="form-control input-sm" id="heure_mois_7" name="heure_mois_7" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Août</label>
                                                        <input class="form-control input-sm" id="heure_mois_8" name="heure_mois_8" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Septembre</label>
                                                        <input class="form-control input-sm" id="heure_mois_9" name="heure_mois_9" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Octobre</label>
                                                        <input class="form-control input-sm" id="heure_mois_10" name="heure_mois_10" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Novembre</label>
                                                        <input class="form-control input-sm" id="heure_mois_11" name="heure_mois_11" value="0" type="text">
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <label>Décembre</label>
                                                        <input class="form-control input-sm" id="heure_mois_12" name="heure_mois_12" value="0" type="text">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>							
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="btnAjouterCarte">Valider</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal edit -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="form-edit"></div>
            </div>
        </div>

        <script>
            function checkSelects() {
                if ($('#select_chantier option:selected').attr('id') != 0 && $('#select_prestation option:selected').attr('id') != 0) {
                    console.log('les deux éléments sont différents');
                    console.log($('#select_chantier option:selected').attr('id'));
                    console.log($('#select_prestation option:selected').attr('id'));
                    $.ajax({
                        url: 'selectBothSelect.php',
                        type: 'post',
                        data: {
                            idChantier: $('#select_chantier option:selected').attr('id'),
                            idPrestation: $('#select_prestation option:selected').attr('id')
                        },
                        success: function (html) {
                            $('#myTable > tbody').html(html);
                        }
                    });
                }
            }

            var model = document.getElementById("myModal");
            $(document).ready(function () {
                checkSelects();
                $('#select_chantier').change(function () {
                    checkSelects();
                    console.log($('#select_chantier option:selected').attr('id'));
                    $.ajax({
                        url: 'selectByChantier.php',
                        type: 'post',
                        data: {
                            idChantier: $('#select_chantier option:selected').attr('id')
                        },
                        success: function (html) {
                            $('#myTable > tbody').html(html);
                        }
                    });
                });
                $('#select_prestation').change(function () {
                    checkSelects();
                    console.log($('#select_prestation option:selected').attr('id'));
                    $.ajax({
                        url: 'selectByPrestation.php',
                        type: 'post',
                        data: {
                            idPrestation: $('#select_prestation option:selected').attr('id')
                        },
                        success: function (html) {
                            $('#myTable > tbody').html(html);
                        }
                    });
                });
                $('#btnAjouterCarte').click(function () {
                    var idChantier = $('#heure_chantier option:selected').attr('id');
                    var idPrestation = $('#heure_prestation option:selected').attr('id');
                    var dateJanvier = document.getElementById('heure_mois_1').value;
                    var dateFevrier = document.getElementById('heure_mois_2').value;
                    var dateMars = document.getElementById('heure_mois_3').value;
                    var dateAvril = document.getElementById('heure_mois_4').value;
                    var dateMai = document.getElementById('heure_mois_5').value;
                    var dateJuin = document.getElementById('heure_mois_6').value;
                    var dateJuillet = document.getElementById('heure_mois_7').value;
                    var dateAout = document.getElementById('heure_mois_8').value;
                    var dateSeptembre = document.getElementById('heure_mois_9').value;
                    var dateOctobre = document.getElementById('heure_mois_10').value;
                    var dateNovembre = document.getElementById('heure_mois_11').value;
                    var dateDecembre = document.getElementById('heure_mois_12').value;
                    var total = dateJanvier + dateFevrier + dateMars + dateAvril + dateMai + dateJuin + dateJuillet + dateAout + dateSeptembre + dateOctobre + dateNovembre + dateDecembre;
                    $.ajax({
                        url: 'insertCarte.php',
                        type: 'post',
                        data: {
                            idChantier: idChantier,
                            idPrestation: idPrestation,
                            dateJanvier: dateJanvier,
                            dateFevrier: dateFevrier,
                            dateMars: dateMars,
                            dateAvril: dateAvril,
                            dateMai: dateMai,
                            dateJuin: dateJuin,
                            dateJuillet: dateJuillet,
                            dateAout: dateAout,
                            dateSeptembre: dateSeptembre,
                            dateOctobre: dateOctobre,
                            dateNovembre: dateNovembre,
                            dateDecembre: dateDecembre
                        },
                        beforeSend: function () {
                            $('#btnAjouterCarte').attr('disabled', 'disabled');
                        },
                        success: function (html) {
                            $('#myTable > tbody').prepend(html);
                            renewTot();
                            $('#myModal').modal('hide');
                        }
                    });
                });
                $('.btnDelete').click(function () {
                    var row = $(this);
                    var idCarte = $(this).data('id');
                    $.ajax({
                        url: 'deleteCarte.php',
                        type: 'post',
                        data: {idCarte: idCarte},
                        success: function () {
                            row.closest('tr').remove();
                            renewTot();
                        }
                    });
                });
            });

            function renewTot() {
                $.ajax({
                    url: 'renewTot.php',
                    success: function (html) {
                        $('#myTable > tbody').html(html);
                    }
                });
            }

            function editValues(idCarte) {
                $.ajax({
                    url: 'editValues.php',
                    type: 'post',
                    data: {idCarte: idCarte},
                    success: function (html) {
                        $("#form-edit").html(html);
                    }
                });
            }

            function updateValues(idCarte, isOk) {
                idChantier = $('#edit_heure_chantier option:selected').attr('id');
                idPrestation = $('#edit_heure_prestation option:selected').attr('id');
                dateJanvier = $('#edit_heure_mois_1').val();
                dateFevrier = $('#edit_heure_mois_2').val();
                dateMars = $('#edit_heure_mois_3').val();
                dateAvril = $('#edit_heure_mois_4').val();
                dateMai = $('#edit_heure_mois_5').val();
                dateJuin = $('#edit_heure_mois_6').val();
                dateJuillet = $('#edit_heure_mois_7').val();
                dateAout = $('#edit_heure_mois_8').val();
                dateSeptembre = $('#edit_heure_mois_9').val();
                dateOctobre = $('#edit_heure_mois_10').val();
                dateNovembre = $('#edit_heure_mois_11').val();
                dateDecembre = $('#edit_heure_mois_12').val();
                $.ajax({
                    url: 'editValues.php',
                    type: 'post',
                    data: {
                        idCarte: idCarte,
                        isOk: isOk,
                        idChantier: idChantier,
                        idPrestation: idPrestation,
                        dateJanvier: dateJanvier,
                        dateFevrier: dateFevrier,
                        dateMars: dateMars,
                        dateAvril: dateAvril,
                        dateMai: dateMai,
                        dateJuin: dateJuin,
                        dateJuillet: dateJuillet,
                        dateAout: dateAout,
                        dateSeptembre: dateSeptembre,
                        dateOctobre: dateOctobre,
                        dateNovembre: dateNovembre,
                        dateDecembre: dateDecembre
                    },
                    success: function (html) {
                        $("#form-edit").html(html);
                        renewTot();
                        $("#editModal").modal('hide');
                    }
                });
            }
        </script>
    </body>
</html>