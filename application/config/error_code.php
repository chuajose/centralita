<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['error_code'][1] = array ("http_code"=>401, "error"=> "Unauthorized");
$config['error_code'][2] = array ("http_code"=>400, "error"=> "ValidationError");
$config['error_code'][3] = array ("http_code"=>400, "error"=> "BadResponse");
$config['error_code'][4] = array ("http_code"=>403, "error"=> "Forbidden");
$config['error_code'][5] = array ("http_code"=>404, "error"=> "Not found");
