<?php
error_reporting(E_ALL);

class Spell_db {
	
	protected $dbcon;

	function __construct() {
		$this->dbcon = mysql_connect('localhost',	'root',	'');
		if ($this->dbcon === false) {
			die('DB not connected: '.mysql_error());
		}

		mysql_select_db('pfspells',	$this->dbcon);
	}
	
	function select($table, $columns = '*', $where = null) {
		$sql = 'SELECT '.$columns.' FROM '.$table;
		if ($where !== null) {
			$sql .= ' WHERE '.$where;
		}
	
		$res = $this->query($sql);

		return $this->compile_results($res);
	}
	
	function insert_bulk($table, $fields, $values) {
		$fields = implode('`, `', $fields);
		
		foreach($values as $value_set) {
			$this->insert($table, $fields, $value_set);
		}
		
		return true;
	}
	
	function insert($table, $fields, $values) {
		$sql = 'INSERT INTO `'.$table.'` (`'.implode("`, `", $fields).'`) VALUES ';
		$sql .= "('".implode("', '", $values)."')";
		
		$this->query($sql);
	}
	
	function query($sql) {
		$res = mysql_query($sql, $this->dbcon);
		if ($res === false) {
			throw new Exception("SQL statement not executed: <br>\n".mysql_error($this->dbcon)."<br>\n".$sql);
		}
		
		return $res;
	}
	
	function compile_results($res) {
		if (mysql_num_rows($res) < 1) {
			return false;
		}
		
		$results = array();
		
		while($row = mysql_fetch_object($res)) {
			array_push($results, $row);
		}
		
		return $results;
	}
	
	function __destruct() {
		mysql_close($this->dbcon);
	}
	
}
	
	
