<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function validate_cif($cif){
		 $this->set_message('validate_cif', "Cif o nif no válido");
         $cif = strtoupper($cif);
		 $cif	=	str_replace(array(' ', '-', '.'),"",$cif);
            for ($i = 0; $i < 9; $i ++)
                $num[$i] = substr($cif, $i, 1);
        //si no tiene un formato valido devuelve error
            if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)){
                return false;
            }
        //comprobacion de NIFs estandar
            if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif))
                if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)){
                    return $cif;
                }else{
                    return false;
                }
            //algoritmo para comprobacion de codigos tipo CIF
            $suma = $num[2] + $num[4] + $num[6];
            for ($i = 1; $i < 8; $i += 2)
                $suma += substr((2 * $num[$i]),0,1) + substr((2 * $num[$i]),1,1);
            $n = 10 - substr($suma, strlen($suma) - 1, 1);
            //comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
            if (preg_match('/^[KLM]{1}/', $cif))
                if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)){
                    return $cif;
                }else{
                    return false;
                }
            //comprobacion de CIFs
            if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif))
                if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)){
                    return $cif;
                }else{
                    return false;
                }
            //comprobacion de NIEs
            //T
            if (preg_match('/^[T]{1}/', $cif))
                if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif)){
                    return $cif;
                }else{
                    return false;
                }
            //XYZ
            if (preg_match('/^[XYZ]{1}/', $cif))
                if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1)){
                    return $cif;
                }else{
                    return false;
                }
	}


    public function exist_code($code)
    {
        $this->set_message('exist_code', "El codigo no pertenece a ninguna empresa");

        $country = substr($code, 0, 3);


        $invitation_validation_code = $this->CI->config->item('invitation_validation_code');

        if($invitation_validation_code['founder'] == $code || $invitation_validation_code['no_founder'] == $code) return TRUE;


        //if($country != 205) return false;
        $code = (int)$code;

        $code = substr($code, 3); //delete 3 first numbers code
        $id =  (int)$code; 

        return isset($this->CI->db)
            ? ($this->CI->db->limit(1)->get_where('companies', array('id' => $id, 'deleted' => 0))->num_rows() === 1)
            : FALSE;
    }
}