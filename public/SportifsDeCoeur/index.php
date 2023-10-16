<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

gestionStatistiques(14);

define('IS_PROD', false);
define('ADMIN', "admin");
define('PASSWD', "btsinfo");

$sdc_fichier_json = file_get_contents("assets/texte_a_afficher.json");
$sdc_fichier_decode_json = json_decode($sdc_fichier_json, true);
$last_news = json_decode($sdc_fichier_json, true)['index']['news'];

/*
 * Mise à jour JSON: mets à jour le JSON pour afficher certaines données de la page d'accueil
 */
if( filter_input(INPUT_POST, 'majJsonInformations', FILTER_VALIDATE_INT, array('options'=>array('default'=>0, 'min_range'=>0, 'max_range'=>1)) === 1) && 
    filter_input(INPUT_POST, 'form_admin_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES) === ADMIN && 
    filter_input(INPUT_POST, 'form_admin_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_FLAG_NO_ENCODE_QUOTES) === PASSWD
  ) {
    $html_body = '  <form id="form_admin_json">
                        <input type="hidden" id="form_admin_json_id" name="form_admin_json_id" value="'. uniqid().'" />
                        <div class="row">
                            <div id="output-json-hidden"></div>
                        </div>
                    </form>';
    
    $html_footer = '<button type="button" class="btn btn-primary" onclick="majJsonInformationsConfirm();" data-dismiss="modal">Sauvegarder</button>';
    
    die(json_encode(array(
        "success" => 1, 
        "html_body" => $html_body, 
        "html_footer" => $html_footer, 
        "json" => $sdc_fichier_json
    )));
} elseif(filter_input(INPUT_POST, 'majJsonInformations', FILTER_VALIDATE_INT, array('options'=>array('default'=>0, 'min_range'=>0, 'max_range'=>1)) === 1)) {
    die(json_encode(array(
        "error" => 1
    )));
}

if( filter_input(INPUT_POST, 'majJsonInformationsConfirm', FILTER_VALIDATE_INT, array('options'=>array('default'=>0, 'min_range'=>0, 'max_range'=>1)) === 1) && 
    filter_input(INPUT_POST, 'form_admin_json_id', FILTER_VALIDATE_INT) !== null
  ) {
    file_put_contents("assets/texte_a_afficher.json", $_POST['liste']);

    die('ok');
}

/*
 * Formulaire Contact: envoi du mail au destinataire
 */
if(filter_input(INPUT_POST, 'envoyerFormContacter', FILTER_VALIDATE_INT, array('options'=>array('default'=>0, 'min_range'=>0, 'max_range'=>1)) === 1)) {
    if(IS_PROD) {
        $email = '';
    } else {
        $email = 'marceau0707@gmail.com';
    }
    //Envoie d'un mail de confirmation//
    $header = "MIME-Version: 1.0\r\n";
    $header .= 'From:"www.sportifdecoeur.org"<no-reply@sportifdecoeur.org>' . "\n";
    $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
    $header .= 'Content-Transfer-Encoding: 8bit';

    $message = '
            <html>
                <head>
                    <meta charset="utf-8" />
                </head>
                <body>
                    <div style="text-align: center;">
                        '. filter_input(INPUT_POST, 'form_contact_email', FILTER_VALIDATE_EMAIL).'
                        <table>
                            <tbody>
                                <tr>
                                    <td>NOM:</td><td>'. filter_input(INPUT_POST, 'form_contact_nom', FILTER_SANITIZE_SPECIAL_CHARS).'</td>
                                    <td>Email:</td><td>'. filter_input(INPUT_POST, 'form_contact_email', FILTER_VALIDATE_EMAIL).'</td>
                                    <td>Téléphone:</td><td>'. filter_input(INPUT_POST, 'form_contact_tel', FILTER_SANITIZE_NUMBER_INT).'</td>
                                    <td>Message:</td><td>'. filter_input(INPUT_POST, 'form_contact_message', FILTER_SANITIZE_SPECIAL_CHARS).'</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </body>
            </html>';
    mail($email, 'Un utilisateur a une question...', $message, $header);
}
?>
<!DOCTYPE html> 
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Sportifs de Coeur | Accueil</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Your custom styles (optional) -->
        <link rel="stylesheet" href="css/style_index.css">
 
        <!-- Insert the ico site -->
        <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
        <link rel="manifest" href="img/site.webmanifest">
        
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
        <script src="js/jquery-3.6.0.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
        <!--<link rel="stylesheet" href="css/JSONedtr-dark.css">-->
        <link rel="stylesheet" href="css/JSONedtr.css">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
                <a class="navbar-brand ml-5" href="./index.php">
                    <img src="img/logoSDC.png" style="max-height: 70px; max-width: 70px;" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" href="#navbar_sdc">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar_sdc" role="tablist">
                    <ul class="nav mb-3 mr-auto" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="nav-home-tab" data-toggle="pill" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Accueil</a>
                        </li>
                        <li class="nav-item dropdown" role="presentation">
                            <a class="nav-link dropdown-toggle" id="nav-run-tab" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">La course du coeur</a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="tab" href="#nav-run-2022-tab">2022</a>
                                <a class="dropdown-item" data-toggle="tab" href="#nav-run-2019-tab">2019</a>
                                <a class="dropdown-item" data-toggle="tab" href="#nav-run-2018-tab">2018</a>
                            </div>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="nav-projects-tab" data-toggle="tab" href="#nav-projects" role="tab" aria-controls="nav-projects" aria-selected="false">Défis et projets</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="nav-partners-tab" data-toggle="tab" href="#nav-partners" role="tab" aria-controls="nav-partners" aria-selected="false">Partenaires</a>
                        </li>
                    </ul>
                    <ul class="nav mb-3 justify-content-end" >
                        <?php if(filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP) === "10.239.19.226" || 
                                filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP) === "fd42:1b19:67cc:abcb:216:3eff:fe7b:a5f5") { ?> 
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#" onclick="$('#admin_modal').modal('show');">Administration</a>
                        </li>
                        <?php } ?>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="img/course_du_coeur/mentions_legales.pdf" target="_blank">Mentions Légales</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="img/course_du_coeur/reglement_interieur.pdf" target="_blank">Règlement Intérieur</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="#" onclick="$('#contact_modal').modal('show');">Contact</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="container-fluid" style="padding-top: 110px;">
            <div class="row">
                <div class="col-sm-12 col-md-3">
                    <div role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="false">
                        <div class="toast-header">
                            <img src="img/logoSDC.png" style="width: 50px; height: 50px;" class="rounded mr-2" alt="Logo de l'association Sportifs de Coeur">
                            <strong class="mr-auto"><?=($last_news['title'])?></strong>
                            <!--<small>11 mins ago</small>-->
                            <button type="button" class="ml-2 mb-1 close hidden" data-dismiss="toast" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="toast-body">
                            <?=($last_news['body'])?><br/>
                            <b class="sdc-key-words"><?=($last_news['footer'])?></b>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6">
                    <div class="main-content">
                        <div class="tab-content" id="pills-tabContent">
                            <!-- Onglet accueil -->
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-10 col-md-8 text-justify">
                                            <h2 class="sdc-title-border">L'origine</h2>
                                            <p>Association Loi 1901, créée en novembre 2011, 
                                                SPORTIFS DE CŒUR, c'est avant tout une belle 
                                                histoire d'Amitié qui a rassemblé, au fil des 
                                                années, des personnes de tous horizons et de 
                                                tous âges !  
                                                Amitié et immense envie de faire quelque 
                                                chose ensemble, pour une cause qui nous 
                                                touche de près : Les enfants malades.
                                            </p>
                                            <p>De là est né : <span class="sdc-key-words" style="font-weight: 600;">SPORTIFS DE CŒUR</span>, qui 
                                                rassemble chaque année depuis 2012, plus de 
                                                400 personnes pour la <span class="sdc-key-words">Course du Coeur</span> qui 
                                                se déroule à Béziers, le long du canal du 
                                                midi, chaque 2ème dimanche de septembre, et 
                                                dont les bénéfices sont intégralement 
                                                reversés pour les enfants malades, à travers 
                                                des actions phares auprès notamment du 
                                                service Pédiatrique du centre Hospitalier de 
                                                Béziers, mais aussi de nombreuses autres 
                                                associations avec lesquelles nous partageons 
                                                valeurs et objectifs pour les enfants.
                                            </p>
                                        </div>
                                        <div class="col-sm-2 col-md-4">
                                            <img src="img/depart_course_du_coeur.jpg" class="mt-5 rounded img-thumbnail" alt="Départ de la 1ère course du Coeur en septembre 2012" />
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2 class="sdc-title-border">Présentation du Bureau</h2>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">Frédéric DOMPER</h3>
                                                        <p class="card-text">Responsable SAV<span style="float: right;"><img src="img/bureau/frederic_domper.png" style="width: 120px; height: 110px;" class="card-img-top rounded img-thumbnail" alt="Photo de Frédéric DOMPER"></span></p>
                                                        <p class="card-text"><small class="text-muted">Président</small></p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">Béatrice COLIN</h3>
                                                        <p class="card-text">Assistante de Direction Immobilier et Expertise Judiciaire<span style="float: right;"><img src="img/bureau/beatrice_colin.png" style="width: 120px; height: 110px;" class="card-img-top rounded img-thumbnail" alt="Photo de Béatrice COLIN"></span></p>
                                                        <p class="card-text"><small class="text-muted">Vice-Présidente</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px;">
                                            <div class="card-deck">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">Nathalie LIGNON</h3>
                                                        <p class="card-text">Maître de cérémonie funéraire<span style="float: right;"><img src="img/bureau/nathalie_lignon.png" style="width: 120px; height: 110px;" class="card-img-top rounded img-thumbnail" alt="Photo de Nathalie LIGNON"></span></p>
                                                        <p class="card-text"><small class="text-muted">Trésorière</small></p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">Caroline LAGARDE</h3>
                                                        <p class="card-text">Photographe - <a href="//www.facebook.com/LESIMAGESDECARO/" target="_blank" style="color: #007bff;"><i class="fab fa-facebook"></i>&nbsp;Les Images de Caro</a><span style="float: right;"><img src="img/bureau/caroline_lagarde.png" style="width: 120px; height: 110px;" class="card-img-top rounded img-thumbnail" alt="Photo de Caroline LAGARDE"></span></p>
                                                        <p class="card-text"><small class="text-muted">Trésorière Adjointe</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12" style="margin-top: 10px;">
                                            <div class="card-deck">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">Claudine AMBIT</h3>
                                                        <p class="card-text">Professeur des Ecoles<span style="float: right;"><img src="img/bureau/claudine_ambit.png" style="width: 120px; height: 110px;" class="card-img-top rounded img-thumbnail" alt="Photo de Claudine AMBIT"></span></p>
                                                        <p class="card-text"><small class="text-muted">Secrétaire</small></p>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">Julie MALMONTE</h3>
                                                        <p class="card-text">Coiffeuse<span style="float: right;"><img src="img/bureau/julie_malmonte.png" style="width: 120px; height: 110px;" class="card-img-top rounded img-thumbnail" alt="Photo de Julie MALMONTE"></span></p>
                                                        <p class="card-text"><small class="text-muted">Secrétaire Adjointe</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6" style="margin-top: 10px;">
                                            <div class="card-deck">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <h3 class="card-title">François RAMONEDA</h3>
                                                        <p class="card-text"><small class="text-muted">Parrain</small><span style="float: right;"><img src="img/bureau/francois_ramoneda.jpg" class="card-img-top rounded" style="width: 200px; height: 110px;" alt="François RAMONEDA, Parrain de Sportifs de Coeur" /></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin onglet accueil -->

                            <!-- Onglet course du coeur depuis 2022 -->
                            <div class="tab-pane fade" id="nav-run-2022-tab" role="tabpanel" aria-labelledby="nav-run-2022-tab">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <h2 class="text-run-title">La course du coeur 2022</h2>
                                        </div>
                                        <div class="col-sm-12 col-md-12 alert-warning text-center" style="margin: 15px 0px;">
                                            <p>Ouverture des inscriptions à partir de 9H dans le stade de Sauclières.</p>
                                            <p><b class="sdc-key-words">TOP DEPART COULEUR</b> : Lunettes obligatoires<br/><b class="sdc-key-words">Offerte aux enfants</b> (en vente sur place)<br/>1 tee shirt <b class="sdc-key-words">offert</b> à chaque coureur</p>
                                            <p><b class="sdc-key-words">COURSE</b> : 10 € - Gratuit pour les enfants de moins de 13 ans<br/><b class="sdc-key-words">REPAS APRES COURSE 10 €</b> : Inscription sur place<br/><span style="color: #631919;">(réservation conseillée : <a href="mailto:beacolin34@gmail.com">ici</a>)</span></p>
                                            <p><b class="sdc-key-words">MERCI A NOS SPONSORS !</b></p>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <img class="rounded img-thumbnail" src="img/course_du_coeur/2022/tract_9_eme_course.jpg" alt="Tract de la course 2022" />
                                        </div>
                                        <div class="col-sm-12 col-md-6 text-center">
                                            <img class="rounded img-thumbnail" src="img/course_du_coeur/2022/parcours_course_du_coeur_2022.jpg" alt="Carte parcours de la course 2022" />
                                            <a class="btn btn-primary" style="margin-top: 10px;" href="img/course_du_coeur/2022/parcours_course_du_coeur_2022.pdf" target="_blank"><i class="fas fa-map"></i>&nbsp;Télécharger</a>
                                            <a class="btn btn-primary" style="margin-top: 10px;" href="img/course_du_coeur/reglement_course_du_coeur.pdf" target="_blank"><i class="fas fa-gavel"></i>&nbsp;Règlement de la course</a>
                                            <hr/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin onglet course du coeur depuis 2022 -->

                            <!-- Onglet course du coeur depuis 2019 -->
                            <div class="tab-pane fade" id="nav-run-2019-tab" role="tabpanel" aria-labelledby="nav-run-2019-tab">
                                <div class="container">
                                    <div class="row text-center">
                                        <div class="col-sm-12 text-center">
                                            <h2 class="text-run-title">La course du coeur 2019</h2>
                                        </div>
                                        <div class="col-sm-12 col-md-12" style="padding-top: 15px;">
                                            <p class="alert-warning">Vous avez été plus de 500 au départ du stade de Sauclières.<br/>
                                                <b class="sdc-key-words">BRAVO</b> et <b class="sdc-key-words">MERCI à TOUS</b>
                                            </p>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <img class="rounded img-thumbnail" src="img/course_du_coeur/2019/tract_8_eme_course.png" alt="Tract de la course 2019" />
                                        </div>
                                        <div class="col-sm-12 col-md-6 text-center">
                                            <img class="rounded img-thumbnail" src="img/course_du_coeur/2019/parcours_course_du_coeur_2019.jpg" alt="Carte parcours de la course 2019" />
                                            <a class="btn btn-primary" style="margin-top: 10px;" href="img/course_du_coeur/2019/parcours_course_du_coeur_2019.pdf" target="_blank"><i class="fas fa-map"></i>&nbsp;Télécharger</a>
                                            <hr/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin onglet course du coeur depuis 2019 -->
                            
                            <!-- Onglet course du coeur depuis 2018 -->
                            <div class="tab-pane fade" id="nav-run-2018-tab" role="tabpanel" aria-labelledby="nav-run-2018-tab">
                                <div class="container">
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <h2 class="text-run-title">La course du coeur 2018</h2>
                                        </div>
                                        <div class="col-sm-12 col-md-12" style="padding-top: 15px;">
                                            <p class="alert-warning">Vous avez été plus de 300 au départ du stade de Sauclières.<br/>
                                                <b class="sdc-key-words">BRAVO</b> et <b class="sdc-key-words">MERCI à TOUS</b>
                                            </p>
                                        </div>
                                        <div class="col-sm-12 col-md-6" style="padding-top: 15px;">
                                            <div class="text-run-content">
                                                <figure>
                                                    <img class="rounded img-thumbnail" src="img/course_du_coeur/2018/tract_7_eme_course.png" alt="Tract pour la 7ème Course du coeur">
                                                    <figcaption>Tract pour la 7ème Course du coeur</figcaption>
                                                </figure>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6" style="padding-top: 15px;">
                                            <div class="text-run-content">
                                                <figure>
                                                    <img class="rounded img-thumbnail" src="img/course_du_coeur/2018/midi_libre_couverture.jpg" alt="Page de couverture Midi Libre du 10 Sept. 2018">
                                                    <figcaption>Page de couverture Midi Libre du 10 Sept. 2018</figcaption>
                                                </figure>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <img style="height: 200px; width: 250px;" src="img/course_du_coeur/2018/depart_couleur_2018.jpg" alt="Image n°1 du départ de la course de 2018">
                                            <img style="height: 200px; width: 250px;" src="img/course_du_coeur/2018/depart_couleur_2018_2.jpg" alt="Image n°1 du départ de la course de 2018">
                                            <img style="height: 200px; width: 250px;" src="img/course_du_coeur/2018/depart_couleur_2018_3.jpg" alt="Image n°1 du départ de la course de 2018">
                                            <img style="height: 200px; width: 250px;" src="img/course_du_coeur/2018/remise_2018.jpg" alt="Remise du trophé de la course de 2018">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin onglet course du coeur depuis 2018   -->
                            
                            <!-- Onglet défis et projets -->
                            <div class="tab-pane fade" id="nav-projects" role="tabpanel" aria-labelledby="nav-projects-tab">
                                <div class="container">
                                    <div class="row text-center">
                                        <div class="col-sm-12">
                                            <h2><a href="#" data-toggle="tooltip" data-placement="left" title="Sur la période 2017-2018">2017-2018</a></h2>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <h6 class="text-defi-title">Nouveau projet pour les nouvelles urgences pédiatriques avec L'Ecole Notre Dame Saint Pierre de Béziers</h6>
                                                </div>
                                                <div class="col-sm-12 col-md-8 text-center">
                                                    <p class="text-defi-content">C'est avec les enfants de l'Ecole NOTRE DAME SAINT PIERRE de Béziers, que nous avons choisi le défi 2017/2018 : 
                                                            UNE NOUVELLE AIRE POUR LE SERVICE D'URGENCES PEDIATRIQUES de L'HOPITAL DE BEZIERS. L'équipe pédagogique a organisé en Mai 2018 
                                                            et Mai 2019 une journée de la solidarité, et les enfants de maternelle et de primaire de l'Ecole ont vendu des mains et des 
                                                            pieds qu'ils avaient dessinés et peint en classe. Pourquoi ? Pour symboliser la solidarité et l'entraide. Leurs efforts ont 
                                                            permis de recueillir 5000 € qu'ils ont offert à SPORTIFS DE COEUR !<br/>
                                                        Une action qui nous a tous émus aux larmes tant c'est magique !<br/>
                                                        C'est donc avec eux que SPORTIFS DE COEUR a choisi ce nouveau défi : Les nouvelles urgences pédiatriques de l'hôpital de BEZIERS !</p>
                                                </div>
                                                <div class="col-sm-12 col-md-4">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2018/resultat_7_eme_course.png" alt="Images de la journée de la 7ème course du coeur">
                                                    </figure>
                                                </div>
                                                <div class="col-sm-7">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2018/message_pour_les_enfants.png" alt="Message pour les enfants" />
                                                    </figure>
                                                </div>
                                                <div class="col-sm-5" style="margin-top: 10vh;">
                                                    <figure>
                                                        <a href="//www.ecolendspbeziers.toutemonecole.fr" target="_blank"><img class="rounded img-thumbnail" src="img/logoNDSP.svg" alt="Logo Ecole Notre Dame-Saint Pierre" /></a>
                                                    </figure>
                                                </div>
                                                <div class="col-sm-7">
                                                    <div id="carouselCeremonie" class="carousel slide" data-ride="carousel">
                                                        <ol class="carousel-indicators">
                                                            <li data-target="#carouselCeremonie" data-slide-to="0" class="active"></li>
                                                            <li data-target="#carouselCeremonie" data-slide-to="1"></li>
                                                            <li data-target="#carouselCeremonie" data-slide-to="2"></li>
                                                        </ol>
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item active">
                                                                <img class="d-block w-100" src="img/course_du_coeur/2018/ceremonie_01.jpg" alt="Image n°1 de la Cérémonie de 2018">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img class="d-block w-100" src="img/course_du_coeur/2018/ceremonie_02.jpg" alt="Image n°2 de la Cérémonie de 2018">
                                                            </div>
                                                            <div class="carousel-item">
                                                                <img class="d-block w-100" src="img/course_du_coeur/2018/ceremonie_03.jpg" alt="Image n°3 de la Cérémonie de 2018">
                                                            </div>
                                                        </div>
                                                        <a class="carousel-control-prev" href="#carouselCeremonie" role="button" data-slide="prev">
                                                            <span class="carousel-control-prev-icon" data-toggle="tooltip" data-placement="left" title="Précédent" aria-hidden="true"></span>
                                                            <span class="sr-only">Précédent</span>
                                                        </a>
                                                        <a class="carousel-control-next" href="#carouselCeremonie" role="button" data-slide="next">
                                                            <span class="carousel-control-next-icon" data-toggle="tooltip" data-placement="right" title="Suivant" aria-hidden="true"></span>
                                                            <span class="sr-only">Suivant</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2018/midi_libre_couverture.jpg" alt="Page de couverture Midi Libre du 10 Sept. 2018" />
                                                        <figcaption>Page de couverture Midi Libre du 10 Sept. 2018</figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <hr/>
                                        </div>
                                        <div class="col-sm-12">
                                            <h2><a href="#" data-toggle="tooltip" data-placement="left" title="Sur la période 2015-2016">2015-2016</a></h2>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <p class="text-defi-status">Défi n°2 validé&nbsp;<i class="fas fa-check-circle" style="color: #08C373;"></i></p>
                                                    <h6>Inauguration de l'aire de jeux aux urgences pédiatriques de l'hôpital de Béziers</h6>
                                                    <p class="text-defi-content">Résultat concret de 2 Courses du Coeur !</p>
                                                </div>
                                                <div class="col-sm-12 col-md-6 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2016/inauguration_aire_de_jeux_ch_beziers.jpg" alt="Inauguration d'un nouvel Aire de Jeux à aux urgences de Béziers">
                                                        <figcaption>Inauguration d'un nouvel Aire de Jeux à aux urgences de Béziers</figcaption>
                                                    </figure>
                                                </div>
                                                <div class="col-sm-12 col-md-6 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2016/projet_2_resume.jpg" alt="Résumé du projet 2 accompagné d'une maquette">
                                                    </figure>
                                                </div>
                                            </div>
                                            <hr/>
                                        </div>
                                        <div class="col-sm-12">
                                            <h2><a href="#" data-toggle="tooltip" data-placement="left" title="Sur la période 2014-2015">2014-2015</a></h2>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12 text-center">
                                                    <h6>Récolte de fonds pour l'association «&nbsp;Autrement classe&nbsp;»</h6>
                                                </div>
                                                <div class="col-sm-12 col-md-4 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/logoAFM.jpg" alt="Logo de l'AFM Téléthon Béziers">
                                                        <figcaption>500 € pour l'AFM TELETHON BEZIERS</figcaption>
                                                    </figure>
                                                </div>
                                                <div class="col-sm-12 col-md-4 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2015/cheque_autrement_classe.jpg" alt="Chèque distribué à l'association Autrement Classe">
                                                    </figure>
                                                </div>
                                                <div class="col-sm-12 col-md-4 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2015/carte_de_visite_autrement_classe.jpg" alt="Carte de visite de l'école privée Autrement Classe">
                                                        <figcaption>1500 € pour l'école privée AUTREMENT CLASSE en 2015 et 2016</figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <hr/>
                                        </div>
                                        <div class="col-sm-12">
                                            <h2><a href="#" data-toggle="tooltip" data-placement="left" title="Sur la période 2012-2013">2012-2013</a></h2>
                                            <div class="row">
                                                <div class="col-sm-12 text-center">
                                                    <p class="text-defi-status">Défi n°1 validé&nbsp;<i class="fas fa-check-circle" style="color: #08C373;"></i></p>
                                                    <h6>Distribution de tablettes équipées pour toutes les chambres du service pédiatrie de l'hôpital de Béziers</h6>
                                                    <p class="text-defi-content">15 tablettes numériques entièrement équipées en logiciels ont pu être remises au <span class="sdc-key-words">SERVICE DES URGENCES PEDIATRIQUES</span> de l'Hôpital de BEZIERS, équipant ainsi toutes les chambres de pédiatrie d'une tablette numérique.</p>
                                                </div>
                                                <div class="col-sm-12 col-md-4 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2012/tablettes.jpg" alt="Tablettes numériques">
                                                        <figcaption>Tablettes numériques</figcaption>
                                                    </figure>
                                                </div>
                                                <div class="col-sm-12 col-md-4 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2012/midi_libre_couverture.png" alt="Article Midi Libre du 12 Janv. 2013">
                                                        <figcaption>Article Midi Libre du 12 Janv. 2013</figcaption>
                                                    </figure>
                                                </div>
                                                <div class="col-sm-12 col-md-4 text-center">
                                                    <figure>
                                                        <img class="rounded img-thumbnail" src="img/course_du_coeur/2012/illustration_01.jpg" alt="Image d'un patient avec la tablette en main">
                                                        <figcaption>Image d'un patient avec la tablette en main</figcaption>
                                                    </figure>
                                                </div>
                                            </div>
                                            <hr/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin onglet défis et projets -->
                            
                            <!-- Onglet partenaires -->
                            <div class="tab-pane fade" id="nav-partners" role="tabpanel" aria-labelledby="nav-partners-tab">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h2>Nos sponsors et partenaires</h2>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck text-center p-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoVilleBeziers.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Blason de la ville de Béziers"></p>
                                                        <h6 class="card-title">Association Sportive Béziers Hérault</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoBCAM.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Béziers Cheminots Athlétisme Méditérannée"></p>
                                                        <h6 class="card-title">Béziers Cheminots Athlétisme Méditérannée</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoMRAS.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Montagnac Radio Assistance Sécurité"></p>
                                                        <h6 class="card-title">Montagnac Radio Assistance Sécurité</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck text-center p-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoASBH.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Association Sportive Béziers Hérault"></p>
                                                        <h6 class="card-title">Association Sportive Béziers Hérault</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoNDSP.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Ecole Notre Dame-Saint Pierre"></p>
                                                        <h6 class="card-title">Ecole Notre Dame-Saint Pierre</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoAuchanDrive.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Auchan Drive"></p>
                                                        <h6 class="card-title">Auchan Drive</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck text-center p-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoDHL.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo DHL"></p>
                                                        <h6 class="card-title">DHL</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoCombesHund.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Combes et Hund"></p>
                                                        <h6 class="card-title">Combes & Hund</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoHugolAndCie.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Hugol et Compagnie"></p>
                                                        <h6 class="card-title">Hugol & Compagnie</h6>
                                                        <h6 class="card-title text-muted">Fruits et primeurs</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck text-center p-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoAmbulancesEclair.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Ambulances Eclair"></p>
                                                        <h6 class="card-title">Ambulances Eclair</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoCHBeziers.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Centre Hospitalier Béziers"></p>
                                                        <h6 class="card-title">Centre Hospitalier Béziers</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoAutocollantCross.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Pins du CROSS du collège 2013"></p>
                                                        <h6 class="card-title">Pins du CROSS du collège 2013</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck text-center p-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoVNF.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Voies Navigables de France"></p>
                                                        <h6 class="card-title">Voies Navigables de France</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoTCS.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Tennis Club de Sauvian"></p>
                                                        <h6 class="card-title">Tennis Club de Sauvian</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoRCB.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Radio Ciel Bleu 107.1"></p>
                                                        <h6 class="card-title">Radio Ciel Bleu 107.1</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card-deck text-center p-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoFondationOrange.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Fondation Orange"></p>
                                                        <h6 class="card-title">Fondation Orange</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoDecathlon.svg" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Décathlon"></p>
                                                        <h6 class="card-title">Décathlon</h6>
                                                    </div>
                                                </div>
                                                <div class="card">
                                                    <div class="card-body">
                                                        <p class="card-text"><img src="img/logoCitation.png" style="width: 200px; height: 150px;" class="card-img-top rounded img-thumbnail" alt="Logo Citation"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin onglet partenaires -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal" id="admin_modal">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Changer les informations de l'accueil</h4>
                        <i class="fas fa-times" style="cursor: pointer;" data-dismiss="modal">&nbsp;</i>
                    </div>
                    <div class="modal-body">
                        <form id="form_admin">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="form_admin_id">Identifiants administrateur</label>
                                        <input type="text" class="form-control" name="form_admin_id" id="form_admin_id" value="admin"/>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="form_admin_password">Mot de passe administrateur</label>
                                        <input type="text" class="form-control" name="form_admin_password" id="form_admin_password" value="btsinfo"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="document.getElementById('form_admin').reset();"><i class="fas fa-redo" style="cursor: pointer;"></i>&nbsp;Vider</button>
                        <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                        <button type="submit" form="form_admin" class="btn btn-primary" onclick="event.preventDefault(); majJsonInformations();"><i class="fas fa-paper-plane" style="cursor: pointer;"></i>&nbsp;Envoyer</button>
                    </div>
                </div>
            </div>
        </div>
                
        
        <div class="modal" id="contact_modal">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Contact</h4>
                        <i class="fas fa-times" style="cursor: pointer;" data-dismiss="modal">&nbsp;</i>
                    </div>
                    <div class="modal-body">
                        <div class="accordion" id="contact-accordeon">
                            <div class="card">
                                <div class="card-header" id="titre-form-contact">
                                    <h2 class="mb-0">
                                        <button class="btn btn-default btn-block btn-accordeon text-left" type="button" data-toggle="collapse" data-target="#contact-accordeon-form" aria-expanded="true" aria-controls="contact-accordeon-form" onclick="changerIcone($(this).find('i'));">
                                            Afficher le formulaire de contact
                                            <i class="fas fa-chevron-down" style="float: right;"></i>
                                        </button>
                                    </h2>
                                </div>
                                
                                <div id="contact-accordeon-form" class="collapse show" aria-labelledby="titre-form-contact" data-parent="#contact-accordeon">
                                    <div class="card-body">
                                        <form id="form_contact" name="form_contact" method="post" action="./index.php">
                                            <input type="hidden" name="form_contact_envoye" id="form_contact_envoye" value="1" />
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <label for="form_contact_nom">NOM Prénom</label>
                                                        <input type="text" class="form-control" name="form_contact_nom" id="form_contact_nom" />
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label for="form_contact_email">Email*</label>
                                                        <input type="email" class="form-control" name="form_contact_email" id="form_contact_email" required />
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label for="form_contact_tel">Téléphone</label>
                                                        <input type="tel" class="form-control" name="form_contact_tel" id="form_contact_tel" />
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <label for="form_contact_message">Votre message*</label>
                                                        <textarea rows="7" class="form-control" name="form_contact_message" id="form_contact_message"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button type="button" class="btn btn-default" onclick="document.getElementById('form_contact').reset();"><i class="fas fa-redo" style="cursor: pointer;"></i>&nbsp;Vider</button>
                                        <button type="button" class="btn btn-primary" onclick="envoyerFormContacter();"><i class="fas fa-paper-plane" style="cursor: pointer;"></i>&nbsp;Envoyer</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="titre-informations-contact">
                                    <h2 class="mb-0">
                                        <button class="btn btn-default btn-block btn-accordeon text-left" type="button" data-toggle="collapse" data-target="#contact-accordeon-informations" aria-expanded="false" aria-controls="contact-accordeon-informations" onclick="changerIcone($(this).find('i'));">
                                            Afficher les informations de contact
                                            <i class="fas fa-chevron-up" style="float: right;"></i>
                                        </button>
                                    </h2>
                                </div>
                                
                                <div id="contact-accordeon-informations" class="collapse" aria-labelledby="titre-informations-contact" data-parent="#contact-accordeon">
                                    <div class="card-body">
                                        <p><q cite="//evene.lefigaro.fr/citation/bon-combat-engage-parce-coeur-demande-64847.php"> Le bon combat est celui engagé parce que notre coeur le demande. </q> - Paulo COELHO</p>
                                        <div class="row text-center">
                                            <div class="col-sm-12 col-md-4">
                                                <img style="width: inherit;" src="img/course_du_coeur/2017/preview_brochure_sportifs_de_coeur_2017.png" alt="Visuel de la brochure de l'année 2017" class="rounded">
                                                <a class="btn btn-light mt-3 mb-3" href="img/course_du_coeur/2017/brochure_sportifs_de_coeur_2017.pdf" target="_blank" role="button">Voir la brochure</a>
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <img style="width: inherit;" src="img/course_du_coeur/preview_bulletin_adhesion.png" alt="Visuel du bulletin d'adhésion" class="rounded">
                                                <a class="btn btn-light mt-3 mb-3" href="img/course_du_coeur/bulletin_adhesion.pdf" target="_blank" role="button">Voir le bulletin d'adhésion</a>
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <img style="width: inherit;" src="img/course_du_coeur/preview_statuts.png" alt="Visuel des statuts de l'association" class="rounded">
                                                <a class="btn btn-light mt-3 mb-3" href="img/course_du_coeur/statuts.pdf" target="_blank" role="button">Voir les statuts</a>
                                            </div>
                                        </div>
                                        
                                        <hr/>
                                        
                                        <h4>ADRESSE POSTALE&nbsp;<a href="https://www.google.com/maps/search/ASSOCIATION+SPORTIFS+DE+COEUR+Beziers" target="_blank"><i class="fas fa-map-marked-alt"></i></a></h4>
                                        <p>ASSOCIATION SPORTIFS DE COEUR<br/>
                                           122 rue de la Crouzette<br/>
                                           34500 BEZIERS
                                        </p>
                                        
                                        <hr/>
                                        
                                        <h4>Contacts</h4>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <p>Frédéric DOMPER - Président<br/>
                                                    <a href="tel:+33623055209">06 23 05 52 09</a>
                                                </p>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <p>Béatrice COLIN - Vice-Présidente<br/>
                                                    <a href="tel:+33621645125">06 21 64 51 25</a>
                                                </p>
                                            </div>
                                        </div>
                                                                                                                        
                                        <hr/>
                                        
                                        <h4>Par email</h4>
                                        <p><a href="mailto:sportifdecoeur@sfr.fr">sportifdecoeur@sfr.fr</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
        
        
        <footer class="page-footer font-small pt-4" id="footer">
            <hr/>
            <!-- Social buttons -->
            <ul class="list-unstyled list-inline text-center">
                <li class="list-inline-item">
                    <p><q cite="//evene.lefigaro.fr/citation/bon-combat-engage-parce-coeur-demande-64847.php">&nbsp;Le bon combat est celui engagé parce que notre coeur le demande&nbsp;</q> Paulo COELHO</p>
                </li>
            </ul>
            <!-- Social buttons -->
  
            <!-- Copyright -->
            <div class="footer-copyright text-center py-3">
                © <a href="<?=filter_input(INPUT_SERVER,'HTTP_REFERER')?>">Association Loi 1901 - Sportifs de Coeur</a> - Reproduction interdite - 2012-<?php echo date("Y"); ?>
            </div>
            <!-- Copyright -->

        </footer>

        <!-- Début scripts-->
        <script src="js/JSONedtr.js"></script>
        <script>
            $(document).ready(function() {
                $('.toast').toast('show', true, 300);
                $('[data-toggle="tooltip"]').tooltip();
                $('.carousel').carousel({
                    interval: 3500
                });
                $('.nav-item a').on('click', function() {
                    window.scrollTo(0, 0);
                    document.title = "Sportifs de Coeur | " + $(this).text();
                });
            });
            
            function changerIcone(icone) {
                if($(icone).hasClass('fa-chevron-down')) {
                    $(icone).removeClass('fa-chevron-down');
                    $(icone).addClass('fa-chevron-up');
                } else {
                    $(icone).removeClass('fa-chevron-up');
                    $(icone).addClass('fa-chevron-down'); 
                }
            }
            
            function majJsonInformations() {
                var form_non_valide = false;
                if($('#form_admin_id').val() === '') {
                    $('#form_admin_id').css('border', '2px solid red');
                    form_non_valide = true;
                } else {
                    $('#form_admin_id').css('border', '2px solid #ddd');
                }
                
                if($('#form_admin_password').val() === '') {
                    $('#form_admin_password').css('border', '2px solid red');
                    form_non_valide = true;
                } else {
                    $('#form_admin_password').css('border', '2px solid #ddd');
                }
                
                if(form_non_valide) return false;
                
                var formData = 'majJsonInformations=1&'+$('#form_admin').serialize();
                
                $.ajax({
                   url: 'index.php', 
                   data: formData,
                   method: 'post', 
                   dataType: 'json', 
                   success: function(r) {
                        if(r.success) {
                            document.getElementById('form_admin').reset();
                            $('#form_admin').css('display', 'none');

                            $('#admin_modal .modal-body').html(r.html_body);
                            $('#admin_modal .modal-footer').html(r.html_footer);
                            if(window.liste !== undefined) window.liste = undefined;
                            window.liste = new JSONedtr(r.json, '#output-json-hidden');
                            $('#output-json-hidden').css('outline', '1px solid black');                            
                        }
                   }, 
                   error: function(r) {
                      alert('Une erreur s\'est produite.'); 
                   }
                });
            }
            
            function majJsonInformationsConfirm() {
                var formData = 'majJsonInformationsConfirm=1&'+ $('#form_admin_json').serialize() +'&liste='+window.liste.getDataString();
                
                $.ajax({
                   url: 'index.php', 
                   data: formData,
                   method: 'post', 
                   dataType: 'html', 
                   success: function(r) {
                       if(r === 'ok') {
                            document.getElementById('form_admin_json').reset();
                            $('#form_admin_json').modal('hide');
                            window.location.reload();
                       }
                   }
                });
            }
            
            function envoyerFormContacter() {
                var formData = 'envoyerFormContacter=1&'+$('#form_contact').serialize();
                
                $.ajax({
                   url: 'index.php', 
                   data: formData,
                   method: 'post', 
                   dataType: 'json', 
                   success: function() {
                       document.getElementById('form_contact').reset();
                   }
                });
            }
        </script>
        <!-- Fin scripts -->        
    </body>
</html>
