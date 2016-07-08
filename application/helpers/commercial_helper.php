<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Commercial extends Base_helper
{
    var $id;

    function __construct()
    {
        $this->model = 'Commercial_model';
        return parent::__construct();
    }


    public function get_all($page = 0, $limit = FALSE, $search = FALSE)
    {
        if ( ! get_cache('commercials'))
        {

            if (!$limit) {
                $limit = NULL;
            }
            $list = $this->ci->{$this->model}->get_all($page, $limit, $search);

            if ($list) {
                foreach ($list as $key => $value) {
                    $this->load($value->id);
                    $list[$key] = $this->_parse();
                }
            }

            create_cache('commercials', $list);

        } else {

            $list = get_cache('commercials');
        }

        $total = $this->ci->{$this->model}->get_total($search);

        return array('list' => $list, 'total' => $total, 'limit'=> $limit, 'page' => $page);
    }


    public function get_extension($id = FALSE)
    {

        $this->ci->db->where('extensions_id', $id);
        $commercial = $this->ci->{$this->model}->get();
        
        return $commercial;
    }


    function get($id = FALSE){

        if($id){
            $this->load($id);
            return $this->_parse();
        }

    }
    public function _parse()
    {
        $this->ci->load->helper('extension_helper');

        $extensions = new Extension();

        $extension = $extensions->load($this->extensions_id);

        if($extension){
            $extension_data = array('id'=>$this->extensions_id, 'number'=> $extension->number, 'status' => $extension->status );
        }else{
            $extension_data = array('id'=>0, 'number'=> 0, 'status' => 0 );

        }

        $status = $this->ci->config->item('commercial','status');
        if($status && isset($status[$this->status])){
            $name_status = $status[$this->status];
        }else{
            $name_status = "not defined";
        }

        $data = array(
            'id'            => intval($this->id),
            'name'          => $this->name,
            'extensions' => $extension_data,
            'status'        => array('name'=>$name_status, 'id'=>$this->status),
            );

        return $data;
    }
}
