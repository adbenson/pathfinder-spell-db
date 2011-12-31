<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function int_or_all($value) {
	if (is_numeric($value) && $value >= 0) {
		return $value;
	}
	else {
		return 'all';
	}
}

function collapse() {
	$div = "<span class='collapse'>";
	$div .= "<img src='assets/images/triangle_knot.png'>";
	$div .= "</span>";
	
	return $div;
}