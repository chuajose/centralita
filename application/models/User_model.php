<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends General_model {

	function __construct(){
       // Call the Model constructor
       parent::__construct();

       $this->table = "users";
    }


	public function get($id)
	{
		$this->db->select('id, email, first_name, last_name, active');
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		if($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}
	function get_all($offset = 0, $limit = 10) {
        $this->db->select('id');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        $query = $this->db->get($this->table);
        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }
    
}
