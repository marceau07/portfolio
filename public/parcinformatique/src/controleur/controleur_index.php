<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Mpdf;

function actionAccueil($twig, $db) {
    extract($_POST);
    $form = array();
    $ordinateur = new Ordinateur($db);
    $listeO= null;
    $nbNetworks = $ordinateur->selectDistinctReseau();
    if (!empty($pc_networks) && !empty($id_network)) {
        if($id_network <> '%') {
            $nbReseau = $_SESSION['numReseau'] = $id_network;
        } else {
            unset($_SESSION['numReseau']);
            $nbReseau = '%';
        }
        $listeO = $ordinateur->selectCarousel($nbReseau);
        
        $chartData = array();
        $listePc = array();
        foreach($listeO as $value) {
            $chartData[] = array("name" => $nbReseau, "children" => ["name" => $value['ip']]);
            
            $listePc[] = array(
                "pc_lien" => '<img src="../web/img/computer.png" id="pc" width="50" height="50">', 
                "pc_ip" => $value['ip'], 
                "pc_nom_os" => $value['nomOs'], 
                "pc_nom_statut" => $value['nomStatut'] . '<div class="statut ' . ($value['nomStatut'] == "En ligne" ? 'correct' : 'eteint') . '" style="margin-left: calc(40%);"></div>', 
                "pc_nom_employe" => $value['unEmploye'], 
                "pc_bouton" => ($value['nomStatut'] == "En ligne" && explode('.', $value['ip'])[3] <> '0' ? '<button type="button" class="btn btn-white" onclick="ouvrirModaleSshAjax(' . $value['idOrdinateur'] . ')">En savoir plus...</button>' : '')
            );
        }
        die(json_encode(array(
            "chartData" => $chartData, 
            "listePc" => $listePc, 
            "nbNetworks" => $nbNetworks, 
        )));
    }
    
    if(!empty($genererCSV)) {
        $spreadsheet = new Spreadsheet();

        //Specify the properties for this document
        $spreadsheet->getProperties()
            ->setTitle('PHP Download Example')
            ->setSubject('A PHPExcel example')
            ->setDescription('A simple example for PhpSpreadsheet. This class replaces the PHPExcel class')
            ->setCreator('php-download.com')
            ->setLastModifiedBy('php-download.com');

        //Adding data to the excel sheet
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'This')
            ->setCellValue('A2', 'is')
            ->setCellValue('A3', 'only')
            ->setCellValue('A4', 'an')
            ->setCellValue('A5', 'example');

        $spreadsheet->getActiveSheet()
            ->setCellValue('B1', "You")
            ->setCellValue('B2', "can")
            ->setCellValue('B3', "download")
            ->setCellValue('B4', "this")
            ->setCellValue('B5', "library")
            ->setCellValue('B6', "on")
            ->setCellValue('B7', "https://php-download.com/package/phpoffice/phpspreadsheet");


        $spreadsheet->getActiveSheet()
            ->setCellValue('C1', 1)
            ->setCellValue('C2', 0.5)
            ->setCellValue('C3', 0.25)
            ->setCellValue('C4', 0.125)
            ->setCellValue('C5', 0.0625);

        $spreadsheet->getActiveSheet()
            ->setCellValue('C6', '=SUM(C1:C5)');
        $spreadsheet->getActiveSheet()
            ->getStyle("C6")->getFont()
            ->setBold(true);


        $filename = 'liste_parc_pc';
        $extension = '.xlsx';
        $filepath = 'other/'.$filename.$extension;
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx"); //Xls is also possible
        $writer->save($filepath);
        
        
        
        
        
        
        
        $reader = IOFactory::createReaderForFile($filepath);
        $phpWord = $reader->load($filepath);

        $phpWord ->getDefaultStyle()->applyFromArray(
                    [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ]
                    ]
                );

        $xmlWriter = IOFactory::createWriter($phpWord,'Mpdf');

        $xmlWriter->writeAllSheets();
        //create folder named files
        $xmlWriter->save("other/fichier1.pdf");
        
        
        
        exit;
    }

    if (isset($_POST['turnOff'])) {
        /**
         * Attribution des droits nécessaires à tous les fichiers présents dans 'scripts'
         * chmod('/var/www/html/symfony4-4017/public/parcinformatique/src/scripts/*', 0755);
         */
        /**
         * Commande exécutant ledit script
         * $exec = exec("/var/www/html/symfony4-4017/public/parcinformatique/src/scripts/nmap.sh 2>&1", $output, $return_var);
         */
        if(isset($_SESSION['numReseau'])) {
            $exec = $ordinateur->updatePcOnOff(2, $_SESSION['numReseau']);
            $form['turnOff'] = false;
            $form['turnOffMsg'] = "Une erreur s'est produite lors de l'extinction des machines. Veuillez réessayer.";
            if ($exec) {
                $form['turnOff'] = true;
                $form['turnOffMsg'] = "Les ordinateurs du réseau 192.168." . $_SESSION['numReseau'] . ".X ont bien été éteints.";
            }
        } elseif(sizeof($nbNetworks) > 1) {
            $reseaux = '';
            foreach($nbNetworks as $key => $value) {
                $exec = $ordinateur->updatePcOnOff(2, $value['reseau']);
                $form['turnOff'] = false;
                $form['turnOffMsg'] = "Une erreur s'est produite lors de l'extinction des machines. Veuillez réessayer.";
                if ($exec) {
                    $form['turnOff'] = true;
                    $reseaux .= '10.239.' . $value['reseau'] . '.X' . ($key < sizeof($nbNetworks) - 1 ? ', ' : '');
                }
            }
            if($exec) {
                $form['turnOffMsg'] = "Les ordinateurs des réseaux " . $reseaux . " ont bien été éteints.";
            }
        }
    }
    $detect = new Mobile_Detect;

    $listeCanvas = $ordinateur->selectDistinctReseau();
    if ($detect->isMobile() || $detect->isTablet()) {
        $form['test'] = "version mobile";
    } else {
        $form['test'] = "version non mobile";
    }

    echo $twig->render('index.html.twig', array('form' => $form, 'listeO' => $listeO, 'listeCanvas' => $listeCanvas, 'nbNetworks' => $nbNetworks));
}

function actionDeconnexion() {
    session_unset();
    session_destroy();
    header("Location: ../web/");
}

function actionConnexion($twig, $db) {
    $form = array();

    if (isset($_POST['btConnexion'])) {
        $email = filter_input(INPUT_POST, 'emailConnexion', FILTER_SANITIZE_EMAIL);
        $mdp = filter_input(INPUT_POST, 'mdpConnexion', FILTER_SANITIZE_SPECIAL_CHARS);
        $employe = new Employe($db);
        $unEmploye = $employe->connect($email);
        if ($unEmploye != null) {
            if (!password_verify($mdp, $unEmploye['mdp'])) {
                $form['valide'] = false;
                $form['message'] = 'Mot de passe incorrect';
            } else {
                $form['valide'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['mdp'] = $mdp;
                $unEmploye = $employe->selectByEmail($email);
                $_SESSION['idRole'] = $unEmploye['idRole'];
                $_SESSION['idEmploye'] = $unEmploye['idEmploye'];
                $_SESSION['prenom'] = $unEmploye['prenomEmploye'];
                header("Location: ../web/");
            }
        } elseif ($unEmploye == false) {
            $form['valide'] = false;
            $form['message'] = 'Login incorrect';
        }
    }
    echo $twig->render('connexion.html.twig', array('form' => $form, 'session' => $_SESSION));
}

function actionInscription($twig, $db) {
    $form = array();

    if (isset($_POST['btInscription'])) {

        $email = htmlspecialchars($_POST['emailInscription']);
        $mdp1 = htmlspecialchars($_POST['mdp1']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $idRole = htmlspecialchars($_POST['idRole']);
        $form['valide'] = true;

        $employe = new Employe($db);

        if ($mdp1 != $mdp2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } elseif (strlen($mdp1) < 8) {
            $form['valide'] = false;
            $form['message'] = 'Votre mot de passe est trop court il doit contenir au minimum 8 caractères';
        } else {
            $_SESSION['emailInscription'] = $email;
            $employe = new Employe($db);
            $exec = $employe->insert($email, password_hash($mdp1, PASSWORD_DEFAULT), $nom, $prenom, $idRole);
//            var_dump($exec);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
                actionDeconnexion($twig);
            }
        }
        $form['emailInscription'] = $email;
    }
    echo $twig->render('inscription.html.twig', array('form' => $form, 'session' => $_SESSION));
}

function actionMaintenance($twig) {
    echo $twig->render('maintenance.html.twig', array());
}

function actionModalIndex($twig, $db) {
    $ordinateur = new Ordinateur($db);
    if (isset($_POST['idOrdinateur'])) {
        $idOrdinateur = htmlspecialchars($_POST['idOrdinateur']);
        $unOrdinateur = $ordinateur->selectById($idOrdinateur);

        echo json_encode($unOrdinateur);
    }
}

function actionGoogleAuth($twig, $db) {
    define("GOOGLE_ID", "944029488040-d090bocufmukr0lea89hvaobe0eeg471.apps.googleusercontent.com");
    define("GOOGLE_SECRET", "RXf3LuzHoX1ETQT5yLujIubN");

    $client = new GuzzleHttp\Client([
        'timeout' => 2.0,
        'verify' => '../web/other/cacert.pem',
    ]);
    $response = $client->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
    $discoveryJSON = json_decode((string) $response->getBody());
    $tokenEndpoint = $discoveryJSON->token_endpoint;
    $userinfoEndpoint = $discoveryJSON->userinfo_endpoint;
    
    try {
        $response = $client->request('POST', $tokenEndpoint, [
            'form_params' => [
                'code' => $_GET['code'],
                'client_id' => GOOGLE_ID,
                'client_secret' => GOOGLE_SECRET,
                'redirect_uri' => "http://serveur1.arras-sio.com/symfony4-4017/public/parcinformatique/web/index.php?page=googleAuth",
                'grant_type' => 'authorization_code'
            ]
        ]);
        $accessToken = json_decode($response->getBody())->access_token;
        $response = $client->request('GET', $userinfoEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);
        $response = json_decode($response->getBody());
        if ($response->email_verified === true) {
            $_SESSION['email'] = $response->email;
            $_SESSION['prenom'] = $response->given_name;
            header("Location: ../web");
        }
    } catch (GuzzleHttp\Exception\ClientException $ex) {
        var_dump($ex->getMessage());
    }
    var_dump($response);
}

function actionFacebookAuth($twig, $db) {
    $appId = '1751809268290063';
    $appSecret = '3e9ebc4a74edf7dc7bf3433030a376f2';

    $fb = new \Facebook\Facebook([
        'app_id' => $appId,
        'app_secret' => $appSecret,
        'default_graph_version' => 'v3.2',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email'];
    $loginUrl = $helper->getLoginUrl('http://serveur1.arras-sio.com/symfony4-4017/public/parcinformatique/web/index.php?page=facebookAuthCallback', $permissions);

    echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
}

function actionFacebookAuthCallback($twig, $db) {
    $appId = '1751809268290063';
    $appSecret = '3e9ebc4a74edf7dc7bf3433030a376f2';

    $fb = new \Facebook\Facebook([
        'app_id' => $appId,
        'app_secret' => $appSecret,
        'default_graph_version' => 'v3.2',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken();
    } catch (Facebook\Exceptions\FacebookResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if (!isset($accessToken)) {
        if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
        } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
        }
        exit;
    }

// Logged in
    echo '<h3>Access Token</h3>';
    var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
    $oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
    $tokenMetadata = $oAuth2Client->debugToken($accessToken);
    echo '<h3>Metadata</h3>';
    var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
    $tokenMetadata->validateAppId('{app-id}'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
    $tokenMetadata->validateExpiration();

    if (!$accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
        try {
            $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
            exit;
        }

        echo '<h3>Long-lived</h3>';
        var_dump($accessToken->getValue());
    }

    $_SESSION['fb_access_token'] = (string) $accessToken;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
}

function actionScan($twig, $db) {
    $ordinateur = new Ordinateur($db);

    // Vide la table
    $ordinateur->clearScan();
    
    $fn = file_get_contents("/var/www/html/symfony4-4017/public/parcinformatique/src/scan/result.txt", "r");
    // Explose dans une varianble à chaque ; trouvé
    $array = explode(';', $fn);

    foreach ($array as $k => $v) {
        var_dump($v);
        echo "<br />";
       
        // Explose à chaque ESPACE trouvé
        $array[$k] = explode(" ", $v);
        var_dump($array[$k]);
        echo "<br />";
        if ($array[$k][1] == 'report') {
            echo 'report';
            $mac = 'N/A';
            $status = 2;
        }
        else{
            if ($array[$k][1] == 'up'){
                echo 'up';
                $mac = 'N/A';
                $status = 1;
            }
            else{
                echo 'autre';
                $mac = $array[$k][1];
                $status = 1;
            }
        }

        
        //$i = (['ip ' => $array[$k][0]] + [' mac ' => $array[$k][1]]);
        /*if ($array[$k][1] == 'report') {
            $mac = 'N/A';
            $status = 2;
        } elseif ($array[$k][1] == 'up') {
            $mac = 'N/A';
            $status = 1;
        } else {
            $mac = $array[$k][1];
            $status = 1;
        }*/

        $ip = $array[$k][0];
        var_dump($ip);
        echo "<br />";
        echo " $ip, $mac, 19, 2, $status";
        echo "<br />";
        echo "<br />";
        $exec = $ordinateur->insert($ip, $mac, 19, 2, $status);
        var_dump($exec);
    
        }
}
