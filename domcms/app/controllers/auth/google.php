<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Google extends CI_Controller {

    public $user;
    public $token;
    public $code;
	
    public function __construct() {
        parent::__construct();
        $this->load->model('members');
    }
	
	public function connect() {
		require_once 'domcms/libraries/googleapi/Google_Client.php';
		require_once 'domcms/libraries/googleapi/contrib/Google_Oauth2Service.php';
		
		$client = new Google_Client();
		$client->setApplicationName("DOM CMS");
		
		// Visit https://code.google.com/apis/console?api=plus to generate your
		// client id, client secret, and to register your redirect uri.
		$client->setClientId(GoogleClientID);
		$client->setClientSecret(GoogleClientSecret);
		$client->setRedirectUri(base_url() . 'auth/google/connect');
		$client->setDeveloperKey(GoogleAPIKey);
		
		$oauth2 = new Google_Oauth2Service($client);
		
		if (isset($_GET['logout'])) {
		  unset($_SESSION['token']);
		}
		
		if(isset($_GET['error'])) {
			redirect('/','refresh');	
		}
		
		if (isset($_GET['code'])) {
		  if (strval($_SESSION['state']) !== strval($_GET['state'])) {
		    die("The session state did not match.");
		  }
		  $client->authenticate();
		  $_SESSION['token'] = $client->getAccessToken();
		  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
		}
		
		if (isset($_SESSION['token'])) {
		  $client->setAccessToken($_SESSION['token']);
		}
		
		if ($client->getAccessToken()) {
			
		  $user = $oauth2->userinfo->get();
		  //print "Your User information: <pre>" . print_r($user, true) . "</pre>";
		  
		  // The access token may have been updated lazily.
		  $_SESSION['token'] = $client->getAccessToken();
		  
		  if(isset($_SESSION['token'])) {
		  	$login_user = $this->members->AuthenticateGoogleUser($user['email'],$user['id']);
			
			if(isset($user['picture'])) {
				$avatar = $this->members->save_google_avatar($user['email'],$user['picture']); 
			} 
			//echo $login_user;
			  
			if($login_user) {
				$this->session->sess_write();
				//var_dump($login_user);
				redirect(base_url(),'refresh');
			}
		  }
		  
		  
		} else {
		  $state = mt_rand();
		  $client->setState($state);
		  $_SESSION['state'] = $state;
		
		  $authUrl = $client->createAuthUrl();
		  print '<script type="text/javascript">window.location.href = "' . $authUrl . '"; </script>';
		}		
	}	
	
	public function login_google_user() {
			
	}
	
	
}
