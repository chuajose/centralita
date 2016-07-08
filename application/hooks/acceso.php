<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acceso
{
	var $CI;
    
    function  __construct() 
    {
        $CI            = &get_instance();
        $this->CI      = $CI;
        //global $CFG;
        //$CFG->set_item('language', 'spanish');
        

  //    require_once(APPPATH.'helpers/api_helper.php');
   } // end Constructor

	function acceder ()
    {
        $CI                  = &get_instance();
        $this->CI            = $CI;
        $this->CI->userId    = FALSE;
        $this->CI->adminId    = FALSE;
        $this->CI->demo    = FALSE;
        $this->CI->companyId = FALSE;
        $this->CI->deviceId = FALSE;
        $this->CI->log_in    = FALSE;
        $this->CI->id_request = time().rand(5, 15);
        $CI->load->config('rest');
        //$CI->userId=152;


            



        if(strtolower($CI->router->class) !== "auth" && strtolower($CI->router->class) !== "updates" && strtolower($CI->router->class) !== "reports")
        {

            $this->headers = apache_request_headers();

            //if(!isset($this->headers["Authorization"]) || empty($this->headers["Authorization"])) $this->headers["Authorization"] = 'Bearer "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkYXRhIjoiMSIsImlhdCI6MTQzMjIxMzYxNCwiZXhwIjoxNDYzNzQ5NjE0fQ.R3SUhM4hDmQlzElwpJ5RSs1vQlaNutAH3J64ub_wXEs"';

            if(!isset($this->headers["Authorization"]) || empty($this->headers["Authorization"]))
            {

               $this->no_access(array('code'=>-1, 'status'=>401, 'response'=>'Sin Autorizacion'));
            }
            else 
            {

                $token = explode(" ", $this->headers["Authorization"]);
                try
                {
                    if (count($token) > 1)
                    {
                        $userToken = JWT::decode(trim($token[1],'"'));
                    }
                    else
                    {
                        $userToken = JWT::decode(trim($token[0],'"'));
                    }

                }
                catch (Exception $e)
                {
      
                    $this->no_access(array('code'=>-1, 'status'=>401, 'response'=>'Error en el token'));
                }

                if ($userToken)
                {

                    $this->CI->load->helper('user_helper');

                    $user = new User();
                    $user = $user->load(intval($userToken->data));


                    if($user)
                    {

                        
                        if($user->active==0)
                        {
                           $this->no_access(array('code'=>-1, 'status'=>401, 'response'=>'Usuario inactivo')); 
                        }

                        $this->CI->userId = $user->id;
                        
                    }
                    else
                    {
                        $this->no_access(array('code'=>-1, 'status'=>401, 'response'=>'Error en la autorizacion del usuario'));
                    }
                    
                }
            }
		}
    

        $method_http = strtolower($this->CI->input->server('REQUEST_METHOD'));
        

        if($method_http == 'post')
        {
            $params = json_encode($this->CI->input->post());
        }
        elseif($method_http == 'patch')
        {
            $params = json_encode($this->CI->patch());
        }
        elseif($method_http == 'deleted')
        {
            $params = json_encode($this->CI->deleted());
        }
        else
        {
            $params = json_encode($this->CI->input->get());
        }

        

        $this->CI->db->insert('logs',array('class' => $this->CI->router->class, 'method' => $this->CI->router->method, 'user_id'=> $this->CI->userId, 'params'=> $params,'type' => $method_http, 'ip' =>$this->CI->input->ip_address(),'request' => $this->CI->id_request, 'device_id'=> $this->CI->deviceId));


    }

    private function no_access ($data)
    {
        
        $CI                 = &get_instance();
        $this->CI           = $CI;
        $_supported_formats = array(
            'xml'               => 'application/xml',
            'json'              => 'application/json',
            'jsonp'             => 'application/javascript',
            'serialized'        => 'application/vnd.php.serialized',
            'php'               => 'text/plain',
            'html'              => 'text/html',
            'csv'               => 'application/csv'
        );        

        $this->CI->db->insert('logs',array('class' => $CI->router->class, 'method' => $CI->router->method, 'user_id'=> $this->CI->userId, 'type' => strtolower($this->CI->input->server('REQUEST_METHOD')), 'ip' =>$this->CI->input->ip_address(),'request' => $this->CI->id_request, 'device_id'=> $this->CI->deviceId));



        header('Content-Type: '.$_supported_formats[$this->CI->config->item('rest_default_format')] . '; charset=' . strtolower($this->CI->config->item('charset')));
        set_status_header($data['status']);
        echo json_encode( array('response'=>$data['response'], 'code'=>$data['code'] ));
        exit();

    }
}
/* End of file acceso.php */
/* Location: ./system/application/hooks/acceso.php */
