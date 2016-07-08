<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	function __construct()
    {
        // Construct our parent class
        parent::__construct();
         $this->load->helper('call_helper');
        $this->load->helper('report_helper');


   }


	public function index($id = false)
	{
		
		$search = array();
        if($this->input->get('id')) $search['commercial_id'] = $this->input->get('commercial_id');

        if($this->input->get('from') || $this->input->get('to')) $search['date'] = array();
        if($this->input->get('from')) $search['date'][0] = date('Y-m-d',$this->input->get('from'));
        if($this->input->get('to')) $search['date'][1] = date('Y-m-d',$this->input->get('to'));
        if($this->input->get('users_id')) $users_id =$this->input->get('users_id'); else $users_id = 1; ;


       	if(empty($search)) die('necesitas algun parametro');

       	$reports = new Report();

       	$reports->save(
       		array(
       			'commercial_id' => $this->input->get('commercial_id'), 
       			'from' => date('Y-m-d',$this->input->get('from')), 
       			'to' => date('Y-m-d',$this->input->get('to')), 
       			'users_id' => $users_id,
       			'date' => date('Y-m-d')
       			)
       		);

        $call = new call();

        $call = $call->get_all(0, 9999999, $search);

        $total = $call['total'];
        $calls = $call['list'];
        $duration = $call['duration'];

		// output headers so that the file is downloaded rather than displayed
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="llamadas.csv"');
		 
		// do not cache the file
		header('Pragma: no-cache');
		header('Expires: 0');
		 
		// create a file pointer connected to the output stream
		$file = fopen('php://output', 'w');
		 
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
		exit();
	}

}
