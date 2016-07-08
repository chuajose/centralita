<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/controllers/api/Api.php';

/**
 * CRUD for commercials
 *
 * This is an example of a few basic commercial interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     Centralita
 * @subpackage  commercials
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class CommercialCtl extends Api
{
    /**
     * Method Constructor.
     * 
     * Check limits to use
     * If exist param id, get data from commercial id
     *
     */
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
         $this->load->helper('commercial_helper');

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['commercial_get']['limit'] = 500; //500 requests per hour per commercial/key
        $this->methods['commercial_post']['limit'] = 100; //100 requests per hour per commercial/key
        $this->methods['commercial_delete']['limit'] = 50; //50 requests per hour per commercial/key


   }

    /**
     * Method Get for get commercials.
     * 
     * Return array with data form commercials.
     * If exist param id, get data from commercial id, else return array with all commercials
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/index
     *      *
     *   - or -
     *   Optional get data limit with name <b>limit</b>
     *   - or -
     *   Optional get data order  with name <b>field</b> and <b>direction</b>
     * If commercials exist, return array <b>commercials</b> with data from commercials:
     * <code>
     * <pre>
     * "commercials": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "commercialname": "administrator",
     *           "groups": [
     *               {
     *                   "id": 1,
     *                   "name": "admin"
     *               },
     *               {
     *                   "id": 2,
     *                   "name": "members"
     *               }
     *           ]
     *       },
     *      {
     *           "id": 2,
     *           "mail": "",
     *           "name": "jose suarez",
     *           "commercialname": "jose",
     *           "groups": [
     *               {
     *                   "id": 2,
     *                   "name": "members"
     *               }
     *           ]
     *       },
     *       ..........
     * 
     *   "total": 1
     * </pre>
     * </code>
     * 
     * list commercials 
     * @param int $id of commercial to load. If its FALSE get all commercials
     * @param int $group of commercial to load. If its FALSE get all comercial commercials
     * @param  int $page page to load
     * @return array 
     */
	function index_get($id = FALSE, $page = 0)
	{

        if(!parent::require_login()) {
            return NULL;
        }

        $page = ($this->get('page')) ? $this->get('page'):$page;
        $id = ($this->get('id')) ? $this->get('id'):$id;
        $search = ($this->get('search')) ? $this->get('search'):FALSE;
        $limit           = ($this->get('limit')) ? $this->get('limit'):$this->config->item('limit_pag');
        $data = array();
        $commercial = new Commercial();
        
        if($id)
        {
            $commercials = $commercial->get($id, $data);
            $total = 1;

        }
        else
        {

            $commercials = $commercial->get_all($page, $limit, $search);
            $total = $commercials['total'];
            $commercials = $commercials['list'];
        }


        //$this->response(0,array('commercials'=>$commercials, 'total'=>$total), 200); // 200 being the HTTP response code


        return parent::response(array('commercials'=>$commercials, 'total'=>$total));

	}

    
    /**
     * Method POST for add commercials.
     * 
     * Return array with data form commercial.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/index
     *      
     * Require post data with name <b>first_name</b>, <b>email</b>,<b>password</b>, <b>password_confirm</b>,
     * If commercials created, return array <b>commercials</b> with data from commercials:
     * <code>
     * <pre>
     * "commercials": [
     *       {
     *           "id": 1,
     *           "name": "Admin istrator",
     *           "status": "administrator",
     *           "extensions_id":80
     *       },
     *      ]
     *   "total": 1
     * </pre>
     * </code>
     * 
     * create commercials 
     * @return array 
     */
    function index_post()
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation'));

        $_POST = $this->post();//Permite poder validar los datos del post
        $commercial = new Commercial();
        
        $this->form_validation->set_rules('name', 'Nombre', 'required');
        
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run()) {

            $name = $this->post('name');
            $extensions_id = $this->post('extensions_id');

            $data = array(
                'name' => $name,
                'extensions_id' => $extensions_id
            );

            $new_commercial = $commercial->save($data);
            if ( !$new_commercial)
            {
                $commercial = FALSE;
                $code = array('status'=>101,'error'=>'No se ha podido crear el comercial');//Lo marco como error para el response con tipo 1, pero ya le paso yo el texto a mostrar
            }
            else
            {
                $code = 0;
                $commercial = $commercial->load($new_commercial); //Marco a FALSE la opcion de cargar empresa para poder enviar los datos del usuario en la respuesta
            }
    
            $this->response($code,array('commercial'=>$commercial), 200); // 200 being the HTTP response code
        }
        notValidationRecord((validation_errors_array() ? validation_errors_array() :  'error')); //response validation error

    }

    /**
     * Method POST for add commercials.
     * 
     * Return array with data form commercial.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/index
     *      
     * Optional post data with name <b>first_name</b>, <b>email</b>,
     * If commercials updated, return array <b>commercials</b> with data from commercial:
     * <code>
     * <pre>
     * "commercials": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "last_name": "Admin istrator",
     *           "commercialname": "administrator",
     *           "groups": [
     *               {
     *                   "id": 1,
     *                   "name": "admin"
     *               },
     *               {
     *                   "id": 2,
     *                   "name": "members"
     *               }
     *           ]
     *       },
     *      ]
     *   "total": 1
     * </pre>
     * </code>
     * 
     * create commercials 
     * @param   $id id commercial to update
     * @return array 
     */
    function index_patch($id = FALSE)
    {
        $data = array();

        $patch = $this->patch();
    
        if( ! empty($patch))
        {   
            $data['id'] = $this->patch('id');
            if($this->patch('name'))$data['name'] = $this->patch('name');
            if($this->patch('status') != NULL)$data['status'] = $this->patch('status');
            if($this->patch('extensions_id'))$data['extensions_id'] = $this->patch('extensions_id');
            if($this->patch('deleted'))$data['deleted'] = $this->patch('deleted');
        }
        
        if(isset($data['deleted']) && $data['deleted'] == 1)$data['extensions_id'] = 1;

        $commercial = new Commercial(); 

        $update = $commercial->save($data);

        if ($update)
        {

            $commercial = $commercial->load($this->patch('id'));
            $code = 0;
        }
        else
        {
            $code = 102;
            $commercial = "";
        }
        return parent::response(array('commercial'=>$commercial));
    }
    
    /**
     *
     * Method delete for delete commercial.
     * 
     * Delete commercial from database
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/index
     * 
     *
     * If delete commercial , return string with message:
     * <code>
     * <pre>
     *    "response": {
     *        "string whit error or blank "
     *    }
     *   "code": {
     *          "status": 0, //Error code
     *           "error": "" //M error
     *       },
     * </pre>
     * </code>
     * 
     * Else return error code
     * @param   $id Get param with id for commercial
     * @return array 
     */
    function index_delete()
    {
        $commercial = new commercials_helper();
        $delete = $commercial->delete($this->get('id'));

        if ($delete)
        {
            $message = "Usuario eliminado";
            $code = 0;
        }
        else
        {
            $message = "No eliminado";
            $code = 103;
        }
        
        $this->response($code,$message, 200); // 200 being the HTTP response code
    }

    /**
     *
     * Method patch to update token device
     * 
     * update tonken device from database
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/token
     * 
     * Require patch data with name <b>token</b>
     * If update token , return string with message:
     * <code>
     * <pre>
     *    "devices": [
     *       {
     *           "id": 4,
     *           "device_id": "12",
     *           "device_type": "android",
     *           "version": "4.2",
     *           "model": "g8",
     *           "updated": {
     *               "date": "2015-06-02 13:54:21",
     *               "timezone_type": 3,
     *               "timezone": "Europe/Berlin"
     *           },
     *           "token": "tokennuevos",
     *           "commercial": 1
     *       }
     *   ],
     * </pre>
     * </code>
     * 
     * Else return error code
     * @return array 
     */
    function token_patch()
    {
        $data = array();

        $patch = $this->patch();
        if(empty($patch))
        {

            return FALSE;
        }

        $this->load->helper('devices_helper');
        $devices = new devices_helper();


        $update = $devices->update($this->deviceId, array('token' => $this->patch('token')));


       
        if ($update)
        {
            $commercials = $update;
            $code = 0;
        }
        else
        {
            $code = 102;
            $commercials = "";
        }

        /*if($this->patch('new') !== FALSE)
        {

            //ejecuto acciones para el usuario recein registrado
            //
            $company = new companies_helper(FALSE,TRUE);
            $this->load->config('companies',TRUE);
   
            $company->add_prepaid($this->companyId,array('amount' => $this->config->item('companies_prepaid_default','companies') ));

            
        }*/


    
        $this->response($code,$update, 200); // 200 being the HTTP response code
    }
    
     /**
     *
     * Method  to desactive commercial
     * 
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/desactive
     * 
     * If desactive commercial , return string with message:
     * Else return error code
     * @return array 
     */
    function request_desactive_get()
    {
        
        /*
        enviar mail para dar de baja a cesar
         */

        $data['type'] = "drop_company";
        $data['value'] = 1;
        $company = new companies_helper(FALSE,TRUE);
        $request = $company->add_request($this->companyId,$data);

        /*
        enviar mail para dar de baja a cesar
         */
                //We send email to usar and to admin
        $this->load->config('notifications', TRUE);
        $this->load->helper('notifications_helper');
        $this->load->helper('notificationsadmins_helper');
        $notification = new Notificationsadmins_helper();
        
        //Send notifcation to company seller
        $data_notification['message'] = $this->config->item('notifications_admin','notifications')['company']['email'][2]['message'];
        $data_notification['link'] = sprintf($this->config->item('notifications_admin','notifications')['company']['email'][2]['link'],$this->companyId );
        $data_notification['entity'] = "company";
        $data_notification['company'] = $this->companyId;
        $data_notification['type'] = 2;
        $data_notification['type_email'] = $this->config->item('notifications_admin','notifications')['company']['email'][2]['type_email'];
        $data_notification['object'] = $this->companyId;
        $data_notification['data'] = array();
        $data_notification['admin'] = 1;

        $send = $notification->send_notification_admin($data_notification);

        

       
        if ($request)
        {
            $commercials = TRUE;
            $code = 0;
        }
        else
        {
            $code = 102;
            $commercials = FALSE;
        }
    
    
        $this->response($code,$commercials, 200); // 200 being the HTTP response code
    }

     /**
     *
     * Method  to send  Privacy Policy to commercial
     * 
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/commercials/equest_privacy
     * 
     * If send privacy policy, return string with message:
     * Else return error code
     * @return array 
     */
    function request_privacy_get()
    {

        $data['type'] = "resend_privacy";
        $data['value'] = 1;
        $company = new companies_helper(FALSE,TRUE);
        $request = $company->add_request($this->companyId,$data);

        /*
        enviar mail para dar de baja a cesar
         */
        $this->load->helper('Notifications_helper');

        $notification = new Notifications_helper();

        $data['id'] = $this->commercialId;
        $data['id_company'] = $this->companyId;
        
        $send = $notification->send_email($data);


                /*
        enviar mail para dar de baja a cesar
         */
        $this->load->config('notifications', TRUE);
        $this->load->helper('notificationsadmins_helper');
        $notification = new Notificationsadmins_helper();
        
        //Send notifcation to company seller
        $data_notification['message'] = $this->config->item('notifications_admin','notifications')['company']['email'][3]['message'];
        $data_notification['link'] = sprintf($this->config->item('notifications_admin','notifications')['company']['email'][3]['link'],$this->companyId );
        $data_notification['entity'] = "company";
        $data_notification['company'] = $this->companyId;
        $data_notification['type'] = 3;
        $data_notification['type_email'] = $this->config->item('notifications_admin','notifications')['company']['email'][3]['type_email'];
        $data_notification['object'] = $this->companyId;
        $data_notification['data'] = array();
        $data_notification['admin'] = 1;
        $send = $notification->send_notification_admin($data_notification,FALSE);


        

       
        if ($send)
        {
            $request = $company->update_request($request);
            $commercials = $send;
            $code = 0;
        }
        else
        {
            $code = 0;
            $commercials = "";
        }
    
        $this->response($code,$commercials, 200); // 200 being the HTTP response code
    }
}
