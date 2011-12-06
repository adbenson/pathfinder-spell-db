<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Spells extends CI_Model {
	
	protected $tables = array(
		'class' => 'classes',
		'level' => 'levels',
		'source' => 'sources'
	);
	
    function __construct()  {
        // Call the Model constructor
        parent::__construct();
        
        $this->load->database();
    }

	function example() {
		$query = $this->db->query('SELECT name, title, email FROM my_table');

		foreach ($query->result() as $row)
		{
		    echo $row->title;
		    echo $row->name;
		    echo $row->email;
		}
		
		echo 'Total Results: ' . $query->num_rows();
	}
	
	public function get_class_map() {
		$this->db->select('id, name');
		$this->db->from($this->tables['class']);
		$this->db->order_by('name', 'ASC');
		$query = $this->db->get();
		
		$result = $query->result();
		
		$classes = array();
		foreach($result as $class) {
			$classes[$class->id] = $class->name;
		}
	
		return $classes;
	}
	
	public function get_levels($class_id) {
		$this->db->select('level');
		$this->db->distinct();
		$this->db->from('levels');
		if ($class_id != 'all') {
			$this->db->where('class_id', $class_id);	
		}
		$this->db->order_by('level', 'ASC');
		$query = $this->db->get();
		
		$levels = array();
		foreach ($query->result() as $result) {
			$levels[] = $result->level;			
		}
		
		return $levels;
	}
	
	public function get_spells($class_id, $level, $sources, $columns) {
		
		$this->db->select('levels.level, '.implode(',', $columns));
		$this->db->from('spells');
		
		$this->db->join('levels', 'spells.id = levels.spell_id');
		
		if ($class_id != 'all') {
			$this->db->where('levels.class_id', $class_id);	
		}
		if ($level != 'all') {
			$this->db->where('levels.level', $level);			
		}
		
		if ($sources != 'all') {
			//
		}
		$this->db->order_by('levels.level, spells.name', 'ASC');
		$query = $this->db->get();
				
		return $query->result_array();
		
	}
	
	public function is_class($class_id) {
		$this->db->select('name');
		$this->db->from('classes');
		$this->db->where('ids', $class_id);
		
		$query = $this->db->get();
		return ($query->num_rows() > 0);
	}
	
	public function get_column_names() {
		return $this->get_name_map('columns');
	}
	
	public function get_source_names() {
		return $this->get_name_map('sources');
	}
	
	private function get_name_map($table) {
		$this->db->select('code, name');
		$this->db->from($table);

		$query = $this->db->get();

		$data = array();
		foreach($query->result_array() as $row) {
			$data[$row['code']] = $row['name'];
		}
		return $data;
	}
	
}

/* End of file spells.php */
/* Location: ./application/controllers/spells.php */