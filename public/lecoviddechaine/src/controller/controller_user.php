<?php

function actionSignIn($twig, $db) {
    $form = array();

    if (isset($_POST['btSignin'])) {

        $nicknameUser = htmlspecialchars($_POST['nicknameUser']);
        $passwordUser1 = htmlspecialchars($_POST['passwordUser1']);
        $passwordUser2 = htmlspecialchars($_POST['passwordUser2']);
        $idRoleUser = htmlspecialchars($_POST['idRole']);

        $form['valide'] = true;

        $user = new User($db);
        if ($passwordUser1 != $passwordUser2) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe sont différents';
        } else {
            $exec = $user->insert($nicknameUser, password_hash($passwordUser1, PASSWORD_DEFAULT), $idRoleUser);

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Veuillez vérifier les informations saisies.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Vous pouvez maintenant vous connecter avec vos identifiants.';
            }
        }
        $form['nicknameUser'] = $nicknameUser;
    }
    echo $twig->render('signin.html.twig', array('form' => $form, 'session' => $_SESSION));
}

function actionLogIn($twig, $db) {
    $form = array();

    $form['date'] = new DateTime();

    if (isset($_POST['btLogin'])) {
        $nicknameUser = filter_input(INPUT_POST, 'nicknameUser', FILTER_SANITIZE_SPECIAL_CHARS);
        $passwordUser = filter_input(INPUT_POST, 'passwordUser', FILTER_SANITIZE_SPECIAL_CHARS);
        $user = new User($db);
        $anUser = $user->connect($nicknameUser);

        if ($anUser != null) {
            if (!password_verify($passwordUser, $anUser['passwordUser'])) {
                $form['valide'] = false;
                $form['message'] = 'Mot de passe incorrect';
            } else {
                $form['valide'] = true;
                $_SESSION['idUser'] = $anUser['idUser'];
                $_SESSION['nicknameUser'] = $nicknameUser;
                $_SESSION['passwordUser'] = $passwordUser;
                $_SESSION['idRoleUser'] = $anUser['idRole'];
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

function actionResultSearch($twig, $db) {
    $user = new User($db);

    $listResult = $user->search($_POST['search']);

    echo json_decode($listResult);
}

function actionMyAccount($twig, $db) {
    $form = array();

    $user = new User($db);
    $form['user'] = $user->selectByNickname($_SESSION['nicknameUser']);
    if (isset($_POST['btModifyAccount'])) {
        $nicknameUser = htmlspecialchars($_POST['nicknameUser']);
        $oldPasswd = htmlspecialchars($_POST['oldPasswd']);
        $newPasswd = htmlspecialchars($_POST['newPasswd']);
        $confirmPasswd = htmlspecialchars($_POST['confirmPasswd']);

        if (password_verify($newPasswd, $form['user']['passwordUser'])) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez choisir un mot de passe différent du précédent.';
        } elseif ($oldPasswd == $newPasswd) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez choisir un mot de passe différent du précédent.';
        } elseif ($newPasswd != $confirmPasswd) {
            $form['valide'] = false;
            $form['message'] = 'Les mots de passe ne concordent pas.';
        } else {

            $exec = $user->updateMdp($nicknameUser, password_hash($newPasswd, PASSWORD_DEFAULT));

            if (!$exec) {
                $form['valide'] = false;
                $form['message'] = 'Une erreur s\'est produite. Veuillez réessayer.';
            } else {
                $form['valide'] = true;
                $form['message'] = 'Votre mot de passe a bien été modifié.';
            }
        }
    }

    if (isset($_POST['nicknameUser']) && !empty($_POST['nicknameUser']) && isset($_POST['isSubscribed']) && !empty($_POST['isSubscribed'])) {
        $user->updateSubscription($_POST['nicknameUser'], $_POST['isSubscribed']);
    }
    
     if (isset($_POST['nicknameUser']) && !empty($_POST['nicknameUser']) && isset($_POST['emailToSub']) && !empty($_POST['emailToSub'])) {
        $exec = $user->updateSubscriptionEmail($_POST['nicknameUser'], $_POST['emailToSub']);
    }

    echo $twig->render('account.html.twig', array('form' => $form));
}

function actionNewsletter($twig, $db) {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];

        $newsletter = new Newsletter($db);

        $exec = $newsletter->insert($email);

        if (!$exec) {
            echo 'notOk';
        } else {
            echo 'ok';
        }
    } else {
        echo 'Une erreur s\'est produite.';
    }
}

function actionDeleteAccount($twig, $db) {
    if (isset($_POST['nicknameUser']) && !empty($_POST['nicknameUser']) || isset($_POST['val']) && !empty($_POST['val'])) {
        if ($_POST['val'] == "CONFIRMER") {
            $user = new User($db);
            $exec = $user->suspendAccount($_POST['nicknameUser']);
            actionLogOut();
        } else {
            echo 'notOk';
        }
    } else {
        echo 'Une erreur s\'est produite.';
    }
}
