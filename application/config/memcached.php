<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: http://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
$config = array(
	'default' => array(
		'hostname' => 'localhost',
		'port'     => '11211',
		'weight'   => '1',
	),

);

$config_time = array(
	'timecahe_db' =>3600,
	'timecache_obj'=> 300,
	);