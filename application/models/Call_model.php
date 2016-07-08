<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Call_model extends General_Model {
	function __construct(){
       // Call the Model constructor
       parent::__construct();

       $this->table = "calls";
    }


	public function get($id)
	{
		$this->db->select('id,date,hour,ext,number,duration,commercial_id');
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		if($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}
	function get_all($offset = 0, $limit = 10, $search = array()) {
        $this->db->order_by('id', 'DESC');
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        if(!empty($search)){

            if(isset($search['commercial_id'])) $this->db->where('commercial_id', $search['commercial_id']);
            if(isset($search['date']) && is_array($search['date'])){
                if(isset($search['date'][0]))$this->db->where('date >=', $search['date'][0]);
                if(isset($search['date'][1]))$this->db->where('date <=', $search['date'][1]);
            }
             
        }
        $query = $this->db->get($this->table);
        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
        
    }

    function get_total($search = array()) {

        if(!empty($search)){

            if(isset($search['commercial_id'])) $this->db->where('commercial_id', $search['commercial_id']);
            if(isset($search['date']) && is_array($search['date'])){
                if(isset($search['date'][0]))$this->db->where('date >=', $search['date'][0]);
                if(isset($search['date'][1]))$this->db->where('date <=', $search['date'][1]);
            }
             
        }
        return $this->db->count_all_results($this->table);
        
        
    }

}
