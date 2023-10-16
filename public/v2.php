<?php 
include_once 'admin/php/admin_ajax.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/php/global_functions.php';

try{
    $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_LOGIN, DB_PASSWD);
} catch(Exception $e){
    $db = NULL;
}

if($db !== NULL) {
    $sql_select_cartes = 'SELECT 
                            index_builder_id, 
                            index_builder_link, 
                            index_builder_picture, 
                            index_builder_alt_picture, 
                            index_builder_title, 
                            index_builder_status, 
                            index_builder_description, 
                            index_builder_languages, 
                            index_builder_github, 
                            index_language_label, 
                            index_language_icon, 
                            index_language_picture 
                        FROM index_builder 
                        LEFT JOIN index_languages ON index_languages.index_language_id = index_builder.index_builder_languages';
    $cartes = $db->query($sql_select_cartes)->fetchAll(PDO::FETCH_ASSOC);
}

//dump($cartes);

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Mes projets V2</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Playfair+Display:700|Raleway:500.700">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.12.1/css/all.css">
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Your custom styles (optional) -->
        <link rel="stylesheet" href="css/style_v2.css">
        <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    </head>
    <body>
        <h1 class="title">Marceau RODRIGUES</h1>

        <div id="app" class="container">
            <?php foreach($cartes as $c) { ?>
                <card data-image="img/<?=$c['index_builder_picture']?>">
                  <h1 slot="header"><?=$c['index_builder_title']?></h1>
                  <p slot="content"><?=$c['index_builder_description']?></p>
                </card>
            <?php } ?>
        </div>
        
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.0.1/vue.min.js"></script>
        <script type="text/javascript" src="js/style_v2.js?v=<?= uniqid()?>"></script>
    </body>
</html>