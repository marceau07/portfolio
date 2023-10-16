<?php

function androidViewAvailabilitiesAction($db) {    
    $availability = new Availability($db);
    try {
        $data = $availability->select();
    } catch (Exception $e) {
        $data = $e->getMessage();
    }
    
    die(json_encode($data));
}