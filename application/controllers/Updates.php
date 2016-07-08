<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reports extends CI_Controller {
	public function import_call()
	{
		
		$DB1 = $this->load->database('centralita', TRUE);
		$DB1->order_by('IdLlamada','ASC');
		$calls = $DB1->get('llamadas');
		foreach ($calls->result() as $row) {
			$data = array(
				'date'=>$row->Fecha,
				'hour' => $row->Hora,
				'ext' => $row->Ext,
				'co' => $row->Co,
				'number' => $row->Numero,
				'duration' => $row->Duracion,
				'commercial_id' => intval($row->IdUsuario)
			);
			$this->db->insert('calls', $data);
			# code...
		}
	}
	public function import_users()
	{
		
		$DB1 = $this->load->database('centralita', TRUE);
		//$DB1->limit(10);
		/*$DB1->where('Activo','1');
		$DB1->where('UExt >','0');
		$DB1->where('Nombre !=','');*/
		$comercial = $DB1->get('usuarios');
		foreach ($comercial->result() as $row) {
			if($row->UExt =="") $ext = 1; 
			else $ext = $row->UExt;
			$data = array(
				'id' => $row->Id,
				'name'=>$row->Nombre,
				'extensions_id' => $ext,
				'status' => 1
			);
			$this->db->insert('commercial', $data);
			# code...
		}
	}
	public function import_extensions()
	{
		
		$DB1 = $this->load->database('centralita', TRUE);
		//$DB1->limit(10);
		$DB1->order_by('IdExt','ASC');
		$comercial = $DB1->get('extensiones');
		foreach ($comercial->result() as $row) {
			$data = array(
				'number'=>$row->NExt,
				'status' => 1
			);
			$this->db->insert('extensions', $data);
			# code...
		}
	}
}

