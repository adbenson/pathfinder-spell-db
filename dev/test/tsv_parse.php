<?php

error_reporting(E_ALL);

$list = parse_tsv('spell_full.tsv');

//print_r($list['headers']);

$dbcon = mysql_connect('localhost', 'root', '');
if ($dbcon === false) {
	die('DB not connected: '.mysql_error());
}

mysql_select_db('pfspells');

//create_table($dbcon, 'raw_data', $list['headers']);

$column_names = array();
foreach($list['headers'] as $header) {
	array_push($column_names, $header['column_name']);
}
$column_names = '(`'.implode('`, `', $column_names).'`)';

foreach($list['data'] as $line) {
	$sql = 'INSERT INTO `raw_data` '.$column_names.' VALUES';
	$sql .= '('.implode(', ', $line).');';
	
//	mysql_query($sql, $dbcon);
}


function create_table($dbcon, $name, $fields) {
	$sql = "DROP TABLE IF EXISTS `{$name}`;";
	mysql_query($sql, $dbcon);

	$sql = "CREATE TABLE `{$name}` (";
	
	foreach ($fields as &$field) {
		$fstring = '`'.$field['column_name'].'` ';
		$fstring .= $field['type'];
		if ($field['size'] > 0) {
			$fstring .= '('.$field['size'].')';
		}
		
		$field = $fstring;
	}
	
	$sql .= implode(', ', $fields);
	
	$sql .= ");";
	
	mysql_query($sql, $dbcon);
}

function parse_tsv($file) {

	$contents = file_get_contents($file);
	
	$contents = str_replace("\r", "", $contents);
	
	$lines = explode("\n", $contents);
	
	$headers = explode("\t", array_shift($lines));
	
	$contents = array(
		'headers' => array(),
		'data' => array()
	);
	
	foreach ($headers as $column_name) {
		$header = array(
			'column_name' => $column_name,
			'type' => 'TINYINT',
			'size' => '0'
		);
		
		array_push($contents['headers'], $header);
	}
	

	
	foreach($lines as $ln => $line) {
		$line = explode("\t", $line);
		
		foreach($line as $fi => &$field) {
			
			if ($field !== 'NULL') {
				$header =& $contents['headers'][$fi];
				
				if ($header['type'] == 'TINYINT' && is_numeric($field)) {
					$length = ($field <= 1)? 1 : strlen($field) * 2;
					
					if ($length > $header['size']) {
						$header['size'] = $length;
					}
				}
				else {
					$field = "'".mysql_escape_string($field)."'";
					
					if ($header['type'] == 'TINYINT') {
						$header['type'] = 'VARCHAR';
					}
					
					if ($header['type'] == 'VARCHAR') {
						$length = strlen($field) * 2;
						
						if ($length > 255) {
							$header['type'] = 'TEXT';
							$header['size'] = 0;
						}
						else if ($length > $header['size']) {
							$header['size'] = $length;
						}
					}
				}
			}
		}
		
		array_push($contents['data'], $line);
	}
	
	unset($lines);
	
	return $contents;

}

?>