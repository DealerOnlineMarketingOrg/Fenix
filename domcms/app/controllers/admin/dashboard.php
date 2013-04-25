<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		//$this->activeNav = 'admin';
    }

    public function index() {
		$this->LoadTemplate('pages/dashboard');
    }

}
