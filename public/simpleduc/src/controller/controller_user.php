<?php

/*
 * Created by Ludivine
 */

function actionSignIn($twig, $db) {
    $form = array();
    $role = new Role($db);
    $listeR = $role->select();
    if (isset($_POST['btSignin'])) {

        $emailUser = htmlspecialchars($_POST['emailUser']);
        $passwordUser1 = htmlspecialchars($_POST['passwordUser1']);
        $passwordUser2 = htmlspecialchars($_POST['passwordUser2']);
        $passwordAdmin = htmlspecialchars($_POST['passwordAdmin']);
        $lastNameUser = htmlspecialchars($_POST['lastNameUser']);
        $firstNameUser = htmlspecialchars($_POST['firstNameUser']);

        $form['valide'] = true;

        $user = new User($db);
        if ($passwordUser1 != $passwordUser2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } elseif (strlen($passwordUser1) < 8) {
            $form['valide'] = false;
            $form['message'] = 'Votre mot de passe est trop court il doit contenir au minimum 8 caractères';
        } else {
            if ($passwordAdmin != "btsinfo") {
                $idRoleUser = '1';
            } else {
                $idRoleUser = '2';
            }
            $_SESSION['emailUser'] = $emailUser;
            $user = new User($db);
            $exec = $user->insert($emailUser, password_hash($passwordUser1, PASSWORD_DEFAULT), $lastNameUser, $firstNameUser, $idRoleUser);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
            }
        }
        $form['emailUser'] = $emailUser;
    }

    echo $twig->render('signin.html.twig', array('form' => $form, 'session' => $_SESSION, 'listeR' => $listeR,'idRoleUser' =>$idRoleUser));
}

/*
 * Created by Ludivine
 */

function actionLogIn($twig, $db) {
    $form = array();

    if (isset($_POST['btLogin'])) {
        $emailUser = filter_input(INPUT_POST, 'emailUser', FILTER_SANITIZE_EMAIL);
        $passwordUser = filter_input(INPUT_POST, 'passwordUser', FILTER_SANITIZE_SPECIAL_CHARS);
        $user = new User($db);
        $anUser = $user->connect($emailUser);
        
        if ($anUser != null) {
            if (!password_verify($passwordUser, $anUser['passwordUser'])) {
                $form['valide'] = false;
                $form['message'] = 'Mot de passe incorrect';
            } else {
                $form['valide'] = true;
                $_SESSION['emailUser'] = $emailUser;
                $_SESSION['passwordUser'] = $passwordUser;
                $anUser = $user->selectByEmail($emailUser);
                $_SESSION['idRoleUser'] = $anUser['idRoleUser'];
                $_SESSION['firstNameUser'] = $anUser['firstNameUser'];
                header("Location: ../web/");
            }
        } elseif ($anUser == false) {
            $form['valide'] = false;
            $form['message'] = 'Login incorrect';
        }
    }
    echo $twig->render('login.html.twig', array('form' => $form));
}

function actionLogOut() {
    session_unset();
    session_destroy();
    header("Location: ../web/");
}

/*
 * Created by Ludivine
 */

function actionUserList($twig, $db) {
    $form = array();
    $user = new User($db);

    if (isset($_GET['id'])) {
        $user = new User($db);
        $aUser = $user->selectById($_GET['id']);

        if ($aUser != null) {
            $form['team'] = $aUser;
        } else {
            $form['message'] = 'Utilisateur incorrecte';
        }
    }
    $liste = $user->select();
    echo $twig->render('user_list.html.twig', array('form' => $form, 'liste' => $liste));
}

/*
 * Created by Ludivine
 */

function actionUserModify($twig, $db) {
    $form = array();

    $role = new Role($db);
    $listeR = $role->select();

    if (isset($_GET['emailUser'])) {
        $user = new User($db);
        $aUser = $user->selectByEmail($_GET['emailUser']);
        if ($aUser != null) {
            $form['user'] = $aUser;
        } else {
            $form['message'] = 'Utilisateur incorrecte';
        }
    } else {
        if (isset($_POST['btModify'])) {
            $user = new User($db);
            $emailUser = $_POST['email'];
            $lastNameUser = $_POST['lastNameUser'];
            $firstNameUser = $_POST['firstNameUser'];
            $idRoleUser = $_POST['idRoleUser'];
            $exec = $user->update($lastNameUser, $firstNameUser, $idRoleUser, $emailUser);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Problème de modification dans la table utilisateur';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Modification réussie !';
            }
        } else {
            $form['message'] = 'Utilisateur non précisé';
        }
    }

    echo $twig->render('user_modify.html.twig', array('form' => $form, 'listeR' => $listeR));
}