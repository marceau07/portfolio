<?php

/* Toutes les fonctions de connection/inscription et deconnexion */

function actionInscrire($twig, $db) {
    $form = array();
    if (isset($_POST['btInscrire'])) {
        $inputEmail = htmlspecialchars($_POST['inputEmail']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $telephone = htmlspecialchars($_POST['telephone']);
        $mdp = htmlspecialchars($_POST['mdp']);
        $mdp2 = htmlspecialchars($_POST['mdp2']);
        $role = htmlspecialchars($_POST['role']);
        $photo = NULL;
        if (isset($_FILES['photo'])) {
            if (!empty($_FILES['photo']['name'])) {
                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '/data/si62019/mrodrigues/Legendarium/web/images/utilisateurs/';
                if (!in_array(substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok)) {
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg !';
                } else {
                    if (file_exists($_FILES['photo']['tmp_name']) && (filesize($_FILES['photo']['tmp_name'])) > $taille_max) {
                        echo 'Votre fichier doit faire moins de 500Ko !';
                    } else {
                        $photo = basename($_FILES['photo']['name']);
// enlever les accents
                        $photo = strtr($photo, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAA
                                             AAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
// remplacer les caractères autres que lettres, chiffres et point par _
                        $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
// copie du fichier

                        move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier . $photo);
                    }
                }
            }
        }
        $form['valide'] = true;
        if ($mdp != $mdp2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } else {
            $utilisateur = new Utilisateur($db);
            $exec = $utilisateur->insert($inputEmail, $nom, $prenom, $pseudo, $telephone, password_hash($mdp, PASSWORD_DEFAULT), $photo, $role);
            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème d\'insertion dans la table utilisateur ';
            }
        }
        $form['photo'] = $photo;
        $form['email'] = $inputEmail;
        $form['nom'] = $nom;
        $form['prenom'] = $prenom;
        $form['pseudo'] = $pseudo;
        $form['telephone'] = $telephone;
        $form['role'] = $role;
    }
// the message
    if (!empty($exec)) {
        $message = "un mail vous a été envoyé avec un resumé de vos informations";
        $from = "marceau0707@gmail.com";
        $to = $form['email'];
        $subject = "Inscription";
        $message = "<p>Mail: " . $form['email'] . "</p><p>Nom: " . $form['nom'] . "</p><p>Prenom: " . $form['prenom'] . "</p>"
                . "<p>Pseudo: " . $form['pseudo'] . "</p><p>Téléphone: " . $form['telephone'] . '</p><br><p>Photo:<img src="http://si6.arras-sio.com/mrodrigues/Legendarium/web/images/utilisateurs/' . $form['photo'] . '" alt="Votre photo"/></p>';
        $headers = "From: " . $from . "\n";
        $headers .= "Reply-To: " . $from . "\n";
        $headers .= "Content-Type: text/html; charset=\"utf-8\"";


        $form['resul'] = mail($to, $subject, $message, $headers);
    }
    echo $twig->render('inscrire.html.twig', array('form' => $form));
}

function actionConnexion($twig, $db) {
    $form = array();
    if (isset($_POST['btConnecter'])) {
        $inputEmail = htmlspecialchars($_POST['inputEmail']);
        $inputPassword = htmlspecialchars($_POST['inputPassword']);
        $utilisateur = new Utilisateur($db);
        $unUtilisateur = $utilisateur->connect($inputEmail);
        if ($unUtilisateur != null) {
            if (!password_verify($inputPassword, $unUtilisateur['mdp'])) {
                $form['valide'] = false;
                $form['message'] = 'Login ou mot de passe incorrect';
            } else {
                $form['valide'] = true;
                $_SESSION['id'] = $unUtilisateur['id'];
                $_SESSION['email'] = $unUtilisateur['email'];
                $_SESSION['photo'] = $unUtilisateur['photo'];
                $_SESSION['pseudo'] = $unUtilisateur['pseudo'];
                $_SESSION['telephone'] = $unUtilisateur['telephone'];
                $_SESSION['role'] = $unUtilisateur['idRole'];
                $_SESSION['nom'] = $unUtilisateur['nom'];
                $_SESSION['prenom'] = $unUtilisateur['prenom'];
                header("Location:index.php");
            }
        } else {
            $form['valide'] = false;
            $form['message'] = 'Login ou mot de passe incorrect';
        }
    }
    echo $twig->render('connexion.html.twig', array('form' => $form));
}

function actionDeconnexion($twig) {
    session_unset();
    session_destroy();
    header("Location:index.php");
    echo $twig->render('deconnexion.html.twig', array());
}

function actionMonCompte($twig, $db) {
    $form = array();
    $utilisateur = new Utilisateur($db);

    $unutilisateur = $utilisateur->selectMonCompte($_SESSION['id'], $_SESSION['login']);
    echo $twig->render('moncompte.html.twig', array('form' => $form, 'u' => $unutilisateur));
}

function actionMdpOublie($twig, $db) {
    $form = array();

    if (isset($_POST['btRecuperer'])) {
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
    }
    if (!empty($unUtilisateur)) {
        $from = "marceau0707@gmail.com";
        $to = $email;
        $subject = "Récupérer votre mot de passe";
        $message = "<p>Mail: " . $email . "</p><p>Votre mot de passe: " . $form['code'] . "</p>";
        $headers = "From: " . $from . "\n";
        $headers .= "Reply-To: " . $from . "\n";
        $headers .= "Content-Type: text/html; charset=\"utf-8\"";

        $form['resul'] = mail($to, $subject, $message, $headers);
    }
    echo $twig->render('mdpOublie.html.twig', array('form' => $form));
}

function actionMdpOublieSecondStep($twig, $db) {
    $form = array();

    if (isset($_POST['btRecupererCode'])) {
        $code = htmlspecialchars($_POST['inputCode']);
        $utilisateur = new Utilisateur($db);
        $exec = $utilisateur->updateMdp();
        if ($exec != null) {
            if (empty($code)) {
                if ($code != form . code) // AJOUTER LE TEST RECUPERANT LE CODE ENVOYE DE LA FONCTION AU DESSUS
                    $form['valide'] = false;
                $form['message'] = 'Veuillez renseigner un code valide';
            } else {
                $form['valide'] = true;
            }
        } else {
            $form['valide'] = false;
            $form['message'] = 'Code incorrect';
        }
    }
    echo $twig->render('mdpOublieSecondStep.html.twig', array('form' => $form));
}

/* ESSAIS DURANT LA PERIODE DE STAGE */

function actionEssai($twig, $db) {
    $form = array();
    $utilisateur = new Utilisateur($db);
    $liste = $utilisateur->mailSender();
    $message = htmlspecialchars($_POST['inputMessage']);
    $objet = htmlspecialchars($_POST['inputObjet']);
    if (isset($_POST['btEnvoyer'])) {
        for ($i = 1 ; $i < sizeof($liste) ; $i++) {            
            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"Legendarium.com"<support@Legendarium.com>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $messagecontenue = "
                                <html>
                                    <body>
                                        <div align='center'>
                                        " . $message . "
                                                <br/>
                                        </div>
                                    </body>
                                </html>
                                ";
            mail($liste[$i]['email'], $objet, $messagecontenue, $header);
        }
    }
    echo $twig->render('essai.html.twig', array('form' => $form, 'liste'=>$liste));
}
