<?php

function actionFaq($twig, $db) {
    $form = array();
    
    $category = new Category($db);
    $faq = new Faq($db);

    $form['category'] = $category->select();
    $form['faq'] = $faq->select();
        
    echo $twig->render('faq.html.twig', array('form' => $form));
}
