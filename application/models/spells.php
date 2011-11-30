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
		$query = $this->db->get();
		
		$result = $query->result();
		
		$classes = array();
		foreach($result as $class) {
			$classes[$class->id] = $class->name;			
		}
		
		return $classes;
	}
	
	public function get_levels($class_id) {
		return array(1,2,3,4,5);
	}
	
}

/* End of file spells.php */
/* Location: ./application/controllers/spells.php */