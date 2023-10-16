<?php

//______________________________Fonction_Acceuil________________________Modifé le 28/05/2019________________________________________//
function actionAccueil($twig, $db) {
    $form = array();

    if (isset($_POST['btNewsletter'])) {
        $email = htmlspecialchars($_POST['email']);

        $newsletter = new Newsletter($db);
        $emailverify = $newsletter->emailExist($email);
        $form['verif'] = $emailverify[0];
        if ($emailverify[0] > 0) {
            $form['valide'] = false;
            $form['message'] = "Cette adresse email est déjà utilisée.";
        } else {
            $newsletter = new Newsletter($db);
            $exec = $newsletter->insert($email);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = "Cette adresse email est déjà utilisée.";
            } else {
                //Envoi d'un mail de confirmation//
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"Julie Bressand"<support@juliebressand.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';
                
                if($_SERVER['SERVER_NAME'] == 'serveur1.arras-sio.com') {
                    $link = "<p>Votre inscription à la newsletter sur notre site <a style='color: green' href='//serveur1.arras-sio.com/symfony4-4017/JulieBressand/web/'><i>Julie Bressand</i></a> a bien été prise en compte.</p>

                                                <p>Si vous souhaitez vous désinscrire de celle-ci, rendez vous sur <a style='color: green' href='//serveur1.arras-sio.com/symfony4-4017/JulieBressand/web/'><i>Julie Bressand</i></a> en bas de page.</p>
                                                ";
                } else {
                    $link = "<p>Votre inscription à la newsletter sur notre site <a style='color: green' href='//juliebressand.livehost.fr'><i>Julie Bressand</i></a> a bien été prise en compte.</p>

                                                <p>Si vous souhaitez vous désinscrire de celle-ci, rendez vous sur <a style='color: green' href='//juliebressand.livehost.fr'><i>Julie Bressand</i></a> en bas de page.</p>
                                                ";
                }
                
                $message = "
                           <html>
                                <body>
                                        <div align='center'>
                                            $link
                                            A très bientôt.
                                        </div>
                                </body>
                          </html>
                          ";
                mail($email, "Cabinet Julie Bressand !", $message, $header);
                //fin d'envoi du mail//
            }
        }
    }

    if (isset($_POST['btUnsub'])) {
        $email = htmlspecialchars($_POST['email']);

        $newsletter = new Newsletter($db);
        $emailverify = $newsletter->emailExist($email);
        $form['verif'] = $emailverify[0];
        if ($emailverify[0] > 0) {
            $form['valide'] = false;
            $form['message'] = "Cette adresse email n'est pas inscrite à la newsletter.";
        } else {
            $newsletter = new Newsletter($db);
            $exec = $newsletter->delete($email);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = "Cette adresse n'est pas inscrite à la newsletter.";
            } else {
                //Envoie d'un mail de confirmation//
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"Julie Bressand"<support@juliebressand.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                if($_SERVER['SERVER_NAME'] == 'serveur1.arras-sio.com') {
                    $link = "<p>Votre désinscription à la newsletter sur notre site <a style='color: green' href='//serveur1.arras-sio.com/symfony4-4017/JulieBressand/web//#newsletter'><i>Julie Bressand</i></a> a bien été prise en compte.</p>";
                } else {
                    $link = "<p>Votre désinscription à la newsletter sur notre site <a style='color: green' href='//juliebressand.livehost.fr/#newsletter'><i>Julie Bressand</i></a> a bien été prise en compte.</p>";
                }
                $message = "
                           <html>
                                <body>
                                        <div align='center'>
                                            $link
                                            Ce mail sera donc le dernier que vous recevrez de notre part, concernant le programme de Newsletter.
                                            A très bientôt.
                                        </div>
                                </body>
                          </html>
                          ";
                mail($email, "Cabinet Julie Bressand !", $message, $header);
                //fin d'envoi du mail//
            }
        }
    }
    echo $twig->render('base.html.twig', array('form' => $form));
}

//______________________________Fonction_Deconnexion________________________Modifé le 27/05/2019________________________________________//
function actionDeconnexion() {
    session_unset();
    session_destroy();
    if($_SERVER['SERVER_NAME'] == 'serveur1.arras-sio.com') { header("Location: ../web"); }
    else { header("Location: /"); }
}

//______________________________Fonction_Connexion________________________Modifé le 27/05/2019________________________________________//
function actionConnexion($twig, $db) {
    $form = array();

    if(htmlspecialchars($_SERVER['QUERY_STRING']) !== "page=connexion") {
        setcookie("last_uri", $_SERVER['QUERY_STRING'], -1);
    }
    
    if (isset($_POST['btConnect'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_SPECIAL_CHARS);
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->connect($email);
        $form['email'] = $email;

        if ($unUtilisateur != null) {
            if (!password_verify($pass, $unUtilisateur['pass'])) {
                $form['valide'] = false;
                $form['message'] = 'Mot de passe incorrect';
            } else {
                $form['valide'] = true;
                $_SESSION['login'] = $email;
                $_SESSION['pass'] = $pass;
                $_SESSION['role'] = $unUtilisateur['idrole'];
                $_SESSION['nom'] = $unUtilisateur['nom'];
                $_SESSION['prenom'] = $unUtilisateur['prenom'];
                $_SESSION['telephone'] = $unUtilisateur['telephone'];

                $locate = $_COOKIE['last_uri'];
                if ($locate == "http://juliebressand.livehost.fr/connexion" || $locate == "http://juliebressand.livehost.fr/web/connexion") {
                    header("Location: /");
                } elseif ($locate == "http://serveur1.arras-sio.com/symfony4-4017/JulieBressand/web/index.php?page=connexion") {
                    header("Location: ../web/");
                } else {
                    header("Location: ../web/index.php?$locate");
                }
            }
        } elseif ($unUtilisateur == false) {
            $form['valide'] = false;
            $form['message'] = 'Login incorrect';
        }
    }
    echo $twig->render('connexion.html.twig', array('form' => $form, 'session' => $_SESSION));
}

//______________________________Fonction_Inscription________________________Modifé le 27/05/2019________________________________________//
function actionInscription($twig, $db) {
    $form = array();

    if (isset($_POST['btInscrire'])) {

        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $pass = htmlspecialchars($_POST['pass']);
        $pass2 = htmlspecialchars($_POST['pass2']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $form['valide'] = true;
        $idrole = 1;

        //-----Captchat------//
        // Ma clé privée
//        $secret = "6LeCyqUUAAAAAMrioXwU-lQR8iZcHyANIlX8pJDP";   // CLE juliebressand.livehost.fr
        $secret = "6LcI-4QcAAAAAA5Lda3lC3p7KGBc2Taf_IAm1PZo";   // CLE serveur symfony4-4017
        // Paramètre renvoyé par le recaptcha
        $response = filter_input(INPUT_POST, 'g-recaptcha-response');
        // On récupère l'IP de l'utilisateur
        $remoteip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');

        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
                . $secret
                . "&response=" . $response
                . "&remoteip=" . $remoteip;
        $decode = json_decode(file_get_contents($api_url), true);
        //-----FinCaptchat------//

        $utilisateur = new Utilisateur($db);
        $emailverify = $utilisateur->emailExist($email);
        $telverify = $utilisateur->telephoneExist($telephone);

        if ($emailverify[0] > 0) {
            $form['valide'] = false;
            $form['message'] = "Cette adresse email est déjà utilisée";
        } elseif ($telverify[0] > 0) {
            $form['valide'] = false;
            $form['message'] = "Ce numéro de téléphone est déjà utilisé";
        } elseif ($pass != $pass2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } elseif (strlen($pass) < 8) {
            $form['valide'] = false;
            $form['message'] = 'Votre mot de passe est trop court il doit contenir au minimum 8 caractères';
        } elseif ($decode['success'] == false) {
            $form['valide'] = false;
            $form['message'] = "Vous n'avez pas validé le captchat";
        } else {
            $_SESSION['login'] = $email;
            $utilisateur = new Utilisateur($db);
            $exec = $utilisateur->insert($nom, $prenom, $email, password_hash($pass, PASSWORD_DEFAULT), $idrole, $telephone);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['messge'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';

                //Envoie d'un mail de confirmation//
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"Julie Bressand"<support@juliebressand.com>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';
                if($_SERVER['SERVER_NAME'] == "serveur1.arras-sio.com") {
                    $link = "<a href='serveur1.arras-sio.com/web/'> Veuillez vous rendre sur le site pour prendre rendez-vous</a></p>";
                } elseif($_SERVER['SERVER_NAME'] == "juliebressand.livehost.fr") {
                    $link = "<a href='juliebressand.livehost.fr/web/index.php'> Veuillez vous rendre sur le site pour prendre rendez-vous</a></p>";
                }
                $message = "
                           <html>
                                <body>
                                         <div align='center'>

                                                Bienvenue $prenom.<br/>

                                                <p> Le cabinet Bressand vous accueil du lundi au samedi de 9h à 18h30.
                                                $link
                                        </div>
                                </body>
                          </html>
                          ";

                mail($email, "Cabinet Julie Bressand !", $message, $header);
                //fin d'envoi du mail//
                actionDeconnexion($twig);
            }
        }
        $form['email'] = $email;
    }

    echo $twig->render('inscription.html.twig', array('form' => $form, 'session' => $_SESSION));
}

//______________________________Fonction_Maitenance________________________Modifé le 27/05/2019________________________________________//
function actionMaintenance($twig) {
    echo $twig->render('maintenance.html.twig', array());
}

//______________________________Fonction_Actualité________________________Modifé le 27/05/2019________________________________________//

function actionActualite($twig, $db) {
    $form = array();

    $actualite = new actualite($db);
    $liste = $actualite->select();

    $form['valide'] = true; //Il est valide de base

    if (isset($_GET['idactu'])) {
        $exec = $actualite->delete($_GET['idactu']);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Il y a eu un problème veuillez vérifier le contenu n\' oubliez pas que le contenu de résumé est limité a 255 caractère.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'actualité a bien été supprimée !';
        }
    }
    if (isset($_GET['idactu'])) {
        $exec = $actualite->selectById($_GET['idactu']);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Il y a eu un problème veuillez vérifier le contenu n\' oubliez pas que le contenu de résumé est limité a 255 caractère.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'L\'actualité a été modifiée avec succès !';
        }
    }
    echo $twig->render('actualite.html.twig', array('liste' => $liste));
}

//______________________________Fonction_Modif_Actualité________________________Modifée le 13/06/2019________________________________________//

function actionGestionActualite($twig, $db) {
    $form = array();
    $actualite = new actualite($db);

    if (isset($_GET['idactu'])) {
        $actualite = new actualite($db);
        $liste = $actualite->selectById($_GET['idactu']);
        if ($liste != null) {
            $form['actualite'] = $liste;
        } else {
            $form['message'] = 'Actu incorrecte';
        }
    }
    if (isset($_POST['btModifier'])) {
        $idactu = htmlspecialchars($_POST['idactu']);
        $titre = htmlspecialchars($_POST['titre']);
        $message = htmlspecialchars($_POST['message']);
        $date = htmlspecialchars($_POST['date']);
        $exec = $actualite->update($idactu, $titre, $message, $date);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Problème de mise à jour dans la table actualite';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Modification réussie';
        }
    }
    $liste = $actualite->select();

    echo $twig->render('gestionActualite.html.twig', array('form' => $form));
}

//_________________________Ajout de la fonction pour afficher la page des cookies____________ le 22/06/2019________________________________________//

function actionCookies($twig) {
    echo $twig->render('cookies.html.twig', array());
}

//_________________________Ajout de la fonction pour afficher la page des CGU_________________ le 23/06/2019________________________________________//

function actionCGU($twig) {
    echo $twig->render('cgu.html.twig', array());
}

function actionCalendar($twig, $db) {
    $form = array();

    if (isset($_POST['bt'])) {
        $date = htmlspecialchars($_POST['date']);
    }

    $rdv = new rdv($db);
    $resultat = $rdv->selectByDisp($date);
    $form['horaire'] = $resultat;

    echo $twig->render('calendrierV2.html.twig', array('form' => $form));
}

?>
