<?php

function getPage($db) {

    $lesPages['index'] = "actionIndex;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
    $lesPages['googleAuth'] = "actionGoogleAuth;0";
    $lesPages['signin'] = "actionSignIn;0";
    $lesPages['logout'] = "actionLogout;0";

    $lesPages['leaderboard'] = "actionLeaderboard;0";
    $lesPages['game'] = "actionGame;0";

    $lesPages['privacy'] = "actionPrivacy;0";
    $lesPages['cgu'] = "actionCgu;0";
    $lesPages['about'] = "actionAbout;0";
    
    $lesPages['download'] = "actionDownload;0";

    if ($db != null) {
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 'index';
        }

        if (!isset($lesPages[$page])) {
            $page = 'index';
        }

        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];

        if ($role != 0) {

            if (isset($_SESSION['login'])) {
                if (isset($_SESSION['role'])) {
                    if ($role != $_SESSION['role']) {
                        $contenu = 'actionIndex';
                    } else {
                        $contenu = $explose[0];
                    }
                } else {
                    $contenu = 'actionIndex';
                }
            } else {
                $contenu = 'actionIndex';
            }
        } else {
            $contenu = $explose[0];
        }
    } else {
        $contenu = 'actionMaintenance';
    }

    return $contenu;
}

?>
