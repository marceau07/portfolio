<?php

function actionScript($twig, $db) {
    if(!empty($_POST['majSignature'])) {
        $fp = fopen("/var/www/html/symfony4-4017/public/parcinformatique/web/img/signatures/signature_".$_POST['idScript'].".png", "wb" );
        fwrite($fp, base64_decode(explode( ',', $_POST['image'])[1])); 
        fclose($fp);
        $sql_update_signature = 'UPDATE parc_informatique_scripts 
                                SET signature = "signature_'.$_POST['idScript'].'.png" 
                                WHERE idScript='.$_POST['idScript'];
        $req_update_signature = $db->prepare($sql_update_signature);
        $req_update_signature->execute();
    }
    $form = array();

    $script = new Script($db);
    $listeS = $script->select();
    
    $os = new Os($db);
    $listeOs = $os->select();

    $dossier = '/var/www/html/symfony4-4017/public/parcinformatique/src/scripts/';
    $ouverture = opendir($dossier);

    $listeFichier = array();
    while ($fichier = readdir($ouverture)) {
        if ($fichier != '.' && $fichier != '..' && $fichier != '.gitkeep') {
            $listeFichier[] = $fichier;
        }
    }
    closedir($ouverture);

    foreach ($listeFichier as $contenu) {    
        $test = pathinfo($contenu, PATHINFO_FILENAME);
        $form[$contenu] = file_get_contents("/var/www/html/symfony4-4017/public/parcinformatique/src/scripts/$contenu");
    }

    $fn = file_get_contents("/var/www/html/symfony4-4017/public/parcinformatique/src/ip/resultatScriptIP.txt", "r");
    list($ip, $mac, $os) = explode(";", $fn);

    echo $twig->render('script.html.twig', array('form' => $form, 'listeS' => $listeS, 'listeFichier' => $listeFichier, 'listeOs' => $listeOs));
}

function actionAjouterScript($twig, $db) {
    $form = array();
    $os = new Os($db);
    $listeOS = $os->select();
    if (isset($_POST['btAjouterScript'])) {

        $nomScript = htmlspecialchars($_POST['nomScript']);
        $version = htmlspecialchars($_POST['version']);
        $descScript = htmlspecialchars($_POST['descScript']);
        $idOs = htmlspecialchars($_POST['idOs']);
        $fichierScript = NULL;
        if (isset($_FILES['fichier'])) {
            if (!empty($_FILES['fichier']['name'])) {
                $extensions_ok = array('sh', 'SH', 'php', 'PHP', 'cmd', 'CMD', 'bat', 'BAT', 'PS1', 'ps1');
                $taille_max = 500000;
                $dest_dosser = '/var/www/html/symfony4-4017/public/parcinformatique/src/scripts/';
                if (!in_array(substr(strrchr($_FILES['fichier']['name'], '.'), 1), $extensions_ok)) {
                    echo 'Veuillez sélectionner un fichier de type sh !';
                } else {
                    if (file_exists($_FILES['fichier']['tmp_name']) && (filesize($_FILES['fichier']['tmp_name'])) > $taille_max) {
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    } else {
                        $fichierScript = basename($_FILES['fichier']['name']);
                        $fichierScript = strtr($fichierScript, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAA
AAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        $fichierScript = preg_replace('/([^.a-z0-9]+)/i', '_', $fichierScript);
                        move_uploaded_file($_FILES['fichier']['tmp_name'], $dest_dosser . $fichierScript);
                    }
                }
            }
        }        
        $script = new Script($db);

        $exec = $script->insert($nomScript, $version, $descScript, $idOs, $fichierScript);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Le script a été ajouté.';
        }
    }
    echo $twig->render('ajouterScript.html.twig', array('form' => $form, 'listeOS' => $listeOS));
}

function actionInstallationScript($twig, $db) {
    $form = array();

    $ordinateur = new Ordinateur($db);

    $listeO = $ordinateur->select();

    echo $twig->render('installerScript.html.twig', array('form' => $form, 'listeO' => $listeO));
}
