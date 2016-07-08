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
class CallCtl extends Api
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
         $this->load->helper('call_helper');

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
        $page = ($this->get('page')) ? $this->get('page'):$page;
        $limit  = ($this->get('limit')) ? $this->get('limit'):$this->config->item('limit_pag');
        if($this->get('commercial_id')) $search['commercial_id'] = $this->get('commercial_id');
        if($this->get('from')) $search['date'][0] = date('Y-m-d',$this->get('from'));
        if($this->get('to')) $search['date'][1] = date('Y-m-d',$this->get('to'));

        $call = new call();

        $call = $call->get_all($page, $limit, $search);

        $total = $call['total'];
        $calls = $call['list'];
        $duration = $call['duration'];
        $more5 = $call['more5'];

        return parent::response(array('calls'=>$calls, 'total'=>$total,'duration'=>$duration, 'more5' => $more5));

	}


    function reload_get()
    {

        
        if(!parent::require_login()) {
            return NULL;
        }

       copy('log.txt','log1.txt');

        $lines = file('log1.txt');
        $last = sizeof($lines) - 1 ;
        unset($lines[$last]);

        $fp = fopen('log1.txt', 'w');
        fwrite($fp, implode('', $lines));
        fclose($fp); 



        $this->db->where('id',1);
        $reload = $this->db->get('reloadcalls');

        if($reload->num_rows()==1){

            $row = $reload->row();

            $line = $row->line;

            $handler = fopen("log1.txt", "r+");

            fseek($handler,$line);

            $cuenta=0;

            while (!feof($handler)) {

                $frase=fgets($handler);

                $cuenta+=strlen($frase);
                
                if (strlen($frase)==0)
                break;

            
                $res=trim($frase);


                //$res = ereg_replace( "([     ]+)", " ", $res );
                $res = preg_replace('/\s\s+/', ' ', $res); //eliminamos mas de un espacion en blanco de la cadena
                $res=explode(" ",$res);

                if(empty($res)) continue;
                $res[5]=str_replace("'",":",$res[5]);

                
                if ($res[1]=="Date" || $res[0]=="--------------------------------------------------------------------------------"){

                }else{
                 
                    $car = explode(":", $res[5]);


                    $duration=$car[0]*3600+$car[1]*60+$car[2];

                    $this->db->where('number', $res[2]);
                    $this->db->join('extensions','extensions.id=extensions_id');
                    $commercial = $this->db->get('commercial');

                    if($commercial->num_rows() == 1){
                        $commercial_id = $commercial->row()->id;
                    }else $commercial_id = 0;
                            
                    list($day,$month,$year)=explode('/',$res[0]);
                    
                    $time='20'.$year.'-'.$month.'-'.$day;

                    $data_call = array(
                        'date' => $time,
                        'hour' => $res[1],
                        'ext' => $res[2],
                        'co' => $res[3],
                        'number' => $res[4],
                        'duration' => $duration,
                        'commercial_id' => $commercial_id,
                        );
                    $this->db->insert('calls', $data_call);
                }
            }
        }

        $total=$line+$cuenta;
        $fecha=date('d/m/Y H:m:s');
        $this->db->where('id',1);
        $this->db->update('reloadcalls', array('date' =>date('Y-m-d H:m:s'), 'line' => $total ));
        unlink('log1.txt');
        return parent::response(array('status' => 1));

    }

    
}
