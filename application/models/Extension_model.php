<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extension_model extends General_Model {
	function __construct(){
       // Call the Model constructor
       parent::__construct();

       $this->table = "extensions";
    }


	public function get($id)
	{
		$this->db->where('extensions.id', $id);
        $this->db->join('commercial', 'extensions_id = '.$this->table.'.id', 'left');
        $this->db->select('commercial.id as commercial_id, commercial.status as commercial_status, number, extensions_id, commercial.name ,extensions.status, extensions.id');
        
		$query = $this->db->get($this->table);
		if($query->num_rows() > 0) {
			return $query->row();
		}
		return FALSE;
	}
	function get_all($offset = 0, $limit = 10, $type = FALSE, $status = FALSE, $search = FALSE ) {
        $this->db->group_by('extensions.number');

        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        if($type !== FALSE){
            if($type == 0) $this->db->where('commercial.id', NULL);
            elseif($type == 1) $this->db->where('commercial.id !=', NULL);
        }

        if($status !== FALSE) $this->db->where('extensions.status', $status);
        if($search !== FALSE) $this->db->where('extensions.number', $search);
        
        $this->db->join('commercial', 'extensions_id = '.$this->table.'.id', 'left');
        $this->db->select('commercial.id as commercial_id, commercial.status as commercial_status, number, extensions_id, commercial.name ,extensions.status, extensions.id');
        $query = $this->db->get($this->table);
        if ($query && $query->num_rows() > 0) {
            return $query->result();
        }
        return FALSE;
        
    }
    function get_total($type = FALSE, $status = FALSE, $search = FALSE) {
        if($type !== FALSE){
            if($type == 0) $this->db->where('commercial.id', NULL);
            elseif($type == 1) $this->db->where('commercial.id !=', NULL);
        }
        if($status !== FALSE) $this->db->where('extensions.status', $status);
        if($search !== FALSE) $this->db->where('extensions.number', $search);

        $this->db->join('commercial', 'extensions_id = '.$this->table.'.id', 'left');
        return  $this->db->count_all_results($this->table);
        
    }

}
