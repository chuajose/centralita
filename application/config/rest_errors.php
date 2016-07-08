<?php
/*
|--------------------------------------------------------------------------
| Errors list
|--------------------------------------------------------------------------
|
| Set error for response
|
*/
$config['rest_error_code'][-1] = 'Excepcion';
$config['rest_error_code'][0] = ''; //Not exist error. All ok

//Autentication
$config['rest_error_code'][1] = 'Error validacion';
$config['rest_error_code'][2] = 'Login incorrecto';
$config['rest_error_code'][3] = 'El usuario demo no puede realizar esta accion';

/*
|--------------------------------------------------------------------------
| Set errors for users data
|--------------------------------------------------------------------------
|
| code from 100 to 199
|
*/
$config['rest_error_code'][100] = 'Datos incompletos o incorrectos';
$config['rest_error_code'][101] = 'No se ha insertado el usuario';
$config['rest_error_code'][102] = 'No se ha actualizado el usuario';
$config['rest_error_code'][103] = 'No se ha borrado el usuario';
$config['rest_error_code'][104] = 'Usuario no encontrado';
$config['rest_error_code'][105] = 'El mail del usuario no existe';


//Erros code to user preload
$config['rest_error_code'][191] = 'El email ya se encuentra en uso';


/*
|--------------------------------------------------------------------------
| Set errors for compnies data
|--------------------------------------------------------------------------
|
| code from 200 to 299
|
*/
$config['rest_error_code'][200] = 'Datos incompletos o incorrectos';
$config['rest_error_code'][201] = 'No se ha insertado la empresa';
$config['rest_error_code'][202] = 'No se ha actualizado la empresa';
$config['rest_error_code'][203] = 'No se ha borrado la empresa';
$config['rest_error_code'][204] = 'Empresa no encontrada';

//Erros code to company preload
$config['rest_error_code'][290] = 'Cif no encontrado en la base de datos de preload';
$config['rest_error_code'][291] = 'El Cif ya se encuentra en uso';
$config['rest_error_code'][292] = 'El Cif no es valido';

/*
|--------------------------------------------------------------------------
| Set errors for orders data
|--------------------------------------------------------------------------
|
| code from 300 to 399
|
*/
$config['rest_error_code'][300] = 'Datos incompletos o incorrectos';
$config['rest_error_code'][301] = 'No se ha insertado el Pedido';
$config['rest_error_code'][302] = 'No se ha actualizado el Pedido';
$config['rest_error_code'][303] = 'No se ha borrado el Pedido';
$config['rest_error_code'][304] = 'Pedido no encontrado';

$config['rest_error_code'][310] = 'Pedido ya denegado';
$config['rest_error_code'][311] = 'No tienes permiso para rechazar el pedido';


//Error for pay comision
$config['rest_error_code'][350] = 'No dispone de saldo';


/*
|--------------------------------------------------------------------------
| Set errors for commisions data
|--------------------------------------------------------------------------
|
| code from 400 to 499
|
*/
$config['rest_error_code'][400] = 'Datos incompletos o incorrectos';
$config['rest_error_code'][401] = 'No se ha insertado el Pedido';
$config['rest_error_code'][402] = 'No se ha actualizado el Pedido';
$config['rest_error_code'][403] = 'No se ha borrado el Pedido';
$config['rest_error_code'][404] = 'Comision no encontrado';
$config['rest_error_code'][405] = 'La comision no llega al valor mínimo';


/*
|--------------------------------------------------------------------------
| Set errors for files data
|--------------------------------------------------------------------------
|
| code from 500 to 599
|
*/
$config['rest_error_code'][500] = 'Datos incompletos o incorrectos';
$config['rest_error_code'][501] = 'No se ha insertado el fichero';
$config['rest_error_code'][502] = 'No se ha actualizado el cichero';
$config['rest_error_code'][503] = 'No se ha borrado el fichero';
$config['rest_error_code'][504] = 'fichero no encontrado';


/*
|--------------------------------------------------------------------------
| Set errors for partnerships data
|--------------------------------------------------------------------------
|
| code from 600 to 699
|
*/
$config['rest_error_code'][600] = 'Datos incompletos o incorrectos';
$config['rest_error_code'][601] = 'No se ha insertado la asociacion';
$config['rest_error_code'][602] = 'No se ha actualizado la asociacion';
$config['rest_error_code'][603] = 'No se ha borrado la asociacion';
$config['rest_error_code'][604] = 'asociacion no encontrado';


/*
|--------------------------------------------------------------------------
| Set errors for config data
|--------------------------------------------------------------------------
|
| code from 700 to 799
|
*/
$config['rest_error_code'][700] = 'El campo de configuración no es válido';

