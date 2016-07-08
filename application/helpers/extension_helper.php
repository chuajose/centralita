<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Extension extends Base_helper
{
    var $id;

    function __construct()
    {
        $this->model = 'Extension_model';
        return parent::__construct();
    }


    public function get_all($page = 0, $limit = FALSE, $type = FALSE, $status = FALSE, $search = FALSE)
    {
        if ( ! get_cache('extensions'))
        {

            if (!$limit) {
                $limit = NULL;
            }
            $list = $this->ci->{$this->model}->get_all($page, $limit, $type, $status, $search);

            if ($list) {
                foreach ($list as $key => $value) {

                    $list[$key] = $this->_parse($value);
                }
            }

            create_cache('extensions', $list);

        } else {

            $list = get_cache('extensions');
        }

        $total = $this->ci->{$this->model}->get_total($type, $status, $search);

        return array('list' => $list, 'total' => $total, 'limit'=> $limit, 'page' => $page);
    }

    function get($id){

        
        return $this->_parse($this->ci->{$this->model}->get($id));
    }

    function save($data = array()){

        if(isset($data['unasigned']) && $data['unasigned'] == 1){

            $this->ci->load->helper('commercial_helper');

            $commercial = new commercial();
            $com = $commercial->get_extension($data['id']);

            $commercial->load($com->id);
            $commercial->save(array('id'=>$com->id, 'extensions_id'=>1));
            unset($data['unasigned']);

        }

        if(!empty($data) && (isset($data['id']) && count($data) > 1) || !isset($data['id'])) return parent::save($data);
        else return true;
    }

    public function _parse($data)
    {
        if(!$data) return FALSE;
        $status = $this->ci->config->item('extension','status');

        if($status){
            $name_status = $status[$data->status];
        }else{
            $name_status = "not defined";
        }

        $data = array(
            'id'            => intval($data->id),
            'number'            => intval($data->number),
            'status'        => array('name'=>$name_status, 'id'=>$data->status),
            'commercial'        => array('name'=>$data->name, 'id'=>$data->commercial_id),
            'assigned' => (!is_null($data->commercial_id))?true:false,
            );

        return $data;
    }
}
