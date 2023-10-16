<?php

function actionMemoryModule($twig, $lang, $db) {
    if(!empty($_POST['moduleComplete'])) {
        $game = new Game($db);
        $game->updateMemory("1", $_COOKIE['PHPSESSID']);
        die();
    }
    
    $form = array();
    
    $form['randomNumber'] = rand(1, 4);
    
    $numbers = [1, 2, 3, 4];
    shuffle($numbers);
    
    echo $twig->render("${lang}/memory.html.twig", array('form' => $form, 'numbers' => $numbers));
}

function actionWiresModule($twig, $lang, $db) {
    if(!empty($_POST['moduleComplete'])) {
        $game = new Game($db);
        $game->updateWires("1", $_COOKIE['PHPSESSID']);
        die();
    }
    
    if(!empty($_POST['wire_selected'])) {
        foreach($_POST['wires'] as $i=>$w) {
            $wires[] = [
                $i => $w['colour']
            ];
        }
        json_encode($wires);
        $serial_number = explode(' ', $_POST['serial_number']);
        if($_POST['nb_wires'] == 3) {
            /**
             * Si la disposition des fils est "BLEU" "BLEU" "ROUGE" -> 2eme fil
             */
            if($wires[0][0] == "blue" && $wires[1][1] == "blue" && $wires[2][2] == "red" && $_POST['wire']['id'] == 2) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * S'il n'y a pas de rouge -> 2eme fil
             */
            elseif($wires[0][0] != "red" && $wires[1][1] != "red" && $wires[2][2] != "red" && $_POST['wire']['id'] == 2) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Sinon -> 3eme fil
             */
            elseif($_POST['wire']['id'] == 3) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            die(json_encode(array(
                    "resultat" => false
                )));
        }
        if($_POST['nb_wires'] == 4) {
            $red_wires = 0;
            $blue_wires = 0;
            $yellow_wires = 0;
            for($i = 0 ; $i < $_POST['nb_wires'] ; $i++) {
                if($wires[$i][$i] == "red") {
                    $last_red_wire = $i;
                    $red_wires++;
                }
                if($wires[$i][$i] == "blue") {
                    $blue_wires++;
                }
                if($wires[$i][$i] == "yellow") {
                    $yellow_wires++;
                }
            }
            
            /**
             * 2+ fils rouges présents et SERIAL NUMBER pair -> dernier fil rouge
             */
            if($red_wires >= 2 &&
                    $serial_number[1] % 2 == 0 && $_POST['wire']['id'] == $last_red_wire
                ) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Si le dernier fil est jaune et qu'il n'y a pas de rouge -> 1er fil
             */
            if($wires[5][5] == "jaune" && $red_wires == 0 && $_POST['wire']['id'] == 1) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * S'il y a un fil bleu
             */
            if($blue_wires == 1 && $_POST['wire']['id'] == 1) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * S'il y a 2 fils jaunes ou plus -> 4eme fil
             */
            if($yellow_wires >=2 && $_POST['wire']['id'] == 4) {
                 die(json_encode(array(
                    "resultat" => true
                )));
            }
            if($_POST['wire']['id'] == 2) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            die(json_encode(array(
                    "resultat" => false
                )));
        }
        if($_POST['nb_wires'] == 5) {
            $black_wires = 0;
            $red_wires = 0;
            $yellow_wires = 0;
            for($i = 0 ; $i < $_POST['nb_wires'] ; $i++) {
                if($wires[$i][$i] == "red") {
                    $red_wires++;
                }
                if($wires[$i][$i] == "black") {
                    $black_wires++;
                }
                if($wires[$i][$i] == "yellow") {
                    $yellow_wires++;
                }
            }
            /**
             * Dernier fil est noir et SERIAL NUMBER pair -> 4eme fil
             */
            if($wires[6][6] == "black" && $serial_number % 2 == 0 && $_POST['wire']['id'] == 4) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Si 1 fil rouge et minimum 2 fils jaunes -> 1er fil
             */
            if($red_wires == 1 && $yellow_wires >= 2 && $_POST['wire']['id'] == 1) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Si pas de fil noir -> 2eme fil
             */
            if($black_wires == 0 && $_POST['wire']['id'] == 2) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Sinon -> 1er fil
             */
            if($_POST['wire']['id'] == 1) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            die(json_encode(array(
                    "resultat" => false
                )));
        }
        if($_POST['nb_wires'] == 6) {
            $yellow_wires = 0;
            $white_wires = 0;
            $red_wires = 0;
            for($i = 0 ; $i < $_POST['nb_wires'] ; $i++) {
                if($wires[$i][$i] == "red") {
                    $red_wires++;
                }
                if($wires[$i][$i] == "yellow") {
                    $yellow_wires++;
                }
                if($wires[$i][$i] == "white") {
                    $white_wires++;
                }
            }
            
            /**
             * Si Pas de jaune et SERIAL NUMBER pair -> 3eme fil
             */
            if($yellow_wires == 0 && $serial_number % 2 == 0 && $_POST['wire']['id'] == 3) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Si 1 jaune et 2 ou plus fils blancs -> 4eme fil
             */
            if($yellow_wires == 1 && $white_wires >= 2 && $_POST['wire']['id'] == 4) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Si pas de rouge -> 6eme fil
             */
            if($red_wires == 0 && $_POST['wire']['id'] == 6) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            /**
             * Sinon -> 4eme fil
             */
            if($_POST['wire']['id'] == 4) {
                die(json_encode(array(
                    "resultat" => true
                )));
            }
            die(json_encode(array(
                    "resultat" => false
                )));
        }
    }
    
    if(!empty($_POST['wires'])) {
        $wires_number = rand(3, 6);
        $wires_colors = array();
        $wires_colors_available = [
                            "0" => "blue",
                            "1" => "red",
                            "2" => "yellow",
                            "3" => "black",
                            "4" => "white",
                        ];
        for($i = 0 ; $i < $wires_number ; $i++) {
            $j = rand(0, 4);
            $wires_colors[] = $wires_colors_available[$j];
        }
        
        die(json_encode(array(
            "wires_number" => $wires_number,
            "wires_colors" => $wires_colors
        )));
    }
    $form = array();
    
    echo $twig->render("${lang}/wires.html.twig", array('form' => $form));
}

function actionSymbolsModule($twig, $lang, $db) {
    if(!empty($_POST['moduleComplete'])) {
        $game = new Game($db);
        $retour = $game->updateSymbols("1", $_COOKIE['PHPSESSID']);
        echo $retour;die;
    }
    
    $form = array();
    
    $nb_a_tirer = 4;
    $val_min = 0;
    $val_max = 5;
    $form['images'] = array();
    while($nb_a_tirer != 0 ) {
      $nombre = mt_rand($val_min, $val_max);
      if(!in_array($nombre, $form['images'])) {
        $form['images'][] = $nombre;
        $form['sorted_pictures'][] = $nombre;
        $nb_a_tirer--;
      }
    }
    sort($form['sorted_pictures'], SORT_NUMERIC);
    $column = rand(1, 6);
    $picturesList = array("column_01" => array("0" => "Keypad28.png", "1" => "Keypad13.png", "2" => "Keypad30.png", "3" => "Keypad12.png", "4" => "Keypad07.png", "5" => "Keypad09.png", "6" => "Keypad23.png"), 
                        "column_02" => array("0" => "Keypad16.png", "1" => "Keypad28.png", "2" => "Keypad23.png", "3" => "Keypad26.png", "4" => "Keypad03.png", "5" => "Keypad09.png", "6" => "Keypad20.png"),
                        "column_03" => array("0" => "Keypad01.png", "1" => "Keypad08.png", "2" => "Keypad26.png", "3" => "Keypad05.png", "4" => "Keypad15.png", "5" => "Keypad30.png", "6" => "Keypad03.png"),
                        "column_04" => array("0" => "Keypad11.png", "1" => "Keypad21.png", "2" => "Keypad31.png", "3" => "Keypad07.png", "4" => "Keypad05.png", "5" => "Keypad20.png", "6" => "Keypad04.png"),
                        "column_05" => array("0" => "Keypad24.png", "1" => "Keypad04.png", "2" => "Keypad31.png", "3" => "Keypad22.png", "4" => "Keypad21.png", "5" => "Keypad19.png", "6" => "Keypad02.png"),
                        "column_06" => array("0" => "Keypad11.png", "1" => "Keypad16.png", "2" => "Keypad27.png", "3" => "Keypad14.png", "4" => "Keypad24.png", "5" => "Keypad18.png", "6" => "Keypad06.png")
                    );
    echo $twig->render("${lang}/symbols.html.twig", array('form' => $form, 'picturesList1' => $picturesList["column_0${column}"][$form['images'][0]], 'picturesList2' => $picturesList["column_0${column}"][$form['images'][1]], 'picturesList3' => $picturesList["column_0${column}"][$form['images'][2]], 'picturesList4' => $picturesList["column_0${column}"][$form['images'][3]]));
}

function actionMorseCodeModule($twig, $lang, $db) {
    if(!empty($_POST['moduleComplete'])) {
        $game = new Game($db);
        $game->updateMorseCode("1", $_COOKIE['PHPSESSID']);
        die();
    }
    
    $form = array();
    
    $rand_number = rand(0, 15);
    
    $words = array(
        "0" => "beats",        
        "1" => "bistro",
        "2" => "bombs",        
        "3" => "boxes",
        "4" => "break",
        "5" => "brick",
        "6" => "flick",
        "7" => "halls",
        "8" => "leaks",
        "9" => "shell",
        "10" => "slick",
        "11" => "steak",
        "12" => "sting",
        "13" => "strobe",
        "14" => "trick",
        "15" => "vector"
    );
     
    $flashes = array(
            "A" => array("short", "long"),
            "B" => array("long", "short", "short", "short"),
            "C" => array("long", "short", "long", "short"),
            "D" => array("long", "short", "short"),
            "E" => array("short"),
            "F" => array("short", "short", "long", "short"),
            "G" => array("long", "long", "short"),
            "H" => array("short", "short", "short", "short"),
            "I" => array("short", "short"),
            "J" => array("short", "long", "long", "long"),
            "K" => array("long", "short", "long"),
            "L" => array("short", "long", "short", "short"),
            "M" => array("long", "long"),
            "N" => array("long", "short"),
            "O" => array("long", "long", "long"),
            "P" => array("short", "long", "long", "short"),
            "Q" => array("long", "long", "short", "long"),
            "R" => array("short", "long", "short"),
            "S" => array("short", "short", "short"),
            "T" => array("long"),
            "U" => array("short", "short", "long"),
            "V" => array("short", "short", "short", "long"),
            "W" => array("short", "long", "long"),
            "X" => array("long", "short", "short", "long"),
            "Y" => array("long", "short", "long", "long"),
            "Z" => array("long", "long", "short", "short"),
            
            "0" => array("long", "long", "long", "long", "long"), 
            "1" => array("short", "long", "long", "long", "long"), 
            "2" => array("short", "short", "long", "long", "long"), 
            "3" => array("short", "short", "short", "long", "long"), 
            "4" => array("short", "short", "short", "short", "long"), 
            "5" => array("short", "short", "short", "short", "short"), 
            "6" => array("long", "short", "short", "short", "short"), 
            "7" => array("long", "long", "short", "short", "short"), 
            "8" => array("long", "long", "long", "short", "short"), 
            "9" => array("long", "long", "long", "long", "short"),
            
            "beats"     => "6;0;0",        
            "bistro"    => "5;5;2",
            "bombs"     => "5;6;5",        
            "boxes"     => "5;3;5",
            "break"     => "5;7;2",
            "brick"     => "5;7;5",
            "flick"     => "5;5;5",
            "halls"     => "5;1;5",
            "leaks"     => "5;4;2",
            "shell"     => "5;0;5",
            "slick"     => "5;2;2",
            "steak"     => "5;8;2",
            "sting"     => "5;9;2",
            "strobe"    => "5;4;5",
            "trick"     => "5;3;2",
            "vector"    => "5;9;5"
        );
        die(var_dump(explode(";", $flashes[$words[$rand_number]])));
    echo $twig->render("${lang}/morse_code.html.twig", array('form' => $form, 'word' => $words[$rand_number]));
}

function actionWin($twig, $lang) {
    $form = array();
    echo $twig->render("${lang}/win.html.twig", array('form' => $form));
}

function actionLose($twig, $lang) {
    $form = array();
    echo $twig->render("${lang}/lose.html.twig", array('form' => $form));
}