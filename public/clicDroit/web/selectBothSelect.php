<?php

require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';
require_once '../src/modele/_classes.php';

$carte = new Carte($db);
if ($_POST['idChantier'] != 0 && $_POST['idPrestation'] != 0) {
    $newRow = $carte->selectByChantierPrestation($_POST['idChantier'], $_POST['idPrestation']);
    $hours = $carte->selectByMonthChantierPrestation($_POST['idChantier'], $_POST['idPrestation']);
    foreach ($newRow as $n) {
        echo '<tr style="font-style:normal"  onclick=editValues(' . $n['idCarte'] . ') data-toggle="modal" data-target="#editModal">';
        echo '<td></td>';
        echo '<td><b>' . $n['nameChantier'] . '&nbsp;(' . $n['codeChantier'] . ')</b></td>';
        echo '<td><b>' . $n['namePrestation'] . '</b></td>';
        echo '<td><b>' . $n['dateJanvier'] . '</b></td>';
        echo '<td><b>' . $n['dateFevrier'] . '</b></td>';
        echo '<td><b>' . $n['dateMars'] . '</b></td>';
        echo '<td><b>' . $n['dateAvril'] . '</b></td>';
        echo '<td><b>' . $n['dateMai'] . '</b></td>';
        echo '<td><b>' . $n['dateJuin'] . '</b></td>';
        echo '<td><b>' . $n['dateJuillet'] . '</b></td>';
        echo '<td><b>' . $n['dateAout'] . '</b></td>';
        echo '<td><b>' . $n['dateSeptembre'] . '</b></td>';
        echo '<td><b>' . $n['dateOctobre'] . '</b></td>';
        echo '<td><b>' . $n['dateNovembre'] . '</b></td>';
        echo '<td><b>' . $n['dateDecembre'] . '</b></td>';
        echo '<td><b>' . round($n['total'], 2) . '</b></td>';
        echo '<td><a href="#" onclick="return false;"  class="btn btn-xs btn-danger"><i data-id="' . $n['idCarte'] . '" class="fa fa-trash btnDelete"></i></a></td></tr>';
    }
    echo '<tr>
                <td></td>
                <td><b>TOTAL Heures</b></td>
                <td></td>';
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
    $tot = $hours['janvier'] + $hours['fevrier'] + $hours['mars'] + $hours['avril'] + $hours['mai'] + $hours['juin'] + $hours['juillet'] + $hours['aout'] + $hours['septembre'] + $hours['octobre'] + $hours['novembre'] + $hours['decembre'];
    echo '<td><b>' . round($tot, 2);
    echo '</b></td>
        <td></td>
        </tr>';
} 
