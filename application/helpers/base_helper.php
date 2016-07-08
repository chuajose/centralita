<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Base_helper
{
    var $ci;
    var $model;
    var $error;
    var $class_data = array();

    function __construct()
    {
        $this->ci = &get_instance();
        if ($this->model) {
            $this->ci->load->model($this->model);
        }
        $this->ci->config->load('status', TRUE);

    }
    public function load($id)
    {
        $data = $this->ci->{$this->model}->get($id);
        if ($data) {
            $this->class_data = array();
            foreach ($data as $key => $value) {
                if (is_numeric($value)) {
                    $this->{$key} = floatval($value);

                } else {
                    $this->{$key} = $value;
                }
                $this->class_data[$key] = $this->{$key};
            }
            return $data;
        }
        return NULL;
    }
    
    public function save($data)
    {
        return $this->ci->{$this->model}->save($data);
    }
    public function get_all($page = 0, $limit = FALSE)
    {
        if (!$limit) {
            $limit = NULL;
        }
        if ($limit && $page) {
            $offset = ($page - 1) * $limit;
        } else {
            $offset = 0;
        }
        $list = $this->ci->{$this->model}->get_all($offset, $limit);
        $total = $this->ci->{$this->model}->total();
        return array('list' => $list, 'total' => $total, 'limit'=> $limit, 'page' => $page);
    }
    public function delete()
    {
        return $this->ci->{$this->model}->delete($this->{strtolower(get_called_class()).'_id'});
    }
}
