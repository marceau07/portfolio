<?php
//$db = new PDO('mysql:host=localhost;dbname=naturopa_btly', 'naturopa_btly', 'azerty59');
class rdv {
    private $db;
    private $drop;
    
    public function __construct($db) {
        $this->db = $db;
        $this->drop = $db->prepare("DELETE rdv FROM julie_bressand_rendez_vous rdv INNER JOIN horaires h ON julie_bressand_rendez_vous.heure=h.id WHERE date=:date AND horaireDeb=:heure");
    }
    
    public function drop($date, $heure) {
        $r = true;
        $this->drop->execute(array(':date'=>$date, ':heure'=>$heure));
        if ($this->drop->errorCode() != 0) {
            print_r($this->drop->errorInfo());
            $r = false;
        }
        return $r;
    }
}

$date = new DateTime('yesterday');
$date2 = $date->format('Y-m-d');

$heure = date("H");
$heure .= ":00:00.00";

$rdv = new rdv($db);
$unrdv = $rdv->drop($date2, $heure);

?>