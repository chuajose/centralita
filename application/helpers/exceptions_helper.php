<?php
if ( ! function_exists('notFoundRecord'))
{
	
    function notFoundRecord($exception)
    {
        $_error =& load_class('Exceptions', 'core');
        $_error->notFoundRecord($exception);
        exit;
    }
}
if ( ! function_exists('notValidationRecord'))
{
	
    function notValidationRecord($exception, $code = 1)
    {
    	$_error =& load_class('Exceptions', 'core');
        $_error->notValidationRecord($exception,$code);
        exit;
    }
}