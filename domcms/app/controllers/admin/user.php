<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Password extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('members');
		$this->load->helper('msg');	
	}

	public function Reset() {
		
	}
	
	public function Change() {
		$name = $this->input->post('name');
		$username = $this->input->post('username');
		$company = $this->input->post('company');
		$address = $this->input->post('address');
		$security = $this->input->post('security');
	}

}