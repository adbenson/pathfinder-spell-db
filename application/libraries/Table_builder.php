<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Table_builder {
	
	protected $headers = array();
	
	public function build() {
		$tbl = tag('table');
		$thtml = $tbl->open();
		
		if (!empty($this->headers)) {
			$thtml
		}
		
		
		$thtml .= $tbl->close();
	}
	
	public function tag($tag) {
		$element = new stdClass;
		$element->open = "<".$tag.">\n";
		$element->close = "</".$tag.">\n";
	}
	
	public function append($value) {
		$this->thtml .= $value;
	}

}

class Element {
	protected static $collection = array(); 
	protected static $table;
	
	protected $tag = '';
	protected $opened = false;
	protected $closed = false;
	
	public function open() {
		self::$table->append("<".$tag.">\n");
		$this->opened = true;
		$this->closed = false;
	}
	
	public function close() {
		
	}
}