<?php

/**
 * General function to database
 *
 * @final Centralita
 * @category models 
 * @author Jose Manuel Suárez Bravo
 * @link http://www.centralita.com
 */
class General_model extends CI_Model {
     

     
    public function __construct() {
        parent::__construct();

    }

   	function  delete($id, $where){

   		$this->db->where($where);
		return $this->db->delete($this->table);
		
   	}

   	function  insert($id, $data){

		return $this->db->insert($this->table, $data);
		
   	}

   	function total() {
        return $this->db->count_all_results($this->table);
    }


   	function save($data){
    	if(isset($data['id'])){
        $id = $data['id'];
        unset($data['id']);
      
	    	$this->db->where('id',$id);
	    	$this->db->update($this->table,$data);
        /*echo $this->db->last_query();
        echo "total :". $this->db->affected_rows();
        echo $id;*/
	    	if($this->db->affected_rows() == 1) return $id;
	    	else return FALSE;

	    }else{
	    	$this->db->insert($this->table,$data);
	    	return $this->db->insert_id();
	    }
    }

    
}