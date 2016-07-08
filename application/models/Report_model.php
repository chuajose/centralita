<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report_model extends General_Model {
	function __construct(){
       // Call the Model constructor
       parent::__construct();

       $this->table = "reports";
    }


	public function get($id)
	{
		$this->db->select('id,commercial_id,to, from, users_id');
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		if($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}
	function get_all($offset = 0, $limit = 10, $search = FALSE) {
    $this->db->order_by('id', 'DESC');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        if($search && is_array($search)){
          if(isset($search['commercial_id'])) $this->db->where('commercial_id', $search['commercial_id']);
        }
        $query = $this->db->get($this->table);
        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
        
    }

    function get_total($search = FALSE) {
        if($search && is_array($search)){
          if(isset($search['commercial_id'])) $this->db->where('commercial_id', $search['commercial_id']);
        }
        return  $this->db->count_all_results($this->table);

        return FALSE;
        
    }

}
