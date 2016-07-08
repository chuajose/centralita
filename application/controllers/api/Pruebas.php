<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

/**
 * CRUD for orders
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     Centralita
 * @subpackage  Pruebas
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class Pruebas extends REST_Controller
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
         $this->load->helper('orders_helper');

        // Configure limits on our controller methods. Ensure
        // you have created the 'limits' table and enabled 'limits'
        // within application/config/rest.php
        $this->methods['company_get']['limit'] = 500; //500 requests per hour per company/key
        $this->methods['company_post']['limit'] = 100; //100 requests per hour per company/key
        $this->methods['company_delete']['limit'] = 50; //50 requests per hour per company/key


   }


	function index_get($id = FALSE, $page = 0)
	{

        $this->load->helper('files_helper');

        $files = new files_helper();

        $response = $files->generate_link();

                $this->response(0,$response, 200); // 200 being the HTTP response code


	}
    function config_get($id = FALSE, $page = 0)
    {


        $files = new companies_helper();

        $response = $files->get_config($this->companyId,'promotion_code');

                $this->response(0,$response, 200); // 200 being the HTTP response code


    }
    
    function devices_get($id = FALSE, $page = 0)
    {
        $this->load->helper('devices_helper');

        $devices = new devices_helper();

        $response = $devices->get_devices_by_user($this->get('id'));

        $this->response(0,$response, 200); // 200 being the HTTP response code


    }

    function devices_post($id = FALSE, $page = 0)
    {
        $this->load->helper('devices_helper');

        $_POST = $this->post();
        $devices = new devices_helper();

        $response = $devices->add($id);
        $this->deviceId = $response;
                $this->response(0,$response, 200); // 200 being the HTTP response code


    }

    function push_post()
    {
        $this->load->helper('notifications_helper');

        $notification = new notifications_helper();

        
        $response = $notification->send_mobile_notification($this->post('id'),1,1);

                $this->response(0,$response, 200); // 200 being the HTTP response code
    }
    
    function notification_get()
    {
        $this->load->helper('notifications_helper');

        $notification = new notifications_helper();

       $page = $this->get('page');
       $limit = 10;
        $data = array( 
                    'pagination'=>array('limit'=>$limit,'offset'=>$page*$limit),
                    
                    );
        $data['data'][] = array('field' =>'companyTrans', 'exp' => "eq", 'value' => 3, 'condition' =>'AND');
  
        $users = $notification->all($data);

               $this->response(0,$users, 200); // 200 being the HTTP response code

        

    }

    function send_push_get()
    {
        $this->load->helper('notifications_helper');
        $notification = new notifications_helper();

        $data['message'] = "resend_privacy";
        $data['link'] = "este se se limg";
        $data['entity'] = $this->get('entity');
        $data['company_trans'] = $this->companyId;
        $data['user_trans'] = $this->userId;
        $data['company_recept'] = $this->get('company_id');
        $data['type'] = $this->get('type');
        $data['object'] = $this->get('object');


       

        $users = $notification->send_notification($data);



        $this->response(0,$users, 200); // 200 being the HTTP response code
    }

    function notification_post()
    {
        $this->load->helper('notifications_helper');
        $notification = new notifications_helper();

        $data['message'] = "resend_privacy";
       //$data['link'] = "este se se limg";
        $data['company_trans'] = $this->companyId;
        $data['user_trans'] = $this->userId;
        $data['company_recept'] = 3;
        $response = $notification->add($data); //array with message, link, company_trans, user_trans, company_recept

        $this->response(0,$response, 200); // 200 being the HTTP response code
    }

    function send_push_new_get()
    {
        $this->load->helper('notifications_helper');
        $notification = new notifications_helper();

        $data['message'] = "resend_privacy";
        $data['link'] = "este se se limg";
        $data['entity'] = $this->get('entity');
        $data['company_trans'] = $this->companyId;
        $data['user_trans'] = $this->userId;
        $data['company_recept'] = $this->get('company_id');
        $data['type'] = $this->get('type');
        $data['object'] = $this->get('object');


       

        $users = $notification->send_notification($data);



        $this->response(0,$users, 200); // 200 being the HTTP response code

    }
    function post_code_get()
    {
        $company = new companies_helper();
        $company->get_companies_by_post_code($this->get('post_code'));
    }

    function favorites_get()
    {
        
        $company = new companies_helper();
        $favorites = $company->get_companies_favorites($this->get('id'));
        $this->response(0,$favorites, 200); // 200 being the HTTP response code
    }

    function favorites_post()
    {
                if( ! $this->post('id_company_f'))notValidationRecord(array('id_company_f', 'Es necesario el campo de la empresa favorita')); //response validation error

        $company = new companies_helper();
        $favorites = $company->add_favorites($this->post('id'), $this->post('id_company_f'));
        $this->response(0,$favorites, 200); // 200 being the HTTP response code
    }

    function favorites_delete()
    {
        $company = new companies_helper();
        $favorites = $company->delete_favorites($this->delete('id'), $this->delete('id_company_f'));
        $this->response(0,$favorites, 200); // 200 being the HTTP response code
    }

    function notes_get()
    {
        $company = new companies_helper();
        $notes = $company->all_notes($this->get('id'));
        $this->response(0,$notes, 200); // 200 being the HTTP response code
    }

    function notes_post()
    {
        $company = new companies_helper();

        $data = array(
            'message' => $this->post('message')
            );
        $notes = $company->add_note($this->post('id'),$this->post('admin'), $data);
        $this->response(0,$notes, 200); // 200 being the HTTP response code
    }

    function locality_get()
    {
        $lat = "43.36615850";
        $lng = "-8.41330060";
        $timeout = 5;
        $ctx = stream_context_create(array('http' => array('timeout' => $timeout)));
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&components=country,locality:ES";
        $notes = file_get_contents($url, false, $ctx);
       // echo $notes;
        $output= json_decode($notes);

    for($j=0;$j<count($output->results[0]->address_components);$j++){
                
        if($output->results[0]->address_components[$j]->types[0]=="locality")echo $output->results[0]->address_components[$j]->long_name.'<br/>';

            }
        $this->response(0,$notes, 200); // 200 being the HTTP response code
    }

    function users_by_company_get()
    {
        $this->load->helper('users_helper');
        $users = new users_helper();

        $notes = $users->get_users_by_company($this->get('id'));
        $this->response(0,$notes, 200); // 200 being the HTTP response code
    }

    function commission_by_company_get()
    {

        $this->load->model('commissions_model');

        $data = array();
        $data['data'][] = array('field' =>'company', 'exp' => "eq", 'value' =>$this->get('id'), 'condition' =>'AND');
        $data['data'][] = array('field' =>'type', 'exp' => "eq", 'value' =>2, 'condition' =>'AND');
        $commissions = $this->commissions_model->count_commissions(FALSE, $data);
        $this->response(0,$commissions, 200); // 200 being the HTTP response code
    }

     function company_promotion_get()
    {

        $company = new companies_helper();
        $commissions = $company->get_companies_promotion(3);
        $this->response(0,$commissions, 200); // 200 being the HTTP response code
    }

    function commission_pay_get()
    {

        $this->companyId = $this->get('id'); 
        $this->load->helper('commissions_helper');

        $this->load->config('companies', TRUE);


        $commssion = new commissions_helper();

        $commissions = $commssion->get_amount_promotion_pay($this->get('id'), $this->get('status'));


        if( $commissions > $this->config->item('companies_commission_pay', 'companies')) 
        {
              /*
                enviar mail a cesar
             */
            $date = date('Y-m-d H:i:s');


            $data['type'] = "request_commission_pay";
            $data['value'] = $commissions;
            $data['date_execution'] = $date;
            $company = new companies_helper(FALSE,TRUE);
            $request = $company->add_request($this->companyId,$data);


            //We send email to usar and to admin
            $this->load->config('notifications', TRUE);
            $this->load->helper('notifications_helper');
            $this->load->helper('notificationsadmins_helper');
            $notification = new Notificationsadmins_helper();
            
            //Send notifcation to company seller
            $data_notification['message'] = $this->config->item('notifications_admin','notifications')['company']['email'][8]['message'];
            $data_notification['link'] = sprintf($this->config->item('notifications_admin','notifications')['company']['email'][8]['link'],$this->companyId );
            $data_notification['entity'] = "company";
            $data_notification['company'] = $this->companyId;
            $data_notification['type'] = 8;
            $data_notification['type_email'] = $this->config->item('notifications_admin','notifications')['company']['email'][8]['type_email'];
            $data_notification['object'] = $this->companyId;
            $data_notification['data'] = array();
            $data_notification['admin'] = 1;

            $send = $notification->send_notification_admin($data_notification);

            $this->response(0,array('commissions_pay' => $commissions), 200); // 200 being the HTTP response code
        }
        else
        {
           notFoundRecord(new Exception("promotion company not found",405));
       }
    }
}
