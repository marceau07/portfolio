<?php

function actionInscriptionAndroid($twig, $db) {
    $results["error"] = false;
    $results['message'] = [];
    if (isset($_POST)) {

        if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password2']) && !empty($_POST['firstName']) && !empty($_POST['lastName'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];
            $lastName = $_POST['lastName'];
            $firstName = $_POST['firstName'];
            $idRole = 1;

            $employe = new Employe($db);

            if (strlen($lastName) < 2 || !preg_match("/^[a-zA-Z]+$/", $lastName)) {
                $results["error"] = true;
                $results["message"]["lastName"] = "Nom invalide";
            }

            if (strlen($firstName) < 2 || !preg_match("/^[a-zA-Z]+$/", $firstName)) {
                $results["error"] = true;
                $results["message"]["firstName"] = "Prénom invalide";
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $results["error"] = true;
                $results["message"]["email"] = "Email invalide";
            } else {
                $exec = $employe->selectByEmail($email);
                if ($exec) {
                    $results["error"] = true;
                    $results["message"]["email"] = "Email déjà pris";
                }
            }

            if ($password !== $password2) {
                $results["error"] = true;
                $results["message"]["password"] = "Les mots de passe doivent être identiques !";
            }

            if ($results["error"] === false) {
                $exec = $employe->insert($email, password_hash($password, PASSWORD_DEFAULT), $lastName, $firstName, $idRole);
                if (!$exec) {
                    $results["error"] = true;
                    $results["message"] = "Erreur lors de l'inscription";
                }
            }
        } else {
            $results["error"] = true;
            $results["message"] = "Veuillez remplir tous les champs !";
        }
        echo json_encode($results);
    }
}

function actionConnexionAndroid($twig, $db) {
    $results["error"] = false;
    $results["message"] = [];

    if (!empty($_POST)) {
        if (!empty($_POST['email']) && !empty($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $employe = new Employe($db);
            $exec = $employe->selectByEmail($email);
            if ($exec) {
                $results["error"] = false;
                $results["full_name"] = $exec['full_name'];
                $results["id"] = $exec['idEmploye'];
                $results["email"] = $exec['email'];
            } else {
                $results["error"] = true;
                $results["message"] = "Pseudo / Mot de passe incorrect";
            }
        } else {
            $results['error'] = true;
            $results['message'] = "Veuillez remplir tous les champs!";
        }
    }
    echo json_encode($results);
}

function actionEmployeAndroid($twig, $db) {
    $employe = new Employe($db);
    $listeE = $employe->select();
    $v = $listeE;
    echo json_encode($v);
}

/*
 * Fonctions Android w/ Flutter
 */

function actionScriptsJson($twig, $db) {
    $script = new Script($db);
    $scriptsList = $script->select();
    echo json_encode($scriptsList);
}

function actionUsersJson($twig, $db) {
    $user = new Utilisateur($db);
    $usersList = $user->select();
    echo json_encode($usersList);
}

function actionEmployeesJson($twig, $db) {
    $employee = new Employe($db);
    $employeesList = $employee->select();
    echo json_encode($employeesList);
}

function actionFunctionsJson($twig, $db) {
    $function = new Fonction($db);
    $functionList = $function->select();
    echo json_encode($functionList);
}

function actionOSJson($twig, $db) {
    $os = new Os($db);
    $osList = $os->select();
    echo json_encode($osList);
}

function actionComputersJson($twig, $db) {
    $computer = new Ordinateur($db);
    $computersList = $computer->select();
    if (!empty($_GET['networkId']) && isset($_GET['networkId'])) {
        $nb = $_GET['networkId'];
        $computersList = $computer->selectCarousel($nb);
    }
    for ($i = 0; $i < sizeof($computersList); $i++) {
        if ($computersList[$i]['unEmploye'] == null) {
            $computersList[$i]['unEmploye'] = "Aucun employé rattaché à ce PC.";
        }
    }

    echo json_encode($computersList);
}

function actionNetworksJson($twig, $db) {
    $computer = new Ordinateur($db);
    $computersNetworksList = $computer->selectDistinctReseau();
    echo json_encode($computersNetworksList);
}

function actionAddOSJson($twig, $db) {
    $os = new Os($db);
    $v = array();
    if (isset($_GET['nomOs']) && !empty($_GET['nomOs'])) {
        $exec = $os->insert($idOs, $_GET['nomOs']);
        if ($exec) {
            $v['message'] = "L'OS a bien été ajouté";
        } else {
            $v['message'] = "Une erreur s'est produite";
        }
    }
    echo json_encode($v);
}

function actionDeleteAccount($twig, $db) {
    $employee = new Employe($db);
    $v = array();
    if (isset($_GET['email']) && !empty($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
        $exec = $employee->deleteByEmail($_GET['email']);
        if ($exec) {
            $v['message'] = " Vous allez être déconnecté.";
            $v['isOk'] = true;
            $email = $_GET['email'];

            //Envoie d'un mail de confirmation//
            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"parcinformatique.fr"<no-reply@si7parcinformatique.fr>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $message = "
                    <html>
                        <body>
                            <div align='center'>
                                Salut $email !<br/>
                                <p>C'est le dernier email que vous recevrez de notre part tant que vous ne vous réinscrivez pas sur l'application et/ou le site.</p>
                                <p>Si vous avez le temps et l'envie, remplissez le formulaire disponible à l'adresse suivante s'il vous plaît: <a href='#'>formulaire</a></p>
                            </div>
                        </body>
                    </html>
                   ";

            mail($email, "SI7 - Questionnaire, à très bientôt", $message, $header);
//fin d'envoie du mail//
        } else {
            $v['message'] = " Une erreur s'est produite.";
            $v['isOk'] = false;
        }
    } else {
        $v['message'] = " Une erreur s'est produite.";
        $v['isOk'] = false;
    }
    echo json_encode($v);
}

function actionRetrieveAccountData($twig, $db) {
    $employee = new Employe($db);
    $v = array();
    if (isset($_GET['email']) && !empty($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
        $v['message'] = " Vous allez recevoir vos informations par email.";
        $v['isOk'] = true;
        $email = $_GET['email'];
        $anEmployee = $employee->selectByEmail($email);
        //Envoie d'un mail de confirmation//
        $header = "MIME-Version: 1.0\r\n";
        $header .= 'From:"parcinformatique.fr"<no-reply@si7parcinformatique.fr>' . "\n";
        $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
        $header .= 'Content-Transfer-Encoding: 8bit';

        $message = "
                    <html>
                        <body>
                            <div align='center'>
                                Hey " . $anEmployee['prenomEmploye'] . ",<br/>
                                <p> Ci-joint vos informations demandées sur l'application.
                                A bientôt sur <a href='//serveur1.arras-sio.com/symfony4-4017/parcinformatique/web/index.php'>SI7 - Parc Informatique.</a></p>
                            </div>
                        </body>
                    </html>
                   ";

        mail($email, "SI7 - Parc Informatique", $message, $header);
//fin d'envoie du mail//
    } else {
        $v['message'] = " Une erreur s'est produite.";
        $v['isOk'] = false;
    }
    echo json_encode($v);
}

function actionSignIn($twig, $db) {
    $v = array();
    $t = "Bienvenue ";
    $employee = new Employe($db);
    if (isset($_GET['email']) && !empty($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) && isset($_GET['fullName']) && !empty($_GET['fullName'])) {
        $name = trim($_GET['fullName']);
        $lastName = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $firstName = trim(preg_replace('#' . $lastName . '#', '', $name));
        $t .= $firstName;
        $t .= ' ';
        $t .= $lastName;
        $t .= " !";

        $exec = $employee->insert($_GET['email'], null, $lastName, $firstName, 1);
        if ($exec) {
            $v['message'] = $t;
            $v['isOk'] = true;

            $email = $_GET['email'];

            //Envoie d'un mail de confirmation//
            $header = "MIME-Version: 1.0\r\n";
            $header .= 'From:"parcinformatique.fr"<no-reply@si7parcinformatique.fr>' . "\n";
            $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';

            $message = "
                    <html>
                        <body>
                            <div align='center'>
                                Bienvenue $firstName !<br/>
                                <p>Connecte-toi vite pour profiter de la puissance de l'outil. Rendez-vous sur <a href='//serveur1.arras-sio.com/symfony4-4017/parcinformatique/web/index.php'>SI7 - Parc Informatique.</a></p>
                            </div>
                        </body>
                    </html>
                   ";

            mail($email, "SI7 - Bienvenue $firstName", $message, $header);
//fin d'envoie du mail//
        } else {
            $v['message'] = " Une erreur s'est produite.";
            $v['isOk'] = false;
        }
    } else {
        $v['message'] = " Une erreur s'est produite.";
        $v['isOk'] = false;
    }
    echo json_encode($v);
}
