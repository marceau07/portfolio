<?php

function actionIndex($twig, $db) {
    $form = array();
    $presta = new Presta($db);
    $date = date('Y-m-d');
    $listeP = $presta->selectJournee($date);

    echo $twig->render('index.html.twig', array('form' => $form, 'listeP' => $listeP));
}

function actionDeconnexion() {
    session_unset();
    session_destroy();
    header("Location: ../web/");
}

function actionMaintenance($twig) {
    echo $twig->render('maintenance.html.twig', array());
}

function actionConnexion($twig, $db) {
    $form = array();

    if (isset($_POST['btConnexion'])) {
        $email = filter_input(INPUT_POST, 'emailConnexion', FILTER_SANITIZE_EMAIL);
        $mdp = filter_input(INPUT_POST, 'mdpConnexion', FILTER_SANITIZE_SPECIAL_CHARS);
        $client = new Client($db);
        $unClient = $client->connect($email);
        if ($unClient != null) {
            if (!password_verify($mdp, $unClient['mdp'])) {
                $form['valide'] = false;
                $form['message'] = 'Mot de passe incorrect';
            } else {
                $form['valide'] = true;
                $_SESSION['email'] = $email;
                $_SESSION['mdp'] = $mdp;
                $unClient = $client->selectByEmail($email);
                $_SESSION['idRole'] = $unClient['idRole'];
                $_SESSION['idCli'] = $unClient['idCli'];
                $_SESSION['prenom'] = $unClient['prenom'];
                header("Location: ../web/");
            }
        } elseif ($unClient == false) {
            $form['valide'] = false;
            $form['message'] = 'Login incorrect';
        }
    }
    echo $twig->render('connexion.html.twig', array('form' => $form, 'session' => $_SESSION));
}

function actionPrestationPdf($twig, $db) {
    $prestation = new Presta($db);
    $date = date('Y-m-d');
    $liste = $prestation->selectJournee($date);
    $html = $twig->render('prestation-pdf.html.twig', array('liste' => $liste));
    
    try{
        ob_end_clean();
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($html);
        $html2pdf->output('prestationJournee.pdf');
    } catch (Html2PdfException $e) {
        echo 'erreur'.$e;
    }
}
