<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

/**
 * CRUD for Errors
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     Centralita
 * @subpackage  Errors
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class ErrorsCtl extends REST_Controller
{
    /**
     * Method Constructor.
     * 
     * Check limits to use
     * If exist param id, get data from user id
     *
     */
	function __construct()
    {
        // Construct our parent class
        parent::__construct();

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['company_get']['limit'] = 500; //500 requests per hour per company/key
        $this->methods['company_post']['limit'] = 100; //100 requests per hour per company/key
        $this->methods['company_delete']['limit'] = 50; //50 requests per hour per company/key


   }


    
    /**
     * Method POST for add error.
     * 
     * Crete new error from device
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/errors/index
     *   Required Field error
     *   
     */
    function index_post()
    {
        $_POST = $this->post();//Permite poder validar los datos del post

        $this->load->model('errors_model');

        $this->errors_model->add($this->deviceId, $this->post('error'));
       
    }

   

}
