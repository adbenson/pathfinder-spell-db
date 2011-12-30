<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spell_db extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('form');
		$this->load->helper('spell_db');
		$this->load->library('session');
		
		if ($this->session->is_new()) {
			$this->_init_user_data();
		}
	}

	public function index() {		
		$data = array();
		
		$data['classes'] = array('all' => 'All Classes') + $this->spells->get_class_map();
		$data['levels'] = array('all' => 'All Levels') + $this->spells->get_levels('all');
		
		$data['class_id'] = int_or_all($this->session->userdata('class'));
		$data['level'] = int_or_all($this->session->userdata('level'));

		$data['sources'] = $this->_map_to_name(
			$this->session->userdata('sources'), 
			$this->spells->get_source_names()
		);
				
		$data['columns'] = $this->_map_to_name(
			$this->session->userdata('columns'),
			$this->spells->get_column_names()
		);

		$this->load->view('header');
		$this->load->view('spells', $data);				
		$this->load->view('footer');
	}
	
	private function _map_to_name($enabled, $names) {
		if (! is_array($enabled)) {
			$enabled = array();
		}
		
		foreach($names as $code => $name) {
			$names[$code] = array(
				'enabled' => in_array($code, $enabled)? 1 : 0,
				'name' => $name
			);
		}
		
		return $names;	
	}
	
	public function get_levels() {
		$class_id = int_or_all($this->input->post('class', true));
		
		$this->session->set_userdata(array('class' => $class_id));
		$this->session->set_userdata(array('level' => 'all'));
		
		$levels = $this->spells->get_levels($class_id);
		array_unshift($levels, 'All Levels');
		
		echo json_encode($levels);		
	}
		
	public function get_spells() {
		$level = int_or_all($this->session->userdata('level'));		
		$class_id = int_or_all($this->session->userdata('class'));
		
		$sources = $this->spells->validate_sources($this->session->userdata('sources'));

		$columns = $this->spells->validate_columns($this->session->userdata('columns'));

		$headings = $this->spells->get_column_headings($columns);
		
		foreach($columns as &$column) {
			$column = 'spells.'.$column;
		}
			
		if ($class_id !== 'all') {
			array_unshift($columns, 'levels.level');
			array_unshift($headings, 'Level');
		}
		
		$spells = $this->spells->get_spells($class_id, $level, $sources, $columns);
		
		$data = array(
			'spells' => $spells,
			'headings' => $headings,
			'booleans' => array_keys($this->spells->get_boolean_columns())
		);
		
		$this->load->view('spell_list', $data);	
	}
	
	public function set_level() {
		$level = int_or_all($this->input->post('level', true));
		$this->session->set_userdata(array('level' => $level));
		
		$this->get_spells();
	}
	
	public function set_columns() {
		$columns = $this->spells->validate_columns($this->input->post(null, true));

		if (empty($columns)) {
			$columns = $this->spells->get_default_columns();
		}
		
		$this->session->set_userdata('columns', $columns);
		
		$this->get_spells();
	}
	
	public function set_sources() {
		$sources = $this->spells->validate_sources($this->input->post(null, true));
		
		if (empty($sources)) {
			$sources = $this->spells->get_default_sources();
		}
		
		$this->session->set_userdata('sources', $sources);
		
		$this->get_spells();
	}
	
	public function get_spell() {
		$spell_id = int_or_all($this->input->post('spell_id', true));
		
		$spell_data = $this->spells->get_spell($spell_id);
		
		$this->load->view('spell_desc', $spell_data);
	}
	
	private function _init_user_data() {
		$columns = $this->spells->get_default_columns();
		$sources = $this->spells->get_default_sources();
		
		$this->session->set_userdata(array(
			'class' => 'all',
			'level' => 'all',
			'columns' => $columns,
			'sources' => $sources
		));
	}

}

/* End of file spelldb.php */
/* Location: ./application/controllers/spelldb.php */