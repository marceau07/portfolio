<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//_________________________________Ajout de la fonction de l'espace membres [28-05-2019]____________________________________________________________________//

function actionEspaceMembres($twig, $db) {
    $form = array();
    
    if(isset($_SESSION['login'])) {
        $utilisateur = new Utilisateur($db);
        $avis = new Avis($db);
        $form['user'] = $utilisateur->selectByEmail($_SESSION['login']);
        $form['av'] = $avis->selectByEmail($_SESSION['login']);
        $exec = $utilisateur->updateForAvis($form['av']['id'], $_SESSION['login']);
        $form['avis'] = $avis->emailExist($_SESSION['login']);
    } else {
        setcookie("last_uri", $_SERVER['QUERY_STRING'], -1);
        if($_SERVER['SERVER_NAME'] == "serveur1.arras-sio.com") {
            header("Location: ../web/index.php?page=connexion");
        } else {
            header("Location: /connexion");
        }
    }

    echo $twig->render('espacemembres.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction d'affichage du calendrier - édité le [13-06-2019]____________________________________________________________________//
function actionPriseRdv($twig, $db) {
    $form = array();

    $form['step'] = false;
    $r = false;

    if (isset($_POST['btNext'])) {
        $date = htmlspecialchars($_POST['date']);
        $today = date('d/m/Y');
        $method = htmlspecialchars($_POST['method']);
        if ($date < $today) {
            $form['step'] = false;
            $r = false;
            $form['warn'] = 'Veuillez entrer une date valide.';
        } elseif ($method == null) {
            $form['step'] = false;
            $r = false;
            $form['danger'] = 'Veuillez sélectionner une autre valeur que "--" pour le choix du rendez-vous.';
        } else {
            $form['date'] = $date;
            $form['method'] = $method;
            $form['step'] = true;
            $r = true;
        }
    }

    if (isset($method) && $method == 'Skype') {
        $form['skype'] = true;
    } elseif (isset($method) && $method == 'Adomicile') {
        $form['Adomicile'] = true;
    }

    if (isset($_POST['btRdv'])) {

        if(isset($date)) $form['date'] = $date;
        if(isset($method)) $form['method'] = $method;

        $date = substr($_POST['date'], 6, 4) . '-' . substr($_POST['date'], 3, 2) . '-' . substr($_POST['date'], 0, 2);
        $method = htmlspecialchars($_POST['method']);
        $message = htmlspecialchars($_POST['message']);
        $heure = htmlspecialchars($_POST['heure']);

        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $email = htmlspecialchars($_SESSION['login']);

        if (!empty($_POST['adresse'])) {
            $adresse = htmlspecialchars($_POST['adresse']);
        } else {
            $adresse = null;
        }

        if (!empty($_POST['pseudoskype'])) {
            $pseudoskype = htmlspecialchars($_POST['pseudoskype']);
        } else {
            $pseudoskype = null;
        }
        $rdv = new rdv($db);
        $exec = $rdv->insert($date, $method, $heure, $message, $adresse, $pseudoskype, $nom, $prenom, $telephone, $email);
        $id_rdv = $db->lastInsertId();
        
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Il y a eu un problème.'
                    . ' Retenter en cliquant ';
        } else {
            $header = "MIME-Version: 2.0\r\n";
            $header .= 'From:"CabinetBressand.com"<support@Cabinet.com>' . "\n";
            $header .= 'Content-Type:text/calendar; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';
            
            $sql_select_horaire = 'SELECT horaireDeb, horaireFin 
                                    FROM horaires 
                                    WHERE id=:id ';
            $req_select_horaire = $db->prepare($sql_select_horaire);
            $req_select_horaire->bindParam(':id', $heure);
            $req_select_horaire->execute();
            $horaire = $req_select_horaire->fetch(PDO::FETCH_ASSOC);
            
            $dtstartIcs = date('Ymd', strtotime($date)).'T'.str_replace(':', '', $horaire['horaireDeb']);
            $dtendIcs = date('Ymd', strtotime($date)).'T'.str_replace(':', '', $horaire['horaireFin']);
            $summaryIcs = 'Rendez-vous Julie Bressand';
            $descriptionIcs = "Bonjour " . $prenom . " " . rtrim($nom) . ", \n\n 
                Vous avez rendez-vous avec  le " . date('d/m/Y', strtotime($date)) . " à ". substr($horaire['horaireDeb'], 0, 5) .".
                \n\n\n\nCordialement,";
            
            $ics = "";
            $ics .= "BEGIN:VCALENDAR\r\n";
            $ics .= "VERSION:2.0\r\n";
            $ics .= "METHOD:REQUEST\r\n";
            $ics .= "PRODID:PHP\r\n";
            $ics .= "BEGIN:VTIMEZONE\r\n";
            $ics .= "TZID:Europe/Paris\r\n";
            $ics .= "X-LIC-LOCATION:Europe/Paris\r\n";
            $ics .= "BEGIN:DAYLIGHT\r\n";
            $ics .= "TZOFFSETFROM:+0100\r\n";
            $ics .= "TZOFFSETTO:+0200\r\n";
            $ics .= "TZNAME:CEST\r\n";
            $ics .= "TZOFFSETTO:+0200\r\n";
            $ics .= "DTSTART:19700329T020000\r\n";
            $ics .= "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\n";
            $ics .= "END:DAYLIGHT\r\n";
            $ics .= "BEGIN:STANDARD\r\n";
            $ics .= "TZOFFSETFROM:+0200\r\n";
            $ics .= "TZOFFSETTO:+0100\r\n";
            $ics .= "TZNAME:CET\r\n";
            $ics .= "DTSTART:19701025T030000\r\n";
            $ics .= "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\n";
            $ics .= "DTSTART:19701025T030000\r\n";
            $ics .= "END:STANDARD\r\n";
            $ics .= "END:VTIMEZONE\r\n";
            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:" . $id_rdv . "\r\n";
            $ics .= "SEQUENCE:1\r\n";
            $ics .= "TRANSP:TRANSPARENT\r\n";
            $ics .= "STATUS:TENTATIVE\r\n";
            $ics .= "ATTENDEE;CN=$email;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE:mailto:$email\r\n"; // DECLINED || NEEDS-ACTION || ACCEPTED
            $ics .= "ORGANIZER;SENT-BY=\"Julie Bressand\":MAILTO:no-reply@juliebressand.fr\r\n";
            $ics .= "DTSTAMP:". date('Ymd')."T".date('His')."\r\n";
            $ics .= "DTSTART;TZID=Europe/Paris:" . $dtstartIcs . "\r\n";
            $ics .= "DTEND;TZID=Europe/Paris:" . $dtendIcs . "\r\n";
            $ics .= "SUMMARY:".$summaryIcs."\r\n";
            $ics .= "DESCRIPTION:".$descriptionIcs."\r\n";
            $ics .= "LOCATION:19 RUE D'EN HAUT - 62490, SAILLY-EN-OSTREVENT\r\n";
            $ics .= "END:VEVENT\r\n";
            $ics .= "END:VCALENDAR\r\n";

            $mail = new PHPMailer(true);
            
            try {
                //Recipients
                $mail->setFrom('no-reply@juliebressand.fr', 'Julie Bressand');
                $mail->addAddress($_SESSION['login'], $prenom . " " . $nom);
                $mail->addBCC('marceau0707@gmail.com');

                //Attachments
                $mail->addStringAttachment($ics, 'invitation.ics');

                //Content
                $mail->isHTML(true);
                $mail->Subject = $summaryIcs;
                $mail->Body    = str_replace('\n', '<br>', $descriptionIcs);
                $mail->AltBody = $descriptionIcs;

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
//            mail($_SESSION['login'], "Votre rendez-vous a bien été enregistré ", $ics, $header);
        }
    }
    
    if(!empty($_SESSION['login'])) {
        $rdv = new rdv($db);
        $form['fait'] = $rdv->emailExist($_SESSION['login']);
    } else {
        setcookie("last_uri", $_SERVER['QUERY_STRING'], -1);
        header("Refresh:7; url=../web/index.php?page=connexion", true, 303);
    }

    if(isset($date)){
        $resultat = $rdv->selectByDisp($date);
        $form['horaire'] = $resultat;
    }

    $avis = new Avis($db);
    $form['avis'] = $avis->select();

    if (isset($_GET['idavis'])) {
        $exec = $avis->selectById($_GET['idavis']);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Il y a eu un problème, lol.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'avis a été modifié avec succès !';
        }
    }
    echo $twig->render('priserdv.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction FAQ [29-05-2019]____________________________________________________________________//

function actionFaq($twig, $db) {
    $form = array();

    $FAQ = new FAQ($db);
    $form['faq'] = $FAQ->select();

    echo $twig->render('faq.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction Gestion Compte [29-05-2019]____________________________________________________________________//

function actionGestionCompte($twig, $db) {
    $form = array();

    $utilisateur = new Utilisateur($db);
    if (isset($_GET['email'])) {
        $utilisateur = new Utilisateur($db);
        $liste = $utilisateur->selectByEmail($_GET['email']);
        if ($liste != null) {
            $form['utilisateur'] = $liste;
        } else {
            $form['message'] = 'Utilisateur incorrect';
        }
        if (isset($_POST['btModifier'])) {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $pass = htmlspecialchars($_POST['pass']);
            $telephone = htmlspecialchars($_POST['telephone']);

            $exec = $utilisateur->update($nom, $prenom, $telephone, $email, password_hash($pass, PASSWORD_DEFAULT));
            if (!exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de mise à jour dans la table utilisateur';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie';

                //Envoi d'un mail de confirmation//
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"CabinetBressand.com"<support@Cabinet.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                $message = "
                 <html>
                 	<body>
                		 <div align='center'>

                			Bienvenue '$nom' .<br/>

              <p> Les modifications ont bien été effectuées.
            Vos informations :   <br />
            Nom : $nom
            Prenom : $prenom
            Email : $email
            Telephone : $telephone

                		</div>
                	</body>
                </html>
                ";
                mail($email, "Cabinet Julie Bressand !", $message, $header);
                //fin d'envoi du mail//
            }
        }
        if (isset($_POST['btSupprimer'])) {
            $email = $_SESSION['login'];

            $exec = $utilisateur->delete($email);
            if (!exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème lors de la suppresion. Contactez-moi ou veuillez réessayer.';
            } else {
                $form['valide'] = true;
                if($_SERVER['SERVER_NAME'] == 'serveur1.arras-sio.com') { header("Location: ../web/index.php?page=deconnexion"); }
                else { header("Location: /deconnexion"); }

                //Envoi d'un mail de confirmation//
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"CabinetBressand.com"<support@Cabinet.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                $message = "
                 <html>
                 	<body>
                		 <div align='center'>
                                    <p>Votre compte utilisateur a bien été effacé de nos serveurs.</p>
                		</div>
                	</body>
                </html>
                ";
                mail($email, "Cabinet Julie Bressand", $message, $header);
            }
        }
    }
    $liste = $utilisateur->select();
    echo $twig->render('compte.html.twig', array('form' => $form, 'liste' => $liste));
}

//_________________________________Ajout de la fonction Mot de Passe Oublié [04-06-2019]____________________________________________________________________//
function actionMdpOublie($twig, $db) {
    $form = array();
    $form['step'] = false;

    if (isset($_POST['btNext'])) {
        $inputEmail = htmlspecialchars($_POST['inputEmail']);
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->selectByEmail($inputEmail);
        if ($unUtilisateur != null) {
            if (empty($inputEmail)) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez renseigner une adresse mail';
            } else {
                $form['valide'] = true;
                $email = $unUtilisateur['email'];
                $form['code'] = rand();
            }
        } else {
            $form['valide'] = false;
            $form['message'] = 'Login ou mot de passe incorrect';
        }
        $form['step'] = true;
        $form['email'] = $inputEmail;
    }
    if (!empty($unUtilisateur)) {
        $subject = "Récupérer votre mot de passe";
        $code = $form['code'];

        //Envoie d'un mail de confirmation//
        $header = "MIME-Version: 1.0\r\n";
        $header .= 'From:"CabinetBressand.com"<support@Cabinet.com>' . "\n";
        $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
        $header .= 'Content-Transfer-Encoding: 8bit';

        $message = "
                    <html>
                        <head>
                            <meta charset='utf-8'>
                            <link rel='stylesheet' href='http://juliebressand.livehost.fr/web/css/email.css'>
                        </head>
                 	<body>
                            <div>
                                <p>Si vous êtes à l'origine de cette action, poursuivez la lecture, dans le cas contraire, connectez-vous et changez votre mot de passe.</p><br><hr>
                                <p>Voici votre identifiant : $email</p><br>
                                <p>Votre code de connexion temporaire est : $code</p>
                            </div>
                	</body>
                    </html>
                    ";

        mail($email, "Cabinet Julie Bressand ! - $subject", $message, $header);
        //fin d'envoie du mail//
    }

    if (isset($_POST['btRecuperer'])) {
        if(!empty($email)) {
            $form['email'] = $email;
        }
        $code = htmlspecialchars($_POST['code']);

        $email = htmlspecialchars($_POST['inputEmail2']);
        $inputCode = htmlspecialchars($_POST['inputCode']);

        if ($code == $inputCode && !empty($email)) {
            $utilisateur = new Utilisateur($db);
            $unUtilisateur = $utilisateur->updateMdp(password_hash($code, PASSWORD_DEFAULT), $email);
            $form['valide2'] = true;
            $form['message2'] = "La modification de votre mot de passe a été effectuée avec succès.\n Utilisez ce code dorénavant pour vous conneter.\nVous pouvez le changer à tout moment depuis la gestion de votre compte.";
        } else {
            $form['valide2'] = false;
            $form['message2'] = "Code de récupération erroné ou email manquant.";
        }
    }
    echo $twig->render('mdpOublie.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction MesRdv [06-06-2019]____________________________________________________________________//

function actionMesRendezvous($twig, $db) {
    $form = array();
    $telephone = $_SESSION['telephone'];

    $rdv = new rdv($db);
    $form['user'] = $rdv->selectByTelephone($telephone);
    $form['date'] = date('Y-m-d');

    $form['rdv'] = $rdv->emailExist($_SESSION['login']);

    echo $twig->render('mesrendezvous.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction Avis [09-06-2019]____________________________________________________________________//
function actionAjouterAvis($twig, $db) {
    $form = array();

    $avis = new Avis($db);
    $form['avis'] = $avis->emailExist($_SESSION['login']);
    if (isset($_POST['btNoter'])) {
        $email = htmlspecialchars($_POST['inputEmail']);
        $note = htmlspecialchars($_POST['note']);
        $unAvis = htmlspecialchars($_POST['avis']);

        $avis = new Avis($db);
        $exec = $avis->insert($unAvis, $note, $email);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez renseigner tous les champs, s\'il vous plaît.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Avis posté avec succès. Merci !';
        }
    }
    if(!empty($form['avis']['email'])) {
        if ($form['avis']['email'] == $email) {
            $form['message'] = 'Vous avez déjà renseigné un avis. Vous pouvez modifier le votre ici: ';
        }
    }

    $utilisateur = new Utilisateur($db);
    $form['user'] = $utilisateur->selectByEmail($_SESSION['login']);

    $form['avis'] = $avis->emailExist($_SESSION['login']);

    if ($form['avis']['nb'] == 1 && $_SERVER['SERVER_NAME'] == "serveur1.arras-sio.com") {
        header("Location: ../web/index.php?page=espacemembres");
    } elseif($form['avis']['nb'] == 1 && $_SERVER['SERVER_NAME'] !== "serveur1.arras-sio.com") {
        header("Location: ../espacemembres");
    }
    echo $twig->render('ajouterAvis.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction de modification de son avis [13-06-2019]____________________________________________________________________//
function actionGestionAvis($twig, $db) {
    $form = array();
    $avis = new Avis($db);

    $form['login'] = $_SESSION['login'];

    if (isset($_POST['btModifier'])) {
        $email = htmlspecialchars($_POST['inputEmail']);
        $note = htmlspecialchars($_POST['note']);
        $message = htmlspecialchars($_POST['avis']);
        $exec = $avis->update($message, $note, $email);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de mise à jour dans la table avis';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Modification réussie';
        }
        header("Location: ../priserdv");
    }
    if (isset($_POST['btSupprimer'])) {
        $email = htmlspecialchars($_POST['inputEmail']);

        $utilisateur = new Utilisateur($db);
        $exec = $utilisateur->updateForAvis(null, $email);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de mise à jour dans la table avis.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Suppression de votre avis effectuée.';
        }
        $exec = $avis->deleteByUser($email);
        header("Location: ../priserdv");
    }
    if (isset($_GET['idavis'])) {
        $form['avis'] = $avis->selectById($_GET['idavis']);
    }

    echo $twig->render('gestionAvis.html.twig', array('form' => $form));
}

//_________________________________Ajout de la fonction de récuperation des informations d'un utilisateur [23-06-2019]____________________________________________________________________//

function actionRecupererInfo($twig, $db) {
    $form = array();

    if (isset($_POST['btRecuperer'])) {
        $utilisateur = new Utilisateur($db);
        $exec = $utilisateur->selectByEmail($_SESSION['login']);
        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème lors de la récupération de vos données, veuillez réessayer.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Un email avec toutes vos informations vous a été envoyé par email.';

            if ($exec['idrole'] == 1) {
                $role = 'Client';
            } else {
                $role = 'Administrateur';
            }

            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"CabinetBressand.com"<support@Cabinet.com>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $message = "
                    <html>
                        <head>
                        </head>
                 	<body>
                            <div>
                                <p>Voici votre identifiant : " . $exec['email'] . "</p><br>
                                <p>Voici nom: " . $exec['nom'] . "</p><br>
                                <p>Voici prénom: " . $exec['prenom'] . "</p><br>
                                <p>Voici numéro de téléphone: " . $exec['telephone'] . "</p><br>
                                <p>Votre statut sur le site: " . $role . "</p><br>
                            </div>
                	</body>
                    </html>
                    ";

            mail($_SESSION['login'], "Cabinet Julie Bressand - Récupération de vos données personnelles", $message, $header);
        }
    }
    echo $twig->render('mesdonnees.html.twig', array('form' => $form));
}

?>
