<?php
class configs {
	private $host= "localhost:3307";
	private $user= "pis";
	private $pwd = "itprog2013";
	private $db  = "pis";
	private $link;
	
	function Connect() {
		$this->link = @mysql_connect($this->host,
					$this->user,$this->pwd)
				OR DIE("Cannot Connect!");				
		@mysql_select_db($this->db,$this->link) 
				OR DIE("Cannot Select!");			
	}
	
	function Disconnect() {
		@mysql_close($this->link);
	}	
}
?>