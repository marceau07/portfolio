<?php 
include_once 'admin/php/admin_ajax.php';
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(1);

if($db !== NULL) {
    $sql_select_portfolio = '   SELECT 
                                    index_builder_id, 
                                    index_builder_link, 
                                    index_builder_picture, 
                                    index_builder_alt_picture, 
                                    index_builder_title, 
                                    index_builder_status, 
                                    index_builder_description, 
                                    index_builder_languages, 
                                    index_builder_github
                                FROM index_builder 
                                WHERE index_builder_group_id=1';
    $portfolio = $db->query($sql_select_portfolio)->fetch(PDO::FETCH_ASSOC);
    $cartes_portfolio = '';
    if(!empty($portfolio)) {
        $languages_portfolio = '';
        if(!empty($portfolio['index_builder_languages'])) {
            $languages_portfolio = $db->query('SELECT index_language_label, index_language_icon, index_language_picture FROM index_languages WHERE index_language_id IN ('.$portfolio['index_builder_languages'].');')->fetchAll(PDO::FETCH_ASSOC);
        }
        $cartes_portfolio = '<div class="col-sm-12 col-md-6 col-lg-3" style="margin-top: 10px;">
                                <!-- Card -->
                                <div class="card" id="card-'.$portfolio['index_builder_id'].'">
                                    <a href="'.$portfolio['index_builder_link'].'">
                                        <!-- Card image -->
                                        <div class="view overlay">
                                            '.(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips) ? '<i class="fas fa-pen fa-btn-edit" onclick="event.preventDefault(); openModeEditionSite('.$portfolio['index_builder_id'].');"></i>' : '').'
                                            <img class="card-img-top" src="img/'.$portfolio['index_builder_picture'].'" alt="'.$portfolio['index_builder_alt_picture'].'">
                                        </div>

                                        <!-- Card content -->
                                        <div class="card-body">
                                            <!-- Title -->
                                            <h4 class="card-title">'.$portfolio['index_builder_title'].'</h4>
                                            <!-- Text -->
                                            <p class="card-text"></p>
                                            <div class="row justify-content-center text-center fa-2x'.(empty($languages_portfolio) ? ' hidden' : '').'">
                                                <p>   
                                                    <b>Langages et Technologies utilisés:</b><br/>';
                                                    if(!empty($languages_portfolio)) {
                                                        foreach($languages_portfolio as $lp) {
                                                            if(!empty($lp['index_language_icon'])) {
                                                                $cartes_portfolio .= '<i class="'.$lp['index_language_icon'].'" data-toggle="tooltip" data-placement="bottom" title="'.$lp['index_language_label'].'"></i>&nbsp;';
                                                            } elseif(!empty($lp['index_language_picture'])) {
                                                                $cartes_portfolio .= '<img src="img/'.$lp['index_language_picture'].'" alt="Logo '.$lp['index_language_label'].'" height="20" width="40" data-toggle="tooltip" data-placement="bottom" title="'.$lp['index_language_label'].'" srcset="img/'.$lp['index_language_picture'].'"/>&nbsp;';
                                                            } else {
                                                                $cartes_portfolio .= '';
                                                            }
                                                        }                                                        
                                                    }
        $cartes_portfolio .= '                  </p>
                                            </div>
                                        </div>
                                    </a>
                                    <!-- Footer card -->
                                    <div class="card-footer hidden">
                                        <div class="row justify-content-center">
                                            <a target="_blank" href="#"></a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card -->
                            </div>';
    }
    
    $sql_select_projets_scolaires = '  SELECT 
                                            indb.index_builder_id, 
                                            indb.index_builder_link, 
                                            indb.index_builder_picture, 
                                            indb.index_builder_alt_picture, 
                                            indb.index_builder_title, 
                                            indb.index_builder_status, 
                                            indb.index_builder_description, 
                                            indb.index_builder_languages, 
                                            indb.index_builder_github, 
                                            inds.index_status_class
                                        FROM index_builder indb
                                        LEFT JOIN index_status inds ON inds.index_status_id=indb.index_builder_status
                                        LEFT JOIN index_languages indl ON indl.index_language_id IN(indb.index_builder_languages)
                                        WHERE indb.index_builder_group_id=2';
    $projets_scolaires = $db->query($sql_select_projets_scolaires)->fetchAll(PDO::FETCH_ASSOC);
    $cartes_projets_scolaires = '';
    if(!empty($projets_scolaires)) {
        foreach($projets_scolaires as $ps) {
            $languages_scolaires = '';
            if(!empty($ps['index_builder_languages'])) {
                $languages_scolaires = $db->query('SELECT index_language_label, index_language_icon, index_language_picture FROM index_languages WHERE index_language_id IN ('.$ps['index_builder_languages'].');')->fetchAll(PDO::FETCH_ASSOC);
            }
            $cartes_projets_scolaires .= '<div class="col-sm-12 col-md-6 col-lg-3" style="margin-top: 10px;">
                            <!-- Card -->
                            <div class="card" id="card-'.$ps['index_builder_id'].'">
                                <a href="'.$ps['index_builder_link'].'">
                                    <!-- Card image -->
                                    <div class="view overlay">
                                        '.(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips) ? '<i class="fas fa-pen fa-btn-edit" onclick="event.preventDefault(); openModeEditionSite('.$ps['index_builder_id'].');"></i>' : '').'
                                        <img class="card-img-top" src="img/'.$ps['index_builder_picture'].'" alt="'.$ps['index_builder_alt_picture'].'"/>
                                    </div>

                                    <!-- Card content -->
                                    <div class="card-body">
                                        <!-- Title -->
                                        <h4 class="card-title '.$ps['index_status_class'].'">'.$ps['index_builder_title'].'</h4>
                                        <hr>
                                        <!-- Text -->
                                        <p class="card-text">'. html_entity_decode($ps['index_builder_description']).'</p>
                                        <div class="row justify-content-center text-center fa-2x'.(empty($languages_scolaires) ? ' hidden' : '').'">
                                            <p>   
                                                <b>Langages et Technologies utilisés:</b><br/>';
                                                if(!empty($languages_scolaires)) {
                                                    foreach($languages_scolaires as $ls) {
                                                        if(!empty($ls['index_language_icon'])) {
                                                            $cartes_projets_scolaires .= '<i class="'.$ls['index_language_icon'].'" data-toggle="tooltip" data-placement="bottom" title="'.$ls['index_language_label'].'"></i>&nbsp;';
                                                        } elseif(!empty($ls['index_language_picture'])) {
                                                            $cartes_projets_scolaires .= '<img src="img/'.$ls['index_language_picture'].'" alt="Logo '.$ls['index_language_label'].'" height="20" width="40" data-toggle="tooltip" data-placement="bottom" title="'.$ls['index_language_label'].'" srcset="img/'.$ls['index_language_picture'].'"/>&nbsp;';
                                                        } else {
                                                            $cartes_projets_scolaires .= '';
                                                        }
                                                    }
                                                }
            $cartes_projets_scolaires .= '  </p>
                                        </div>
                                    </div>
                                </a>
                                <!-- Footer card -->
                                <div class="card-footer'.(!empty($ps['index_builder_github']) ? '' : ' hidden').'">
                                    <div class="row justify-content-center">
                                        <a target="_blank" href="'.$ps['index_builder_github'].'">
                                            <i class="fab fa-github-square blue-ic fa-2x" data-toggle="tooltip" data-placement="bottom" title="Voir le code"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card -->
                        </div>';
        }
    }
    
    $sql_select_projets_personnels = '  SELECT 
                                            indb.index_builder_id, 
                                            indb.index_builder_link, 
                                            indb.index_builder_picture, 
                                            indb.index_builder_alt_picture, 
                                            indb.index_builder_title, 
                                            indb.index_builder_status, 
                                            indb.index_builder_description, 
                                            indb.index_builder_languages, 
                                            indb.index_builder_github, 
                                            inds.index_status_class
                                        FROM index_builder indb
                                        LEFT JOIN index_status inds ON inds.index_status_id=indb.index_builder_status
                                        LEFT JOIN index_languages indl ON indl.index_language_id IN(indb.index_builder_languages)
                                        WHERE indb.index_builder_group_id=3';
    $projets_personnels = $db->query($sql_select_projets_personnels)->fetchAll(PDO::FETCH_ASSOC);
    $cartes_projets_personnels = '';
    if(!empty($projets_personnels)) {
        foreach($projets_personnels as $pp) {
            $languages_personnels = (!empty($pp['index_builder_languages']) ? $db->query('SELECT index_language_label, index_language_icon, index_language_picture FROM index_languages WHERE index_language_id IN ('.$pp['index_builder_languages'].');')->fetchAll(PDO::FETCH_ASSOC) : "");

            $cartes_projets_personnels .= '
                        <div class="col-sm-12 col-md-6 col-lg-3" style="margin-top: 10px;">
                            <!-- Card -->
                            <div class="card" id="card-'.$pp['index_builder_id'].'">
                                <a href="'.$pp['index_builder_link'].'">
                                    <!-- Card image -->
                                    <div class="view overlay">
                                        '.(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips) ? '<i class="fas fa-pen fa-btn-edit" onclick="event.preventDefault(); openModeEditionSite('.$pp['index_builder_id'].');"></i>' : '').'
                                        <img class="card-img-top" src="img/'.$pp['index_builder_picture'].'" alt="'.$pp['index_builder_alt_picture'].'"/>
                                    </div>

                                    <!-- Card content -->
                                    <div class="card-body">
                                        <!-- Title -->
                                        <h4 class="card-title '.$pp['index_status_class'].'">'.$pp['index_builder_title'].'</h4>
                                        <hr>
                                        <!-- Text -->
                                        <p class="card-text">'. html_entity_decode($pp['index_builder_description']).'</p>
                                        <div class="row justify-content-center text-center fa-2x'.(empty($languages_personnels) ? ' hidden' : '').'">
                                            <p>
                                                <b>Langages et Technologies utilisés:</b><br/>';
                                                if(!empty($languages_personnels)) {
                                                    foreach($languages_personnels as $lp) {
                                                        if(!empty($lp['index_language_icon'])) {
                                                            $cartes_projets_personnels .= '<i class="'.$lp['index_language_icon'].'" data-toggle="tooltip" data-placement="bottom" title="'.$lp['index_language_label'].'"></i>&nbsp;';
                                                        } elseif(!empty($lp['index_language_picture'])) {
                                                            $cartes_projets_personnels .= '<img src="img/'.$lp['index_language_picture'].'" alt="Logo '.$lp['index_language_label'].'" height="20" width="40" data-toggle="tooltip" data-placement="bottom" title="'.$lp['index_language_label'].'" srcset="img/'.$lp['index_language_picture'].'"/>&nbsp;';
                                                        } else {
                                                            $cartes_projets_personnels .= '';
                                                        }
                                                    }
                                                }
            $cartes_projets_personnels .= ' </p>
                                        </div>
                                    </div>
                                </a>
                                <!-- Footer card -->
                                <div class="card-footer'.(!empty($ps['index_builder_github']) ? '' : ' hidden').'">
                                    <div class="row justify-content-sm-center">
                                        <a target="_blank" href="'.$pp['index_builder_github'].'">
                                            <i class="fab fa-github-square blue-ic fa-2x" data-toggle="tooltip" data-placement="bottom" title="Voir le code"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card -->
                        </div>';
        }
    }
    
    $sql_select_tests_fonctionnels = '  SELECT 
                                            indb.index_builder_id, 
                                            indb.index_builder_link, 
                                            indb.index_builder_picture, 
                                            indb.index_builder_alt_picture, 
                                            indb.index_builder_title, 
                                            indb.index_builder_status, 
                                            indb.index_builder_description, 
                                            indb.index_builder_languages, 
                                            indb.index_builder_github, 
                                            inds.index_status_class
                                        FROM index_builder indb
                                        LEFT JOIN index_status inds ON inds.index_status_id=indb.index_builder_status
                                        LEFT JOIN index_languages indl ON indl.index_language_id IN(indb.index_builder_languages)
                                        WHERE indb.index_builder_group_id=5';
    $tests_fonctionnels = $db->query($sql_select_tests_fonctionnels)->fetchAll(PDO::FETCH_ASSOC);
    $cartes_tests_fonctionnels = '';
    if(!empty($tests_fonctionnels)) {
        foreach($tests_fonctionnels as $tf) {
            $cartes_tests_fonctionnels .= '
                        <div class="col-sm-12 col-md-6 col-lg-3" style="margin-top: 10px;">
                            <!-- Card -->
                            <div class="card" id="card-'.$tf['index_builder_id'].'">
                                <a href="'.$tf['index_builder_link'].'">
                                    <!-- Card image -->
                                    <div class="view overlay">
                                        '.(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips) ? '<i class="fas fa-pen fa-btn-edit" onclick="event.preventDefault(); openModeEditionSite('.$tf['index_builder_id'].');"></i>' : '').'
                                        <img class="card-img-top" src="img/'.$tf['index_builder_picture'].'" alt="'.$tf['index_builder_alt_picture'].'"/>
                                    </div>

                                    <!-- Card content -->
                                    <div class="card-body">
                                        <!-- Title -->
                                        <h4 class="card-title '.$tf['index_status_class'].'">'.$tf['index_builder_title'].'</h4>
                                        <hr>
                                        <!-- Text -->
                                        <p class="card-text">'. html_entity_decode($tf['index_builder_description']).'</p>
                                    </div>
                                </a>
                                <!-- Footer card -->
                                <div class="card-footer hidden">
                                    <div class="row justify-content-sm-center">
                                        <a target="_blank" href="#"></a>
                                    </div>
                                </div>
                            </div>
                            <!-- Card -->
                        </div>';
        }
    }
    
    $sql_select_projets_abandonnes = '  SELECT 
                                            index_builder_title, 
                                            index_builder_link
                                        FROM index_builder 
                                        WHERE index_builder_status = 1 ';
    $req_select_projets_abandonnes = $db->prepare($sql_select_projets_abandonnes);
    $req_select_projets_abandonnes->execute();
    $liste_projets_abandonnes = $req_select_projets_abandonnes->fetchAll(PDO::FETCH_ASSOC);
    $projets_abandonnes = '';
    $i = 0;
    foreach($liste_projets_abandonnes as $value) {
        $projets_abandonnes .= "<a href='".$value['index_builder_link']."' target='_self'>".$value['index_builder_title']."</a>";
        if(count($liste_projets_abandonnes) - 1 > $i) $projets_en_cours .= "<br/>";
        $i++;
    }
    
    $sql_select_projets_en_cours = 'SELECT 
                                        index_builder_title, 
                                        index_builder_link
                                    FROM index_builder 
                                    WHERE index_builder_status = 2 ';
    $req_select_projets_en_cours = $db->prepare($sql_select_projets_en_cours);
    $req_select_projets_en_cours->execute();
    $liste_projets_en_cours = $req_select_projets_en_cours->fetchAll(PDO::FETCH_ASSOC);
    $projets_en_cours = '';
    $i = 0;
    foreach($liste_projets_en_cours as $value) {
        $projets_en_cours .= "<a href='".$value['index_builder_link']."' target='_self'>".$value['index_builder_title']."</a>";
        if(count($liste_projets_en_cours) - 1 > $i) $projets_en_cours .= "<br/>";
        $i++;
    }

    $sql_select_projets_acheves = ' SELECT 
                                        index_builder_title, 
                                        index_builder_link
                                    FROM index_builder 
                                    WHERE index_builder_status = 3 ';
    $req_select_projets_acheves = $db->prepare($sql_select_projets_acheves);
    $req_select_projets_acheves->execute();
    $liste_projets_acheves = $req_select_projets_acheves->fetchAll(PDO::FETCH_ASSOC);
    $projets_acheves = '';
    $i = 0;
    foreach($liste_projets_acheves as $value) {
        $projets_acheves .= "<a href='".$value['index_builder_link']."' target='_self'>".$value['index_builder_title']."</a>";
        if(count($liste_projets_acheves) - 1 > $i) $projets_acheves .= "<br/>";
        $i++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Mes projets</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css" 
                integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" 
                crossorigin="anonymous"/>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css?v=<?=uniqid()?>">
        <!-- Material Design Bootstrap -->
        <link rel="stylesheet" href="css/mdb.min.css?v=<?=uniqid()?>">
        <!-- Your custom styles (optional) -->
        <link rel="stylesheet" href="css/style.css?v=<?=uniqid()?>">
        <link rel="stylesheet" href="css/loader.css?v=<?=uniqid()?>">
        <link href="css/dayNight.css?v=<?=uniqid()?>" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="//uicdn.toast.com/chart/latest/toastui-chart.min.css" />
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    </head>
    <body id="body">
        <!--Main Navigation-->
        <header>
            <nav class="navbar fixed-top navbar-expand-lg" id="navbar">
                <?php if(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips)) { ?>
                    <a class="navbar-brand" href="#"><i class="fas fa-tools" style="font-size: 100%;" onclick="afficherModalConnexionAdministration();" title="Mode d'administration"></i>&nbsp;<strong>MR</strong></a>  
                    <span onclick="exporterDonnees();" style="cursor: pointer;"><i id="icone_spinner" class="fas fa-spinner" style="display: none; animation: fa-spin 2s ease infinite;"></i><i id="icone_archivage" class="fas fa-archive"></i>&nbsp;Exporter données&nbsp;</span>
                <?php } ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse text-center" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item<?=(empty($cartes_portfolio) ? ' hidden' : '')?>">
                            <a class="nav-link" href="#portfolio">Portfolio<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item<?=(empty($cartes_projets_scolaires) ? ' hidden' : '')?>">
                            <a class="nav-link" href="#projetsScolaires">Projets scolaires</a>
                        </li>
                        <li class="nav-item<?=(empty($cartes_projets_personnels) ? ' hidden' : '')?>">
                            <a class="nav-link" href="#projetsPersonnels">Projets personnels</a>
                        </li>
                    </ul>
                    <?php if(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips)) { ?>
                    <div class="ml-auto">
                        <span data-toggle="tooltip" data-placement="bottom" title="Navigateur et comparateur de fichiers" style="cursor: pointer;" onclick="$('#loader').css('display', ''); window.location.href = 'gestion_documentaire.php';"><i class="fas fa-folder-tree"></i>&nbsp;</span>
                    </div>
                    <div class="mr-auto">
                    <?php } else { ?>
                    <div class="ml-auto mr-auto">
                    <?php } ?>
                        <span data-toggle="tooltip" data-placement="bottom" data-html="true"><i class="fas fa-sync" style="font-size: 1em; animation: fa-spin 2s ease infinite;"></i>&nbsp;&nbsp;Serveur actif depuis&nbsp;<span id="tempsActiviteServeur" style="font-weight: 700;">0 minute</span></span>
                        <span id="tempsActiviteServeurEteint" style="font-weight: 700; display: none;"><a target="_blank" href="//plateforme.nuage-pedagogique.fr/user-profile?idStart=184"><i class="fas fa-power-off"></i></a>&nbsp;&nbsp;Serveur éteint</span>
                    </div>
                    <div class="random">
                        <span date-template="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div></div>" 
                              data-toggle="popover" 
                              title="Projets abandonnés" 
                              data-content="<?=$projets_abandonnes?>" 
                              data-placement="bottom">
                            <code class="nav-item black-text nbr ltr">P</code><code class="nav-item black-text nbr ltr">r</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">j</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">t</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">a</code><code class="nav-item black-text nbr ltr">b</code><code class="nav-item black-text nbr ltr">a</code><code class="nav-item black-text nbr ltr">n</code><code class="nav-item black-text nbr ltr">d</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">n</code><code class="nav-item black-text nbr ltr">n</code><code class="nav-item black-text nbr ltr">é</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">:</code>&nbsp;<code id="archivedProjects" class="nav-item white-text"></code>&nbsp;&nbsp;
                        </span>
                        <span date-template="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div></div>" 
                              data-toggle="popover" 
                              title="Projets en cours" 
                              data-content="<?=$projets_en_cours?>" 
                              data-placement="bottom">
                            <code class="nav-item black-text nbr ltr">P</code><code class="nav-item black-text nbr ltr">r</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">j</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">t</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">n</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">c</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">u</code><code class="nav-item black-text nbr ltr">r</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">:</code>&nbsp;<code id="wipProjects" class="nav-item white-text"></code>&nbsp;&nbsp;
                        </span>
                        <span date-template="<div class='popover' role='tooltip'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div></div>" 
                              data-toggle="popover" 
                              title="Projets achevés" 
                              data-content="<?=$projets_acheves?>" 
                              data-placement="bottom">
                            <code class="nav-item black-text nbr ltr">P</code><code class="nav-item black-text nbr ltr">r</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">j</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">t</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">a</code><code class="nav-item black-text nbr ltr">c</code><code class="nav-item black-text nbr ltr">h</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">v</code><code class="nav-item black-text nbr ltr">é</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">:</code>&nbsp;<code id="finishedProjects" class="nav-item white-text"></code>&nbsp;&nbsp;
                        </span>
                        <code class="nav-item black-text nbr ltr">N</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">m</code><code class="nav-item black-text nbr ltr">b</code><code class="nav-item black-text nbr ltr">r</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">t</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">t</code><code class="nav-item black-text nbr ltr">a</code><code class="nav-item black-text nbr ltr">l</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">d</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">&nbsp;</code><code class="nav-item black-text nbr ltr">p</code><code class="nav-item black-text nbr ltr">r</code><code class="nav-item black-text nbr ltr">o</code><code class="nav-item black-text nbr ltr">j</code><code class="nav-item black-text nbr ltr">e</code><code class="nav-item black-text nbr ltr">t</code><code class="nav-item black-text nbr ltr">s</code><code class="nav-item black-text nbr ltr">:</code>&nbsp;<code id="totalProjects" class="nav-item white-text"></code>
                    </div>
                </div>
            </nav>
        </header>
        
        <div class="screen" id="loader">
            <div class="loader">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
        <!--Main Navigation-->
        <div class="wrapper">
            <div class="container-fluid">
                <div class="row" style="margin-top: 10vh;">

                    <div class="col-sm-12 col-md-2">
                        <div class="alert alert-success" id="toast" role="alert" style="display: none;">
                            Le robot a bien été démarré.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="row justify-content-center d-none">
                            <i class="fas fa-chart-pie fa-2x icons-color" style="cursor: pointer;" onclick="afficherStatsAdministration();"></i>
                        </div>
                    </div>
                    
                    <div class="col-sm-12 col-md-8">
                        <div class="row justify-content-center dnone">
                            <div style="max-width: 25%; width: 15vh; border-right: 3px solid green;" class="<?=(empty($cartes_portfolio) ? 'hidden':'')?>">
                                <label class="switch">
                                    <input type="checkbox" id="filtre_portfolio" onclick="$('#portfolio h2').trigger('click'); $('#filtre_portfolio')[0].checked = !$('#filtre_portfolio')[0].checked;" checked >
                                    <span class="slider round"></span>
                                    <span style="position: relative; left: 7vh; bottom: 2vh; font-weight: 500;">&nbsp;Portfolio</span>
                                </label>
                            </div>
                            &nbsp;
                            <div style="max-width: 25%; width: 17vh; border-right: 3px solid green;" class="<?=(empty($cartes_projets_scolaires) ? 'hidden':'')?>">
                                <label class="switch">
                                    <input type="checkbox" id="filtre_projets_scolaires" onclick="$('#projetsScolaires h2').trigger('click'); $('#filtre_projets_scolaires')[0].checked = !$('#filtre_projets_scolaires')[0].checked;" checked >
                                    <span class="slider round"></span>
                                    <span style="position: relative; left: 7vh; bottom: 0.75vh; font-weight: 500;">&nbsp;Projets Scolaires</span>
                                </label>
                            </div>
                            &nbsp;
                            &nbsp;
                            <div style="max-width: 25%; width: 17vh; border-right: 3px solid green;" class="<?=(empty($cartes_projets_personnels) ? 'hidden':'')?>">
                                <label class="switch">
                                    <input type="checkbox" id="filtre_projets_personnels" onclick="$('#projetsPersonnels h2').trigger('click'); $('#filtre_projets_personnels')[0].checked = !$('#filtre_projets_personnels')[0].checked;" checked >
                                    <span class="slider round"></span>
                                    <span style="position: relative; left: 7vh; bottom: 0.75vh; font-weight: 500;">&nbsp;Projets Personnels</span>
                                </label>
                            </div>
                            &nbsp;
                            &nbsp;
                            <div style="max-width: 25%; width: 17vh;" class="<?=(empty($cartes_tests_fonctionnels) ? 'hidden':'')?>">
                                <label class="switch">
                                    <input type="checkbox" id="filtre_tests_fonctionnels" onclick="$('#testsFonctionnels h2').trigger('click'); $('#filtre_tests_fonctionnels')[0].checked = !$('#filtre_tests_fonctionnels')[0].checked;" checked >
                                    <span class="slider round"></span>
                                    <span style="position: relative; left: 7vh; bottom: 0.75vh; font-weight: 500;">&nbsp;Tests fonctionnels</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- Switch Day/Night -->
                    <div class="col-sm-1 col-md-2 justify-content-center">
                        <div class="toggle toggle--daynight">
                            <input type="checkbox" id="toggle--daynight" class="toggle--checkbox" onclick="dayMode()">
                            <label class="toggle--btn" for="toggle--daynight"><span class="toggle--feature"></span></label>
                        </div>
                    </div>
                    <!-- Fin switch Day/Night 
                    
                    <!-- Mon Portfolio -->
                    <div class="col-sm-12 col-md-8 offset-md-2<?=(empty($cartes_portfolio) ? ' hidden':'')?>" id="portfolio">
                        <div class="text-center">
                            <h2 class="h2-responsive" style="cursor: pointer;" onclick="$('#filtre_portfolio')[0].checked = !$('#filtre_portfolio')[0].checked; $('#cartes_portfolio').toggleClass('hidden');changerClassIcone($(this.firstElementChild));"><i class="fas fa-angle-down" style="color: #FFFF8D; font-size: 100%;"></i>&nbsp;Mon portfolio:</h2>
                        </div>
                        <div class="row justify-content-center" id="cartes_portfolio">
                            <?php echo $cartes_portfolio; ?>
                        </div>
                    </div>
                    <!-- Fin Mon Portfolio -->

                    <!-- Projets scolaires -->
                    <div class="col-sm-12 col-md-8 offset-md-2 separationBorderFull<?=(empty($cartes_projets_scolaires) ? ' hidden':'')?>" id="projetsScolaires">
                        <div class="text-center">
                            <h2 class="h2-responsive" style="cursor: pointer;" onclick="$('#filtre_projets_scolaires')[0].checked = !$('#filtre_projets_scolaires')[0].checked; $('#cartes_projets_scolaires').toggleClass('hidden');changerClassIcone($(this.firstElementChild));"><i class="fas fa-angle-down" style="color: #FFFF8D; font-size: 100%;"></i>&nbsp;Mes projets scolaires:</h2>
                        </div>
                        <div class="row justify-content-center" id="cartes_projets_scolaires">
                            <?php echo $cartes_projets_scolaires; ?>
                        </div>
                    </div>
                    <!-- Fin projets scolaires -->

                    <!-- Projets personnels -->
                    <div class="col-sm-12 col-md-8 offset-md-2 separationBorderFull<?=(empty($cartes_projets_personnels) ? ' hidden':'')?>" id="projetsPersonnels">
                        <div class="text-center">
                            <h2 class="h2-responsive" style="cursor: pointer;" onclick="$('#filtre_projets_personnels')[0].checked = !$('#filtre_projets_personnels')[0].checked; $('#cartes_projets_personnels').toggleClass('hidden');changerClassIcone($(this.firstElementChild));"><i class="fas fa-angle-down" style="color: #FFFF8D; font-size: 100%;"></i>&nbsp;Mes projets personnels:</h2>
                        </div>
                        <div class="row justify-content-center" id="cartes_projets_personnels">
                            <?php echo $cartes_projets_personnels; ?>
                        </div>
                    </div>
                    <!-- Fin projets personnels -->


                    <!-- Pentesting -->
                    <div class="col-sm-12 col-md-8 offset-md-2 separationBorderFull" id="scorePentesting">
                        <div class="text-center">
                            <h2 class="h2-responsive">Score Pentesting:</h2>
                        </div>
                        <div class="row justify-content-center">
                            <script src="//tryhackme.com/badge/398857"></script>
                            <img alt="score" src="//www.hackthebox.eu/badge/image/535124" />
                        </div>
                    </div>
                    <!-- Fin pentesting -->

                    <!-- Tests fonctionnels -->
                    <div class="col-sm-12 col-md-8 offset-md-2 separationBorderFull<?=(empty($cartes_tests_fonctionnels) ? ' hidden':'')?>" id="testsFonctionnels">
                        <div class="text-center">
                            <h2 class="h2-responsive" style="cursor: pointer;" onclick="$('#filtre_tests_fonctionnels')[0].checked = !$('#filtre_tests_fonctionnels')[0].checked; $('#cartes_tests_fonctionnels').toggleClass('hidden');changerClassIcone($(this.firstElementChild));" id="testsFonctionnels"><i class="fas fa-angle-down" style="color: #FFFF8D; font-size: 100%;"></i>&nbsp;Tests fonctionnels:</h2>
                        </div>
                        <div class="row justify-content-sm-center" id="cartes_tests_fonctionnels">
                            <?php echo $cartes_tests_fonctionnels; ?>
                        </div>
                    </div>
                    <!-- Fin tests fonctionnels -->
                </div>
            </div>
            <a href="#" id="scrollUp" style="display: none;"><span></span></a>
            <a href="#" id="scrollDown" style="display: none;"><span></span></a>
            <div class="push"></div>
        
            <!-- Modales -->
            <div class="modal fade" id="modale_connexion_administration" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Connexion au panel d'administration</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="form_admin_username">Nom:</label>
                                    <input type="text" class="form-control" name="form_admin_username" id="form_admin_username" placeholder="Nom utilisateur admin" />
                                </div>
                                <div class="col-sm-6">
                                    <label for="form_admin_password">Mot de passe:</label>
                                    <input type="password" class="form-control" name="form_admin_password" id="form_admin_password" placeholder="btsinfo" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="afficherAdministration();">S'authentifier</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modale_statistiques_administration" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Statistiques</h5>
                            <button type="button" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.5em;" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="font-size: 2em;">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="chart_stats_index" class="text-center" style="width: 100%; height: 50vh;"></div>
                                    <div id="script_loaded"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-md btn-light" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="modale_administration_ajouter" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ajouter un nouveau projet</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="enregistrerSite();">Confirmer</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modales -->
        </div>

        <!-- Footer -->
        <footer class="footer font-small pt-4" id="footer">
            <!-- Social buttons -->
            <ul class="list-unstyled list-inline flex-center">
                <li class="list-inline-item">
                    <a class="btn-floating btn-li mx-1" href="//plateforme.nuage-pedagogique.fr" target="_blank"><i class="fas fa-globe-europe blue-grey-ic" title="Serveur Cloud"></i></a>
                </li>
                <li class="list-inline-item">
                    <a class="btn-floating btn-li mx-1" href="phpmyadmin/" target="_blank"><i class="fab fa-php blue-ic"></i></a>
                </li>
                <li class="list-inline-item">
                    <a class="btn-floating btn-li mx-1" href="//gitlab.com/mrodrigues18" target="_blank"><i class="fab fa-gitlab orange-ic" title="Gitlab"></i></a>
                </li>
                <li class="list-inline-item">
                    <a class="btn-floating btn-li mx-1" href="//trello.com/marceaurodrigues"><i class="fab fa-trello blue-gradient-rgba" title="Trello"></i></a>
                </li>
                <li class="list-inline-item">
                    <a class="btn-floating btn-li mx-1" href="//www.linkedin.com/in/marceau-rodrigues-66a216182/" target="_blank">
                        <i class="fab fa-linkedin-in blue-ic" title="Linkedin"></i>
                    </a>
                </li>
            </ul>
            <!-- Social buttons -->

            <!-- Copyright -->
            <div class="footer-copyright text-center py-3 text-primary">
                <a href="http://serveur1.arras-sio.com/symfony4-4017/">Marceau Rodrigues</a> © 2019-<?php echo date("Y"); ?>
            </div>
            <!-- Copyright -->

        </footer>
        <!-- Footer -->


        <!-- Modernizr -->
        <script src='//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js'></script>
        <!-- jQuery -->
        <script type="text/javascript" src="js/jquery.min.js?v=<?=uniqid()?>"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="js/popper.min.js?v=<?=uniqid()?>"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js?v=<?=uniqid()?>"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="js/mdb.min.js?v=<?=uniqid()?>"></script>
        <script src="//uicdn.toast.com/chart/latest/toastui-chart.min.js"></script>
        <!-- Your custom scripts (optional) -->
        <?=(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips) ?'<script type="text/javascript" src="admin/js/admin.js?v='.uniqid().'"></script>' : '')?>
        <script src="js/initVariables.js?v=<?=uniqid()?>" type="text/javascript"></script>
        <script>
            $(document).ready(function() {
                $('#loader').css('display', 'none');
                window.chartStats = undefined;
                
                $(window).scroll(function(){ 
                    if ($(this).scrollTop() > 100) { 
                        $('#scrollDown').fadeOut(); 
                        $('#scrollUp').fadeIn(); 
                    } else { 
                        $('#scrollDown').fadeIn(); 
                        $('#scrollUp').fadeOut(); 
                    } 
                }); 
                $('#scrollUp').click(function(){ 
                    $("html, body").animate({ scrollTop: 0 }, 600); 
                    return false; 
                });
                $('#scrollDown').click(function(){ 
                    $("html, body").animate({ scrollTop: $(document).height() }, 600); 
                    return false; 
                });
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover({ trigger: "manual" , html: true, animation:false})
                    .on("mouseenter", function () {
                        var _this = this;
                        $(this).popover("show");
                        if($('#body').hasClass('night')) {
                            $('.popover-header, .popover-body').css('background-color', '#181818');
                            $('.popover-header, .popover-body').css('color', 'whitesmoke');
                        } else {
                            $('.popover-header, .popover-body').css('background-color', '#d3d3d3');
                            $('.popover-header, .popover-body').css('color', 'grey');
                        }
                        
                        $(".popover").on("mouseleave", function () {
                            $(_this).popover('hide');
                        });
                    }).on("mouseleave", function () {
                        var _this = this;
                        setTimeout(function () {
                            if (!$(".popover:hover").length) {
                                $(_this).popover("hide");
                            }
                        }, 300);
                    });
                recupererTempsActivite();
                setInterval(function() {
                    recupererTempsActivite();
                }, 30000);
                sortLetters();  

                dayMode();
            });
            
            if(parseInt(<?=strtotime('now 18:00:00') - strtotime('now')?>) < 0) {
                if($('#toggle--daynight').is(':checked')) {
                   $('#toggle--daynight').trigger('click');
                }
            } else if(parseInt(<?=strtotime('now') - strtotime('now 08:00:00')?>) > 0) {
                if(!$('#toggle--daynight').is(':checked')) {
                    $('#toggle--daynight').trigger('click');
                }
            }
            
            function toggleClassFilters(filter) {
                $('.filter-icons').each(function() {
                    if($(this).is(':cheked')) {
                        $('#'+filter).css('display', 'inline-block');
                    }
                    if($(this).is(':not(:checked)')) {
                        $('#'+filter).css('display', 'none');
                    }
                });
            }
            
            $('#modale_statistiques_administration').on('hide.bs.modal', function() {
                $('#script_loaded').html('');
            });
            
            function afficherStatsAdministration() {
                $('#chart_stats_index').html('');
                $('#script_loaded').html('');
                
                $.ajax({
                    url: 'admin/php/admin_ajax.php', 
                    method: 'post', 
                    dataType: 'json', 
                    data: {
                        afficherStatsAdministration: 1
                    }, 
                    success: function(r) {
                        if(r.bEstOk === true) {
                            $('#script_loaded').html(r.js);
                        }
                    }
                });
                $('#modale_statistiques_administration').modal('show');
            }
            
            function recupererTempsActivite() {
                $('#tempsActiviteServeurEteint').css('display', 'none');
                $.ajax({
                    url: 'admin/php/admin_ajax.php', 
                    method: 'post', 
                    dataType: 'json', 
                    data: {
                        recupererTempsActivite: 1
                    }, 
                    success: function(r) {
                        $('#tempsActiviteServeur').parent().closest('span').css('display', 'block');
                        $('#tempsActiviteServeur').html(r.uptime);
                        
                        $('#tempsActiviteServeur').parent().closest('span').attr('data-html', 'true');
                        $('#tempsActiviteServeur').parent().closest('span').attr('data-original-title', r.fermeDans); // 'Le serveur se fermera dans <br/>'
                    },
                    error: function(data) {
                        if(data.status === 503) {
                            $('#tempsActiviteServeur').parent().closest('span').css('display', 'none');
                            $('#tempsActiviteServeurEteint').css('display', 'block');
                        }
                    }
                });
            }
            
            function sortLetters() {
                var $randomnbr = $('.nbr');
                var $timer = 10;
                var $it;
                var $data = 0;
                var index;
                var letters = ["P", "r", "o", "j", "e", "t", "s", " ", "a", "b", "a", "n", "d", "o", "n", "n", "é", "s", ":",
                    "P", "r", "o", "j", "e", "t", "s", " ", "e", "n", " ", "c", "o", "u", "r", "s", ":",
                    "P", "r", "o", "j", "e", "t", "s", " ", "a", "c", "h", "e", "v", "é", "s", ":",
                    "N", "o", "m", "b", "r", "e", " ", "t", "o", "t", "a", "l", " ", "d", "e", " ", "p", "r", "o", "j", "e", "t", "s", ":"];

                $randomnbr.each(function() {
                    change = Math.round(Math.random() * 100);
                    $(this).attr('data-change', change);
                });
                
                $(".fa-angle-down").addClass(function() {
                    return "fa-2x";
                });
                
                function random() {
                    return Math.round(Math.random() * 9);
                }

                function select() {
                    return Math.round(Math.random() * $randomnbr.length + 1);
                }

                function value() {
                    $('.nbr:nth-child(' + select() + ')').html('' + random() + '');
                    $('.nbr:nth-child(' + select() + ')').attr('data-number', $data);
                    $data++;

                    $randomnbr.each(function() {
                        if (parseInt($(this).attr('data-number')) > parseInt($(this).attr('data-change')) && $(this).hasClass('nbr')) {
                            index = $('.ltr').index(this);
                            $(this).html(letters[index]);
                            $(this).removeClass('nbr');
                            
                        }
                    });
                }
                $it = setInterval(value, $timer);
            }
            
            <?php if(empty($allowed_ips) || in_array($ip_index_address, $allowed_ips)) { ?>
                function exporterDonnees() {
                    $.ajax({
                        url: 'admin/php/admin_ajax.php', 
                        method: 'post', 
                        data: {
                            exporterDonnees: 1
                        }, 
                        beforeSend: function() {
                           $('#icone_archivage').css('display', 'none');
                           $('#icone_spinner').css('display', 'inline-block');
                        }, 
                        success: function() {
                            alert('Export effectué');
                        }, 
                        complete: function() {
                           $('#icone_spinner').css('display', 'none'); 
                           $('#icone_archivage').css('display', 'inline-block');
                        }
                    });
                }
                
                function afficherModalConnexionAdministration() {
                    $('#modale_connexion_administration .modal-body input').val('');
                    $('#modale_connexion_administration').modal('show');
                    $('#form_admin_username').trigger('focus');
                    $('#modale_connexion_administration').find('.modal-content').removeClass('night');
                    $('#modale_connexion_administration').find('.modal-content').removeClass('day');
                    if($('#body').hasClass('night')) {
                        $('#modale_connexion_administration').find('.modal-content').addClass('night');
                    } else {
                        $('#modale_connexion_administration').find('.modal-content').addClass('day');
                    }
                    
                    $('#form_admin_username').val('admin');
                    $('#form_admin_password').val('btsinfo');
                }
                
                function verifierFormAdmin() {
                    var formValide = true;
                    var languagesSelected = false;
                    $('#form_administration_ajouter .admin_obligatoire').css('border', '1px solid #ddd');
                    $('.admin_obligatoire').each(function() {
                       if($(this).val() === 0 || $(this).val() === '') {
                           $(this).css('border', '2px solid #FF0000');
                           formValide = false;
                       }
                    });
                    
                    $('#form_admin_languages input').each(function() { 
                        if($(this).is(':checked')) {
                            languagesSelected = true;
                        }
                    });
                    if(!languagesSelected) {
                        $('#form_admin_languages_label').css('border', '2px solid #FF0000');
                        formValide = false;
                    } else {
                        $('#form_admin_languages_label').css('border', '1px solid #ddd');
                    }
                    
                    return formValide;
                }

                function enregistrerSite() {
                    var languagesSelected = false;
                    var formData = new FormData();
                    
                    if(!verifierFormAdmin()) {
                        return false;
                    }

                    formData.append("enregistrerSite", 1);
                    formData.append("form_admin_group_id", $('#form_admin_group_id').val());
                    formData.append("form_admin_link", $('#form_admin_link').val());
                    formData.append("form_admin_picture", document.getElementById('form_admin_picture').files[0]);
                    formData.append("form_admin_picture_alt", $('#form_admin_picture_alt').val());
                    formData.append("form_admin_title", $('#form_admin_title').val());
                    formData.append("form_admin_status", $('#form_admin_status').val());
                    formData.append("form_admin_description", $('#form_admin_description').val());
                    $('#form_admin_languages input').each(function() { 
                        if($(this).is(':checked')) {
                            formData.append("form_admin_languages[]", $(this).val());
                            languagesSelected = true;
                        }
                    });
                    if(!languagesSelected) formData.append("form_admin_languages[]", ""); 
                    formData.append("form_admin_github", $('#form_admin_github').val());

                    $.ajax({
                        url: 'admin/php/admin_ajax.php', 
                        method: 'post', 
                        dataType: 'json', 
                        processData: false,
                        contentType: false,
                        data: formData, 
                        success: function() {
                            document.getElementById("form_administration_ajouter").reset();
                            majLienSite();
                            alert('Import effectué');
                        }
                    });
                }

                function afficherAdministration() {
                    if($('#form_admin_username').val() === "" || $('#form_admin_password').val() === "") {
                        if($('#form_admin_username').val() === "") $('#form_admin_username').css('border', '2px solid red');
                        if($('#form_admin_password').val() === "") $('#form_admin_password').css('border', '2px solid red');
                        return false;
                    }
                    $.ajax({
                        url: 'admin/php/admin_ajax.php', 
                        method: 'post', 
                        dataType: 'json', 
                        data: {
                            afficherAdministration: 1, 
                            admin_username: $('#form_admin_username').val(), 
                            admin_password: $('#form_admin_password').val()
                        }, 
                        success: function(r) {
                            if(r.success === 1) {
                                $('#modale_administration_ajouter .modal-body').html(r.message);
                                $('#modale_connexion_administration').modal('hide');
                                $('#modale_administration_ajouter').modal('show');
                                if($('#body').hasClass('night')) {
                                    $('#modale_administration_ajouter').find('.modal-content').addClass('night');
                                } else {
                                    $('#modale_administration_ajouter').find('.modal-content').addClass('day');
                                }
                            } else {
                                alert(r.message);
                                return false;
                            }
                        }
                    });
                }
            <?php } ?>
            
            function changerClassIcone(icone) {
                if(icone.hasClass('fa-angle-down')) {
                    icone.removeClass('fa-angle-down');
                    icone.addClass('fa-angle-up');
                } else {
                    icone.removeClass('fa-angle-up'); 
                    icone.addClass('fa-angle-down');
                }
            }
            
            function dayMode() {                
                var mode = $('#toggle--daynight').is(':checked');
                 if (mode === true) {
                    $('.icons-color').addClass('c-grey'); 
                    $('.icons-color').removeClass('c-whitesmoke'); 
                     
                    $('#footer').addClass('white');
                    $('#footer').removeClass('bg-dark');

                    $('#body').removeClass('night');
                    $('#body').addClass('day');

                    $('#navbar').addClass('navbar-light white');
                    $('#navbar').removeClass('navbar-dark bg-dark');

                    $('.card, #scrollUp, #scrollDown').addClass('bg-white');
                    $('.card, #scrollUp, #scrollDown').removeClass('bg-dark');
                    $('#scrollUp span').css('border-bottom-color', '#202020');
                    $('#scrollDown span').css('border-top-color', '#202020');
                    
                    $('#checkboxes label').css('color', '#202020');

                    $('a, h1, h2, code, span').addClass('black-text');
                    $('a, h1, h2, code, span').removeClass('white-text');
                    
                    $('#link_dynamic_host').addClass('black-text');
                    $('#link_dynamic_host').removeClass('white-text');                    
                    
                    $('#form_edit_languages label').addClass('black-text');
                    $('#form_edit_languages label').removeClass('white-text');                    
                    
                    $('#form_edit_img').addClass('black-text');
                    $('#form_edit_img').removeClass('white-text');
                    
                    if(window.chartStats !== undefined) {
                        window.chartStats.updateOptions({
                            theme: {
                                legend: {
                                    label: {
                                        color: '#000000'
                                    }
                                }, 
                                chart: {
                                    backgroundColor: 'rgba(255, 255, 255)'
                                }, 
                                xAxis: {
                                    title: {
                                        fontSize: 15,
                                        color: '#000000'
                                    },
                                    label: {
                                        fontSize: 11,
                                        color: '#000000'
                                    }
                                }, 
                                yAxis: {
                                    title: {
                                        fontSize: 15,
                                        color: '#000000'
                                    },
                                    label: {
                                        fontSize: 11,
                                        color: '#000000'
                                    }
                                }
                            }
                        });
                    }
                } else {
                    $('.icons-color').removeClass('c-grey');
                    $('.icons-color').addClass('c-whitesmoke'); 
                    
                    $('#footer').removeClass('white');
                    $('#footer').addClass('bg-dark');

                    $('#body').removeClass('day');
                    $('#body').addClass('night');

                    $('#navbar').removeClass('navbar-light white');
                    $('#navbar').addClass('navbar-dark bg-dark');

                    $('.card, #scrollUp, #scrollDown').removeClass('bg-white');
                    $('.card, #scrollUp, #scrollDown').addClass('bg-dark');
                    $('#scrollUp span').css('border-bottom-color', '#ffffff');
                    $('#scrollDown span').css('border-top-color', '#ffffff');
                    
                    $('#checkboxes label').css('color', 'white');
                    
                    $('a, h1, h2, code, span').removeClass('black-text');
                    $('a, h1, h2, code, span').addClass('white-text');
                    
                    $('#link_dynamic_host').removeClass('black-text');
                    $('#link_dynamic_host').addClass('white-text');
                    
                    $('#form_edit_languages label').removeClass('black-text');
                    $('#form_edit_languages label').addClass('white-text');
                    
                    $('#form_edit_img').removeClass('black-text');
                    $('#form_edit_img').addClass('white-text');
                    
                    if(window.chartStats !== undefined) {
                        window.chartStats.updateOptions({
                            theme: {
                                legend: {
                                    label: {
                                        color: '#FFFFFF'
                                    }
                                }, 
                                chart: {
                                    backgroundColor: 'rgba(24, 24, 24)'
                                }, 
                                xAxis: {
                                    title: {
                                        fontSize: 15,
                                        color: '#FFFFFF'
                                    },
                                    label: {
                                        fontSize: 11,
                                        color: '#FFFFFF'
                                    }
                                }, 
                                yAxis: {
                                    title: {
                                        fontSize: 15,
                                        color: '#FFFFFF'
                                    },
                                    label: {
                                        fontSize: 11,
                                        color: '#FFFFFF'
                                    }
                                }
                            }
                        });
                    }
                }
            }
            
            function afficherGraph() {
                $('#index_graph').toggleClass('hidden');
            }
            
            function activerRobot() {
                $.ajax({
                    url: 'Bot/index.php',
                    success: function() {
                        $("#toast").show();
                        $("#btnBot").hide();
                    }
                });
            }
        </script>
    </body>
</html>
