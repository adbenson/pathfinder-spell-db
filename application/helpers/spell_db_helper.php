<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function int_or_all($value) {
	if (is_numeric($value) && $value >= 0) {
		return $value;
	}
	else {
		return 'all';
	}
}