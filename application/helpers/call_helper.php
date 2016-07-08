<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Call extends Base_helper
{
    var $id;

    function __construct()
    {
        $this->model = 'Call_model';
        return parent::__construct();
    }


    public function get_all($page = 0, $limit = FALSE, $search = FALSE)
    {
        if ( ! get_cache('calls'))
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

            create_cache('calls', $list);

        } else {

            $list = get_cache('calls');
        }

        $total = $this->ci->{$this->model}->get_total($search);

        $extra_data = $this->get_all_data($search);


        return array('list' => $list, 'total' => $total, 'limit'=> $limit, 'page' => $page, 'duration' => $extra_data['duration'], 'more5' => $extra_data['more5']);
    }

    public function get_all_data($search = array()){
        $duration = 0;
        $more5 = 0;
        $list = $this->ci->{$this->model}->get_all(0, 999999999999, $search);
        if ($list) {
            foreach ($list as $key => $value) {
                $duration+=$value->duration;
                if($value->duration > 299) $more5++;

            }
        }

        return array('duration'=> $duration, 'more5' => $more5 );
    }

    public function _parse($data)
    {
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
        

        $data = array(
            'id'         => intval($data->id),
            'number'     => intval($data->number),
            'date'       => $data->date,
            'hour'       => $data->hour,
            'ext'        => $data->ext,
            'duration'   => $data->duration,
            'commercial' => $commercial_data,
            );

        return $data;
    }
}
