<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Commercial_model extends General_Model {
	function __construct(){
       // Call the Model constructor
       parent::__construct();

       $this->table = "commercial";
    }


	public function get($id = false)
	{
		$this->db->select('id, name, status, extensions_id');
		if($id)$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		if($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}
	function get_all($offset = 0, $limit = 10, $search = FALSE) {
      $this->db->where('deleted',0);
      $this->db->order_by('id', 'DESC');

        $this->db->select('id');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        if($search){

          $this->db->like('name',$search);
        }
        
        $query = $this->db->get($this->table);
        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
    }

    function get_total($search = FALSE) {
        $this->db->where('deleted',0);

        if($search){

          $this->db->like('name',$search);
        }
        return  $this->db->count_all_results($this->table);
        
    }
}
