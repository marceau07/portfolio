<?php

function getPage($db) {

// Inscrire vos contrôleurs ici   
//Pages de base
    $lesPages['index'] = "actionIndex;0";
    $lesPages['signin'] = "actionSignIn;0";
    $lesPages['mentions'] = "actionMentions;0";
    $lesPages['login'] = "actionLogIn;0";
    $lesPages['logout'] = "actionLogOut;0";
    $lesPages['about'] = "actionAbout;0";
    $lesPages['maintenance'] = "actionMaintenance;0";
//Utilisateur
    $lesPages['user'] = "actionUserList;0";
    $lesPages['usermodify'] = "actionUserModify;0";
//Team
    $lesPages['team'] = "actionTeamList;0";
    $lesPages['teammodify'] = "actionTeamModify;0";
    $lesPages['teamadd'] = "actionTeamAdd;0";
//Contact
    $lesPages['contact'] = "actionContactList;0";
    $lesPages['contactmodify'] = "actionContactModify;0";
    $lesPages['contactadd'] = "actionContactAdd;0";
//Firm
    $lesPages['firmadd'] = "actionFirmAdd;0";
    $lesPages['firmmodify'] = "actionFirmModify;0";
    $lesPages['firm'] = "actionFirmList;0";
//Specifications
    $lesPages['specification'] = "actionSpecificationList;0";
    $lesPages['specificationadd'] = "actionSpecificationAdd;0";
//Skill
    $lesPages['skill'] = "actionSkillList;0";
    $lesPages['skilladd'] = "actionSkillAdd;0";
    $lesPages['skillmodify'] = "actionSkillModify;0";
    //Contract
    $lesPages['contract'] = "actionContractList;0";
    $lesPages['contractadd'] = "actionContractAdd;0";
    $lesPages['contractmodify'] = "actionContractModify;0";
    //Employee
    $lesPages['employee'] = "actionEmployeeList;0";
    $lesPages['employeeadd'] = "actionEmployeeAdd;0";
    $lesPages['employeemodify'] = "actionEmployeeModify;0";
    $lesPages['listemployee'] = "actionListEmployee;0";
    $lesPages['assignateampage'] = "actionAssignATeamPage;0";
    $lesPages['assignateam'] = "actionAssignATeam;0";
    $lesPages['assignataskpage'] = "actionAssignATaskPage;0";
    $lesPages['assignatask'] = "actionAssignATask;0";
    //Project
    $lesPages['project'] = "actionProjectList;0";
    $lesPages['projectadd'] = "actionProjectAdd;0";
    $lesPages['projectmodify'] = "actionProjectModify;0";
    //Module
    $lesPages['module'] = "actionModuleList;0";
    $lesPages['moduleadd'] = "actionModuleAdd;0";
    $lesPages['modulemodify'] = "actionModuleModify;0";
    //Task 
    $lesPages['task'] = "actionTaskList;0";
    $lesPages['taskadd'] = "actionTaskAdd;0";
    $lesPages['taskmodify'] = "actionTaskModify;0";
    //WebService PDF
    $lesPages['userPDFWS'] = "actionUserPDFWS;0";
    $lesPages['teamPDFWS'] = "actionTeamPDFWS;0";
    $lesPages['contractPDFWS'] = "actionContractPDFWS;0";
    $lesPages['employeePDFWS'] = "actionEmployeePDFWS;0";
    $lesPages['projectPDFWS'] = "actionProjectPDFWS;0";
    $lesPages['taskPDFWS'] = "actionTaskPDFWS;0";
    $lesPages['skillPDFWS'] = "actionSkillPDFWS;0";
    $lesPages['firmPDFWS'] = "actionFirmPDFWS;0";
    $lesPages['contactPDFWS'] = "actionContactPDFWS;0";
    $lesPages['modulePDFWS'] = "actionModulePDFWS;0";
    //WebService Ajax
    $lesPages['skillWS'] = "actionSkillWS;0";
        $lesPages['moduleWS'] = "actionModuleWS;0";



    if ($db != null) {
        if (isset($_GET['page'])) {
            // Nous mettons dans la variable $page, la valeur qui a été passée dans le lien
            $page = $_GET['page'];
        } else {
            // S'il n'y a rien en mémoire, nous lui donnons la valeur « accueil » afin de lui afficher une page
            //par défaut
            $page = 'index';
        }

        if (!isset($lesPages[$page])) {
            // Nous rentrons ici si cela n'existe pas, ainsi nous redirigeons l'utilisateur sur la page d'accueil
            $page = 'index';
        }

        $explose = explode(";", $lesPages[$page]);
        $role = $explose[1];

        // Si le rôle nécessite de contrôler les droits
        if ($role != 0) {
            // Nous vérifions que la personne est connectée
            if (isset($_SESSION['login'])) {
                //Nous vérifions qu'elle a un rôle
                if (isset($_SESSION['role'])) {

                    if ($role != $_SESSION['role']) {
                        //Nous redigeons la personne vers la page d'acccueil car elle n'a pas le bon rôle 
                        $contenu = 'actionIndex';
                    } else {
                        // La personne est autorisée à récupérer  
                        $contenu = $explose[0];
                    }
                } else {
                    // Dans la session le rôle n'existe pas donc on va sur la page d'accueil 
                    $contenu = 'actionIndex';
                }
            } else {
                // La personne n'est pas connectée, donc on va sur la page d'accueil  
                $contenu = 'actionIndex';
            }
        } else {
            // Nous donnons du contenu non protégé  
            $contenu = $explose[0];
        }
    } else {
        // La base de données n'est pas accessible
        $contenu = 'actionMaintenance';
    }
// La fonction envoie le contenu
    return $contenu;
}

?>