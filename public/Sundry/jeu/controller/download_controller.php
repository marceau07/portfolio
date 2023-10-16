<?php

function actionDownload($twig, $db) {
    $form = array();

    echo $twig->render("download.html.twig", array('form' => $form));
}
