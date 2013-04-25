<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ','rb');
define('FOPEN_READ_WRITE','r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE','wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE','w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE','ab');
define('FOPEN_READ_WRITE_CREATE','a+b');
define('FOPEN_WRITE_CREATE_STRICT','xb');
define('FOPEN_READ_WRITE_CREATE_STRICT','x+b');

/*
|---------------------------------------------------------------------
| STRING CONSTANTS AVAILABLE APP WIDE
|---------------------------------------------------------------------
|
| These are strings available anywhere to any Model, View or Controller
|
**********************************************************************/

define('SITETITLE','Dealer Online Marketing | Content Manager');
define('COMPANYNAME','Dealer Online Marketing');
define('COMPANYURLLINK','<a href="http://www.dealeronlinemarketing.com" target="_blank">www.dealeronlinemarketing.com</a>');
define('COMPANYURL','http://www.dealeronlinemarketing.com');
define('THEMEDIR','itsbrain');
define('THEMEIMGS','imgs/');
define('GLOBALIMGS','imgs/global/imgs/');
define('DOMDIR','Global');
define('COPYRIGHT', '&copy; Copyright 2013 DealerOnlineMarketing.com. All Rights Reserved.');
define('LOGO', '<img src="imgs/login_logo.png" alt="Dealer Online Marketing" />');
define('APPNAME','DOM CMS');
define('VERSION','Beta 0.2');
define('GAPIEMAIL','');
define('GAPIPASS','');
define('GOOGLEFONTS','Abel|Open+Sans+Condensed:300');
define('GoogleClientID','170027429160.apps.googleusercontent.com');
define('GoogleClientSecret','kMAavg_InUyakJwcHolLQsFn');
define('GoogleAPIKey','AIzaSyCPrBMaamOoxDEuwb0Y65mIKdjDQuZX9CY');


/*
|----------------------------------------------------------------------
| BOOLEAN CONSTANTS TO TURN FEATURES ON AND OFF GLOBALLY
|----------------------------------------------------------------------
|
| These are lists of features that are able to be turned off by a simple boolean change
|
***********************************************************************/

define('DROPDOWNS',true);
define('CLIENTFILTER',true);
define('TAGFILTER',true);
define('SEARCH',false);
define('BREADCRUMBS',true);
define('USERNAVIGATION',false);
define('BOOKMARKS',true);
define('ACCOUNT',true);
define('SETTINGS',true);
define('PRIVACY',true);
define('APPROVALS',true);
define('MESSAGES',true);
define('OFFLINE',false);
define('OAUTH',false);
define('FLUIDLAYOUT',false);
/*
|-----------------------------------------------------------------------
| DATE TIME TEMPLATES
|-----------------------------------------------------------------------
|
| These are date templates that can be used as templates in the date() function 
|
************************************************************************/

define('FULL_MILITARY_DATETIME','Y-m-d H:i:s'); //DISPLAYS IN 24 HOUR FORMAT
define('FULL_NORMAL_DATETIME', 'Y-m-d h:i:s a'); //DISPLAYS IN 12 HOUR FORMAT WITH AM OR PM, ALL NUMBERS
define('FULL_TEXT_DATETIME','l F NS, Y \a\t g:i a'); //DISPLAYS TEXT DATETIME like Sunday January 1st 2012 at 1:00 am

/* End of file constants.php */
/* Location: ./application/config/constants.php */