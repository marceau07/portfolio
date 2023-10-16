<?php

function actionManage($twig) {
    $form = array();
    echo $twig->render('manage.html.twig', array('form' => $form));
}

function actionAddArticle($twig, $db) {
    $form = array();

    $category = new Category($db);
    $listC = $category->select();

    $user = new User($db);
    $listU = $user->select();

    $newsletter = new Newsletter($db);
    $listN = $newsletter->select();

    if (isset($_POST['btAjouterArticle'])) {
        $titleArticle = $_POST['titleArticle'];
        $contentArticle = $_POST['contentArticle'];
        $dateArticle = date("Y-m-d", time());
        $sourceArticle = htmlspecialchars($_POST['sourceArticle']);
        $imageArticle = NULL;
        $idCategory = htmlspecialchars($_POST['idCategory']);
        $idUser = htmlspecialchars($_POST['idUser']);

        if (isset($_FILES['imageArticle'])) {
            if (!empty($_FILES['imageArticle']['name'])) {
                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 10000000;
                $dest_dossier = dirname(__DIR__, 2) . '/web/img/articles/';

                if (!in_array(substr(strrchr($_FILES['imageArticle']['name'], '.'), 1), $extensions_ok)) {
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg.';
                } else {
                    if (file_exists($_FILES['imageArticle']['tmp_name']) && (filesize($_FILES['imageArticle']['tmp_name'])) > $taille_max) {
                        echo 'Votre fichier doit faire moins de 10 Mo !';
                    } else {
                        $imageArticle = basename($_FILES['imageArticle']['name']);
                        $imageArticle = strtr($imageArticle, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        $imageArticle = preg_replace('/([^.a-z0-9]+)/i', '_', $imageArticle);
                        move_uploaded_file($_FILES['imageArticle']['tmp_name'], $dest_dossier . $imageArticle);
                    }
                }
            }
        }

        $form['valide'] = true;

        $article = new Article($db);
        $exec = $article->insert($titleArticle, NULL, $contentArticle, $dateArticle, $sourceArticle, $imageArticle, 1, $idCategory, $idUser, NULL);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Article posté.';

            // Envoi d'un mail aux abonnés à la newsletter
            for ($i = 0; $i < sizeof($listN); $i++) {
                $header = "MIME-Version: 1.0\r\n";
                $header .= 'From:"lecoviddechaine.fr"<lecoviddechaine.fr>' . "\n";
                $header .= 'Content-Type:text/html; charset="uft-8"' . "\n";
                $header .= 'Content-Transfer-Encoding: 8bit';

                $message = "
                            <html>
                                <body>
                                    <div align='center'>
                                        Un nouvel article est paru sur le site, consultez-le !<br/>
                                        <b>$titleArticle</b><br/>
                                        <a href='http//serveur1.arras-sio.com/symfony4-4017/lecoviddechaine/web/'>Le Covid Déchaîné</a>
                                    </div>
                                </body>
                            </html>
                            ";

                mail($listN[$i]['email'], "Nouvel article sur LCD !", $message, $header);
            }
            // Fin envoi mail
            actionFullArticleToPdf($twig, $db);
        }
    }

    echo $twig->render('add_article.html.twig', array('form' => $form, 'listU' => $listU, 'listC' => $listC));
}

function actionAddCategory($twig, $db) {
    $form = array();

    $category = new Category($db);

    if (isset($_POST['btAddCategory'])) {
        $labelCategory = htmlspecialchars($_POST['labelCategory']);

        $exec = $category->insert($labelCategory);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Une erreur s\'est produite.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Catégorie ajoutée.';
        }
    }

    echo $twig->render('add_category.html.twig', array('form' => $form));
}

function actionAddFaq($twig, $db) {
    $form = array();

    $category = new Category($db);

    $listC = $category->select();

    if (isset($_POST['btAjouterFaq'])) {
        $titleFaq = htmlspecialchars($_POST['titleFaq']);
        $answerFaq = $_POST['answerFaq'];
        $idCategory = htmlspecialchars($_POST['idCategory']);

        $form['valide'] = true;

        $faq = new Faq($db);

        $exec = $faq->insert($titleFaq, $answerFaq, $idCategory);

        if (!$exec) {
            $form['valide'] = false;
            $form['message'] = 'Veuillez vérifier les informations saisies.';
        } else {
            $form['valide'] = true;
            $form['message'] = 'Ajout effectué avec succès.';
        }
    }

    echo $twig->render('add_faq.html.twig', array('form' => $form, 'listC' => $listC));
}

function actionDeleteArticle($twig, $db) {
    if(!empty($_POST['id_article'])) {
        $article = new Article($db);
        $article->delete($_POST['id_article']);
    }
    
}

function actionUpdateArticle($twig, $db) {
    extract($_POST);

    if (!empty($idArticle) && isset($idArticle) || !empty($titleArticle) && isset($titleArticle) || !empty($contentArticle) && isset($contentArticle)) {
        $article = new Article($db);
        
        $exec = $article->update(ltrim($titleArticle), ltrim($contentArticle), $idArticle);

        if (!$exec) {
            echo 'Une erreur s\'est produite';
        } else {
            echo 'Modification effectuée';
            actionFullArticle($twig, $db);
            die;
        }
    } else {
        echo 'Une erreur s\'est produite';
    }
}

function actionPanel($twig, $db) {
    $form = array();

    $article = new Article($db);

    $form['articleActivated'] = $article->selectVisibilityY();
    $form['articleUnactivated'] = $article->selectVisibilityN();

    echo $twig->render('panel.html.twig', array('form' => $form));
}

function actionUpdateVisibilityArticle($twig, $db) {
    extract($_POST);

    $article = new Article($db);

    if (!empty($idArticle) && isset($idArticle) && isset($isActivated)) {
        $exec = $article->visibility(!$isActivated, $idArticle);
    }

    echo $isActivated ? 'Article désactivé' : 'Article réactivé';
}
