<?php

function webIndexAction($db, $twig) {
    $form = array();
    
    echo $twig->render('web/index.html.twig', array('form' => $form));
}