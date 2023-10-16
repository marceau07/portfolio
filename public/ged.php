<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

//if(!empty($allowed_ips) && !in_array($ip_index_address, $allowed_ips)) {
//    header("Location: index.php");
//}

session_reset();
session_start();

if(!empty($_POST['modifierFichier'])) {
    $sCheminFichier = filter_input(INPUT_POST, 'sCheminFichier', FILTER_DEFAULT);
    $sContenuFichier = $_POST['sContenuFichier'];
    if(is_writable(LIEN_RACINE.'/'.$sCheminFichier)) {
        $fp = fopen(LIEN_RACINE.'/'.$sCheminFichier, "w"); 
        ftruncate($fp, 0);
        fputs($fp, $sContenuFichier);
        fclose($fp);
        die('ok');
    } else {
        die('pas ok');
    }
}

if(!empty($_POST['afficherModaleComparateur'])) {
	$sLienFichier = str_replace(array('//', 'public/', 'recette/'), array('/', '', ''), $_POST['sLienFichier']);
	$sNomFichier = str_replace(array('//', 'public/', 'recette/'), array('/', '', ''), $_POST['sNomFichier']);

    if($_POST['bChangerDroits'] === "false" && $_POST['bCreerFichier'] === "false") {
        if(file_exists(LIEN_RECETTE.$sLienFichier) && file_exists(LIEN_PROD.$sLienFichier)) {
            if(is_writable(LIEN_RECETTE.$sLienFichier) && is_writable(LIEN_PROD.$sLienFichier)) {
                $fichier_recette = shell_exec("cat ".LIEN_RECETTE.$sLienFichier);
                $fichier_recette_type = getLanguageFromMimeType(mime_content_type(LIEN_RECETTE.$sLienFichier));
                $fichier_prod = shell_exec("cat ".LIEN_PROD.$sLienFichier);
                $fichier_prod_type = getLanguageFromMimeType(mime_content_type(LIEN_PROD.$sLienFichier));
                if($fichier_recette == null) {
                    $fichier_recette = '';
                }
                if($fichier_prod == null) {
                    $fichier_prod = '';
                }

                die(json_encode(array(
                    "reload" => false,
                    "success" => true,
                    "fichier_recette" => $fichier_recette, 
                    "fichier_recette_type" => $fichier_recette_type, 
                    "fichier_prod" => $fichier_prod,
                    "fichier_prod_type" => $fichier_prod_type, 
                )));
            } else {
                die(json_encode(array(
                    "reload" => false,
                    "success" => false,
                    "icone" => '<i class="fas fa-exclamation-triangle"></i>', 
                    "message" => "Un des deux fichiers n'est pas accessible en écriture.", 
                    "suggestion" => '<a href="#" onclick="afficherModaleComparateur(\''.$_POST['sLienFichier'].'\', \''.$_POST['sNomFichier'].'\', true, false);">Cliquez-ici pour changer les droits des fichiers.</a>'
                )));
            }
        }

        die(json_encode(array(
            "reload" => false,
            "success" => false,
            "icone" => '<i class="fas fa-exclamation-triangle"></i>', 
            "message" => "Un des deux fichiers n'existe pas.", 
            "suggestion" => '<a href="#" onclick="afficherModaleComparateur(\''.$_POST['sLienFichier'].'\', \''.$_POST['sNomFichier'].'\', false, true);">Cliquez-ici pour le créer.</a>'
        )));
    } else {
        if($_POST['bChangerDroits'] === "true") {
            if(is_writable(LIEN_RECETTE.$sLienFichier) !== true) {
                shell_exec('sudo chmod 766 '.LIEN_RECETTE.$sLienFichier);
            } 
            if(is_writable(LIEN_PROD.$sLienFichier) !== true) {
                shell_exec('sudo chmod 766 '.LIEN_PROD.$sLienFichier);
            }
        } 
        if($_POST['bCreerFichier'] === "true") {       
			if(file_exists(LIEN_RECETTE.$sLienFichier) === false) {
                mkdir(LIEN_RECETTE.$sLienFichier, 0777, true);
                touch(LIEN_RECETTE.$sLienFichier);
                shell_exec('sudo chown -hR login4017:login4017 '.LIEN_RECETTE.$sLienFichier);
                shell_exec('sudo chmod 766 '.LIEN_RECETTE.$sLienFichier);
            }
            if(file_exists(LIEN_PROD.$sLienFichier) === false) {
                mkdir(LIEN_PROD.$sNomFichier.$sLienFichier, 0777, true);
                touch(LIEN_PROD.$sLienFichier);
                shell_exec('echo "" > '.LIEN_PROD.$sLienFichier);
                shell_exec('sudo chown -hR login4017:login4017 '.LIEN_PROD.$sLienFichier);
                shell_exec('sudo chmod 766 '.LIEN_PROD.$sLienFichier);
            }
        }
        die(json_encode(array(
            "reload" => true
        )));
    }
}

if(!empty($_POST['genererTousLesManquants'])) {
    /**
     * Génération des dossiers
    **/
    foreach($_SESSION['fichiersManquants']['recette']['dossiers'] as $value) {
        if (!mkdir(LIEN_RECETTE. $value, 0777, true)) {
            die('Échec lors de la création du dossier de recette...');
        }
    }
    foreach($_SESSION['fichiersManquants']['public']['dossiers'] as $value) {
        if (!mkdir(LIEN_PROD. $value, 0777, true)) {
            die('Échec lors de la création du dossier de production...');
        }
    }
    
    /**
     * Génération des fichiers
    **/
    $fichierRecette = $fichierProd = '';
    foreach($_SESSION['fichiersManquants']['recette']['fichiers'] as $value) {
        if(!file_exists(LIEN_RECETTE.$value)) {
            $fichierRecette = fopen(LIEN_RECETTE.$value, "w");
            fwrite($fichierRecette, '');
            fclose($fichierRecette);
        }
    }
    foreach($_SESSION['fichiersManquants']['public']['fichiers'] as $value) {
        if(!file_exists(LIEN_PROD.$value)) {
            $fichierProd = fopen(LIEN_PROD.$value, "w");
            fwrite($fichierProd, '');
            fclose($fichierProd);
        }
    }
    die('ok');
}

if(!empty($_GET['renommer'])) {
    $path = $_GET['path'];
    if($path !== '/') {
        $form_ancien_nom = $_GET['form_ancien_nom'];
        $form_nouveau_nom = $_GET['form_nouveau_nom'];
        if(rename($path.$form_ancien_nom, $path.$form_nouveau_nom)) {
            die("Fichier / Dossier renommé");
        }
        die("Une erreur s'est produite"); 
    }
    die("Droits insuffisants");
}

if(!empty($_GET['supprimer'])) {
    $path = $_GET['path'];
    $form_element_nom = $_GET['form_element_nom'];
    if($path !== '/' && !empty($form_element_nom)) {
        if(shell_exec("rm -rf ". $path.$form_element_nom)) {
            die("Fichier / Dossier supprimé");
        }
        die("Une erreur s'est produite"); 
    }
    die("Droits insuffisants");
}

if(!empty($_POST['creerDossier'])) {
    if(boolval($_POST['bEstProd']) === true) {
        if (!mkdir(LIEN_PROD. filter_input(INPUT_POST, 'sNomDossier', FILTER_DEFAULT), 0777, true)) {
            die('Échec lors de la création du dossier de production...');
        }
        die('ok');
    } 
    if(boolval($_POST['bEstProd']) === false) {
        if (!mkdir(LIEN_RECETTE. filter_input(INPUT_POST, 'sNomDossier', FILTER_DEFAULT), 0777, true)) {
            die('Échec lors de la création du dossier de recette...');
        }
        die('ok');
    } 
    die('pas ok');
}

if(!empty($_GET['creerDossierLocal'])) {
    if(!is_dir($_GET['path'].$_GET['form_creer_dossier']."/")) {
        if(mkdir($_GET['path'].$_GET['form_creer_dossier'], 0777, true)) {
            die('Dossier créé');
        }
    } 
    die('Ce dossier existe déjà');
}

if(!empty($_POST['supprimerFichier'])) {
    $path = filter_input(INPUT_POST, 'path', FILTER_DEFAULT);
    
    if(!is_null($path)) {
        $result = shell_exec('rm -rf '. LIEN_RACINE.'/'.$path);
        die('ok');
    }
    die('pas ok');
}

if(!empty($_GET['creerFichierLocal'])) {
    if(!file_exists($_GET['path'].$_GET['form_creer_fichier'])) {
        shell_exec('touch '.$_GET['path'].$_GET['form_creer_fichier']);
        shell_exec('sudo chown -hR login4017:login4017 '.$_GET['path'].$_GET['form_creer_fichier']);
        shell_exec('sudo chmod 766 '.$_GET['path'].$_GET['form_creer_fichier']);
//        $fp = fopen($_GET['path'].$_GET['form_creer_fichier'], 'w');
//        if(fwrite($fp, '') !== false) {
//            fclose($fp);
//            die('Fichier créé');
//        }
//        fclose($fp);
    }
    die('Ce fichier existe déjà');
}

if(!empty($_POST['creerFichier'])) {
    $fichierProd = $fichierRecette = '';
    $lienFichierProd = LIEN_PROD.filter_input(INPUT_POST, 'sNomFichier', FILTER_DEFAULT);
    $lienFichierRecette = LIEN_RECETTE.filter_input(INPUT_POST, 'sNomFichier', FILTER_DEFAULT);
    if(boolval($_POST['bEstProd']) === true) {
        if(!file_exists($lienFichierProd)) {
            $fichierProd = fopen($lienFichierProd, "w");
            fwrite($fichierProd, filter_input(INPUT_POST, 'sContenuFichier', FILTER_DEFAULT));
            fclose($fichierProd);
            die('ok');
        } else {
            die('Ce fichier existe déjà.');
        }
    }
    if(boolval($_POST['bEstProd']) === false) {
        if(!file_exists($lienFichierRecette)) {
            $fichierRecette = fopen($lienFichierRecette, "w");
            fwrite($fichierRecette, filter_input(INPUT_POST, 'sContenuFichier', FILTER_DEFAULT));
            fclose($fichierRecette);
            die('ok');
        } else {
            die('Ce fichier existe déjà.');
        }
    }
    die('pas ok');
}

function getSizeName($octet) {
    // Array contenant les differents unités 
    $unite = array('octet','ko','Mo','Go');
    
    if ($octet < 1000) // octet
    {
        return $octet." ".$unite[0];
    }
    else 
    {
        if ($octet < 1000000) // ko
        {
            $ko = round($octet/1024,2);
            return $ko." ".$unite[1];
        }
        else // Mo ou Go 
        {
            if ($octet < 1000000000) // Mo 
            {
                $mo = round($octet/(1024*1024),2);
                return $mo." ".$unite[2];
            }
            else // Go 
            {
                $go = round($octet/(1024*1024*1024),2);
                return $go." ".$unite[3];    
            }
        }
    }
}

function getLanguageFromMimeType($mime_type) {
    $language = '';
    switch ($mime_type) {
        case 'application/x-httpd-php':
        case 'text/x-php': 
            $language = 'php';
            break;
        case 'text/javascript':
        case 'application/javascript':
        case 'application/x-javascript':
        case 'text/ecmascript': 
        case 'application/ecmascript':
            $language = 'javascript';
            break;
        case 'application/json': 
        case 'application/x-json':
        case 'application/manifest+json':
            $language = json_encode(array('name' => "javascript", 'json' => true));
        case 'application/ld+json': 
            $language = json_encode(array('name' => "javascript", 'jsonld' => true));
        default:
            $language = '';
    }
    
    return $language;
}

function getTotalSize($dir) {
    $dir = rtrim(str_replace('\\', '/', $dir), '/');

    if (is_dir($dir) === true) {
        $totalSize = 0;
        $os        = strtoupper(substr(PHP_OS, 0, 3));
        // If on a Unix Host (Linux, Mac OS)
        if ($os !== 'WIN') {
            $io = popen('/usr/bin/du -sb ' . $dir, 'r');
            if ($io !== false) {
                $totalSize = intval(fgets($io, 80));
                pclose($io);
                return $totalSize;
            }
        }
        // If on a Windows Host (WIN32, WINNT, Windows)
        if ($os === 'WIN' && extension_loaded('com_dotnet')) {
            $obj = new \COM('scripting.filesystemobject');
            if (is_object($obj)) {
                $ref       = $obj->getfolder($dir);
                $totalSize = $ref->size;
                $obj       = null;
                return $totalSize;
            }
        }
        // If System calls did't work, use slower PHP 5
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            $totalSize += $file->getSize();
        }
        return $totalSize;
    } else if (is_file($dir) === true) {
        return filesize($dir);
    }
}

function files_identical($fn1, $fn2) {
    if(filetype($fn1) !== filetype($fn2)) {
        return FALSE;
    }
    
    if(filesize($fn1) !== filesize($fn2)) {
        return FALSE;
    }
    
    if(!$fp1 = fopen($fn1, 'rb')) {
        return FALSE;
    }

    if(!$fp2 = fopen($fn2, 'rb')) {
        fclose($fp1);
        return FALSE;
    }

    $same = TRUE;
    while (!feof($fp1) and !feof($fp2)) {
        if(fread($fp1, READ_LEN) !== fread($fp2, READ_LEN)) {
            $same = FALSE;
            break;
        }
    }

    if(feof($fp1) !== feof($fp2)) {
        $same = FALSE;
    }

    fclose($fp1);
    fclose($fp2);

    return $same;
}

$dossiers_public = preg_split('/'.PHP_EOL.'/', shell_exec("ls ".LIEN_PROD));
$dossiers_recette = preg_split('/'.PHP_EOL.'/', shell_exec("ls ".LIEN_RECETTE));
$dossiers = array_unique(array_merge($dossiers_recette, $dossiers_public));
if(!empty($_GET['script']) && $_GET['script'] == 1) {
    echo '<div><span style="background-color: blue; padding: 1px 10px;">&nbsp;</span><span>&nbsp;&nbsp;Fichier/Dossier présent</span></div>';
    echo '<div><span style="background-color: orange; padding: 1px 10px;">&nbsp;</span><span>&nbsp;&nbsp;Fichier/Dossier manquant en recette</span></div>';
    echo '<div><span style="background-color: red; padding: 1px 10px;">&nbsp;</span><span>&nbsp;&nbsp;Fichier/Dossier manquant en production</span></div>';
    echo '=========================Dossiers/Fichiers================================<br/>';
    foreach($dossiers as $d) {
        if($d !== "") {
            $chemin_recette = LIEN_RECETTE.$d;
            $chemin_public = LIEN_PROD.$d;
            $dossier_recette_existe = is_dir($chemin_recette) ? true : false;
            $fichier_recette_existe = is_file($chemin_recette) ? true : false;
            $dossier_public_existe = is_dir($chemin_public) ? true : false;
            $fichier_public_existe = is_file($chemin_public) ? true : false;
            if($dossier_recette_existe === true && $dossier_public_existe === true && $fichier_recette_existe === false && $fichier_public_existe === false) {
                echo '<span style="color: blue;">Les deux dossiers '.$d.' exisent</span><br/>';
            } else {
                if($fichier_recette_existe === true && $fichier_public_existe === true) {
                    echo '<span style="color: blue;">Les deux fichiers '.$d.' exisent</span><br/>';            
                } else {
                    if($dossier_recette_existe === false && $fichier_recette_existe === false && strpos($d, '.') !== false) {
                        echo '<span style="color: orange;">Le fichier '.$d.' n\'existe pas en recette</span><br/>';
                    } elseif(strpos($d, '.') === false && $dossier_recette_existe === false) {
                        echo '<span style="color: orange;">Le dossier '.$d.' n\'existe pas en recette</span><br/>';
                    }
                    if($dossier_public_existe === false && $fichier_public_existe === false && strpos($d, '.') !== false) {
                        echo '<span style="color: red;">Le fichier '.$d.' n\'existe pas en production</span><br/>';
                    } elseif(strpos($d, '.') === false && $dossier_public_existe === false) {
                        echo '<span style="color: red;">Le dossier '.$d.' n\'existe pas en production</span><br/>';
                    }
                }
            }
        //    if(files_identical('./index.php', './index2.php')) echo 'files identical';
        //    else echo 'Les fichiers sont différents';
        }
    }
    echo '=========================Fin Dossiers/Fichiers===========================<br/>';
} elseif(!empty($_GET['parcourirArborescence'])) {
    $path = (in_array(filter_input(INPUT_GET, 'path', FILTER_DEFAULT), array('/', './', '../', '/../')) ? LIEN_RACINE : filter_input(INPUT_GET, 'path', FILTER_DEFAULT));

    if(substr($path, -1) === '/') {
        $path = substr($path, 0, -1);
    }
    if(substr($path, 0, 1) === '/') {
        $path = substr($path, 1);
    }
    if($path != LIEN_RACINE) {
        if(is_file(LIEN_RACINE.'/'.$path)) {
            $_SESSION['path'] = LIEN_RACINE.'/'.$path;
        } 

        if(is_dir(LIEN_RACINE.'/'.$path.'/')) {
            $_SESSION['path'] = LIEN_RACINE.'/'.$path.'/';
        }
    } else {
        unset($_SESSION['path']);
    }

    if(is_file((isset($_SESSION['path']) ? $_SESSION['path'] : $path))) {
        $fichier_affiche = shell_exec("cat ".(isset($_SESSION['path']) ? $_SESSION['path'] : $path));
        if(empty($fichier_affiche)) $fichier_affiche = ' ';
    } elseif(is_dir((isset($_SESSION['path']) ? $_SESSION['path'] : $path).'/')) {
        $arbo_affichee = preg_split('/'.PHP_EOL.'/', shell_exec("ls ".(isset($_SESSION['path']) ? $_SESSION['path'] : $path)));
        uasort($arbo_affichee, function ($a, $b) {
            if((!isset($_GET['column']) && !isset($_GET['sort'])) || ($_GET['column'] == 1 && $_GET['sort'] == 'asc')) {
                return strcasecmp($a, $b);
            } elseif($_GET['column'] == 1 && $_GET['sort'] == 'desc') {
                return strcasecmp($b, $a);
            }
        });
    } 
    ?> 
    <!DOCTYPE html>
    <html lang="fr" id="html">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <title>GED</title>
            <!-- Font Awesome -->
            <link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css" 
                integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" 
                crossorigin="anonymous"/>
            <!-- Bootstrap core CSS -->
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <!-- CodeMirror -->
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">
            <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
            <style>
                .hidden {
                    display: none !important;
                }
                .modal-xlg {
                    max-width: 1700px;
                }
                #loading {
                    position: fixed;
                    display: block;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                    text-align: center;
                    opacity: 0.8;
                    background-color: #262626;
                    z-index: 99;
                }
                .modal-dark .modal-content {
                    background-color: #1e2229;
                    color: #9d9d9d;
                }

                .modal-dark .modal-content .modal-header,
                .modal-dark .modal-content .modal-footer {
                    border-color: #424242;
                }

                .modal-dark .form-control {
                    background-color: #282d36;
                    border-color: #282d36;
                }
                .loader{
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    z-index: 100;
                }
                .fake_link {
                    text-decoration: underline;
                    cursor: pointer;
                }
                .CodeMirror {
                    height: 70vh;
                }
                svg path, svg rect {
                  fill: #FF6700;
                }
                .table-personalisee {
                    font-size: 1em;
                    margin: 0 auto;
                    width: 96%;
                    border-collapse: separate;
                    border-spacing: 0px;
                    border-radius: 14px;
                    border:solid 1px;
                }

                .table-personalisee tr:first-child td {
                    border-top:none;
                }

                .table-personalisee tr:last-child td {
                    border-bottom:none;
                }
                .pre_seletionne {
                    background-color: #262626 !important;
                }
                .ligne-surlignable:hover {
                    background-color: #262626;
                }
                td {
                    padding: 5px;
                    border-top: solid 1px #d21d21;
                    border-bottom: solid 1px #d21d21;
                }
                
                #context-menu {
                    position:fixed;
                    z-index:10000;
                    width:150px;
                    background:#1b1a1a;
                    border-radius:5px;
                    transform:scale(0);
                    transform-origin:top left;
                }
                #context-menu.active {
                    transform:scale(1);
                    transition:transform 300ms ease-in-out;
                }
                #context-menu .item {
                    padding:8px 10px;
                    font-size:15px;
                    color:#eee;
                }
                #context-menu .item:hover {
                    background:#555;
                }
                #context-menu .item i {
                    display:inline-block;
                    margin-right:5px;
                }
                #context-menu hr {
                    margin:2px 0px;
                    border-color:#555;
                }
                
                #toast {
                    height: auto;
                    margin: auto;
                    background-color: #333;
                    color: #fff;
                    text-align: center;
                    border-radius: 2px;
                    position: fixed;
                    z-index: 1;
                    right: 20px;
                    top: 30px;
                    font-size: 17px;
                    white-space: nowrap;
                }
                
                #toast_icone {
                    width: 50px;
                    height: auto;
                    float: left;
                    padding-top: 2.5rem;
                    padding-bottom: 2.25rem;
                    box-sizing: border-box;
                    color: #fff;
                }
                
                .toast_red {
                    background-color: #d21d21;
                }
                
                #toast_message {
                    position: relative;
                    top: 3vh;
                }
                
                .toast_message {
                    color: #fff;
                    padding: 16px;
                    overflow: hidden;
                    white-space: nowrap;
                    display: contents;
                }
            </style>
        </head>
        <body class="bg-dark">
            <div id="loading" class="hidden">
                <div class="loader loader--style5" title="4">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" 
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" 
                        height="30px" viewBox="0 0 24 30" 
                        style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <rect x="0" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml" attributeName="transform" 
                                type="translate" values="0 0; 0 20; 0 0" begin="0" 
                                dur="0.6s" repeatCount="indefinite" />
                        </rect>
                        <rect x="10" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml" attributeName="transform" 
                                type="translate" values="0 0; 0 20; 0 0" begin="0.2s" 
                                dur="0.6s" repeatCount="indefinite" />
                        </rect>
                        <rect x="20" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml" attributeName="transform" 
                                type="translate" values="0 0; 0 20; 0 0" begin="0.4s" 
                                dur="0.6s" repeatCount="indefinite" />
                        </rect>
                    </svg>
                </div>
            </div>
            
            <div class="hidden" id="toast">
                <div id="toast_icone"></div>
                <div class="toast_message"><span id="toast_message"></span></div>
            </div>
            
            <div id="context-menu">
                <div class="item" onclick="window.location.reload();">
                    <i class="fa fa-refresh"></i> Reload
                </div>
                <div class="item" onclick="event.preventDefault();">
                    <i class="fa fa-times"></i> Exit
                </div>
            </div>

            <div class="wrapper">
                <div class="col-sm-12 col-md-6" style="float: left;">
                    <table class="table table-responsive table-dark table-personalisee">
                        <?php if(empty($fichier_affiche)) { ?>
                        <thead>
                            <tr>
                                <th>
                                    <i style="cursor: pointer;" class="fas fa-folder-plus fa-2x" data-toggle="tooltip" title="Créer un dossier" onclick="afficherModaleCreerDossier();"></i>
                                </th>
                                <th colspan="5">
                                    <i style="cursor: pointer;" class="fas fa-file-plus fa-2x" data-toggle="tooltip" title="Créer un fichier" onclick="afficherModaleCreerFichier();"></i>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="6"><span class="fake_link" onclick="window.location.href = 'ged.php?parcourirArborescence=1&path='">Symfony4-4017</span> 
                                    <?php
                                    if(!empty($_GET['path'])) {
//                                        echo ' > ';
                                        $i = sizeof(explode('/', $_GET['path']));
                                        foreach(explode('/', $_GET['path']) as $key => $l) if(!empty ($l)) {
                                            $lien = '';
                                            for($j = 0 ; $j <= $key ; $j++) {
                                                $lien .= explode('/', $_GET['path'])[$j].'/';
                                            }
                                            echo ' > <span class="fake_link" onclick="window.location.href=\'ged.php?parcourirArborescence=1&path=/'.$lien.'\'">' .$l. '</span>';
                                            if($key < $i) {
                                                $i--;
                                            }
                                        }
                                    } ?>
                                </th>
                            </tr>
                            <tr>
                                <th id="filtre_1" colspan="2" class="text-center" style="vertical-align: middle; width: auto; cursor: pointer;" data-filtre="asc" onclick="filtrerTable(1, $(this));">Nom<span style="float: right;"><i id="sort_name" class="fas fa-sort-up"></i></span></th>
                                <th class="text-center" style="vertical-align: middle; width: 15%;">Taille</th>
                                <th class="text-center" style="vertical-align: middle; width: 15%;">Dernière modification</th>
                                <th class="text-center" style="vertical-align: middle; width: 25%;">Actions</th>
                                <?php if(isset($_SESSION['path']) && (strpos($_SESSION['path'], "/public/") !== false || strpos($_SESSION['path'], "/recette/") !== false)) {
                                    echo '<th class="text-center" style="vertical-align: middle; width: 10%; cursor: pointer;" onclick="window.location.href = \'ged.php?script=1\';">Comparer Prod/Recette</th>'; 
                                } else {
                                    echo '<th></th>';
                                } ?>
                            </tr>
                        </thead>
                        <?php } ?>
                        <tbody>
                            <tr class="ligne-surlignable">
                                <td><a href="./ " ><i data-toggle="tooltip" data-placement="right" title="Retourner au Portfolio" class="fas fa-home fa" style="cursor: pointer; font-size: 1.5em; color: darkgrey;"></i></a></td>
                                <td colspan="5" style="cursor: pointer;" ondblclick="retourArriere();">..</td>
                            </tr>
                            <?php $i = 1;
                            if(!empty($arbo_affichee[0])) {
                                foreach($arbo_affichee as $value) { 
                                    if($value !== '' && is_dir((isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '').$value.'/') === true) { 
                                        $file = (isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path'].$value) : $value);
                                        $filesize = getTotalSize($file);
//                                        $filesize = passthru("du -h -b ".$file." | awk '{n1 += $1} END {print n1}'"); // Autre méthode
                                        $bEstFichier = is_file((isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '').$value);
                                        $bEstRepertoire = is_dir((isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '').$value.'/');
                                        ?>
                                        <tr class="ligne-surlignable" onclick="ligneSelectionnee(this);">
                                            <td style="width: 10%;"><i style="<?=($_SESSION['path'] === LIEN_RACINE && $i > 1 ? 'padding-left: 15px;' : '')?><?=($bEstFichier ? '':'color: darkorange;')?>" class="fas <?=($bEstFichier ? 'fa-file' : 'fa-folder')?> "></i></td> 
                                            <td class="text-left <?=($filesize > 10000000 && $bEstFichier ? '' : ' fake_link')?>" style="width: auto; <?=($filesize > 10000000 && $bEstFichier ? '' : 'cursor: pointer;')?>" <?=($filesize > 10000000 && $bEstFichier ? '' : 'ondblclick="window.location.href = \'ged.php?parcourirArborescence=1&path='.(isset($_SESSION['path']) ? str_replace('/var/www/html/symfony4-4017/', '', $_SESSION['path'].$value) : $value).'\';"')?>><?=($filesize > 10000000 && $bEstFichier ? '<span data-toggle="tooltip" data-placement="right" data-html="true" title="Fichier trop volumineux<br>(> 10Mo)">' : '')?><?=$value?><?=($filesize > 10000000 && $bEstFichier ? '</span>' : '')?></td>
                                            <td style="text-align: center; width: 15%;"><?php echo getSizeName($filesize) ?></td>
                                            <td style="text-align: center; width: 15%;"><?php echo date_create(shell_exec("date +%D -r ".$file))->format('d/m/Y') ?></td>
                                            <td style="text-align: center; width: 25%;">
                                                <a target="_blank" href="download_dir.php?dir=<?= str_replace('/var/www/html/symfony4-4017/', '', (isset($_SESSION['path']) ? $_SESSION['path'].$value : $value))?>"><i class="fas fa-download" style="cursor: pointer; color: #2d6291;"></i></a>
                                                <i class="fas fa-edit" style="cursor: pointer; color: #2d6291;" onclick="afficherModaleRenommer('<?=$value?>');"></i>
                                                <i class="fas fa-trash-alt" style="cursor: pointer; color: #a90000;" onclick="afficherModaleSupprimer('<?=$value?>');"></i>
                                            </td>
                                            <td style="text-align: center;">
                                            <?php if(isset($_SESSION['path']) && 
                                                is_file('/var/www/html/symfony4-4017/'.(isset($_SESSION['path']) ? str_replace(array('//', '/var/www/html/symfony4-4017/'), array('/', ''), $_SESSION['path']) : '').$value) && 
                                                (strpos($_SESSION['path'], "/public/") !== false
                                                    || strpos($_SESSION['path'], "/recette/") !== false
                                                )
                                            ) {
                                                echo '<i style="cursor: pointer;" onclick="afficherModaleComparateur(\''. str_replace(array('public/', 'recette/'), array('', ''), $_GET['path'])."/".$value.'\', \''.$value.'\');" data-toggle="tooltip" data-placement="right" title="Voir les deux fichiers côte à côte" ><svg style="height: 2vh;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path style="fill: #6560a1;" d="M320 488C320 497.5 314.4 506.1 305.8 509.9C297.1 513.8 286.1 512.2 279.9 505.8L199.9 433.8C194.9 429.3 192 422.8 192 416C192 409.2 194.9 402.7 199.9 398.2L279.9 326.2C286.1 319.8 297.1 318.2 305.8 322.1C314.4 325.9 320 334.5 320 344V384H336C371.3 384 400 355.3 400 320V153.3C371.7 140.1 352 112.8 352 80C352 35.82 387.8 0 432 0C476.2 0 512 35.82 512 80C512 112.8 492.3 140.1 464 153.3V320C464 390.7 406.7 448 336 448H320V488zM456 79.1C456 66.74 445.3 55.1 432 55.1C418.7 55.1 408 66.74 408 79.1C408 93.25 418.7 103.1 432 103.1C445.3 103.1 456 93.25 456 79.1zM192 24C192 14.52 197.6 5.932 206.2 2.076C214.9-1.78 225-.1789 232.1 6.161L312.1 78.16C317.1 82.71 320 89.2 320 96C320 102.8 317.1 109.3 312.1 113.8L232.1 185.8C225 192.2 214.9 193.8 206.2 189.9C197.6 186.1 192 177.5 192 168V128H176C140.7 128 112 156.7 112 192V358.7C140.3 371 160 399.2 160 432C160 476.2 124.2 512 80 512C35.82 512 0 476.2 0 432C0 399.2 19.75 371 48 358.7V192C48 121.3 105.3 64 176 64H192V24zM56 432C56 445.3 66.75 456 80 456C93.25 456 104 445.3 104 432C104 418.7 93.25 408 80 408C66.75 408 56 418.7 56 432z"/></svg></i>';
                                            } ?>
                                            </td>
                                            
                                        </tr>
                                    <?php $i++;
                                    }
                                }
                                foreach($arbo_affichee as $value) { 
                                    if($value !== '' && is_file((isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '').$value) === true) { 
                                        $file = (isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path'].$value) : $value);
                                        $filesize = getTotalSize($file);
//                                        $filesize = passthru("du -h -b ".$file." | awk '{n1 += $1} END {print n1}'"); // Autre méthode
                                        $bEstFichier = is_file((isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '').$value);
                                        $bEstRepertoire = is_dir((isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '').$value.'/');
                                        ?>
                                        <tr class="ligne-surlignable" onclick="ligneSelectionnee(this);">
                                            <td style="width: 10%;"><i style="<?=($_SESSION['path'] === LIEN_RACINE && $i > 1 ? 'padding-left: 15px;' : '')?><?=($bEstFichier ? '':'color: darkorange;')?>" class="fas <?=($bEstFichier ? 'fa-file' : 'fa-folder')?> "></i></td> 
                                            <td class="text-left <?=($filesize > 10000000 && $bEstFichier ? '' : ' fake_link')?>" style="width: auto; <?=($filesize > 10000000 && $bEstFichier ? '' : 'cursor: pointer;')?>" <?=($filesize > 10000000 && $bEstFichier ? '' : 'ondblclick="window.location.href = \'ged.php?parcourirArborescence=1&path='.(isset($_SESSION['path']) ? str_replace('/var/www/html/symfony4-4017/', '', $_SESSION['path'].$value) : $value).'\';"')?>><?=($filesize > 10000000 && $bEstFichier ? '<span data-toggle="tooltip" data-placement="right" data-html="true" title="Fichier trop volumineux<br>(> 10Mo)">' : '')?><?=$value?><?=($filesize > 10000000 && $bEstFichier ? '</span>' : '')?></td>
                                            <td style="text-align: center; width: 15%;"><?php echo getSizeName($filesize) ?></td>
                                            <td style="text-align: center; width: 15%;"><?php echo date_create(shell_exec("date +%D -r ".$file))->format('d/m/Y') ?></td>
                                            <td style="text-align: center; width: 25%;">
                                                <a target="_blank" href="download_file.php?file=<?= str_replace('/var/www/html/symfony4-4017/', '', (isset($_SESSION['path']) ? $_SESSION['path'].$value : $value))?>"><i class="fas fa-file-download" style="cursor: pointer; color: #2d6291;"></i></a>
                                                <i class="fas fa-edit" style="cursor: pointer; color: #2d6291;" onclick="afficherModaleRenommer('<?=$value?>');"></i>
                                                <i class="fas fa-trash-alt" style="cursor: pointer; color: #a90000;" onclick="afficherModaleSupprimer('<?=$value?>');"></i>
                                            </td>
                                            <td style="text-align: center;">
                                            <?php if(isset($_SESSION['path']) && 
                                                is_file('/var/www/html/symfony4-4017/'.(isset($_SESSION['path']) ? str_replace(array('//', '/var/www/html/symfony4-4017/'), array('/', ''), $_SESSION['path']) : '').$value) && 
                                                (strpos($_SESSION['path'], "/public/") !== false
                                                    || strpos($_SESSION['path'], "/recette/") !== false
                                                )
                                            ) {
                                                echo '<i style="cursor: pointer;" onclick="afficherModaleComparateur(\''. str_replace(array('public/', 'recette/'), array('', ''), $_GET['path'])."/".$value.'\', \''.$value.'\');" data-toggle="tooltip" data-placement="right" title="Voir les deux fichiers côte à côte" ><svg style="height: 2vh;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path style="fill: #6560a1;" d="M320 488C320 497.5 314.4 506.1 305.8 509.9C297.1 513.8 286.1 512.2 279.9 505.8L199.9 433.8C194.9 429.3 192 422.8 192 416C192 409.2 194.9 402.7 199.9 398.2L279.9 326.2C286.1 319.8 297.1 318.2 305.8 322.1C314.4 325.9 320 334.5 320 344V384H336C371.3 384 400 355.3 400 320V153.3C371.7 140.1 352 112.8 352 80C352 35.82 387.8 0 432 0C476.2 0 512 35.82 512 80C512 112.8 492.3 140.1 464 153.3V320C464 390.7 406.7 448 336 448H320V488zM456 79.1C456 66.74 445.3 55.1 432 55.1C418.7 55.1 408 66.74 408 79.1C408 93.25 418.7 103.1 432 103.1C445.3 103.1 456 93.25 456 79.1zM192 24C192 14.52 197.6 5.932 206.2 2.076C214.9-1.78 225-.1789 232.1 6.161L312.1 78.16C317.1 82.71 320 89.2 320 96C320 102.8 317.1 109.3 312.1 113.8L232.1 185.8C225 192.2 214.9 193.8 206.2 189.9C197.6 186.1 192 177.5 192 168V128H176C140.7 128 112 156.7 112 192V358.7C140.3 371 160 399.2 160 432C160 476.2 124.2 512 80 512C35.82 512 0 476.2 0 432C0 399.2 19.75 371 48 358.7V192C48 121.3 105.3 64 176 64H192V24zM56 432C56 445.3 66.75 456 80 456C93.25 456 104 445.3 104 432C104 418.7 93.25 408 80 408C66.75 408 56 418.7 56 432z"/></svg></i>';
                                            } ?>
                                            </td>
                                            
                                        </tr>
                                    <?php $i++;
                                    }
                                }
                            } elseif(!empty($fichier_affiche)) { ?>
                                <tr>
                                    <td style="width: 10%;"><i class="fas fa-folder-open"></i></td>
                                    <td colspan="4">
                                        <textarea cols="15" id="file-edit-tree" onfocus="afficherEditorArbre();"><?=$fichier_affiche?></textarea><br>
                                        <button class="btn btn-danger btn-md" onclick="event.preventDefault(); supprimerFichier();">Supprimer</button>
                                        <button class="btn btn-primary btn-md" onclick="event.preventDefault(); modifierFichier('<?=$_GET['path']?>', window.fileEditTree);">Modifier</button>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td style="width: 10%;"><i class="fas fa-folder-open"></i></td>
                                    <td colspan="4">Aucun fichier ni dossier présent. <a href="#" onclick="retourArriere();">Revenir en arrière</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-12 col-md-6" style="float: right;<?=(strpos($_GET['path'], 'web') !== false 
                                                                        || strpos($_GET['path'], 'public') !== false
                                                                        || strpos($_GET['path'], 'recette') !== false
                                                                        || strpos($_GET['path'], 'index.php') !== false
                                                                        ? '' : 'display: none;')?>">
                    <input type="hidden" class="form-control" name="form_site_preview" id="form_site_preview" onchange="majIframe(this.value);" value="<?=(!empty($_GET['path']) ? $_GET['path'] : '')?>" />
                    <h4 class="text-white text-center"><i class="fas fa-eye" style="cursor: pointer;" id="preview_eye" onclick="togglePreview();">&nbsp;Prévisualiser</i></h4>
                    <div id="preview"></div>
                </div>
            </div>
            
            <div class="modal modal-dark fade" id="modale_creer_dossier" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Créer un dossier</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="form_creer_dossier">Nom du dossier</label>
                                    <input class="form-control" type="text" name="form_creer_dossier" id="form_creer_dossier">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="creerDossierLocal('<?=(isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '')?>');">Confirmer</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal modal-dark fade" id="modale_creer_fichier" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Créer un fichier</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="form_creer_fichier">Nom du fichier</label>
                                    <input class="form-control" type="text" name="form_creer_fichier" id="form_creer_fichier">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="creerFichierLocal('<?=(isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '/')?>');">Confirmer</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal modal-dark fade" id="modale_renommer" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Renommer un fichier / dossier</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input class="form-control" type="hidden" name="form_ancien_nom" id="form_ancien_nom">
                                    <label for="form_nouveau_nom">Nouveau nom</label>
                                    <input class="form-control" type="text" name="form_nouveau_nom" id="form_nouveau_nom">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary" onclick="renommer('<?=(isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '')?>');">Confirmer</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal modal-dark fade" id="modale_supprimer" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Supprimer un fichier / dossier</h5>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input class="form-control" type="hidden" name="form_element_nom" id="form_element_nom">
                                    <p>Êtes-vous certain(e) de vouloir supprimer cet élément ?</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" onclick="supprimer('<?=(isset($_SESSION['path']) ? str_replace('//', '/', $_SESSION['path']) : '')?>');">OUI</button>
                            <button type="button" class="btn btn-light" data-dismiss="modal">NON</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade modal-dark" id="modale_comparateur_fichiers" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xlg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Comparateur des fichiers&nbsp;:&nbsp;<span id="modale_comparateur_title"></span></h5>
                            <button type="button" class="btn btn-danger btn-md" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: 90vh; overflow-x: hidden; overflow-y: auto;">
                            <div class="row">
                                <div style="width: 45% !important;">
                                    <label for="file-edit-tree-left" style="width: 99%;">Recette&nbsp;:<span style="float: right; cursor: pointer;" id="btn_save_recette"><i class="fad fa-save fa-2x" style="color: cornflowerblue;"></i></span></label>
                                    <textarea rows="15" cols="15" id="file-edit-tree-left" name="file-edit-tree-left">&nbsp;</textarea>
                                </div>
                                <div style="width: 10% !important; text-align: center;">
                                    <i class="fas fa-angle-double-right fa-2x" style="margin-top: 33vh;"></i>
                                </div>
                                <div style="width: 45% !important;">
                                    <label for="file-edit-tree-right" style="width: 99%;">Production&nbsp;:<span style="float: right; cursor: pointer;" id="btn_save_production"><i class="fad fa-save fa-2x" style="color: cornflowerblue;"></i></span></label>
                                    <textarea rows="15" cols="15" id="file-edit-tree-right" name="file-edit-tree-right">&nbsp;</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript" src="js/jquery.min.js"></script>
            <!-- Bootstrap tooltips -->
            <script type="text/javascript" src="js/popper.min.js"></script>
            <!-- Bootstrap core JavaScript -->
            <script type="text/javascript" src="js/bootstrap.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/mode/xml/xml.js"></script>

            <script>
                window.nb_pre_selectionne = 0;
                $('#loading').removeClass('hidden');
                $('#file-edit-tree').focus();
                
                /* Désactive le menu clic droit et en ajoute un personnalisé */
//                window.addEventListener("contextmenu",function(event){
//                    event.preventDefault();
//                    var contextElement = document.getElementById("context-menu");
//                    contextElement.style.top = event.offsetY + "px";
//                    contextElement.style.left = event.offsetX + "px";
//                    contextElement.classList.add("active");
//                });
//                window.addEventListener("click",function(){
//                    document.getElementById("context-menu").classList.remove("active");
//                });

                $(document).ready(function() {
                    $('[data-toggle="tooltip"]').tooltip();
                    $('#loading').addClass('hidden');
                    $('#form_site_preview').trigger('change');
                });
                
                document.addEventListener('keydown', (event) => {
                    const nomTouche = event.key;
                    
                    if(nomTouche === "Backspace" && !$('body').hasClass('modal-open') && window.fileEditTree === undefined) {
                        retourArriere();
                    }
                });
                
                $('#form_creer_dossier').on('keyup', function() {
                    $(this).val($(this).val().replace(/\s/g, "_"));
                });
                
                $('#form_creer_fichier').on('keyup', function() {
                    $(this).val($(this).val().replace(/\s/g, "_"));
                });
                
                $('#modale_comparateur_fichiers').on('shown.bs.modal', function() {
                    window.fileEditTreeLeft.refresh();
                    window.fileEditTreeRight.refresh();
                });
                $('#modale_comparateur_fichiers').on('hidden.bs.modal', function() {
                    window.fileEditTreeLeft.toTextArea();
                    window.fileEditTreeLeft = undefined;
                    window.fileEditTreeRight.toTextArea();
                    window.fileEditTreeRight = undefined;
                });
                
                function filtrerTable(iColumn, uType) {
                    var sOrder = uType.attr('data-filtre');
                    if(sOrder === 'asc') {
                        uType.attr('data-filtre', 'desc');
                        $('#sort_name').removeClass('fa-sort-up');
                        $('#sort_name').addClass('fa-sort-down');
                    } else {
                        uType.attr('data-filtre', 'asc');
                        $('#sort_name').removeClass('fa-sort-down');
                        $('#sort_name').addClass('fa-sort-up');
                    }
                    sOrder = uType.attr('data-filtre');
                    setTimeout(function() {
                        var urlBase = window.location.search.match("parcourirArborescence=1&path=[-a-zA-Z0-9@:%._\+~#=\/]{0,256}")[0];
                        window.location.href = "ged.php?" + urlBase + '&column='+iColumn+'&sort='+sOrder;
                    }, 500);
                }
                
                function ligneSelectionnee(ligne) {
                    $(ligne).toggleClass('pre_seletionne');
//                    $('.ligne-surlignable').each(function() {
//                        $(this).removeClass('pre_seletionne');
//                    });
                }
                
                function togglePreview() {
                    $('#preview').toggleClass('hidden');
                    if($('#preview_eye').hasClass('fa-eye')) {
                        $('#preview_eye').removeClass('fa-eye');
                        $('#preview_eye').addClass('fa-eye-slash');
                    } else {
                        $('#preview_eye').removeClass('fa-eye-slash');
                        $('#preview_eye').addClass('fa-eye');
                    }
                }
                
                function reinitFileEditorTree() {
                    window.fileEditTree.toTextArea();
                    $('#file-edit-tree').addClass('hidden');
                    window.fileEditTree = undefined;
                }
                
                function retourArriere() {
                    if(window.fileEditTree !== undefined) {
                        window.fileEditTree = undefined;
                    }
                    const urlParams = new URLSearchParams(window.location.search);
                    const path = urlParams.get('path');

                    if(window.location.href.slice(0, window.location.href.lastIndexOf('/')) !== 'http://serveur1.arras-sio.com/symfony4-4017/public/' && 
                        window.location.href.slice(0, window.location.href.lastIndexOf('/')) !== 'http://serveur1.arras-sio.com/symfony4-4017/public' && 
                        path !== '/') {
                            if(path.match(/\//gi).length === 1 && path.length === 1) {
                                window.location.href = window.location.href.slice(0, window.location.href.lastIndexOf('/')).replace(/&path=/, '&path=/');
                            } else {
                                window.location.href = window.location.href.slice(0, window.location.href.lastIndexOf('/'));
                            }
                    } else {
                        window.location.href = 'ged.php?parcourirArborescence=1&path=';
                    }
                }
                
                function afficherEditorArbre() {
                    if(window.fileEditTree === undefined) {
                        window.fileEditTree = CodeMirror.fromTextArea(document.getElementById('file-edit-tree'), {
                            lineNumbers: true,
                            theme: 'monokai', 
                            tabSize: 2
                        });
                    }
                }
                
                function afficherModaleCreerDossier() {
                    $('#form_creer_dossier').val('');
                    $('#modale_creer_dossier').modal('show');
                }
                
                function afficherModaleRenommer(ancienNom) {
                    $('#form_ancien_nom').val(ancienNom);
                    $('#form_nouveau_nom').val('');
                    $('#modale_renommer').modal('show');
                }
                
                function renommer(path = '/') {
                    if(path !== '/') {
                        $.ajax({
                            url: 'ged.php',
                            method: 'get', 
                            data: {
                               renommer: 1, 
                               form_ancien_nom: $('#form_ancien_nom').val(), 
                               form_nouveau_nom: $('#form_nouveau_nom').val(), 
                               path: path
                            }, 
                            beforeSend: function() {
                               $('#loading').removeClass('hidden'); 
                            }, 
                            success: function(r) {
                                window.location.reload();
                            }, 
                            complete: function() {
                                $('#loading').addClass('hidden'); 
                            }, 
                            error: function() {
                               $('#loading').addClass('hidden'); 
                            }
                        });
                    }
                }
                
                
                function afficherModaleSupprimer(elementNom) {
                    $('#form_element_nom').val(elementNom);
                    $('#modale_supprimer').modal('show');
                }
                
                function supprimer(path = '/') {
                    if(path !== '/') {
                        $.ajax({
                            url: 'ged.php',
                            method: 'get', 
                            data: {
                               supprimer: 1, 
                               form_element_nom: $('#form_element_nom').val(), 
                               path: path
                            }, 
                            beforeSend: function() {
                               $('#loading').removeClass('hidden'); 
                            }, 
                            success: function(r) {
                                window.location.reload();
                            }, 
                            complete: function() {
                                $('#loading').addClass('hidden'); 
                            }, 
                            error: function() {
                               $('#loading').addClass('hidden'); 
                            }
                        });
                    }
                }
                
                function creerDossierLocal(path = '/') {
                    $.ajax({
                        url: 'ged.php',
                        method: 'get', 
                        data: {
                           creerDossierLocal: 1, 
                           form_creer_dossier: $('#form_creer_dossier').val(), 
                           path: path
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            window.location.reload();
                        }, 
                        complete: function() {
                            $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }
                
                function afficherModaleCreerFichier() {
                    $('#form_creer_fichier').val('');
                    $('#modale_creer_fichier').modal('show');
                }
                
                function creerFichierLocal(path = '/') {
                    $.ajax({
                        url: 'ged.php',
                        method: 'get', 
                        data: {
                           creerFichierLocal: 1, 
                           form_creer_fichier : $('#form_creer_fichier').val(), 
                           path: path
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            window.location.reload();
                        }, 
                        complete: function() {
                            $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }
                
                function majIframe(site_src) {
                    if(site_src.endsWith('web') || site_src.endsWith('public') || site_src.endsWith('recette') || site_src.endsWith('index.php')){
                        var site = site_src.replace('//', '/');
                        $('#preview').html('<iframe src="http://<?=$_SERVER['HTTP_HOST']?>/symfony4-4017/'+site+'" style="height: 95vh; width: 100%;"></iframe>');
                    } else {
                        $('#preview').html('');
                    }
                }
                
                function parcourirArborescence(path = '/') {
                    $.ajax({
                        url: 'ged.php',
                        method: 'get', 
                        data: {
                           parcourirArborescence: 1, 
                           path: path
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            var newDoc = document.open("text/html", "replace");
                            newDoc.write(r);
                            newDoc.close();
                            
                            if(window.fileEditTree !== undefined) {
                                reinitFileEditorTree();
                            }
                        }, 
                        complete: function() {
                            $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }
                
                function supprimerFichier() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const path = urlParams.get('path');
                    
                    $.ajax({
                        url: 'ged.php',
                        method: 'post', 
                        dataType: 'html', 
                        data: {
                           supprimerFichier: 1, 
                           path: path
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            if(r === 'ok') {
                                retourArriere();
                            }
                        }, 
                        complete: function() {
                            $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }
                
                function modifierFichier(sCheminFichier, sContenuFichier) {
                    var formData = new FormData();
                    formData.append("modifierFichier", 1);
                    formData.append("sCheminFichier", sCheminFichier);
                    formData.append("sContenuFichier", sContenuFichier.getValue());
                    
                    $.ajax({
                        url: 'ged.php',
                        method: 'post', 
                        dataType: 'html', 
                        processData: false,
                        contentType: false,
                        data: formData, 
                        success: function(r) {
                            if(r === "pas ok") {
                                $('#toast_icone').html('<i class="far fa-ban"></i>');
                                $('#toast_icone').addClass('toast_red');
                                $('#toast_message').html("&nbsp;&nbsp;Vous n'avez pas la<br/>&nbsp;permission pour&nbsp;<br/>effectuer<br/>cette action");
                                $('#toast').removeClass('hidden');
                                setTimeout(function() {
                                    $('#toast').addClass('hidden');
                                }, 5000);
                            }
//                           window.location.reload();
                        }
                    });
                }
            </script> 
        </body>
    </html>
<?php
    } else { ?>
    <!DOCTYPE html>
    <html lang="fr" id="html">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <title>GED</title>
            <!-- Font Awesome -->
            <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.15.4/css/all.css">
            <!-- Bootstrap core CSS -->
            <link rel="stylesheet" href="css/bootstrap.min.css">
            <!-- CodeMirror -->
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/codemirror.min.css">
            <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.52.2/theme/monokai.min.css">
            <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
            <style>
                .hidden {
                    display: none !important;
                }
                 .modal-xlg {
                    max-width: 1700px;
                }
                #loading {
                    position: fixed;
                    display: block;
                    width: 100%;
                    height: 100%;
                    top: 0;
                    left: 0;
                    text-align: center;
                    opacity: 0.8;
                    background-color: #262626;
                    z-index: 99;
                }

                .loader{
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    z-index: 100;
                }
                
                .modal-dark .modal-content {
                    background-color: #1e2229;
                    color: #9d9d9d;
                }

                .modal-dark .modal-content .modal-header,
                .modal-dark .modal-content .modal-footer {
                    border-color: #424242;
                }

                .modal-dark .form-control {
                    background-color: #282d36;
                    border-color: #282d36;
                }
                
                svg path, svg rect {
                  fill: #FF6700;
                }
            </style>
        </head>
        <body>
            <div id="loading" class="hidden">
                <div class="loader loader--style5" title="4">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" 
                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" 
                        height="30px" viewBox="0 0 24 30" 
                        style="enable-background:new 0 0 50 50;" xml:space="preserve">
                        <rect x="0" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml" attributeName="transform" 
                                type="translate" values="0 0; 0 20; 0 0" begin="0" 
                                dur="0.6s" repeatCount="indefinite" />
                        </rect>
                        <rect x="10" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml" attributeName="transform" 
                                type="translate" values="0 0; 0 20; 0 0" begin="0.2s" 
                                dur="0.6s" repeatCount="indefinite" />
                        </rect>
                        <rect x="20" y="0" width="4" height="10" fill="#333">
                            <animateTransform attributeType="xml" attributeName="transform" 
                                type="translate" values="0 0; 0 20; 0 0" begin="0.4s" 
                                dur="0.6s" repeatCount="indefinite" />
                        </rect>
                    </svg>
                </div>
            </div>

            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th><i style="cursor: pointer;" class="fas fa-upload fa-2x" data-toggle="tooltip" title="Créer tous les éléments manquants" onclick="genererTousLesManquants();"></i>
                            &nbsp;&nbsp;Informations</th>
                        <th class="text-center"><i style="cursor: pointer;" class="fas fa-file-signature fa-2x" data-toggle="tooltip" title="Parcourir les fichiers" onclick="/*parcourirArborescence();*/window.location.href = 'ged.php?parcourirArborescence=1&path=';"></i></th>
                        <th class="text-center"><i class="fas fa-edit fa-2x" data-toggle="tooltip" title="De ce côté se trouvent les éditeurs (cliquez sur l'icône à gauche de la ligne"></i>&nbsp;&nbsp;Editeur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    unset($_SESSION['fichiersManquants']);
                    foreach($dossiers as $key => $d) {
                        if($d !== "") {
                            echo '<tr>';
                            $chemin_recette = LIEN_RECETTE.$d;
                            $chemin_public = LIEN_PROD.$d;
                            $dossier_recette_existe = is_dir($chemin_recette) ? true : false;
                            $fichier_recette_existe = is_file($chemin_recette) ? true : false;
                            $dossier_public_existe = is_dir($chemin_public) ? true : false;
                            $fichier_public_existe = is_file($chemin_public) ? true : false;
                            
                            if($dossier_recette_existe === true && $dossier_public_existe === true && $fichier_recette_existe === false && $fichier_public_existe === false) {
                                echo '<td style="color: #3fff33;">Les deux dossiers <b>'.$d.'</b> exisent</td><td></td><td></td>';
                            } else {
                                if($fichier_recette_existe === true && $fichier_public_existe === true) {
                                    echo '<td style="color: #3fff33;">Les deux fichiers <b>'.$d.'</b> exisent';
                                    if(files_identical($chemin_public, $chemin_recette)) {
                                        echo ' et sont <b style="color: green;">identiques</b>';
                                    } else {
                                        echo ' et sont <b style="color: red;">différents:</b> <i style="cursor: pointer;" onclick="afficherModaleComparateur(\''.$d.'\', \''.$d.'\');" data-toggle="tooltip" data-placement="right" title="Voir les deux fichiers côte à côte" ><svg style="height: 2vh;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path style="fill: #6560a1;" d="M320 488C320 497.5 314.4 506.1 305.8 509.9C297.1 513.8 286.1 512.2 279.9 505.8L199.9 433.8C194.9 429.3 192 422.8 192 416C192 409.2 194.9 402.7 199.9 398.2L279.9 326.2C286.1 319.8 297.1 318.2 305.8 322.1C314.4 325.9 320 334.5 320 344V384H336C371.3 384 400 355.3 400 320V153.3C371.7 140.1 352 112.8 352 80C352 35.82 387.8 0 432 0C476.2 0 512 35.82 512 80C512 112.8 492.3 140.1 464 153.3V320C464 390.7 406.7 448 336 448H320V488zM456 79.1C456 66.74 445.3 55.1 432 55.1C418.7 55.1 408 66.74 408 79.1C408 93.25 418.7 103.1 432 103.1C445.3 103.1 456 93.25 456 79.1zM192 24C192 14.52 197.6 5.932 206.2 2.076C214.9-1.78 225-.1789 232.1 6.161L312.1 78.16C317.1 82.71 320 89.2 320 96C320 102.8 317.1 109.3 312.1 113.8L232.1 185.8C225 192.2 214.9 193.8 206.2 189.9C197.6 186.1 192 177.5 192 168V128H176C140.7 128 112 156.7 112 192V358.7C140.3 371 160 399.2 160 432C160 476.2 124.2 512 80 512C35.82 512 0 476.2 0 432C0 399.2 19.75 371 48 358.7V192C48 121.3 105.3 64 176 64H192V24zM56 432C56 445.3 66.75 456 80 456C93.25 456 104 445.3 104 432C104 418.7 93.25 408 80 408C66.75 408 56 418.7 56 432z"/></svg></i>';
                                    }
                                    echo '</td><td></td><td></td>';
                                } else {
                                    if($dossier_recette_existe === false && $fichier_recette_existe === false && strpos($d, '.') !== false) {
                                        echo '<td style="color: orange;"><i style="cursor: pointer;" class="fas fa-file-upload fa-2x" onclick="afficherEditeurCode('.$key.', 0, \''.$d.'\');"></i>&nbsp;Le fichier <b>'.$d.'</b> n\'existe pas en recette</td><td></td><td><textarea class="hidden" id="file-editor-'.$key.'"></textarea><span class="hidden" id="btn-file-editor-'.$key.'"></span></td>';
                                        $_SESSION['fichiersManquants']['recette']['fichiers'][] = $d;
                                    } elseif(strpos($d, '.') === false && $dossier_recette_existe === false) {
                                        echo '<td style="color: orange;"><i style="cursor: pointer;" class="fas fa-folder-plus fa-2x" onclick="creerDossier(0, \''.$d.'\');"></i>&nbsp;Le dossier <b>'.$d.'</b> n\'existe pas en recette</td><td></td><td></td>';
                                        $_SESSION['fichiersManquants']['recette']['dossiers'][] = $d;
                                    }
                                    if($dossier_public_existe === false && $fichier_public_existe === false && strpos($d, '.') !== false) {
                                        echo '<td style="color: red;"><i style="cursor: pointer;" class="fas fa-file-upload fa-2x" onclick="afficherEditeurCode('.$key.', 1, \''.$d.'\');"></i>&nbsp;Le fichier <b>'.$d.'</b> n\'existe pas en production</td><td></td><td><textarea class="hidden" id="file-editor-'.$key.'"></textarea><span class="hidden" id="btn-file-editor-'.$key.'"></span></td>';
                                        $_SESSION['fichiersManquants']['public']['fichiers'][] = $d;
                                    } elseif(strpos($d, '.') === false && $dossier_public_existe === false) {
                                        echo '<td style="color: red;"><i style="cursor: pointer;" class="fas fa-folder-plus fa-2x" onclick="creerDossier(1, \''.$d.'\');"></i>&nbsp;Le dossier <b>'.$d.'</b> n\'existe pas en production</td><td></td><td></td>';
                                        $_SESSION['fichiersManquants']['public']['dossiers'][] = $d;
                                    }
                                }
                            }
                            echo '</tr>';
                        }
                    } ?>
                </tbody>
            </table>
            
            <div class="modal fade modal-dark" id="modale_comparateur_fichiers" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xlg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Comparateur de fichiers&nbsp;:&nbsp;<span id="modale_comparateur_title"></span></h5>
                            <button type="button" class="btn btn-danger btn-md" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <label for="file-edit-tree-left">Recette&nbsp;:</label>
                                        <textarea rows="15" cols="15" id="file-edit-tree-left" name="file-edit-tree-left">&nbsp;</textarea>
                                    </div>
                                    <div class="col-sm-2 text-center">
                                        <i class="fas fa-angle-double-right fa-2x" style="margin-top: 15vh;"></i>
                                    </div>
                                    <div class="col-sm-5">
                                        <label for="file-edit-tree-right">Production&nbsp;:</label>
                                        <textarea rows="15" cols="15" id="file-edit-tree-right" name="file-edit-tree-right">&nbsp;</textarea>
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

            <script type="text/javascript" src="js/jquery.min.js"></script>
            <!-- Bootstrap tooltips -->
            <script type="text/javascript" src="js/popper.min.js"></script>
            <!-- Bootstrap core JavaScript -->
            <script type="text/javascript" src="js/bootstrap.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/codemirror.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/xml/xml.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/php/php.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/sql/sql.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/javascript/javascript.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/powershell/powershell.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/spreadsheet/spreadsheet.min.js"></script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.3/mode/shell/shell.min.js"></script>

            <script>
                $('#loading').removeClass('hidden');

                $(document).ready(function() {
                    window.codeMirror = undefined;
                    window.fileEditTreeLeft = undefined;
                    window.fileEditTreeRight = undefined;
                    $('[data-toggle="tooltip"]').tooltip();
                    $('#loading').addClass('hidden');
                    var urlParams = new URLSearchParams(window.location.href);

                    if(urlParams.get("sort") === "desc") {
                        $("#filtre_1").attr("filtre", "asc");
                    } else {
                        $("#filtre_1").attr("filtre", "desc");
                    }
                });
            </script> 
            <script>                
                function afficherEditeurCode(iNumIndex, bEstProd, sNomFichier) {
                    if(window.codeMirror !== undefined) {
                        reinitFileEditor();
                    }
                    if(bEstProd === true) {
                        $('#btn-file-editor-'+iNumIndex).html('').html('<button class="btn btn-primary" onclick="creerFichier('+ iNumIndex + ', 1, \''+ sNomFichier +'\');">Créer !</button><button class="btn btn-light" onclick="reinitFileEditor('+ iNumIndex +');">Fermer</button>');
                    } else {
                        $('#btn-file-editor-'+iNumIndex).html('').html('<button class="btn btn-primary" onclick="creerFichier('+ iNumIndex + ', 0, \''+ sNomFichier +'\');">Créer !</button><button class="btn btn-light" onclick="reinitFileEditor('+ iNumIndex +');">Fermer</button>');
                    }
                    $('#btn-file-editor-'+iNumIndex).removeClass('hidden');
                    $('#btn-file-editor-'+iNumIndex).addClass('textarea-a-retirer');
                    $('#file-editor-'+iNumIndex).addClass('textarea-a-retirer');
                    window.codeMirror = CodeMirror.fromTextArea(document.getElementById('file-editor-'+iNumIndex), {
                        lineNumbers: true,
                        theme: 'monokai', 
                        tabSize: 2
                    });
                    window.codeMirror.setValue('console.log("Hello, World");');
                    $('#file-editor-'+iNumIndex).removeClass('hidden');
                }
                
                $('#modale_comparateur_fichiers').on('shown.bs.modal', function() {
                    window.fileEditTreeLeft.refresh();
                    window.fileEditTreeRight.refresh();
                });
                $('#modale_comparateur_fichiers').on('hidden.bs.modal', function() {
                    window.fileEditTreeLeft.toTextArea();
                    window.fileEditTreeLeft = undefined;
                    window.fileEditTreeRight.toTextArea();
                    window.fileEditTreeRight = undefined;
                });
				
				function afficherModaleComparateur(sLienFichier, sNomFichier, bChangerDroits = false, bCreerFichier = false) {
                    $('#modale_comparateur_title').html('');
                    
                    $.ajax({
                        url: 'ged.php',
                        method: 'post', 
                        dataType: 'json', 
                        data: {
                           afficherModaleComparateur: 1, 
                           sLienFichier: sLienFichier, 
                           sNomFichier: sNomFichier, 
                           bChangerDroits: bChangerDroits, 
                           bCreerFichier: bCreerFichier
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            if(r.reload === true) {
                                afficherModaleComparateur(sLienFichier, sNomFichier);
                            } else {
                                if(r.success === true) {
                                    if(window.fileEditTreeLeft === undefined) {
                                        window.fileEditTreeLeft = CodeMirror.fromTextArea(document.getElementById('file-edit-tree-left'), {
                                            lineNumbers: true,
                                            theme: 'monokai', 
                                            tabSize: 1, 
                                            matchBrackets: true,
                                            styleActiveLine: true,
                                        });
                                        window.fileEditTreeLeft.setOption("mode", r.fichier_recette_type);
                                        window.fileEditTreeLeft.setValue(r.fichier_recette);
                                    }

                                    if(window.fileEditTreeRight === undefined) {
                                        window.fileEditTreeRight = CodeMirror.fromTextArea(document.getElementById('file-edit-tree-right'), {
                                            lineNumbers: true,
                                            theme: 'monokai', 
                                            tabSize: 1, 
                                            matchBrackets: true,
                                            styleActiveLine: true,
                                        });
                                        window.fileEditTreeRight.setOption("mode", r.fichier_prod_type);
                                        window.fileEditTreeRight.setValue(r.fichier_prod);
                                    }
                                    
                                    const urlParams = new URLSearchParams(window.location.search);
                                    const path = urlParams.get('path').replace(/\/recette/, '').replace(/\/public/, '').replace(/recette/, '').replace(/public/, '').replace(/\/\//, '');
                                    $('#btn_save_recette').attr('onclick', "event.preventDefault(); modifierFichier('recette"+path+"/"+sNomFichier+"', window.fileEditTreeLeft);");
                                    $('#btn_save_production').attr('onclick', "event.preventDefault(); modifierFichier('public"+path+"/"+sNomFichier+"', window.fileEditTreeRight);");
                                    $('#modale_comparateur_title').html(sLienFichier);
                                    $('#modale_comparateur_fichiers').modal('show');
                                    $('#modale_comparateur_fichiers').modal('handleUpdate');
                                } else {
                                    $('#toast_icone').html(r.icone);
                                    $('#toast_icone').addClass('toast_red');
                                    $('#toast_message').html('&nbsp;&nbsp;  '+r.message+'&nbsp;&nbsp;<br/>&nbsp;'+r.suggestion);
                                    $('#toast').removeClass('hidden');
                                    setTimeout(function() {
                                        $('#toast').addClass('hidden');
                                    }, 5000);
                                } 
                            }
                        }, 
                        complete: function() {
                           $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }
                
                function genererTousLesManquants() {
                    $.ajax({
                        url: 'ged.php',
                        method: 'post', 
                        data: {
                           genererTousLesManquants: 1
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            window.location.reload();
                        }, 
                        complete: function() {
                           $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }

                function reinitFileEditor(iNumIndex = 0) {
                    window.codeMirror.toTextArea();
                    if(iNumIndex > 0) {
                        $('#btn-file-editor-'+iNumIndex).html('');
                        $('#file-editor-' + iNumIndex).addClass('hidden');
                    } else{
                        $('.textarea-a-retirer').each(function() {
                            $(this).removeClass('textarea-a-retirer');
                            $(this).html('');
                            $(this).addClass('hidden');
                        });
                    }
                    window.codeMirror = undefined;
                }

                function parcourirArborescence(path = '/') {
                    $.ajax({
                        url: 'ged.php',
                        method: 'get', 
                        dataType: 'json', 
                        data: {
                           parcourirArborescence: 1, 
                           path: path
                        }, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            var newDoc = document.open("text/html", "replace");
                            newDoc.write(r.html);
                            newDoc.close();
                        }, 
                        complete: function() {
                           $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }

                function creerDossier(bEstProd, sNomDossier) {
                    $.ajax({
                        url: 'ged.php',
                        method: 'post', 
                        data: {
                           creerDossier: 1, 
                           bEstProd: bEstProd, 
                           sNomDossier: sNomDossier
                        },  
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            window.location.reload();
                        }, 
                        complete: function() {
                           $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }

                function creerFichier(iNumIndex, bEstProd, sNomFichier) {
                    var formData = new FormData();
                    formData.append("creerFichier", 1);
                    formData.append("bEstProd", bEstProd);
                    formData.append("sNomFichier", sNomFichier);
                    formData.append("sContenuFichier", window.codeMirror.getValue());

                    $.ajax({
                        url: 'ged.php',
                        method: 'post',  
                        processData: false,
                        contentType: false,
                        data: formData, 
                        beforeSend: function() {
                           $('#loading').removeClass('hidden'); 
                        }, 
                        success: function(r) {
                            reinitFileEditor(iNumIndex);
                            window.location.reload();
                        }, 
                        complete: function() {
                           $('#loading').addClass('hidden'); 
                        }, 
                        error: function() {
                           $('#loading').addClass('hidden'); 
                        }
                    });
                }
            </script>
        </body>
    </html>
<?php } ?>