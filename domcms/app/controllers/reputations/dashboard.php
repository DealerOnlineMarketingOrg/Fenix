<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->activeNav = 'reputation';
		$this->load->model('reputation_api','social_api');
    }

    public function index() {
		
		$yelp = array();
		$google = array();
		$yahoo = array();
		$bing = array();
		
		//$yelpData = json_decode(file_get_contents('http://api.yelp.com/phone_search?phone=7035335046&ywsid=' . $yelpKey));
		$yelpData = $this->social_api->getYelpData();
		$bingData = $this->social_api->getBingData();
		/*
		| Yelp Review vars
		*/
		print_object($bingData);	
		
		//$this->LoadTemplate('pages/reputations/dashboard');
    }

}
