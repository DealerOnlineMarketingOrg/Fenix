<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

/*auth redirects*/
$route['login'] 						= 'auth/login';
$route['authenticate'] 					= 'auth/login/login_user';
$route['logout']						= 'auth/logout';
$route['reset_password']				= 'auth/password/processResetPass';
$route['signin']						= 'auth/login/login_user';
$route['log']							= 'auth/login/log_failed_attempt';
$route['check_pass']					= 'auth/password/checkIfPasswordIsGenerated';
$route['change_pass']					= 'auth/password/processChangePassword';
$route['change']						= 'auth/password/change';
$route['change_password_form']			= 'auth/password/loadChangePasswordForm';
$route['reset_password_form']			= 'auth/password/loadResetPasswordForm';
$route['lock_user']						= 'auth/login/breach_warning';
$route['google_connect']				= 'auth/google/connect';
$route['google_authenticate']			= 'auth/google/auth';
$route['example']						= 'auth/google/example';
$route['passwords']						= 'admin/passwords';
$route['passwords/(:any)']				= 'admin/passwords/$1';
$route['mysettings']					= 'user/settings';
$route['profile/avatar/upload']			= 'user/profile/upload_avatar';
$route['profile/(:any)']				= 'user/profile/view';
$route['profile/(:any)/(:any)']			= 'user/profile/view/$2';
$route['profile/update/userInfo']		= 'user/profile/update_UserInfo';
$route['profile/update/userContactInfo']= 'user/profile/update_UserContactInfo';

//admin redirects
$route['groups']						= 'admin/groups/index';
$route['groups/(:any)']					= 'admin/groups/$1';
$route['masterlist']					= 'admin/masterlist';

$route['clients']						= 'admin/clients';
$route['clients/edit/(:any)']			= 'admin/clients/edit';
$route['clients/(:any)']				= 'admin/clients/$1';
$route['client/(:any)']					= 'admin/clients/profile';

$route['users']							= 'admin/users';
$route['users/(:any)']					= 'admin/users/$1';

$route['contacts']						= 'admin/contacts/index';
$route['contacts/(:any)']				= 'admin/contacts/$1';

$route['agency']						= 'admin/agency';
$route['agency/(:any)']					= 'admin/agency/$1';

$route['dpr']							= 'reports/dpr';
$route['dpr/(:any)']					= 'reports/dpr/$1';

$route['vendors']						= 'admin/vendors/index';
$route['vendors/add']					= 'admin/vendors/add';
$route['vendors/edit']					= 'admin/vendors/edit';
$route['vendors/remove']				= 'admin/vendors/remove';

//redirects
$route['reputation']					= 'reputations/dashboard';
$route['bing']							= 'reputations/bing/dashboard';
$route['beta']							= 'updates';
$route['beta/changes']					= 'updates/load_table';
$route['beta/check']					= 'updates/check_db_for_new_updates';
$route['beta/count']					= 'updates/get_count';
$route['beta/remove']					= 'updates/remove_change';
$route['beta/edit']						= 'updates/load_change_form';
$route['beta/update']					= 'updates/update_change';
$route['add_release_change']			= 'updates/add';

$route['dashboard'] = 'admin/dashboard';

$route['default_controller']            = "admin/dashboard";
//$route['404_override'] 					= 'errors/file_not_found';
/* End of file routes.php */
/* Location: ./application/config/routes.php */