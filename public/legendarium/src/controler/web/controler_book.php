<?php

function webUpdateCoverBookAction($db, $twig) {
    $result = array();
    $book_new_picture = '';
    $book = new Book($db);
    if(!empty($_POST['form_cover_update'])) {
        $data = $_POST;
        try {
            if (isset($_FILES['form_cover_file'])) {
                if (!empty($_FILES['form_cover_file']['name'])) {
                    $extensions_ok = array('png', 'jpg', 'jpeg', 'jfif');
                    $dest_dossier = '/var/www/html/symfony4-4017/public/legendarium/web/img/books/';
                    if (!in_array(substr(strrchr($_FILES['form_cover_file']['name'], '.'), 1), $extensions_ok)) {
                        echo 'Veuillez sélectionner un fichier de type image !';
                    } else {
                        $photo = basename($_FILES['form_cover_file']['name']);
                        // enlever les accents
                        $photo = strtr($photo, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAA
                                         AAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                        // remplacer les caractères autres que lettres, chiffres et point par _
                        $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
                        // copie du fichier

//                        move_uploaded_file($_FILES['form_cover_file']['tmp_name'], $dest_dossier . $photo);
                        move_uploaded_file($_FILES['form_cover_file']['tmp_name'], $dest_dossier . 'book_cover_0' . $_GET['idBook'] . '_new.' . substr(strrchr($_FILES['form_cover_file']['name'], '.'), 1));
                        $book_new_picture = 'book_cover_0' . $_GET['idBook'] . '_new.' . substr(strrchr($_FILES['form_cover_file']['name'], '.'), 1);
                    }
                }
            }
            
            $data['book_picture_new'] = 'book_cover_0' . $_GET['idBook'] . '_new.' . substr(strrchr($_FILES['form_cover_file']['name'], '.'), 1);
            
            $exec = $book->updatePicture($data['book_picture_new'], $_GET['idBook']);
            if($exec) { echo "La couverture du livre a bien été mise à jour"; }
            else { echo "Une erreur s'est produite lors de la mise à jour de la couverture"; }
        } catch (Exception $e) {
            echo $e->getMessage();
        }        
    }
    $b = $book->selectById($_GET['idBook']);
    
    
    echo $twig->render('android/update_cover.html.twig', array('b' => $b, 'book_new_picture' => $book_new_picture));
}
