<?php

function actionEmployeWS($twig, $db) {

    $idEmploye = $_GET['idEmploye'];
    $email = $_GET['email'];
    $nomEmploye = $_GET['nomEmploye'];
    $prenomEmploye = $_GET['prenomEmploye'];

    $employe = new Employe($db);

    if ($_GET['action'] == 'edit') {
        $exec = $employe->update($email, $nomEmploye, $prenomEmploye, $idEmploye);
    } else if ($_GET['action'] == 'delete') {
        $exec = $employe->delete($idEmploye);
    }
}

function actionScriptWS($twig, $db) {
    $v = array();
    $script = new Script($db);
    $os = new Os($db);

    $idScript = $_GET['idScript'];
    $nomScript = $_GET['nomScript'];
    $version = $_GET['version'];
    $descScript = $_GET['descScript'];
    $idOs = $_GET['idOS'];
    $nomFichier = $_GET['nomFichier'];
    $contenu = $_GET['fichierScript'];

    $file = '/var/www/html/symfony4-4017/public/parcinformatique/src/scripts/' . $nomFichier;
    
    $v['idOs'] = $idOs;

    if ($_GET['action'] == 'edit') {
        $exec = $script->update($nomScript, $version, $descScript, $idOs, $idScript);
        if ($file == false) {
            $v['status'] = 'Error: ' . $file;
        } else {
            file_put_contents($file, $contenu);
            $v['status'] = 'fichier enregistré sous '. $file;
        }
    } else if ($_GET['action'] == 'delete') {
        $exec = $script->delete($idScript);
    }
    $json = json_encode($v);
    echo $json;
}

function actionParcWS($twig, $db) {
    $v = array();
    foreach ($_GET as $uneValeur) {
        $v['valeur'] = $v['valeur'] . $uneValeur;
    }

    $idOrdinateur = $_GET['idOrdinateur'];
    $ip = $_GET['ip'];
    $mac = $_GET['mac'];
    $reseau = $_GET['reseau'];
    $os = $_GET['os'];
    $statut = $_GET['statut'];
    $employe = $_GET['employe'];

    $ordinateur = new Ordinateur($db);

    if ($_GET['action'] == 'edit') {
        $exec = $ordinateur->update($ip, $mac, $reseau, $os, $statut, $employe);
    } else if ($_GET['action'] == 'delete') {
        $exec = $ordinateur->delete($idOrdinateur);
    }
    $json = json_encode($v);
    echo $json;
}

function actionSsh($twig, $db) {
    extract($_POST);
    if (!empty($server) || !empty($port) || !empty($login) || !empty($password)) {
//        $connection = ssh2_connect($server, $port);

        $connection = ssh2_connect('10.239.19.30', 22);
//        $_SESSION['connexion'] = ssh2_auth_password($connection, 'login4018', 'EMDduFSpXYBQzOe');
        
        $_SESSION['connexion'] = ssh2_auth_password($connection, $login, $password);
        if ($_SESSION['connexion']) {
            echo 'ok';
            $_SESSION['serverSSH'] = "10.239.19.30";
            $_SESSION['portSSH'] = 22;
            $_SESSION['loginSSH'] = $login;
            $_SESSION['passwordSSH'] = $password;
        } else {
            echo 'Connexion au serveur impossible.';
        }
    } else {
        echo "Veuillez remplir tous les champs";
    }
}

function actionCmd($twig, $db) {
    extract($_POST);
    $connection = ssh2_connect('10.239.19.30', 22);
    if (ssh2_auth_password($connection, $_SESSION['loginSSH'], $_SESSION['passwordSSH'])) {
        /**
         * Pwd pour afficher le chemin actuel
         */
        $streamPWD = ssh2_exec($connection, "pwd");
        stream_set_blocking($streamPWD, true);
        $stream_out_pwd = ssh2_fetch_stream($streamPWD, SSH2_STREAM_STDIO);
        $resultatPWD = stream_get_contents($stream_out_pwd);
        /**
         * $HOME pour afficher le login de l'utilisateur actuel
         */
        $streamHome = ssh2_exec($connection, "echo \$HOSTNAME");
        stream_set_blocking($streamHome, true);
        $stream_out_home = ssh2_fetch_stream($streamHome, SSH2_STREAM_STDIO);
        $resultatHome = stream_get_contents($stream_out_home);
        /**
         * Exécution de la commande saisie dans le terminal pour afficher le chemin actuel
         */
        $streamCMD = ssh2_exec($connection, $commande);
        stream_set_blocking($streamCMD, true);
        $stream_out_cmd = ssh2_fetch_stream($streamCMD, SSH2_STREAM_STDIO);
        $resultatCMD = stream_get_contents($stream_out_cmd);

        echo $resultatHome, $resultatPWD, $resultatCMD;
    }
}

function adminSshAccess(Request $request) {
    $chemin = '/var/www/html/symfony4-4017';
    $path = '';

    if ($request->get('path') != null) {
        $path = $request->get('path');
        $chemin = '/var/www/html' . $path;
    }
    $t = explode("/", $path);
    if (count($t) > 0) {
        $prec = '/';
        for ($i = 0; $i < count($t) - 1; $i++) {
            if (!empty($t[$i])) {
                $prec .= $t[$i];
                if ($i < count($t) - 2) {
                    $prec .= '/';
                }
            }
        }
    } else {
        $prec = '';
    }
    if (($path == '/') || empty($path)) {
        $prec = '';
        $path = '';
    }
    $connection = ssh2_connect('10.239.19.30', 22);
    ssh2_auth_password($connection, 'login4018', 'EMDduFSpXYBQzOe');

    $sftp = ssh2_sftp($connection);
    $sftp_fd = intval($sftp);

    $dir = 'ssh2.sftp://' . $sftp_fd . $chemin;
    $handle = opendir($dir);

    $content = array();
    $content['handle'] = $handle;

    $rep = array();
    $fichiers = array();
    while (false != ($file = readdir($handle))) {
        if (substr("$file", 0, 1) != ".") {
            if (is_dir($file)) {
                
            } else {
                $info = stat($dir . '/' . $file);
                if ((string) is_dir($dir . '/' . $file) == 1) {
                    $rep[] = array_merge($info, array('name' => $file, 'is_dir' => (string) is_dir($dir . '/' . $file)));
                } else {
                    $fichiers[] = array_merge($info, array('name' => $file, 'is_dir' => (string) is_dir($dir . '/' . $file)));
                }
            }
        }
    }
}

function actionListeOrdinateurPdf($twig, $db) {
    $ordinateur = new Ordinateur($db);
    $liste = $ordinateur->select();
    if(isset($_GET['numReseau'])) {
        $liste = $ordinateur->selectByNetwork($_GET['numReseau']);
    }
    $html = $twig->render('ordinateur-liste-pdf.html.twig', array('liste' => $liste));

    try {
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('listeOrdinateurs.pdf');
    } catch (Html2PdfException $e) {
        echo 'erreur ' . $e;
    }
}
