<?php
require('spell_db.php');

error_reporting(E_ALL);

$db = new Spell_db();
	
//create_classes($dbcon);

//create_levels_table($dbcon);

//populate_levels($dbcon);

//populate_sources($db);

populate_schools($db);

function populate_schools($db) {
	
//	$db->query('CREATE TABLE `schools` (`id` INT NOT NULL AUTO_INCREMENT, `code` VARCHAR(8), `name` VARCHAR(32), PRIMARY KEY(`id`))');


	$schools = $db->query('SELECT DISTINCT `school` FROM `spells`');
	
	$schools = $db->compile_results($schools);
	
	foreach($schools as $row) {
		$db->insert('schools', array('code', 'name'), array($row->school, $row->school));
	}
}

function populate_sources($db) {

//	$db->query('CREATE TABLE `sources` (`id` INT NOT NULL AUTO_INCREMENT, `code` VARCHAR(8), `name` VARCHAR(32), PRIMARY KEY(`id`))');
	
	$sources = $db->query('SELECT DISTINCT `source` FROM `spells`');
	
	$sources = $db->compile_results($sources);
	
	foreach($sources as $row) {
		$db->insert('sources', array('name'), array($row->source));
	}

}

function populate_levels($dbcon) {
	
	$result = mysql_query('SELECT `id`, `code` FROM `classes`', $dbcon);
	
	while($class = mysql_fetch_object($result)) {
		$code = $class->code;
		
		$level_r = mysql_query('SELECT `id`, `'.$code.'` FROM `raw_data` where `'.$code.'` IS NOT NULL');
		
		while($spell = mysql_fetch_object($level_r)) {
		
			$sql = 'INSERT INTO `levels` (`spell_id`, `class_id`, `level`) VALUES ';
			$sql .= '('.$spell->id.', '.$class->id.', '.$spell->$code.')';
		
			mysql_query($sql);
			echo mysql_error();
		}
		
	}
	mysql_free_result($result);
}


function create_levels_table($dbcon) {
	$sql = 'CREATE TABLE `levels` ( ';
	$sql .= '`spell_id` INT NOT NULL, `class_id` INT NOT NULL, `level` INT NOT NULL';
	$sql .= ')';
	
	mysql_query($sql, $dbcon);
	
	echo mysql_error();
}

function create_classes($dbcon) {
	
	$sql = 'CREATE TABLE `classes` (';
	$sql .= '`id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY(`id`), ';
	$sql .= '`code` VARCHAR(32), `name` VARCHAR(32)';
	$sql .= ')';
	
//	mysql_query($sql, $dbcon);
	
	$classes = array(
		'sor' => 'Sorcerer', 
		'wiz' => 'Wizard',
		'cleric' => 'Cleric', 
		'druid' => 'Druid', 
		'ranger' => 'Ranger', 
		'bard' => 'Bard', 
		'paladin' => 'Paladin', 
		'alchemist' => 'Alchemist', 
		'summoner' => 'Summoner', 
		'witch' => 'Witch', 
		'inquisitor' => 'Inquisitor', 
		'oracle' => 'Oracle', 
		'antipaladin' => 'Antipaladin', 
		'magus' => 'Magus'
	);
	
	foreach($classes as $code => $name) {
		$sql = 'INSERT INTO `classes` (`code`, `name`) VALUES ';
		$sql .= "('".$code."', '".$name."')";
		echo $sql;
		mysql_query($sql, $dbcon);
	}
	
}

?>