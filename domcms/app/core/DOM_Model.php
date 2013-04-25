<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class DOM_Model extends CI_Model {
		
		public $usr;
		
		public function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
			$this->usr = $this->session->userdata('valid_user');
		}
	}
