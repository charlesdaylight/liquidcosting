<?php  if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );}
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
*/

$active_group  = 'default';
$active_record = true;

$db['default']['hostname'] = '127.0.0.1';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'liquid_cp';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = 'cepf_';
$db['default']['pconnect'] = false;
$db['default']['db_debug'] = true;
$db['default']['cache_on'] = false;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8mb4';
$db['default']['dbcollat'] = 'utf8mb4_unicode_ci';
$db['default']['swap_pre'] = '{PRE}';
$db['default']['autoinit'] = true;
$db['default']['stricton'] = false;


/*
 End of file database.php */
/* Location: ./application/config/database.php */
