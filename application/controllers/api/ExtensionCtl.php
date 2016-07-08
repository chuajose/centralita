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
 * @subpackage  extensions
 * @category    Controller
 * @author      Jose Manuel Suarez Bravo
 * @link        https://github.com/chuajose
*/
class ExtensionCtl extends Api
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
         $this->load->helper('extension_helper');

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
	function index_get($page = 0)
	{

        
        if(!parent::require_login()) {
            return NULL;
        }

        $type = ($this->get('type') !== FALSE && $this->get('type') !== NULL) ? $this->get('type'):FALSE;
        $status = ($this->get('status') !== FALSE && $this->get('status') !== NULL) ? $this->get('status'):FALSE;
        $search = ($this->get('search') !== FALSE && $this->get('search') !== NULL) ? $this->get('search'):FALSE;
        $page = ($this->get('page')) ? $this->get('page'):$page;
        $limit           = ($this->get('limit')) ? $this->get('limit'):$this->config->item('limit_pag');

        $extension = new extension();

        $extensions = $extension->get_all($page, $limit, $type, $status, $search);

        $total = $extensions['total'];
        $extensions = $extensions['list'];
    

        //$this->response(0,array('extensions'=>$extensions, 'total'=>$total), 200); // 200 being the HTTP response code


        return parent::response(array('extensions'=>$extensions, 'total'=>$total));

	}

    
    

    /**
     * Method POST for add extensions.
     * 
     * Return array with data form extension.
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/extensions/index
     *      
     * Optional post data with name <b>first_name</b>, <b>email</b>,
     * If extensions updated, return array <b>extensions</b> with data from extension:
     * <code>
     * <pre>
     * "extensions": [
     *       {
     *           "id": 1,
     *           "mail": "admin@admin.com",
     *           "name": "Admin istrator",
     *           "last_name": "Admin istrator",
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
     *      ]
     *   "total": 1
     * </pre>
     * </code>
     * 
     * create extensions 
     * @param   $id id extension to update
     * @return array 
     */
    function index_patch($id = FALSE)
    {
        $data = array();

        $patch = $this->patch();
    
        if( ! empty($patch))
        {   
            $data['id'] = $this->patch('id');
            if($this->patch('unasigned'))$data['unasigned'] = $this->patch('unasigned');
            if($this->patch('status') !== FALSE)$data['status'] = $this->patch('status');
        }
        
        $extension = new extension(); 

        $update = $extension->save($data);


        if ($update)
        {

            $extension = $extension->get($this->patch('id'));
        }
        else
        {
            $extension = "";
        }
        return parent::response(array('extension'=>$extension));
    }
    
    /**
     *
     * Method delete for delete extension.
     * 
     * Delete extension from database
     *
     * Maps to the following URL
     *      http://www.centralita.es/api/v1/extensions/index
     * 
     *
     * If delete extension , return string with message:
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
     * @param   $id Get param with id for extension
     * @return array 
     */
    function index_delete()
    {
        $extension = new extensions_helper();
        $delete = $extension->delete($this->get('id'));

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


    function status_get()
    {

        if(!parent::require_login()) {
            return NULL;
        }

        $type = ($this->get('type')) ? $this->get('type'):0;
        $extension = new extension();

        $extensions = $extension->get_used($type);

        $total = count($extensions['list']);
        $extensions = $extensions['list'];
    

        //$this->response(0,array('extensions'=>$extensions, 'total'=>$total), 200); // 200 being the HTTP response code


        return parent::response(array('extensions'=>$extensions, 'total'=>$total));

    }

    
}
