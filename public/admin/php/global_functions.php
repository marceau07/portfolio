<?php
require_once($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/admin/php/bdd.php");
define('READ_LEN', 4096);
define('LIEN_RECETTE', '/var/www/html/symfony4-4017/recette/');
define('LIEN_PROD', '/var/www/html/symfony4-4017/public/');
define('LIEN_RACINE', '/var/www/html/symfony4-4017');

define('BASE_LIEN', '/var/www/html/symfony4-4017');
define('RECETTE', BASE_LIEN.'/recette');
define('PRODUCTION', BASE_LIEN.'/public');

$allowed_ips = array();
// $allowed_ips = array("93.23.106.110");

if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_index_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_index_address = $_SERVER['HTTP_X_FORWARDED_FOR'];  
} else {
    $ip_index_address = $_SERVER['REMOTE_ADDR'];
}

function gestionStatistiques($id_module) {
    global $ip_index_address;
    global $db;
    
    if($db !== NULL && !empty($ip_index_address)) {
        /**
         * Statistiques
         */
        $sql_select_stats = 'SELECT 
                                index_statistique_id, 
                                index_statistique_count 
                             FROM index_statistiques 
                             WHERE index_statistique_ip LIKE "'.$ip_index_address.'" AND index_statistique_module_id = '.$id_module;
        $statistique = $db->query($sql_select_stats)->fetch(PDO::FETCH_ASSOC);
        if(empty($statistique['index_statistique_id'])) {
            $sql_insert_stats = 'INSERT INTO index_statistiques 
                                    (index_statistique_ip, 
                                    index_statistique_count, 
                                    index_statistique_module_id, 
                                    index_statistique_last_date)
                                 VALUES 
                                    ("'.$ip_index_address.'", 
                                     1, 
                                     '. intval($id_module).', 
                                     NOW()) ';
            $insert = $db->query($sql_insert_stats);
        } else {
            $countVisit = $statistique['index_statistique_count'];
            $countVisit++;
            $sql_update_stats = 'UPDATE index_statistiques 
                                 SET index_statistique_ip = "'.$ip_index_address.'", 
                                     index_statistique_count = '.$countVisit.', 
                                     index_statistique_module_id = '.$id_module.', 
                                     index_statistique_last_date = NOW() 
                                 WHERE index_statistique_id = '.$statistique['index_statistique_id'].' AND index_statistique_module_id = '.$id_module;
            $db->query($sql_update_stats);
        }
        /**
         * Fin statistiques
         */
    }
}