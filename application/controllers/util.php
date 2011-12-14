<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Util extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('spell_db');
	}

	public function index() {
		$spells = $this->spells->get_spells('all', 'all', 'all', array('*'));
		
		$this->db->select('id, code');
		$this->db->from('classes');
		$query = $this->db->get();
				
		foreach($query->result_array() as $class) {
			
			$this->db->select('id, '.$class['code']);
			$this->db->from('spells');
			$this->db->where($class['code'].' IS NOT NULL');
			$query = $this->db->get();
						
			foreach($query->result_array() as $row) {
				echo ('.');
				$this->db->insert('levels', array(
					'spell_id' => $row['id'],
					'class_id' => $class['id'],
					'level' => $row[$class['code']]
				));
			}
		}
		
		echo "done";
		
	}	
	
}