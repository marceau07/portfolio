<?php 

?>
<!DOCTYPE html>
<html lang="fr" id="html">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>Gestion documentaire</title>
		<!-- Font Awesome -->
		<link rel="stylesheet" href="//pro.fontawesome.com/releases/v5.10.0/css/all.css" 
			integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" 
			crossorigin="anonymous"/>
		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
		<style>		
			.hidden {
				display: none !important;
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
			svg path, svg rect {
				fill: #FF6700;
			}
			
			.file > i {
				color: #cfe2f3;
			}
			.dir > i {
				color: #EFBB1A;
			}
			
			#editeur_fichier {
				position: absolute;
				width: 65vw;
				height: 100vh;
				left: 35vw;
			}
			#arborescence_fichiers > div > span {
				margin: 5px 10px;
			}
			#arborescence_fichiers > div:hover {
				border: 2px solid #b2e3ff;
			}
		</style>
	</head>
	<body style="background-color: #181818; color: aliceblue;">		
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
	
		<div class="container">
			<div class="row">
				<div class="col-xs-6">
					<div id="arborescence_fichiers">
					</div>
				</div>
				<div class="col-xs-6">
					<div id="editeur_fichier_wrapper">
						<div id="editeur_fichier"></div>
					</div>
				</div>
			</div>
		</div>
	
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<!-- Bootstrap tooltips -->
		<script type="text/javascript" src="js/popper.min.js"></script>
		<!-- Bootstrap core JavaScript -->
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<!-- ACE editor -->
		<script src="js/ace/ace.js" type="text/javascript" charset="utf-8"></script> 
		<script src="js/ace/theme-twilight.js" type="text/javascript" charset="utf-8"></script> 
		<script src="js/ace/ext-beautify.js" type="text/javascript" charset="utf-8"></script> 
		<!-- Fin ACE editor -->
		<script>
			function afficherArborescence(chemin = '/') {
				$("#arborescence_fichiers").html("");
				if(editeur_fichier !== undefined) {
					editeur_fichier.destroy();
					editeur_fichier.container.remove();
					$("#editeur_fichier_wrapper").html('<div id="editeur_fichier"></div>');
				}
				$.ajax({
					url: 'admin/php/ged_ajax.php',
					method: 'post', 
					dataType: 'html', 
					data: {
					   afficherArborescence: 1, 
					   chemin: chemin, 
					}, 
					beforeSend: function() {
					   $('#loading').removeClass('hidden'); 
					}, 
					success: function(r) {
						$("#arborescence_fichiers").html(r);
					}, 
					complete: function() {
						$('#loading').addClass('hidden'); 
					}, 
					error: function() {
					   $('#loading').addClass('hidden'); 
					}
				});
			}
			
			var editeur_fichier = undefined;
			function editionFichier(fichier) {
				if(editeur_fichier !== undefined) {
					editeur_fichier.destroy();
					editeur_fichier.container.remove();
					$("#editeur_fichier_wrapper").html('<div id="editeur_fichier"></div>');
				}
				$.ajax({
					url: 'admin/php/ged_ajax.php',
					method: 'post', 
					dataType: 'json', 
					data: {
					   editionFichier: 1, 
					   fichier: fichier, 
					},  
					success: function(r) {
						$("#editeur_fichier").html(r.contenu);
						editeur_fichier = ace.edit("editeur_fichier");
						editeur_fichier.setTheme("ace/theme/twilight");
						editeur_fichier.setOptions({ enableSnippets: true });
						editeur_fichier.session.setMode({ path: "ace/mode/" + r.type, inline: true });
					}, 
				});
			}
			
			<?php 
			if(isset($_GET["chemin"]) && substr($_GET["chemin"], 0, 1) !== "/") {
				$chemin = "/".$_GET["chemin"];
			} elseif(isset($_GET["chemin"]) && substr($_GET["chemin"], 0, 1) === "/") {
				$chemin = $_GET["chemin"];
			} else {
				$chemin = "/";
			}
				?>
			afficherArborescence('<?=$chemin?>');
		</script>
	</body>
</html>
