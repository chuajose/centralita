<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {

	function __construct()
    {
        parent::__construct();

    }

    private function _draw($code, $message)
    {
        $CI                 = &get_instance();
        $this->CI           = $CI;
        //var_dump($this->CI);
        $this->CI->load->config('rest');
        $this->CI->load->config('rest_errors');

        $_supported_formats = array(
            'xml'               => 'application/xml',
            'json'              => 'application/json',
            'jsonp'             => 'application/javascript',
            'serialized'        => 'application/vnd.php.serialized',
            'php'               => 'text/plain',
            'html'              => 'text/html',
            'csv'               => 'application/csv'
        );
        $headers = $this->CI->input->request_headers();

        if(is_array($message)) $message_response = json_encode($message);else $message_response = $message;

        if($this->CI->userId)$this->CI->db->insert('logs_response',array('class' => $this->CI->router->class, 'method' => $this->CI->router->method, 'user_id'=> $this->CI->userId, 'response'=> $message_response,'code' =>$code, 'request' => $this->CI->id_request));


        if ( isset($headers['Content-Type']) &&  $headers['Content-Type']==='json')
        {
            header('Content-Type: '.$_supported_formats[$this->CI->config->item('rest_default_format')] . '; charset=' . strtolower($this->CI->config->item('charset')));
            //set_status_header($data['status']);
            echo json_encode( array('response'=>"", 'code'=>array(  config_item('rest_status_field_name') => $code,  config_item('rest_message_field_name') => $message)));
            //exit();
        }
        else
        {
            header('Content-Type: '.$_supported_formats[$this->CI->config->item('rest_default_format')] . '; charset=' . strtolower($this->CI->config->item('charset')));
            //set_status_header($data['status']);
            echo json_encode( array('response'=>"", 'code'=>array(  config_item('rest_status_field_name') => $code,  config_item('rest_message_field_name') => $message)));
            //die($exception->getMessage());
        }
        exit();
    }
	function notFoundRecord($exception)
	{
		
        $CI                 = &get_instance();
        $this->CI           = $CI;
        //var_dump($this->CI);
        $this->CI->load->config('rest');
        $this->CI->load->config('rest_errors');
        
        $msg_error = config_item('rest_error_code');
        $code = $exception->getCode();


        $message = $msg_error[$code];

        //$message.= "  Exception:" .$exception->getMessage();
       


        $this->_draw($code, $message);


	}

    function notValidationRecord($message, $code = 1)
    {
        $this->_draw($code, $message);
    }


} 