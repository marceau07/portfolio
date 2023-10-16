<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function actionError404($twig) {
    
    var_dump($_SERVER);
    echo $twig->render('errors/error404.html.twig', array());
}