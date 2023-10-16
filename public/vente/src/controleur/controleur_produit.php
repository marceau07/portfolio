<?php

function actionProduit($twig, $db) {
    $form = array(); 
    $produit = new Produit($db);
   
    if (isset($_POST['btAjouter'])) {
        $inputDesignation = $_POST['inputDesignation'];
        $inputDescription = $_POST['inputDescription']; 
        $inputPrix = $_POST['inputPrix']; 
        $inputidType = $_POST['inputidType'];
        $photo = NULL;
        
        if(isset($_FILES['photo'])) {
            if(!empty($_FILES['photo']['name'])) {
                $extensions_ok = array('png', 'gif', 'jpg', 'jpeg');
                $taille_max = 500000;
                $dest_dossier = '/var/www/html/vente/web/images/';
                if( !in_array( substr(strrchr($_FILES['photo']['name'], '.'), 1), $extensions_ok )) {
                    echo 'Veuillez sélectionner un fichier de type png, gif ou jpg !';
                }
                else {
                if( file_exists($_FILES['photo']['tmp_name'])&& (filesize($_FILES['photo']['tmp_name'])) > $taille_max){
                    echo 'Votre fichier doit faire moins de 500Ko !';
                }
                else { 
                    $photo = basename($_FILES['photo']['name']);
// enlever les accents
                    $photo = strtr($photo,'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ','AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
// remplacer les caractères autres que lettres, chiffres et point par _
                    $photo = preg_replace('/([^.a-z0-9]+)/i', '_', $photo);
// copie du fichier
                    move_uploaded_file($_FILES['photo']['tmp_name'], $dest_dossier.$photo);
                }
                }
            }
        }

        $exec = $produit->insert($inputDesignation,$inputDescription, $inputPrix, $inputidType, $photo);
    }
   
    if(isset($_POST['btSupprimer'])){
        $cocher = $_POST['cocher'];
        $form['valide'] = true;
        foreach ( $cocher as $id) {
            $exec=$produit->delete($id);
        
            if (!$exec){
                $form['valide'] = false;
                $form['message'] = 'Problème de suppression dans la table produit';
            }
        }
    }
    
    if(isset($_GET['id'])){
       $exec=$produit->delete($_GET['id']);
        if (!$exec){
            $form['valide'] = false;
            $form['message'] = 'Problème de suppression dans la table produit';
        }
        else {
            $form['valide'] = true;
            $form['message'] = 'Produit supprimé avec succès';
        }
    }    
    
    $limite=3;
    if(!isset($_GET['nopage'])){
        $inf=0;
        $nopage=0;
    }
    else{
        $nopage=$_GET['nopage'];
        $inf=$nopage * $limite;
    }
    $r = $produit->selectCount();
    $nb = $r['nb'];
    
    $liste = $produit->selectLimit($inf,$limite);
    $form['nbpages'] = ceil($nb/$limite);
   
    echo $twig->render('produit.html.twig', array('form'=>$form,'liste'=>$liste));
}

function actionModifProduit($twig, $db) {
    $form = array();
    
    if(isset($_GET['id'])) {
        $produit = new Produit($db);
        $unProduit = $produit->selectById($_GET['id']);

            if ($unProduit!=null){
                $form['produit'] = $unProduit;
                $type = new Type($db);
                $liste = $type->select();
                $form['type'] = $liste;
            }
            else{
                $form['message'] = 'Produit incorrect';
            }
    }
    
    else {
        if(isset($_POST['btModifier'])) {
            $produit = new Produit($db);
            $id = $_POST['id'];
            $designation = $_POST['designation'];
            $description = $_POST['description'];
            $prix = $_POST['prix'];
            $idType = $_POST['idType'];
            $exec = $produit->update($id, $designation, $description, $prix, $idType);
        }
       
    }
    
   echo $twig->render('produit-modif.html.twig', array('form'=>$form));
   
}
                    
function actionListeProduitPdf($twig, $db){
    $produit = new Produit($db);
    $liste = $unProduit = $produit->select();
    
    $html = $twig->render('produit-liste-pdf.html.twig', array('liste'=>$liste)); // Nous envoyons notre liste de produit dans le moteur de template TWIG
    
    try {
        ob_end_clean(); // Cette commande s'assure de ne pas envoyer de données avant le fichier PDF
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'fr'); // Création d'une page au format A4 de langue française orienté en mode portrait
        $html2pdf->writeHTML($html); // Nous écrivons le résultat de twig dans la variable html2pdf
        $html2pdf->output('listedesproduits.pdf'); // Nous écrivons dans un fichier PDF nommé listedesproduits
    } 
    
    catch (Html2PdfException $e) {
        echo 'erreur '.$e;
    }
}

function actionProduitWS($twig, $db){
    $produit = new Produit($db);
    $json = json_encode($liste = $produit->select());
    echo $json;
}
?>
