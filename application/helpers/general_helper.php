<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_helper{

	public function __construct($id=FALSE, $get_company = FALSE){

		$this->ci =& get_instance();

	}

}

function seconds_to_time($seg_ini) {

	$hours = floor($seg_ini/3600);
	$minutes = floor(($seg_ini-($hours*3600))/60);
	$seconds = $seg_ini-($hours*3600)-($minutes*60);
	return $hours.'h:'.$minutes.'m:'.$seconds.'s';

}

/*
 * Function to CACHE
 *
 *
 */
if ( ! function_exists('create_cache'))
{
	function create_cache ($name = "", $data = array(), $time = 600){
		$ci = &get_instance();

		if ($ci->cache->memcached->is_supported() && $ci->config->item('cache_use'))
		{
			$ci->cache->memcached->save($name,$data, $time);
		}
	}
}

if ( ! function_exists('get_cache'))
{
	function get_cache ($name = ""){
		$ci = &get_instance();

		if ($ci->cache->memcached->is_supported() && $ci->config->item('cache_use'))
		{

			 return $ci->cache->memcached->get($name);//conectase por telnet y para compraobar si exsties hacer get scache_id

		}
		else
		{
			return false;
		}
	}
}

if ( ! function_exists('delete_cache'))
{
	function delete_cache ($name = FALSE){
		$ci = &get_instance();
		
		if ($ci->cache->memcached->is_supported() && $ci->config->item('cache_use'))
		{
			if ( ! $name) //if not exist name, clean all cache
			{
				$ci->cache->memcached->clean();
			}
			elseif(get_cache($name)) 
			{
				return $ci->cache->memcached->delete($name);
			}
			else
			{
				return false;
			}
		}
	}
}




function script_in_background ($cmd) {
    
    if (substr(php_uname(), 0, 7) == "Windows"){
        $open=popen('start /B C:/xampp/php/php.exe '.FCPATH.'cli.php "'.$cmd.'"', "r");
        pclose($open);
    } else {

        $cmd='php '.FCPATH.'cli.php "'.$cmd.'" > /dev/null &';
       // echo $cmd;
        exec($cmd);
    }
}