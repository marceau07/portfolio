<?php 
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/admin/php/global_functions.php';

if(!empty($_POST["afficherArborescence"])) {
	$chemin = $_POST['chemin'];
	if (strpos($_POST['chemin'], '..') !== false ||
		strpos($_POST['chemin'], '~') !== false) {
			die('stop');
	}
	$full = BASE_LIEN . $chemin;
	if (!$files = scandir($full)) {
		die('stop');
	}
	$arbo = "";
	$lien_en_consultation = '<input type="hidden" id="chemin_en_consultation" name="chemin_en_consultation" value="'.addslashes($chemin).'" />';

	// Dossier supérieur
	if (strlen($chemin) > 1) {
		$expl_chemin = explode('/', $chemin);
		$upper = '';
		for ($i = count($expl_chemin) - 3; $i >= 0; $i--) {
			$upper = $expl_chemin[$i] .'/'. $upper ;
		}
		$arbo .= '<div class="upper" onclick="afficherArborescence(\''.addslashes($upper).'\');"><i class="fa fa-level-up" style="transform: scale(-1, 1);"></i>&nbsp;..</div>';
	}


	// Contenu
	$fichier_presents = false;
	foreach($files as $f) if($f !== '.' && $f !== '..' && substr($f, 0, 1) !== '.') {
		$fichier_presents = true;
		$explode = explode('.', $f);
		if(!isset($explode[1]) && is_dir($full.$f)) {
			$arbo .= '<div class="dir" onclick="afficherArborescence(\''.addslashes($chemin.$f).'/'.'\');"><i class="fa fa-folder-open"></i>&nbsp;<span>'.$f.'</span></div>';
		}
	}
	foreach($files as $f) if($f !== '.' && $f !== '..' && substr($f, 0, 1) !== '.') {
		$fichier_presents = true;
		$explode = explode('.', $f);
		if(isset($explode[1]) || is_file($full.$f)) {
			$arbo .= '<div class="file" onclick="editionFichier(\''.addslashes($chemin.$f).'\');"><i class="fa fa-file"></i>&nbsp;<span>'.$f.'</span></div>';
		}
	}

	$html = $arbo;
	if($html === "" || $fichier_presents === false) {
	$html .= '
			<div class="row" style="padding-top: 15px;">
				<div class="col-xs-3">
					<div class="alert alert-warning">
						<span>Aucun dossier trouvé</span>
					</div>
				</div>
			</div>';
	}
	$html = $lien_en_consultation . $html;
	die($html);
}

if(!empty($_POST["editionFichier"])) {
	$explode = explode(".", (BASE_LIEN.$_POST["fichier"]));
	switch($explode[sizeof($explode)-1]) {
		case "cs": 
			$type = "csharp";
			break;
		case "css": 
			$type = "css";
			break;
		case "dart": 
			$type = "dart";
			break;
		case "html": 
			$type = "html";
			break;
		case "java": 
			$type = "java";
			break;
		case "js": 
			$type = "javascript";
			break;
		case "lock": 
		case "json": 
			$type = "json";
			break;
		case "kotlin": 
			$type = "kotlin";
			break;
		case "less": 
			$type = "less";
			break;
		case "md": 
			$type = "markdown";
			break;
		case "php": 
			$type = "php";
			break;
		case "py": 
			$type = "python";
			break;
		case "rb": 
			$type = "ruby";
			break;
		case "sass": 
			$type = "sass";
			break;
		case "scss": 
			$type = "scss";
			break;
		case "sh": 
			$type = "sh";
			break;
		case "svg": 
			$type = "svg";
			break;
		case "sql": 
		case "sqlite": 
		case "sqlite3": 
			$type = "sql";
			break;
		case "twig": 
			$type = "twig";
			break;
		case "xml": 
			$type = "xml";
			break;
		case "yml": 
		case "yaml": 
			$type = "yaml";
			break;
		default:  
			$type = "plain_text";
			break;
	}
	
	die(json_encode(array(
		"contenu" => str_replace(array("<", "&"), array("<code><</code>", "<code>&</code>"), shell_exec("cat ".BASE_LIEN.$_POST["fichier"])),
		"type" => $type
	)));
}
?>