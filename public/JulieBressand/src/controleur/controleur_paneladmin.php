<?php

//_________________________________Ajout de la fonction d'affichage du Panel [28-05-2019]____________________________________________________________________//

function actionPanel($twig, $db) {
    $form = array();
    
    $date = date('Y-m-d');

    $avis = new Avis($db);
    $form['avis'] = $avis->selectLastForPanel($date);
    
    $actualite = new actualite($db);
    $form['actualite'] = $actualite->selectLastForPanel();
    
    $utilisateur = new Utilisateur($db);
    $form['utilisateur'] = $utilisateur->selectForPanel();
    
    if (isset($_POST['btAjouter'])) {

        $titre = htmlspecialchars($_POST['titre']);
        $message = htmlspecialchars($_POST['message']);
        $date = date('Y-m-d');
        
        if ($exec) {
            $form['actu'] = false;
            $form['message'] = 'Il y a eu un problème veuillez vérifier le contenu.';
        } else {
            $form['actu'] = true;
            $form['message'] = 'L\'actualité a été postée avec succès !';
        }
        $actualite = new actualite($db);
        $exec = $actualite->insert($titre, $message, $date);
        
        

        $form['titre'] = $titre;
    }

    if (isset($_POST['btAjouterFaq'])) {

        $quest = $_POST['quest'];
        $rep = $_POST['rep'];

        if ($exec) {
            $form['faq'] = false;
            $form['message'] = 'Il y a eu un problème veuillez vérifier le contenu.';
        } else {
            $form['faq'] = true;
            $form['message'] = 'La faq a été postée avec succès !';
        }

        $FAQ = new FAQ($db);
        $exec = $FAQ->insert($quest, $rep);
    }
    $newsletter = new Newsletter($db);
    $liste = $newsletter->select();

    if (isset($_POST['btEnvoyer'])) {
        $message = htmlspecialchars($_POST['Message']);
        $objet = htmlspecialchars($_POST['Objet']);
        for ($i = 0; $i <= sizeof($liste); $i++) {

            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"Julie Bressand Newsletter"<juliebressand.com>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $messagecontenue = "
                                <html>
                                  <body>
                                    <div align='center'>

                                    $message.<br/>

                                    </div>
                                  </body>
                                </html>
                                ";
            mail($liste[$i]['email'], $objet, $messagecontenue, $header);
        }

        if ($exec) {
            $form['mail'] = false;
            $form['message'] = 'Un erreur s\'est produite veuillez ressayer.';
        } else {
            $form['mail'] = true;
        }
    }
    if ($_SESSION['role'] == 2) {
        echo $twig->render('panel.html.twig', array('form' => $form, 'liste' => $liste));
    } else {
        if($_SERVER['SERVER_NAME'] == 'serveur1.arras-sio.com') { header("Location: ../web/page=error404"); }
        else { header("Location: /error404"); }
    }
}

//_________________________________Ajout de la fonction Gestion rendez-vous [05-06--2019]____________________________________________________________________//

function actionGestionrdv($twig, $db) {

    $rdv = new rdv($db);
    $liste = $rdv->selectByLast();

    $dateActuelle = new DateTime('now');
    $form['date'] = $dateActuelle;
    $form['nbrrdv'];

    if ($_SESSION['role'] == 2) {
        echo $twig->render('gestionrdv.html.twig', array('form' => $form, 'liste' => $liste));
    } else {
        if($_SERVER['SERVER_NAME'] == 'serveur1.arras-sio.com') { header("Location: ../web/"); }
        else { header("Location: /"); }
    }
}

?>
