<?php
require_once($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/admin/php/bdd.php");
define("ADMIN_USER", array("seaumar", "login4017", "marceau", "root", "admin"));
define("ADMIN_PASSWD", "btsinfo");
define("TEMPS", 14400);

if(!empty($_POST['recupererTempsActivite'])) {
    $date1 = date_create_from_format('Y-m-d H:i:s', trim(shell_exec('uptime -s')), new DateTimeZone('Europe/Paris'))->modify('+4 hour');
    $date2 = date_create_from_format('Y-m-d H:i:s', date('Y-m-d H:i:s'), new DateTimeZone('Europe/Paris'));
    $closedInHours = $date1->diff($date2)->format('%d/%m/%y');
    $closedInHoursExplode = explode('/', $closedInHours);
    
    $closedIn = $date1->diff($date2)->format('%H:%i:%s');
    $closedInExplode = explode(':', $closedIn);

    $fermeDans = "Le serveur ne se ferme plus.";
//    $fermeDans = (!empty($closedInHoursExplode[2]) ? $closedInHoursExplode[2].($closedInHoursExplode[2] > 1 ? " années " : " an ") : "");
//    $fermeDans .= (!empty($closedInHoursExplode[1]) ? $closedInHoursExplode[1]." mois " : "");
//    $fermeDans .= (!empty($closedInHoursExplode[0]) ? $closedInHoursExplode[0].($closedInHoursExplode[0] > 1 ? " jours " : " jour ") : "");
//    $fermeDans .= (!empty($closedInExplode[0]) ? $closedInExplode[0].($closedInExplode[0] > 1 ? " heures " : " heure ") : "");
//    $fermeDans .= (!empty($closedInExplode[1]) ? $closedInExplode[1].($closedInExplode[1] > 1 ? " minutes " : " minute ") : "");
    
    $data = shell_exec('uptime -p');
    $uptime = str_replace('up ', '', $data);
    if(preg_match('/, /', $uptime)) {
        $uptime = str_replace(', ', '', $uptime, $countMinutes);
    }
    
    if(preg_match('/years/', $uptime, $countHours)) { // Années
        $uptime = str_replace("years", "ans ", $uptime);
    } elseif(preg_match('/year/', $uptime, $countHours)) { // Année
        $uptime = str_replace("year", "année ", $uptime);
    }
    if(preg_match('/weeks/', $uptime, $countHours)) { // Semaines
        $uptime = str_replace("weeks", "semaines ", $uptime);
    } elseif(preg_match('/week/', $uptime, $countHours)) { // Semaine
        $uptime = str_replace("week", "semaine ", $uptime);
    }
    if(preg_match('/days/', $uptime, $countHours)) { // Jours 
        $uptime = str_replace("days", "jours ", $uptime);
    } elseif(preg_match('/day/', $uptime, $countHours)) { // Jour
        $uptime = str_replace("day", "jour ", $uptime);
    }
    if(preg_match('/months/', $uptime, $countHours)) { // Mois 
        $uptime = str_replace("months", "mois ", $uptime);
    } elseif(preg_match('/month/', $uptime, $countHours)) { // Mois
        $uptime = str_replace("month", "mois ", $uptime);
    } 
    if(preg_match('/years/', $uptime, $countHours)) { // Années 
        $uptime = str_replace("years", "années ", $uptime);
    } elseif(preg_match('/year/', $uptime, $countHours)) { // Année
        $uptime = str_replace("year", "année ", $uptime);
    } 
    if(preg_match('/hours/', $uptime, $countHours)) { // Heures
        $uptime = str_replace("hours", "heures et ", $uptime);
        if($countMinutes === 0 && $countHours > 0) $uptime .= ' 0 minute';
    } elseif(preg_match('/hour/', $uptime, $countHours)) { // Heure
        $uptime = str_replace("hour", "heure et ", $uptime);
        if($countMinutes === 0 && $countHours > 0) $uptime .= ' 0 minute';
    } 
    
    if(!preg_match('/days/', $uptime, $countHours) && !preg_match('/day/', $uptime, $countHours) && !preg_match('/hours/', $uptime, $countHours) && !preg_match('/hour/', $uptime, $countHours)) {
        if(isset($countMinutes) && $countMinutes === 0) {
            $uptime = '0 minute';
        }
    }
    
    die(json_encode(array(
      'uptime' => $uptime,   
      'fermeDans' => $fermeDans
    )));
}

if(!empty($_POST['enregistrerSite'])) {
    enregistrerSite();
    die;
}

function enregistrerSite() {
    global $db;
	
    $index_builder_group_id = htmlspecialchars($_POST['form_admin_group_id']);
    $index_builder_link = htmlspecialchars($_POST['form_admin_link']);
    $index_builder_picture = "logoNoLogo.png";
    $index_builder_alt_picture = htmlspecialchars($_POST['form_admin_picture_alt']);
    $index_builder_title = htmlspecialchars($_POST['form_admin_title']);
    $index_builder_status = htmlspecialchars($_POST['form_admin_status']);
    $index_builder_description = htmlspecialchars($_POST['form_admin_description']);
    $index_builder_languages = rtrim(implode(', ', $_POST['form_admin_languages']), ', ');
    $index_builder_github = htmlspecialchars($_POST['form_admin_github']);
    if (isset($_FILES['form_admin_picture'])) {
        if (!empty($_FILES['form_admin_picture']['name'])) {
            $extensions_ok = array('png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG', 'svg', 'SVG');
            $dest_dossier = dirname(__DIR__, 2) . '/img/';
            $extension = explode('/', $_FILES['form_admin_picture']['type'])[1];
            if (!in_array(substr(strrchr($_FILES['form_admin_picture']['name'], '.'), 1), $extensions_ok)) {
                echo 'Veuillez sélectionner un fichier de type png, jpg ou vectoriel.';
            } else {
                $index_builder_picture = basename(htmlspecialchars($_FILES['form_admin_picture']['name']));
                $index_builder_picture = strtr($index_builder_picture, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $index_builder_picture = preg_replace('/([^.a-z0-9]+)/i', '_', $index_builder_picture);
                if(substr($index_builder_picture, 0, 4) != "logo") {
                    $index_builder_picture = "logo".$index_builder_picture;
                }
                move_uploaded_file($_FILES['form_admin_picture']['tmp_name'], $dest_dossier . $index_builder_picture);
            }
        }
    }
    
    $sql_insert_site = 'INSERT INTO index_builder 
                            (index_builder_group_id, 
                            index_builder_link, 
                            index_builder_picture, 
                            index_builder_alt_picture, 
                            index_builder_title, 
                            index_builder_status, 
                            index_builder_description, 
                            index_builder_languages, 
                            index_builder_github)
                        VALUES
                            (:index_builder_group_id, 
                            :index_builder_link, 
                            :index_builder_picture, 
                            :index_builder_alt_picture, 
                            :index_builder_title, 
                            :index_builder_status, 
                            :index_builder_description, 
                            :index_builder_languages, 
                            :index_builder_github)';
    $req_insert_site = $db->prepare($sql_insert_site);
    $req_insert_site->bindParam(':index_builder_group_id', $index_builder_group_id);
    $req_insert_site->bindParam(':index_builder_link', $index_builder_link);
    $req_insert_site->bindParam(':index_builder_picture', $index_builder_picture);
    $req_insert_site->bindParam(':index_builder_alt_picture', $index_builder_alt_picture);
    $req_insert_site->bindParam(':index_builder_title', $index_builder_title);
    $req_insert_site->bindParam(':index_builder_status', $index_builder_status);
    $req_insert_site->bindParam(':index_builder_description', $index_builder_description);
    $req_insert_site->bindParam(':index_builder_languages', $index_builder_languages);
    $req_insert_site->bindParam(':index_builder_github', $index_builder_github);
    if($req_insert_site->execute()) {
        die(json_encode(array(
            "success" => 1,
            "error" => 0,
            "message" => 'Import effectué'
        )));
    }
    die(json_encode(array(
        "success" => 0,
        "error" => 1,
        "message" => 'Echec lors de l\'importation'
    )));
}

if(!empty($_POST['afficherStatsAdministration'])) {
    $sql_select_rows = 'SELECT 
                            index_statistique_module_id
                        FROM index_statistiques
                        GROUP BY index_statistique_module_id ';
    $req_select_rows = $db->prepare($sql_select_rows);
    $req_select_rows->execute();
    $rows = $req_select_rows->fetchAll(PDO::FETCH_COLUMN);
    
    $sql_select_statistiques = 'SELECT 
                                    MAX(ind_stat.index_statistique_ip) AS ip, 
                                    ind_buil.index_builder_title, 
                                    SUM(ind_stat.index_statistique_count) AS totModule 
                                FROM index_statistiques ind_stat 
                                JOIN index_builder ind_buil ON (ind_stat.index_statistique_module_id = ind_buil.index_builder_group_id) 
                                GROUP BY index_statistique_ip, ind_stat.index_statistique_module_id ';
    $req_select_statistiques = $db->prepare($sql_select_statistiques);
    $req_select_statistiques->execute();
    $statsIndex = $req_select_statistiques->fetchAll(PDO::FETCH_ASSOC);
    
    $sql_select_series_statistiques = 'SELECT 
                                            ind_buil.index_builder_title, 
                                            ind_stat.index_statistique_ip, ';
    $i = 0;
    foreach($rows as $key => $r) {
        $sql_select_series_statistiques .= ' IF(ind_stat.index_statistique_module_id = '.intval($r).', SUM(ind_stat.index_statistique_count), 0) AS totModule'.intval($r).($i < sizeof($rows)-1 ? ',':'');
        $i++;
    }
    $sql_select_series_statistiques .= ' FROM index_statistiques ind_stat 
                                         JOIN index_builder ind_buil ON (ind_stat.index_statistique_module_id = ind_buil.index_builder_group_id)
                                         GROUP BY index_statistique_ip, index_builder_group_id ';
    $req_select_series_statistiques = $db->prepare($sql_select_series_statistiques);
    $req_select_series_statistiques->execute();
    $statsIndexSeries = $req_select_series_statistiques->fetchAll(PDO::FETCH_ASSOC);
    
    $tableau_series = array();
    $tableau_added = array();
    foreach($statsIndexSeries as $value) {
        if(!isset($tableau_series['title'])) {
            $tableau_series['title'] = $value['index_builder_title'];
        }
        foreach($rows as $key => $r) {
            $tableau_series['Module'.$r] = $value['totModule'.$r];
        }
    }

    ob_start(); ?>
    <script>
    var el = document.getElementById('chart_stats_index');
    
    var data = {
        categories: [
            <?php 
            $i = 1;
            foreach($statsIndex as $value) {
                echo "'".$value['ip']."'".($i < sizeof($statsIndex) ? ", ":"");
                $i++;
            } ?>
            
        ],
        series: [
                {
                    name: '<?=str_replace("'", "", $tableau_series['title'])?>', 
                    data: [
                        <?php 
                            $i = 0;
                            foreach($rows as $r) {
                                echo $r.($i < sizeof($rows)-1 ?',':'');
                                $i++;
                            }
                        ?>
                    ]
                },
        ]
    };
    var options = {
            chart: { width: 'auto', height: 'auto' }, 
            series: {
                selectable: true 
            }, 
            legend: {
//                align: 'bottom',
//                item: {
//                  width: 70,
//                  overflow: 'ellipsis'
//                }
            }
        };

    window.chartStats = toastui.Chart.columnChart({ el, data, options });

    dayMode();
    $('#modale_statistiques_administration').modal('show');
    if($('#body').hasClass('night')) {
        $('#modale_statistiques_administration').find('.modal-content').addClass('night');
    } else {
        $('#modale_statistiques_administration').find('.modal-content').addClass('day');
    }
    </script>
    <?php 
    $js = ob_get_clean();
    
    die(json_encode(array(
        "bEstOk" => true, 
        "js" => $js
    )));
}

if(!empty($_POST['afficherAdministration'])) {
	$result = "Nom d'utilisateur ou mot de passe administrateur incorrect.";
	if(in_array($_POST['admin_username'], ADMIN_USER) && ADMIN_PASSWD === $_POST['admin_password']) {
		$sql_select_groups = 'SELECT * FROM index_groups';
		$req_select_groups = $db->prepare($sql_select_groups);
		$req_select_groups->execute();
		$groupsLists = $req_select_groups->fetchAll(PDO::FETCH_ASSOC);
		
                $sql_select_status = 'SELECT * FROM index_status';
		$req_select_status = $db->prepare($sql_select_status);
		$req_select_status->execute();
		$statusLists = $req_select_status->fetchAll(PDO::FETCH_ASSOC);
		
                $sql_select_languages = 'SELECT * FROM index_languages';
		$req_select_languages = $db->prepare($sql_select_languages);
		$req_select_languages->execute();
		$statusLanguages = $req_select_languages->fetchAll(PDO::FETCH_ASSOC);
		
		$result = ' <div class="container-fluid">
                                <form onchange="verifierFormAdmin();" id="form_administration_ajouter" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <label for="form_admin_group_id">Section</label>
                                            <select class="form-control admin_obligatoire" id="form_admin_group_id" name="form_admin_group_id">
                                                <option value="0">Sélectionner une section</option>';
                                            foreach($groupsLists as $group) {
                                                $result .= '<option value="'.$group['index_group_id'].'">'.$group['index_group_label'].'</option>';
                                            }
                $result .= '                </select>
                                        </div>
                                        <div class="col-sm-12 form-group" style="overflow-x: auto;">
                                            <label for="form_admin_link">Lien vers le site</label>&nbsp;
                                            <p>
                                                <span id="link_dynamic_host">
                                                </span>
                                                <span id="link_dynamic">
                                                    {votre lien}
                                                </span>
                                            </p>
                                            <p><input type="longtext" class="form-control admin_obligatoire" id="form_admin_link" name="form_admin_link" onkeyup="majLienSite();" /></p>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label for="form_admin_link">Logo:</label>
                                            <input type="file" class="form-control-file" style="color: #FFFFFF;" id="form_admin_picture" name="form_admin_picture" />
                                            <input type="text" placeholder="Nom du logo" class="form-control admin_obligatoire" id="form_admin_picture_alt" name="form_admin_picture_alt" />
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label for="form_admin_title">Titre:</label>
                                            <input type="text" class="form-control admin_obligatoire" id="form_admin_title" name="form_admin_title" />
                                            
                                            <label for="form_admin_status">Etat:</label>
                                            <select class="form-control" id="form_admin_status" name="form_admin_status">
                                                <option value="0">Sans statut</option>';
                                            foreach($statusLists as $status) {
                                                $result .= '<option value="'.$status['index_status_id'].'">'.$status['index_status_label'].'</option>';
                                            }
                $result .= '                </select>
                                            
                                            <label for="form_admin_description">Description:</label>
                                            <input type="text" class="form-control admin_obligatoire" id="form_admin_description" name="form_admin_description" />
                                            
                                            <label for="form_admin_languages" id="form_admin_languages_label">Langages utilisés:</label>
                                            <ul id="form_admin_languages" name="form_admin_languages" style="list-style-type: none;">
                                                <li>
                                                    <div class="row">';
                                                    foreach($statusLanguages as $lang) {
                $result .= '                            <div class="col-sm-4">
                                                            <input type="checkbox" class="form-check-input" name="form_admin_language_'.strtolower($lang['index_language_label']).'" id="form_admin_language_'.strtolower($lang['index_language_label']).'" value="'.$lang['index_language_id'].'"/>
                                                            <label for="form_admin_language_'.strtolower($lang['index_language_label']).'">'.$lang['index_language_label'].'</label>&nbsp;
                                                        </div>';
                                                    }
                $result .= '                        </div>    
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-12 form-group">
                                            <label for="form_admin_github">Lien vers le répertoire Git:</label>
                                            <input type="text" class="form-control" id="form_admin_github" name="form_admin_github" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <script>
                                majLienSite();
                                function majLienSite() {
                                    var defaultLink = window.location.href;
                                    defaultLink = defaultLink.replace("#", "");
                                    if(!defaultLink.endsWith("/")) defaultLink = defaultLink + "/";
                                    if($("#form_admin_link").val() === "") {
                                        $("#link_dynamic_host").html(defaultLink);
                                        $("#link_dynamic_host").css("display", "inline-block"); 
                                        $("#link_dynamic").html("").html("{votre lien}"); 
                                    } else { 
                                        if($("#form_admin_link").val().startsWith("http", 0)) {
                                            $("#link_dynamic_host").css("display", "none"); 
                                        } else {
                                            $("#link_dynamic_host").html(defaultLink);
                                            $("#link_dynamic_host").css("display", "inline-block"); 
                                        }
                                        $("#link_dynamic").html("").html($("#form_admin_link").val()); 
                                    }
                                }
                            </script>';
            die(json_encode(array(
                "success" => 1,
                "error" => 0,
                "message" => $result
            )));
	}
        die(json_encode(array(
            "success" => 0,
            "error" => 1,
            "message" => $result
        )));
}

if(!empty($_POST['editerSite'])) {
    if (isset($_FILES['form_edit_img'])) {
        if (!empty($_FILES['form_edit_img']['name'])) {
            $index_builder_picture = NULL;
            $index_builder_picture_alt = NULL;
            
            $extensions_ok = array('png', 'PNG', 'jpg', 'JPG', 'jpeg', 'JPEG');
            $dest_dossier = dirname(__DIR__, 2) . '/img/';
            $extension = explode('/', $_FILES['form_edit_img']['type'])[1];
            if (!in_array(substr(strrchr($_FILES['form_edit_img']['name'], '.'), 1), $extensions_ok)) {
                die(json_encode(array(
                    'sMessage' => 'Veuillez sélectionner un fichier de type png ou jpg.', 
                    'bEstOk' => false
                )));
            } else {
                $index_builder_picture = basename(htmlspecialchars($_FILES['form_edit_img']['name']));
                $index_builder_picture = strtr($index_builder_picture, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
                $index_builder_picture = preg_replace('/([^.a-z0-9]+)/i', '_', $index_builder_picture);
                if(substr($index_builder_picture, 0, 4) != "logo") {
                    $index_builder_picture = "logo".$index_builder_picture;
                }
                $index_builder_picture_alt = "Logo ". str_replace('logo', '', explode('.', $_FILES['form_edit_img']['name'])[0]);
                move_uploaded_file($_FILES['form_edit_img']['tmp_name'], $dest_dossier . $index_builder_picture);
            }
        }
    }
    
    
    $sql_update_carte = '   UPDATE index_builder 
                            SET
                                index_builder_group_id=:index_builder_group_id, 
                                index_builder_title=:index_builder_title, 
                                index_builder_description=:index_builder_description ';
    if(!empty($_POST['form_edit_languages'])) {
        $sql_update_carte .= ', index_builder_languages = "'.implode(',', $_POST['form_edit_languages']).'" ';  
    } else {
        $sql_update_carte .= ', index_builder_languages = NULL ';
    }
    if (isset($_FILES['form_edit_img'])) {
        $sql_update_carte .= ', index_builder_picture=:index_builder_picture, 
                                index_builder_alt_picture=:index_builder_alt_picture ';
    }
    $sql_update_carte .= '  WHERE index_builder_id=:index_builder_id ';
    $req_update_carte = $db->prepare($sql_update_carte);
    $req_update_carte->bindParam(':index_builder_id', $_POST['id_card']);
    $req_update_carte->bindParam(':index_builder_group_id', $_POST['form_edit_group_id']);
    $req_update_carte->bindParam(':index_builder_title', $_POST['form_edit_titre']);
    $req_update_carte->bindParam(':index_builder_description', $_POST['form_edit_resume']);
    if (isset($_FILES['form_edit_img'])) {
        $req_update_carte->bindParam(':index_builder_picture', $index_builder_picture);
        $req_update_carte->bindParam(':index_builder_alt_picture', $index_builder_picture_alt);
    }
    if($req_update_carte->execute()) {
        die(json_encode(array( 
            'bEstOk' => true
        )));
    }
    die(json_encode(array(
        'sMessage' => 'Une erreur s\'est produite.', 
        'bEstOk' => false
    )));
}

if(!empty($_POST['openModeEditionSite'])) {
    $sql_select_carte = '   SELECT 
                                index_builder_group_id, 
                                index_builder_title, 
                                index_builder_description, 
                                index_builder_languages, 
                                index_builder_github
                            FROM index_builder 
                            WHERE index_builder_id=:index_builder_id ';
    $req_select_carte = $db->prepare($sql_select_carte);
    $req_select_carte->bindParam(':index_builder_id', $_POST['id_card']);
    $req_select_carte->execute();
    $carte = $req_select_carte->fetch(PDO::FETCH_ASSOC);
    
    $sql_select_groups = 'SELECT 
                            index_group_id, 
                            index_group_label
                          FROM index_groups';
    $req_select_groups = $db->prepare($sql_select_groups);
    $req_select_groups->execute();
    $groups = $req_select_groups->fetchAll(PDO::FETCH_ASSOC);
    $groupsLists = '';
    foreach($groups as $value) {
        if($carte['index_builder_group_id'] == $value['index_group_id']) {
            $groupsLists .= '<option value="'.$value['index_group_id'].'" selected>'.$value['index_group_label'].'</option>';
        } else {
            $groupsLists .= '<option value="'.$value['index_group_id'].'">'.$value['index_group_label'].'</option>';
        }
    }
    
    $sql_select_languages = 'SELECT 
                                index_language_id, 
                                index_language_label 
                            FROM index_languages';
    $req_select_languages = $db->prepare($sql_select_languages);
    $req_select_languages->execute();
    $statusLanguages = $req_select_languages->fetchAll(PDO::FETCH_ASSOC);
    $langages = '';
    foreach($statusLanguages as $value) {
        $langages .= '<div class="col-sm-6" style="padding-left: 35px;">';
        $langages .= '  <input type="checkbox" class="form-check-input" name="form_edit_languages_'.strtolower($value['index_language_label']).'" id="form_edit_languages_'.strtolower($value['index_language_label']).'_'.$_POST['id_card'].'" value="'.$value['index_language_id'].'" '.(is_numeric(strpos($carte['index_builder_languages'], $value['index_language_id'])) === true ? 'checked' : '').'/>';
        $langages .= '  <label for="form_edit_languages_'.strtolower($value['index_language_label']).'_'.$_POST['id_card'].'">'.$value['index_language_label'].'</label>&nbsp;';
        $langages .= '</div>';
    }
    
    die(json_encode(array(
        "carte_titre"       => $carte['index_builder_title'], 
        "carte_description" => $carte['index_builder_description'],
        "carte_github"      => $carte['index_builder_github'],
        "groupsLists"       => $groupsLists, 
        "langages"          => $langages 
    )));
}

if(!empty($_POST['exporterDonnees'])) {
    $test = shell_exec('sudo sh /var/www/html/symfony4-4017/scripts/export_to_mega.sh');
	
    die("ok:".$test);
}

?>