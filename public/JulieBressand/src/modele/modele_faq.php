<?php

class FAQ {

	private $db;
	private $insert;
	private $select;
	private $delete;

	public function __construct($db) {
			$this->db = $db;
			$this->insert = $db->prepare("INSERT INTO julie_bressand_faq(quest, rep) 
										VALUES(:quest, :rep )");
			$this->select = $db->prepare('SELECT * 
										FROM julie_bressand_faq f 
										ORDER BY idfaq DESC');
			$this->delete = $db->prepare("DELETE FROM julie_bressand_faq 
										WHERE idfaq=:idfaq");
	}

	public function insert($quest, $rep) {
			$r = true;
			$this->insert->execute(array(':quest' => $quest, ':rep' => $rep));
			if ($this->insert->errorCode() != 0) {
				print_r($this->insert->errorInfo());
				$r = false;
			}
			return $r;
	}

	public function delete($idfaq) {
			$r = true;
			$this->delete->execute(array(':idfaq' => $idfaq));
			if ($this->delete->errorCode() != 0) {
				print_r($this->delete->errorInfo());
				$r = false;
			}
			return $r;
	}

	public function select() {
			$listefaq = $this->select->execute();
			if ($this->select->errorCode() != 0) {
				print_r($this->select->errorInfo());
			}
			return $this->select->fetchAll();
	}

}

?>
