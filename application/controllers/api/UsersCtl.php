<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/controllers/api/Api.php';

/**
 * CRUD for users
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     Centralita
 * @subpackage  Users
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class UsersCtl extends Api
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
         $this->load->helper('user_helper');

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['user_get']['limit'] = 500; //500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key


   }

    /**
     * Method Get for get users.
     * 
     * Return array with data form users.
     * If exist param id, get data from user id, else return array with all users
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/users/index
     *      *
     *   - or -
     *   Optional get data limit with name <b>limit</b>
     *   - or -
     *   Optional get data order  with name <b>field</b> and <b>direction</b>
     * If users exist, return array <b>users</b> with data from users:
     * <code>
     * <pre>
     * "users": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "username": "administrator",
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
     *           "username": "jose",
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
     * list users 
     * @param int $id of user to load. If its FALSE get all users
     * @param int $group of user to load. If its FALSE get all comercial users
     * @param  int $page page to load
     * @return array 
     */
	function index_get($id = FALSE, $page = 0)
	{

        $page = ($this->get('page')) ? $this->get('page'):$page;
        $id = ($this->get('id')) ? $this->get('id'):$id;
        $limit           = ($this->get('limit')) ? $this->get('limit'):$this->config->item('limit_pag');
        $data = array();
        $user = new User();
        
        if($id)
        {
            $users = $user->load($id);
            $total = count($users);
        }
        else
        {

            $users = $user->get_all($data);
            $total = $users['total'];
            $users = $users['list'];
        }

        $this->response(0,array('users'=>$users, 'total'=>$total), 200); // 200 being the HTTP response code

	}

    /**
     * Method Get for user logged.
     * 
     * Return array with data form user.
     * If exist param id, get data from user id
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/users/me
     *      *
     * If users exist, return array <b>users</b> with data from user:
     * <code>
     * <pre>
     * "users": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "username": "administrator",
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
     *   ],
     *   "total": 1
     * </pre>
     * </code>
     * 
     * data user logged
     * @return array 
     */
    function me_get()
    {
        $id = $this->userId;

        $user = new User();
        
        $users = $user->get($id,array(),TRUE);

        $total = $users['total'];
        $users = $users['users'];

        $this->response(0,array('users'=>$users, 'total'=>$total), 200); // 200 being the HTTP response code
    }
    
    
    /**
     * Method POST for add users.
     * 
     * Return array with data form user.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/users/index
     *      
     * Require post data with name <b>first_name</b>, <b>email</b>,<b>password</b>, <b>password_confirm</b>,
     * If users created, return array <b>users</b> with data from users:
     * <code>
     * <pre>
     * "users": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "username": "administrator",
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
     * create users 
     * @return array 
     */
    function index_post()
    {
        $_POST = $this->post();//Permite poder validar los datos del post
        $user = new User(FALSE,TRUE);
        $new_user = $user->add();
        if ( ! is_array($new_user))
        {
            $users = array('users'=>"","total"=>0);
            $code = array('status'=>101,'error'=>$new_user);//Lo marco como error para el response con tipo 1, pero ya le paso yo el texto a mostrar
        }
        else
        {
            $code = 0;
            $users = $new_user; //Marco a FALSE la opcion de cargar empresa para poder enviar los datos del usuario en la respuesta
        }
    
        $this->response($code,array('users'=>$users['users'], 'total'=>$users['total']), 200); // 200 being the HTTP response code
    }

    /**
     * Method POST for add users.
     * 
     * Return array with data form user.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/users/index
     *      
     * Optional post data with name <b>first_name</b>, <b>email</b>,
     * If users updated, return array <b>users</b> with data from user:
     * <code>
     * <pre>
     * "users": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "last_name": "Admin istrator",
     *           "username": "administrator",
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
     * create users 
     * @param   $id id user to update
     * @return array 
     */
    function index_patch($id = FALSE)
    {
        $data = array();

        $patch = $this->patch();


        if( ! empty($patch))
        {
            $data['last_name'] = $this->patch('last_name');
            $data['first_name'] = $this->patch('name');
            $data['email'] = $this->patch('email');
        }


        if($this->patch('password'))
        {
            $this->load->helper(array('form', 'url'));
            $this->load->library(array('ion_auth','form_validation'));
            $this->lang->load('auth');
            $this->load->config('ion_auth', TRUE);
            $this->form_validation->set_data($patch);

            $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

            if ($this->form_validation->run() === FALSE)
            {
                notValidationRecord((validation_errors_array()) ? validation_errors_array() :  'Error al cambiar la contraseña'); //response validation error
            }
            $data['password'] = $this->patch('password');

        }
        

            $user = new User(FALSE,TRUE); //Segundo parametro a true para que me devuelva tambien los datos de la empresa al actualizar el usuario
            $update = $user->update($this->userId,$data);
            if ($update)
            {
                $users = $update;
                $code = 0;
            }
            else
            {
                $code = 102;
                $users = "";
            }
    
        $this->response($code,array('users'=>$users['users'], 'total'=>$users['total']), 200); // 200 being the HTTP response code
    }
    
    /**
     *
     * Method delete for delete user.
     * 
     * Delete user from database
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/users/index
     * 
     *
     * If delete user , return string with message:
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
     * @param   $id Get param with id for user
     * @return array 
     */
    function index_delete()
    {
        $user = new User();
        $delete = $user->delete($this->get('id'));

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
     *      http://www.centralita.es/api/v1/users/token
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
     *           "user": 1
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
            $users = $update;
            $code = 0;
        }
        else
        {
            $code = 102;
            $users = "";
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
    
    
}
