<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spell_db extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper('form');
	}

	public function index() {
		
		$data = array();
		
		$data['classes'] = $this->spells->get_class_map();
		array_unshift($data['classes'], 'All Classes');
		$data['levels'] = array('All Levels');

		$this->load->view('header');
		$this->load->view('spell_list', $data);				
		$this->load->view('footer');
	}
	
	public function get_levels() {
		$class_id = $this->input->post('class', true);
		
		$levels = $this->spells->get_levels($class_id);
		array_unshift($levels, 'All Levels');
		
		return json_encode($levels);		
	}

}

/* End of file spelldb.php */
/* Location: ./application/controllers/spelldb.php */