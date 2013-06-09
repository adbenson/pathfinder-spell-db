<?php

$db = new mysqli('localhost', 'root', '', 'pfspells');

if ($db->connect_error) {
    die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
}

$class_name = $db->real_escape_string($_GET["class"]);
$level = $db->real_escape_string($_GET["level"]);

$result = $db->query("SELECT * FROM `classes` WHERE `name` = '{$class_name}'");

$class_id = $result->fetch_object()->id;

$result = $db->query(
	"SELECT `name`, `description_formated` 
	FROM	`spells` JOIN `levels` 
	ON		`spells`.`id` = `levels`.`spell_id` 
	WHERE	`levels`.`class_id` = '{$class_id}' AND `levels`.`level` = {$level}"
);

echo "<table>";

while($spell = $result->fetch_object()) {

	echo "<tr>";
	
	echo "<td>{$spell->name}</td>";
	echo "<td>{$spell->description_formated}</td>";
	
	echo "</tr>";

}

echo "</table>";


?>
