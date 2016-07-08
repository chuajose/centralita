<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/controllers/api/Api.php';

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     Centralita
 * @subpackage  Auth
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class Auth extends Api
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

        
   }
	/*function create_get()
    {$this->load->database();
         $this->load->config('populate_data', 'populate');

        foreach ($this->config->item('populate') as $key=>$value) {

            foreach ($value as $val) {
                $this->db->insert($key, $val);
            }
            
        }
    }*/

    /**
     * Method Post for login.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/login
     * 
     * Require post data with name <b>email</b>  and <b>password</b>
     *
     * If user login, return array <b>users</b> with data from user:
     * <code>
     * <pre>
     * users": {
     *      "id": 1,
     *       "mail": "admin@admin.com",
     *       "name": "Admin istrator",
     *       "username": "administrator",
     *       "groups": [
     *           {
     *               "id": 1,
     *                "name": "admin"
     *            },
     *            {
     *                "id": 2,
     *                "name": "members"
     *            }
     *        ]
     * }
     * </pre>
     * </code>
     * @return array
     */
    public function login_post()
    {

        $this->load->helper(array('form', 'url', 'user_helper'));


        $this->load->library(array('ion_auth','form_validation'));
        $this->lang->load('auth');
        $tables = $this->config->item('tables','ion_auth');

        $_POST = $this->post();//Permite poder validar los datos del post
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');

        if($this->form_validation->run()){
            $email=$this->post('email');
            $password=$this->post('password');

            if ($id = $this->ion_auth->login($email, $password, false))
            {
                $user = new User();
                $users = $user->load($id);     
                $this->userId = $id;
                return parent::response(array('users' => $users));
                
            }

            return parent::response(array(config_item('rest_status_field_name') => 2,  config_item('rest_message_field_name') => array('login'=>$this->ion_auth->errors())));
        }
        notValidationRecord((validation_errors_array() ? validation_errors_array() : ($this->ci->ion_auth->errors() ? $this->ci->ion_auth->errors() : 'error'))); //response validation error
   }

   

   /**
     * Method Post for Regiter user and company.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/register
     * 
     * Require post data with name <b>email</b>, <b>password</b>, <b>cif</b>, <b>first_name</b>, <b>last_name</b>, <b>password_confirm</b>, <b>name</b> Name company, <b>post_code</b>, <b>city</b>, <b>province_id</b> id or string, <b>address</b>, <b>credit</b>, <b>category_id</b>, <b>lat</b> Latiutde, <b>lng</b> Longitude
     *
     * If user register, return array <b>users</b> with data from user:
     * <code>
     * <pre>
     * users": {
     *      "id": 1,
     *       "mail": "admin@admin.com",
     *       "name": "Admin istrator",
     *       "username": "administrator",
     *       "groups": [
     *           {
     *               "id": 1,
     *                "name": "admin"
     *            },
     *            {
     *                "id": 2,
     *                "name": "members"
     *            }
     *        ]
     * }
     * </pre>
     * </code>
     * @return array
     */
   public function register_post()
   {
        $_POST = $this->post();
        
        $this->load->helper(array('form', 'url', 'user_helper'));


        $this->load->library(array('ion_auth','form_validation'));
        $this->lang->load('auth');
        $tables = $this->config->item('tables','ion_auth');
        //validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique['.$tables['users'].'.email]');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->post('first_name')) . ' ' . strtolower($this->post('last_name'));
            $email    = strtolower($this->post('email'));
            $password = $this->post('password');
            $additional_data = array(
                'first_name' => $this->post('first_name'),
                'last_name'  => $this->post('last_name'),
            );
        }
        if ($this->form_validation->run() == true && $id=$this->ion_auth->register($username, $password, $email, $additional_data,array(1)))
        {
            $user = new User();
            $users = $user->load($id);     
            $this->userId = $id;
            return parent::response(array('users' => $users));

        }
        notValidationRecord((validation_errors_array() ? validation_errors_array() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : 'error'))); //response validation error


   }
   
    /**
     * Method Post for recovery password
     * 
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/forgot_password
     * 
     * Require post 
     *     email  
     *
     * If recovery password its ok, return array <b>recovery</b> with value TRUE:
     * <code>
     * <pre>
     * "recovery": TRUE
     * </pre>
     * </code>
     * 
     * Else return code error
     * @return array
     */
    function forgot_password_post()
    {
        $this->load->library(array('ion_auth','form_validation'));
        $this->lang->load('auth');
        $this->load->config('rest_errors',TRUE);
        $tables = $this->config->item('tables','ion_auth');
        $_POST = $this->post();
        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        
        if ($this->form_validation->run() == false)
        {
           
            notValidationRecord((validation_errors_array()) ? validation_errors_array() :  'error'); //response validation error

        }
        else
        {
          
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            
            if(empty($identity)) {
    
                notValidationRecord($this->config->item('rest_error_code','rest_errors')[105]);
                
            }
            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});
            if ($forgotten)
            {
                return $this->response($this->code_return,array('recovery' => $forgotten),200);

            }
            else
            {
                notValidationRecord($this->ion_auth->errors());
            }
        }
    }

}
