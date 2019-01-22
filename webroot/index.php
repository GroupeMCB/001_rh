<?php  

//A commenter lorsqu'en production
error_reporting(E_ALL);
ini_set('display_errors',TRUE);
ini_set('display_startup_errors', TRUE); 

define ('WEBROOT',dirname(__FILE__));
define ('ROOT', dirname(WEBROOT));
define ('DS', DIRECTORY_SEPARATOR);
define ('CORE',ROOT.DS.'core');
define ('BASE_URL', dirname(dirname($_SERVER['SCRIPT_NAME'])) );


require CORE.DS.'includes.php';
new Dispatcher();
 ?>