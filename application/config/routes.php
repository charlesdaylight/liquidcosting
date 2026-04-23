<?php  if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );}
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller']   = 'liquidcp/frontend/index';
$route['404_override']         = 'default/zerror/index';
$route['uiformbuilder/(:any)'] = 'formbuilder/frontend/$1';
$route['liquidcp/login']       = 'liquidcp/account/login';
$route['liquidcp/signup']      = 'liquidcp/account/signup';
$route['liquidcp/logout']      = 'liquidcp/account/logout';
$route['liquidcp/account/authenticate'] = 'liquidcp/account/authenticate';
$route['liquidcp/account/create'] = 'liquidcp/account/create';
$route['liquidcp/dashboard']   = 'liquidcp/frontend/dashboard';
$route['liquidcp/studio']      = 'liquidcp/frontend/studio';
$route['liquidcp/estimate']    = 'liquidcp/frontend/estimate';
$route['liquidcp/save']        = 'liquidcp/frontend/save';
$route['liquidcp/quote/(:num)'] = 'liquidcp/frontend/quote/$1';
$route['liquidcp/pdf/(:num)']   = 'liquidcp/frontend/pdf/$1';
$route['admin/liquidcp']        = 'liquidcp/admin/index';
$route['admin/liquidcp/quotations'] = 'liquidcp/admin/quotations';
$route['admin/liquidcp/quotation/(:num)'] = 'liquidcp/admin/quotation/$1';
$route['admin/liquidcp/rules']  = 'liquidcp/admin/rules';
$route['admin/liquidcp/save-rules'] = 'liquidcp/admin/save_rules';
$route['admin/liquidcp/engine-runs'] = 'liquidcp/admin/engine_runs';

// ADMIN
$route['^(\w{2})/(.*)$']    = '$2';
$route['^(\w{2})$']         = $route['default_controller'];
$route['admin/login|admin'] = 'default/intranet/login';

/*
 End of file routes.php */
/* Location: ./application/config/routes.php */
