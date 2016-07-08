<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/controllers/api/Api.php';

/**
 * CRUD for extensions
 *
 * This is an example of a few basic extension interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package     Centralita
 * @subpackage  Reports
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class ReportsCtl extends Api
{
    /**
     * Method Constructor.
     * 
     * Check limits to use
     * If exist param id, get data from extension id
     *
     */
	function __construct()
    {
        // Construct our parent class
        parent::__construct();
         $this->load->helper('report_helper');

   }

    /**
     * Method Get for get extensions.
     * 
     * Return array with data form extensions.
     * If exist param id, get data from extension id, else return array with all extensions
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/extensions/index
     *      *
     *   - or -
     *   Optional get data limit with name <b>limit</b>
     *   - or -
     *   Optional get data order  with name <b>field</b> and <b>direction</b>
     * If extensions exist, return array <b>extensions</b> with data from extensions:
     * <code>
     * <pre>
     * "extensions": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "extensionname": "administrator",
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
     *           "extensionname": "jose",
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
     * list extensions 
     * @param int $id of extension to load. If its FALSE get all extensions
     * @param int $group of extension to load. If its FALSE get all comercial extensions
     * @param  int $page page to load
     * @return array 
     */
	function index_get($id = FALSE, $page = 0)
	{

        
        if(!parent::require_login()) {
            return NULL;
        }
        $search = array();
        if($this->get('commercial_id')) $search['commercial_id'] = $this->get('commercial_id');
        $page = ($this->get('page')) ? $this->get('page'):$page;
        $limit           = ($this->get('limit')) ? $this->get('limit'):$this->config->item('limit_pag');

        $report = new Report();

        $report = $report->get_all($page, $limit, $search);

        $total = $report['total'];
        $report = $report['list'];
    
        //$this->response(0,array('extensions'=>$extensions, 'total'=>$total), 200); // 200 being the HTTP response code


        return parent::response(array('reports'=>$report, 'total'=>$total));

	}


    function index_post()
    {

        
        if(!parent::require_login()) {
            return NULL;
        }

        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation'));

        $_POST = $this->post();//Permite poder validar los datos del post
        
        $this->form_validation->set_rules('commercial_id', 'Comercial', 'required');
        
        $this->form_validation->set_error_delimiters('', '');

        if ($this->form_validation->run()) {


            $reports = new Report();
            $data = array( 
                'users_id' => $this->userId,
                'date' => date('Y-m-d')
                );

            if($this->post('commercial_id'))$data['commercial_id'] = $this->post('commercial_id');
            if($this->post('from'))$data['from'] = date('Y-m-d',$this->post('from'));
            if($this->post('to'))$data['to'] = date('Y-m-d',$this->post('to'));

            $id = $reports->save($data);

            if($id){


                $search = array();
                if($this->post('commercial_id')) $search['commercial_id'] = $this->input->get('commercial_id');

                if($this->post('from') || $this->post('to')) $search['date'] = array();
                if($this->post('from')) $search['date'][0] = date('Y-m-d',$this->post('from'));
                if($this->post('to')) $search['date'][1] = date('Y-m-d',$this->post('to'));

                $this->load->helper('call_helper');

                $call = new call();

                $call = $call->get_all(0, 9999999, $search);

                $total = $call['total'];
                $calls = $call['list'];
                $duration = $call['duration'];

               
                 
                // create a file pointer connected to the output stream
                $file = fopen('tmp/'.$id.'.csv', 'w');
                 
                // send the column headers
                fputcsv($file, array('Numero', 'Duración', 'Fecha', 'comercial'));
                 
                // output each row of the data
                if($calls){
                    foreach ($calls as $row)
                    {
                        //$data = array($row->number,$row->duration,$row->date,$row->commercial['name']);
                        $data =  array($row['number'], $row['duration'], $row['date'].' '.$row['hour'], $row['commercial']['name']);
                        fputcsv($file, $data);
                    }
                }
                

                fputcsv($file, array('Total', $total, 'Duracion', $duration));
            }


            

     
        
            return parent::response(array('reports'=>$reports->load($id)));

        }

        notValidationRecord((validation_errors_array() ? validation_errors_array() :  'error')); //response validation error

    }
    
    
    
}
