<?php

class Horaire{
    
    private $db;
    private $insert; 
    private $select;
    private $update;
    
    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO legendarium_schedules(schedule_day_label, schedule_am_start, schedule_am_end, schedule_pm_start, schedule_pm_end) 
									VALUES(:schedule_day_label, :schedule_am_start, :schedule_am_end, :schedule_pm_start, :schedule_pm_end)");             
        $this->select = $db->prepare("SELECT * 
									FROM legendarium_schedules");
        $this->update = $db->prepare("UPDATE legendarium_schedules 
									SET schedule_day_label=:schedule_day_label, schedule_am_start=:schedule_am_start, schedule_am_end=:schedule_am_end, schedule_pm_start=:schedule_pm_start, schedule_pm_end=:schedule_pm_end 
									WHERE schedule_id=:schedule_id"); 
    }

    public function insert($schedule_day_label, $schedule_am_start, $schedule_am_end, $schedule_pm_start, $schedule_pm_end) {
        $r = true;
        $this->insert->execute(array(':schedule_day_label' => $schedule_day_label, ':schedule_am_start' => $schedule_am_start, 
									':schedule_am_end' => $schedule_am_end, ':schedule_pm_start' => $schedule_pm_start, ':schedule_pm_end' => $schedule_pm_end));
        if ($this->insert->errorCode()!=0){
            print_r($this->insert->errorInfo());  
            $r=false;
        }
        return $r;
    }
    
    public function select() {
        $this->select->execute();
        if ($this->select->errorCode()!=0){
            print_r($this->select->errorInfo());  
        }
        return $this->select->fetchAll();
    }

    public function update($schedule_id, $schedule_day_label, $schedule_am_start, $schedule_am_end, $schedule_pm_start, $schedule_pm_end){
        $r = true;
        $this->update->execute(array(':schedule_id' => $schedule_id, ':schedule_day_label' => $schedule_day_label, 
									':schedule_am_start' => $schedule_am_start, ':schedule_am_end' => $schedule_am_end, 
									':schedule_pm_start' => $schedule_pm_start, ':schedule_pm_end' => $schedule_pm_end));
        if ($this->update->errorCode()!=0){
            print_r($this->update->errorInfo());
            $r=false;
        }
        return $r;
    }
}

?>