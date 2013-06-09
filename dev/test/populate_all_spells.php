<html>

<head>
</head>

<body>
</body>

</html>

<?php

error_reporting(E_ALL);

$db = mysql_connect("localhost","root","");
if (!$db) {
	die('Could not connect: ' . mysql_error());
}

mysql_select_db("pfspells", $db);

all_data_table($db);

$spells = parseCSV('spell_full.csv');

$headers = array_shift($spells);

foreach ($spells as $spell) {
	$sql = insert('all_data', $headers, $spell);
	$inserted = mysql_query($sql, $db);
	if (! $inserted) {
		echo "---\nInsert failed: ".$sql."\n".mysql_error($db)."\n";
	}
}

mysql_close($db);

function all_data_table($db) {

	$dropped = mysql_query("DROP TABLE IF EXISTS all_data;", $db);
	if (! $dropped) {
		echo "Table not dropped: ".mysql_error($db)."\n";
		return;
	}

	$primaryKey = "id INT(8) NOT NULL AUTO_INCREMENT,\nPRIMARY KEY(id),";

$fields = "
name VARCHAR(255),
school VARCHAR(255),
subschool VARCHAR(255),
descriptor VARCHAR(255),
spell_level VARCHAR(255),
casting_time VARCHAR(255),
components VARCHAR(255),
costly_components TINYINT(1),
range_ VARCHAR(255),
area VARCHAR(255),
effect VARCHAR(255),
targets VARCHAR(255),
duration VARCHAR(255),
dismissible TINYINT(1),
shapeable TINYINT(1),
saving_throw VARCHAR(255),
spell_resistence VARCHAR(255),
description TEXT,
description_formated TEXT,
source VARCHAR(255),
full_text TEXT,
verbal TINYINT(1),
somatic TINYINT(1),
material TINYINT(1),
focus TINYINT(1),
divine_focus TINYINT(1),
sor INT(4),
wiz INT(4),
cleric INT(4),
druid INT(4),
ranger INT(4),
bard INT(4),
paladin INT(4),
alchemist INT(4),
summoner INT(4),
witch INT(4),
inquisitor INT(4),
oracle INT(4),
antipaladin INT(4),
magus INT(4),
deity VARCHAR(255),
SLA_Level INT(4),
domain VARCHAR(255),
short_description TEXT";
	
	$sql = "CREATE TABLE all_data (\n".$primaryKey.$fields.")";
	
	$created = mysql_query($sql, $db);
	if (! $created) {
		echo "Table not created: ".mysql_error($db)."\n";
	}
}

function parseCSV($file) {

	$spellFile = fopen($file, 'r');
//	$headers = explode(',', fgets($spellFile));

	$spells = array();

//	foreach($headers as $header) {
//		$fields[$header] = array();
//	}

	while($spell = fgets($spellFile)) {
		
		$inQuotes = false;
		$chars = str_split(trim($spell));
		
		foreach($chars as &$char) {
			if ($char == '"' && $state = 'new') {
				$inQuotes = ! $inQuotes;
			}
			else if (! $inQuotes && $char == ',') {
				$char = '|';
			}
		}
		
		$spell = implode($chars);
		
		$spell = preg_replace('/""/', '"', $spell);
		
		$spell = preg_replace('/"?\|"?/', '|', $spell);
		
		$spell = preg_replace('/(^"|"$)/', '', $spell);
		
		$spell = explode('|', mysql_real_escape_string($spell));
/*
		$spellValues = array();
		for($i=0; $i<count($spell); $i++) {
			array_push($fields[$headers[$i]], $spell[$i]);
			$spellValues[$headers[$i]] = $spell[$i];
		}
*/
		array_push($spells, $spell);
	}
/*
	$classCodes = array(
		'sor', 'wiz', 'cleric', 'druid', 'ranger', 'bard', 'paladin', 'alchemist', 'summoner', 'witch', 'inquisitor', 'oracle',  'antipaladin', 'magus' => 'Magus'
	);
	$classNames = array(
		'Sorcerer', 'Wizard', 'Cleric', 'Druid', 'Ranger', 'Bard', 'Paladin', 'Alchemist', 'Summoner', 'Witch', 'Inquisitor', 'Oracle',  'Antipaladin', 'magus' => 'Magus'
	);

	$schools = array_unique($fields['school']);

	$sources = array_unique($fields['source']);
*/
	return $spells;
}

/*
$levels = array();
foreach($spells as $sid => $spell) {

	foreach($classCodes as $cid => $code) {
		
		$level = $spell[$code];
		
		if (is_numeric($level)) {
			array_push($levels, array(
				'spell' => $sid,
				'class' => $cid,
				'level' => $level
			));
		}
	}
}
*/
function insert($tbl, $columns, $values) {
	foreach($values as &$value) {
		if (! is_numeric($value) && $value != 'NULL') {
			$value = "'$value'";
		}
	}
	return "INSERT INTO ".$tbl." (".implode(", ", $columns).")\n".
		"VALUES (".implode(", ", $values).");";
}


?>