<?php

function actionClient($twig, $db) {
    $form = array();
    $clients = new Client($db);
    $listeC = $clients->select();

    echo $twig->render('clients.html.twig', array('form' => $form, 'listeC' => $listeC));
}

function actionProfile($twig, $db) {
    $form = array();
    $clients = new Client($db);

    $form['profile'] = $clients->selectByEmail($_SESSION['email']);

    if (isset($_POST['btModifier'])) {
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);
        $dateNaiss = htmlspecialchars($_POST['dateNaiss']);
        $cp = htmlspecialchars($_POST['cp']);
        $ville = htmlspecialchars($_POST['ville']);
        $rue = htmlspecialchars($_POST['rue']);
        $telephone = htmlspecialchars($_POST['telephone']);

        $exec = $clients->update($nom, $prenom, $dateNaiss, $cp, $ville, $rue, $telephone, $_SESSION['email']);
    }

    echo $twig->render('profile.html.twig', array('form' => $form));
}

function actionSupprimerCompte($twig, $db) {
    $clients = new Client($db);


    $exec = $clients->delete($_SESSION['email']);
    actionDeconnexion();
    header("Location: index.php");
}

function actionInscription($twig, $db) {
    $form = array();

    if (isset($_POST['btInscription'])) {

        $email = htmlspecialchars($_POST['emailInscription']);
        $mdp1 = htmlspecialchars($_POST['mdp1']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $dateNaiss = htmlspecialchars($_POST['dateNaiss']);
        $cp = htmlspecialchars($_POST['cp']);
        $ville = htmlspecialchars($_POST['ville']);
        $rue = htmlspecialchars($_POST['rue']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $idRole = htmlspecialchars($_POST['idRole']);
        $form['valide'] = true;

        $client = new Client($db);

        if ($mdp1 != $mdp2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } elseif (strlen($mdp1) < 8) {
            $form['valide'] = false;
            $form['message'] = 'Votre mot de passe est trop court il doit contenir au minimum 8 caractères';
        } else {
            $_SESSION['emailInscription'] = $email;
            $client = new Client($db);
            $exec = $client->insert($email, password_hash($mdp1, PASSWORD_DEFAULT), $nom, $prenom, $dateNaiss, $cp, $ville, $rue, $telephone, $idRole);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';

//Envoie d'un mail de confirmation//
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"CabinetDuDocteur.com"<no-reply@CDD.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                $message = "
                           <html>
                                <body>
                                         <div align='center'>
                                                Bienvenue $prenom.<br/>
                                                <p> Le cabinet du docteur est content de vous compter dans ses clients.
                                                <a href='si6.arras-sio.com/web/index.php'> Veuillez vous rendre sur le site pour prendre rendez-vous.</a> </p>

                                        </div>
                                </body>
                          </html>
                          ";

                mail($email, "Cabinet du Docteur", $message, $header);
//fin d'envoie du mail//
                actionDeconnexion($twig);
            }
        }
        $form['emailInscription'] = $email;
    }
    echo $twig->render('inscription.html.twig', array('form' => $form, 'session' => $_SESSION));
}

function actionPrendreRdv($twig, $db) {
    $form = array();

    $rdv = new Demander($db);
    $prestation = new Presta($db);
    $listeP = $prestation->select();
    $reglement = new Reglement($db);
    $listeR = $reglement->select();
    
    if (isset($_POST['btPriseRdv'])) {
        $jourY = htmlspecialchars($_POST['jourY']);
        $jourM = htmlspecialchars($_POST['jourM']) + 1;
        $jourD = htmlspecialchars($_POST['jourD']);
        $choix = htmlspecialchars($_POST['choix']);
        $idPrest = htmlspecialchars($_POST['idPrest']);
        $idRegle = htmlspecialchars($_POST['idRegle']);
        if ($choix == "am") {
            $heureH = htmlspecialchars($_POST['heureH']);
        } else {
            $heureH = htmlspecialchars($_POST['heureH']) + 12;
        }
        $heureM = htmlspecialchars($_POST['heureM']);

        $heure = "$heureH:$heureM";
        $jour = "$jourY-$jourM-$jourD";

        $dateDeb = "$jour $heure";
        $heure += 1;
        $dateFin = "$jour $heure";
        $idCli = $_SESSION['idCli'];

        $form['valide'] = true;
        $exec = $rdv->insert($dateDeb, $idCli, $idPrest, $dateFin, $idRegle);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez réessayer.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Votre rendez-vous a bien été pris en compte.';
        }
    }
    echo $twig->render('priserdv.html.twig', array('form' => $form, 'listeP' => $listeP, 'listeR' => $listeR));
}
