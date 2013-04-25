<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends DOM_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
		if(isset($_SESSION['google'])) {
			unset($_SESSION['google']);	
		}
		$this->session->unset_userdata('valid_user');
		//create cookie
		redirect('/','refresh');
	}
}