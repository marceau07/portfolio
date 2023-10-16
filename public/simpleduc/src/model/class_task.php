<?php

class Task {

    private $db;
    private $insert;
    private $select;
    private $update;
    private $delete;
    private $selectById;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $db->prepare("INSERT INTO simpleduc_tasks(labelTask, descTask, idTeamTask, costTask ) 
									VALUES(:labelTask, :descTask, :idTeamTask, :costTask)");
        $this->select = $db->prepare("SELECT t.idTeam,tk.idTask, tk.labelTask, tk.descTask, tk.idTeamTask, tk.costTask, t.nameTeam 
									FROM simpleduc_tasks tk 
									INNER JOIN simpleduc_teams t ON tk.idTeamTask = t.idTeam 
									ORDER BY labelTask");
        $this->update = $db->prepare("UPDATE simpleduc_tasks 
									SET labelTask=:labelTask, descTask=:descTask, idTeamTask=:idTeamTask, costTask=:costTask 
									WHERE idTask=:idTask");
        $this->selectById = $db->prepare("SELECT t.idTeam, idTask, tk.labelTask, tk.descTask, tk.idTeamTask, tk.costTask, t.nameTeam 
										FROM simpleduc_tasks tk 
										INNER JOIN simpleduc_teams t ON tk.idTeamTask = t.idTeam 
										WHERE idTask=:idTask 
										ORDER BY idTask");
        $this->delete = $db->prepare("DELETE FROM simpleduc_tasks 
									WHERE idTask=:idTask");
    }

    public function insert($labelTask, $descTask, $idTeamTask, $costTask) {
        $r = true;
        $this->insert->execute(array(':labelTask' => $labelTask, ':descTask' => $descTask, ':idTeamTask' => $idTeamTask, ':costTask' => $costTask));
        if ($this->insert->errorCode() != 0) {
            print_r($this->insert->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function update($idTask, $labelTask, $descTask, $idTeamTask, $costTask) {
        $r = true;
        $this->update->execute(array(':idTask' => $idTask,':labelTask' => $labelTask, ':descTask' => $descTask, ':idTeamTask' => $idTeamTask, ':costTask' => $costTask));
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function delete($idTask) {
        $r = true;
        $this->delete->execute(array(':idTask' => $idTask));
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            $r = false;
        }
        return $r;
    }

    public function selectById($idTask) {
        $this->selectById->execute(array(':idTask' => $idTask));
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

}
?>

