<?php

function webLoginAction($db, $twig) {
    $form = array();
    
    echo $twig->render('web/login.html.twig', array('form' => $form));
}
