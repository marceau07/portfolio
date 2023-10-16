<?php

function getPage() {

    /* Pages de base */
    $lesPages['index'] = "actionIndex";
    $lesPages['settings'] = "actionSettings";
    $lesPages['history'] = "actionHistory";

    /* Pages du jeu */
    $lesPages['symbols'] = "actionSymbolsModule";
    $lesPages['wires'] = "actionWiresModule";
    $lesPages['morse'] = "actionMorseCodeModule";
    $lesPages['memory'] = "actionMemoryModule";
    
    /* Pages victoire/défaites */
    $lesPages['win'] = "actionWin";
    $lesPages['lose'] = "actionLose";

    $lesPages['getModulesComplete'] = "actionGetModulesComplete";
    
    
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 'index';
    }
    if (!isset($lesPages[$page])) {
        $page = 'index';
    }
    $contenu = $lesPages[$page];

    // La fonction envoie le contenu
    return $contenu;
}

?>