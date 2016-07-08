<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Report extends Base_helper
{
    var $id;

    function __construct()
    {
        $this->model = 'Report_model';
        return parent::__construct();
    }


    public function get_all($page = 0, $limit = FALSE, $search = FALSE)
    {
        if ( ! get_cache('reports'))
        {

            if (!$limit) {
                $limit = NULL;
            }
            $list = $this->ci->{$this->model}->get_all($page, $limit, $search);

            if ($list) {
                foreach ($list as $key => $value) {

                    $list[$key] = $this->_parse($value);
                }
            }

            create_cache('reports', $list);

        } else {

            $list = get_cache('reports');
        }

        $total = $this->ci->{$this->model}->get_total($search);

        return array('list' => $list, 'total' => $total, 'limit'=> $limit, 'page' => $page);
    }

    public function _parse($data)
    {
        $url = base_url().'reports/index/';
        if($data->commercial_id){

            $this->ci->load->helper('commercial_helper');
            $commercial = new Commercial();
            $commercial = $commercial->load($data->commercial_id);
            if($commercial){
            $commercial_data = array(
                'id'   => $commercial->id,
                'name' => $commercial->name
            );
            }else{
                $commercial_data = array(
                'id'   => 0,
                'name' => 'Desconocido'
                );
            }

        }else{
            $commercial_data = array(
                'id'   => 0,
                'name' => ''
                );
        }
            
        if($data->commercial_id){
            $url = $url.'?commercial_id='.$data->commercial_id;
        }

        if($data->to){
            if($data->commercial_id)$url = $url.'&to='.$data->to;
            else $url = $url.'?to='.$data->to;
        }


        if($data->from){
            if($data->commercial_id || $data->to)$url = $url.'&from='.$data->from;
            else $url = $url.'?from='.$data->from;
        }

        $data = array(
            'id'            => intval($data->id),
            'to'            => $data->to,
            'from'        => $data->from,
            'commercial'        => $commercial_data,
            'url' => $url
            );

        return $data;
    }
}
