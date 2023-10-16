<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';
require_once '../src/modele/_classes.php';

$carte = new Carte($db);
$carte->insert($_POST['idChantier'], $_POST['idPrestation'], $_POST['dateJanvier'], $_POST['dateFevrier'], $_POST['dateMars'], $_POST['dateAvril'], $_POST['dateMai'], $_POST['dateJuin'], $_POST['dateJuillet'], $_POST['dateAout'], $_POST['dateSeptembre'], $_POST['dateOctobre'], $_POST['dateNovembre'], $_POST['dateDecembre']);
$newRow = $carte->lastInsert();
$hours = $carte->selectByMonth();
echo '<tr style="font-style:normal"  onclick=editValues(' . $newRow['idCarte'] . ') data-toggle="modal" data-target="#editModal">';
echo '<td></td>';
echo '<td><b>' . $newRow['nameChantier'] . '&nbsp;(' . $newRow['codeChantier'] . ')</b></td>';
echo '<td><b>' . $newRow['namePrestation'] . '</b></td>';
echo '<td><b>' . $newRow['dateJanvier'] . '</b></td>';
echo '<td><b>' . $newRow['dateFevrier'] . '</b></td>';
echo '<td><b>' . $newRow['dateMars'] . '</b></td>';
echo '<td><b>' . $newRow['dateAvril'] . '</b></td>';
echo '<td><b>' . $newRow['dateMai'] . '</b></td>';
echo '<td><b>' . $newRow['dateJuin'] . '</b></td>';
echo '<td><b>' . $newRow['dateJuillet'] . '</b></td>';
echo '<td><b>' . $newRow['dateAout'] . '</b></td>';
echo '<td><b>' . $newRow['dateSeptembre'] . '</b></td>';
echo '<td><b>' . $newRow['dateOctobre'] . '</b></td>';
echo '<td><b>' . $newRow['dateNovembre'] . '</b></td>';
echo '<td><b>' . $newRow['dateDecembre'] . '</b></td>';
echo '<td><b>' . round($newRow['total'], 2) . '</b></td>';
echo '<td><a href="#"  onclick="return false;" class="btn btn-xs btn-danger"><i data-id="' . $newRow['idCarte'] . '" class="fa fa-trash btnDelete"></i></a></td></tr>';
