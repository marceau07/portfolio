<?php

function indexAction($db, $twig) {
    $form = array();
    
    echo $twig->render('index.html.twig', array('form' => $form));
}