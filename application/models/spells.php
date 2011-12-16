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
		
//		echo $this->db->last_query();
		
		$levels = array();
		foreach ($query->result() as $result) {
			$levels[] = $result->level;			
		}
		
		return $levels;
	}
	
	public function get_spells($class_id, $level, $sources, $columns) {
		if (empty($columns)) {
			$columns = $this->get_default_columns();
		}
		
		$source_key = array_search('spells.source', $columns);
		if ($source_key !== false) {
			$columns[$source_key] = 'sources.name AS source_name';
			$this->db->join('sources', 'spells.source = sources.code');
		}
				
		$this->db->select(implode(',', $columns));
		
		$this->db->from('spells');
		
		if ($class_id != 'all') {
			$this->db->where('levels.class_id', $class_id);
		}
		
		if ($level != 'all') {
			$this->db->where('levels.level', $level);	
		}
		
		if ($level != 'all' || $class_id != 'all') {
			$this->db->join('levels', 'spells.id = levels.spell_id', 'left');
			$this->db->order_by('levels.level', 'ASC');	
		}
		
		if ($sources != 'all' && !empty($sources)) {
			$where = implode("' OR spells.source = '", $sources);
			$this->db->where("(spells.source = '".$where."')");
		}
		
		$this->db->order_by('spells.name', 'ASC');
		
		$query = $this->db->get();
		
//echo $this->db->last_query();die;
						
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
		$this->db->order_by('order', 'ASC');

		$query = $this->db->get();

		$data = array();
		foreach($query->result_array() as $row) {
			$data[$row['code']] = $row['name'];
		}
		return $data;
	}
	
	public function get_column_headings($columns) {
		$names = $this->get_column_names();
		
		$headings = array();
		foreach($columns as $column) {
			if (array_key_exists($column, $names)) {
				$headings[] = $names[$column];
			}
		}

		return $headings;
	}
	
	public function get_default_columns() {
		return $this->get_defaults('columns');
	}
	
	public function get_default_sources() {
		return $this->get_defaults('sources');
	}
	
	private function get_defaults($table) {
		$this->db->select('code');
		$this->db->from($table);
		$this->db->where('default', 1);
		
		$query = $this->db->get();
		
		$data = array();
		foreach($query->result_array() as $row) {
			$data[] = $row['code'];
		}
		return $data;
	}
	
	public function validate_columns($columns) {
		return $this->validate('columns', $columns);
	}
	
	public function validate_sources($sources) {
		return $this->validate('sources', $sources);
	}
	
	private function validate($table, $values) {
		if (! is_array($values)) {
			return array();
		}
		
		$allowed = array_keys($this->get_name_map($table));
		
		return array_intersect($allowed, $values);
	}
	
	public function get_boolean_columns() {
		$this->db->select('code');
		$this->db->from('columns');
		$this->db->where('boolean', 1);
		
		$query = $this->db->get();
		
		$data = array();
		foreach($query->result_array() as $row) {
			$data[] = $row['code'];
		}
		return $data;
	}
	
}

/* End of file spells.php */
/* Location: ./application/controllers/spells.php */