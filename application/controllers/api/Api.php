<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 */
require APPPATH . '/libraries/REST_Controller.php';
class Api extends REST_Controller
{
    var $my_user = NULL;
    function __construct()
    {
        parent::__construct();
        
    }
    function response($response = NULL, $code = 0, $continue = false) {
        if ($code === 0) {
            return parent::response(array('code' => 0, 'response' => $response), 200);
        }
        $this->load->config('error_code');
        $error = $this->config->item($code, 'error_code');
        if (!$error) {
            $error = array ("http_code"=>400, "error"=> "BadResponse");
        }
        return parent::response(array('code' => $code, 'error' => $error['error'], 'response' => $response), $error['http_code']);
    }
    function require_login() {
        if ($this->userId) {
            return TRUE;
        } else {
            $this->response('Sin autorización', 1);
            return FALSE;
        }
    }
}