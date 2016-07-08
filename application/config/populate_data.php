<?php

$config['populate']['users'][] = array( 'username' => 'admins', 'password' => '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36', 'email' => 'admins@admin.com', 'created_on' => time(), 'first_name'=> 'Jose','last_name'=>'Suarez');


$config['populate']['groups'][] = array( 'name' => 'admin', 'description' => 'administrators');
$config['populate']['groups'][] = array( 'name' =>'members', 'description' => 'General User');


//$config['populate']['users_groups'][] = array( 'user_id' =>1, 'group_id' => 1);
/*$config['populate']['users_groups'][] = array( 'user_id' =>2, 'group_id' => 2);

$config['populate']['countries'][]=array( 'name' => 'Spain');


$config['populate']['provinces'][] = array(	'name'=>'Alava','country_id'=>1);
$config['populate']['provinces'][] = array(	'name'=>'Albacete','country_id'=>1);
$config['populate']['provinces'][] = array(	'name'=>'Alicante','country_id'=>1);
$config['populate']['provinces'][] = array(	'name'=>'Almeria','country_id'=>1);
$config['populate']['provinces'][] = array(	'name'=>'Avila','country_id'=>1);

$config['populate']['categories'][] = array('name' => 'Comercio');
		

$config['populate']['companies'][] = array('name' => 'Circulo Gacela', 'address' => 'Fernando macías 35 1', 'city'=>'A Coruña', 'post_code'=>15005, 'cif'=> '44811936R', 'credit' => 5000, 'province_id'=>1, 'lat'=>0, 'lng'=>0, 'status'=>0,'category_id'=>1);
$config['populate']['companies'][] = array('name' => 'Codelab', 'address' => 'Fernando macías 35 1', 'city'=>'A Coruña', 'post_code'=>15005, 'cif'=> '44811936R', 'credit' => 8000, 'province_id'=>1, 'lat'=>0, 'lng'=>0, 'status'=>0, 'category_id'=>1);

*/
$config['populate']['users_companies'][] = array('user_id'=>1, 'company_id'=>2);
$config['populate']['users_companies'][] = array('user_id'=>2, 'company_id'=>3);