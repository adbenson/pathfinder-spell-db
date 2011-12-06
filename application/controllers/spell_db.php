<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spell_db extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->library('session');
		
		if ($this->session->is_new()) {
			$this->_init_user_data();
		}
	}

	public function index() {		
		$data = array();
		
		$data['classes'] = array('all' => 'All Classes') + $this->spells->get_class_map();
		$data['levels'] = array('all' => 'All Levels') + $this->spells->get_levels('all');
		
		$data['class_id'] = $this->session->userdata('class');
		$data['level'] = $this->session->userdata('level');

		$data['sources'] = $this->_map_to_name(
			$this->session->userdata('sources'), 
			$this->spells->get_source_names()
		);
				
		$data['columns'] = $this->_map_to_name(
			$this->session->userdata('columns'),
			$this->spells->get_column_names()
		);

		$this->load->view('header');
		$this->load->view('spell_list', $data);				
		$this->load->view('footer');
	}
	
	private function _map_to_name($enabled, $names) {		
		$map = array();
		
		foreach($enabled as $field => $enabled) {
			$map[$field] = array(
				'enabled' => $enabled,
				'name' => $names[$field]
			);
		}
		
		return $map;	
	}
	
	public function get_levels() {
		$class_id = $this->input->post('class', true);
		if (! is_numeric($class_id)) {
			$class_id = 'all';
		}
		$this->session->set_userdata(array('class' => $class_id));
		
		$this->session->set_userdata(array('level' => 'all'));
		
		$levels = $this->spells->get_levels($class_id);
		array_unshift($levels, 'All Levels');
		
		echo json_encode($levels);		
	}
	
	public function set_level() {
		$level = $this->input->post('level', true);
		if (! is_numeric($level)) {
			$level = 'all';
		}
		$this->session->set_userdata(array('level' => $level));
	}
	
	public function get_spells() {
		$level = $this->session->userdata('level');		
		$class_id = $this->session->userdata('class');

		$default_columns = $this->_column_names();
		$selected_columns = $this->session->userdata('columns');
		
		$columns = array();
		foreach($default_columns as $column) {
			if (array_key_exists($column, $selected_columns) && $selected_columns[$column] == 1) {
				$columns[] = $column;
			}
		}		
		
		$spells = $this->spells->get_spells($class_id, $level, 'all', $columns);
		
		echo $this->_build_table($spells);	
	}
	
	public function set_columns() {
		$chosen_columns = $this->input->post(null, true);
		
		$columns = array();
		foreach($this->_column_names() as $column) {
			$columns[$column] = (array_key_exists($column, $chosen_columns))? 1 : 0;
		}
		
		$this->session->set_userdata('columns', $columns);
	}
	
	public function set_sources() {
		$chosen_sources = $this->input->post(null, true);
		
		$sources = array();
		foreach($this->_source_names() as $source) {
			$sources[$source] = (array_key_exists($source, $chosen_sources))? 1 : 0;
		}
		
		$this->session->set_userdata('sources', $sources);
	}
	
	private function _init_user_data() {
		$columns = $this->config->item('default_spell_columns');
		$sources = $this->config->item('default_spell_sources');
		
		$this->session->set_userdata(array(
			'class' => 'all',
			'level' => 'all',
			'columns' => $columns,
			'sources' => $sources
		));
	}
	
	private function _build_table($spells) {
		
		$this->load->library('table');
		$this->table->set_heading(array_keys($spells[0]));
		$table = $this->table->generate($spells);
		
		return $table; 
	}
	
	private function _column_names() {
		return array_keys($this->config->item('default_spell_columns'));
	}
	
	private function _source_names() {
		return array_keys($this->config->item('default_sources'));
	}

}

/* End of file spelldb.php */
/* Location: ./application/controllers/spelldb.php */