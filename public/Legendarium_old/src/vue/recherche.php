<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$livre = $bd->query('SELECT titre, synopsis, photo FROM livres ORDER BY titre');

if(isset($_GET['q']) AND !empty($_GET['q'])){
        $q = htmlspecialchars($_GET['q']);
        $livre = new Livre($db);
        $recherche = $livre->recherche($q);
    }

if($livre->rowCount() > 0){
    
}