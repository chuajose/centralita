<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class User extends Base_helper
{
    var $id;

    function __construct()
    {
        $this->model = 'User_model';
        return parent::__construct();
    }


    public function get_all($page = 0, $limit = FALSE)
    {
        if ( ! get_cache('users'))
        {

            if (!$limit) {
                $limit = NULL;
            }
            $list = $this->ci->{$this->model}->get_all($page, $limit);
            
            if ($list) {
                foreach ($list as $key => $value) {
                    $this->load($value->id);
                    $list[$key] = $this->class_data;
                }
            }

            create_cache('users', $list);

        } else {

            $list = get_cache('users');
        }

        $total = $this->ci->{$this->model}->total();
        return array('list' => $list, 'total' => $total, 'limit'=> $limit, 'page' => $page);
    }
    public function _parse($data)
    {
        $this->id = intval($data->id);
        $this->first_name = $data->first_name;
        $this->last_name = $data->last_name;
        $this->email = $data->email;
        $data = array(
            'id' => intval($data->id),
            'first_name' => $data->first_name,
            'last_name' => $data->last_name,
            'email' => $data->email,
        );
        return $data;
    }
}
