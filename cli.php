<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.s
 */
if (isset($_SERVER['REMOTE_ADDR'])) {
       die('Command Line Only!');
   }

   set_time_limit(0);

   $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'] = $argv[1];


   require dirname(__FILE__) . '/index.php';
//php /var/www/html/cli.php "controlador/metodo/parametros"
