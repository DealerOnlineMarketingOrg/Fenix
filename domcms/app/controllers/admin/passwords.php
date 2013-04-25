<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Passwords extends DOM_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('administration');
        $this->load->helper('string_parser');
        $this->load->helper('msg');
		$this->activeNav = 'admin';
    }

    public function index() {
		//LOAD THE TEMPLATE
		$level = $this->user['DropdownDefault']->LevelType;
		if($level != 'c' AND $level != 3) {
			throwError(newError('Passwords', -1, 'Sorry, passwords are only allowed on the client level. Choose a client and try again.',0,''));	
			redirect('/','refresh');
		}
		$this->LoadTemplate('pages/passwords/listings');
    }
	
	public function load_table() {
		//LOAD THE TEMPLATE
		$this->load->dom_view('pages/passwords/table', $this->theme_settings['ThemeViews']);
	}
	
	private function getData($action) {
		$this->load->model('administration');

		$html = '';
		
		$types = $this->administration->getAllTypes();
		$vendors = $this->administration->getVendors();

		if ($action == 'edit') {
			$client_id = FALSE;
			$pwd_id = FALSE;
			if(isset($_POST['pwd_id'])) {
				$pwd_id = $this->input->post('pwd_id');
			}elseif(isset($_GET['pwdid'])) {
				$pwd_id = $this->input->get('pwdid');
			}else {
				$client_id = $this->user['DropdownDefault']->SelectedClient;
			}
		
			if ($client_id)
				$password = $this->administration->getPasswords($client_id);
			else
				$password = $this->administration->getPasswordsByID($pwd_id);

			if($password) {
				$data = array(
					'password' => $password[0],
					'html' => $html,
					'types' => $types,
					'vendors' => $vendors
				);
				return $data;
			} else
				return FALSE;
				
		} else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;
			$data = array(
				'clientID' => $client_id,
				'html' => $html,
				'types' => $types,
				'vendors' => $vendors
			);
			return $data;
		}
	}
	
	public function Add($msg=false) {
		$data = $this->getData('add');

		if ($data)
			$this->load->dom_view('forms/passwords/add', $this->theme_settings['ThemeViews'], $data);
		else
			echo 0;
	}
	
	public function Edit($msg=false) {
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
		/*
		if($level == 'g') {
			redirect('/groups/edit','refresh');	
		}
		
		if($level == 'a') {
			redirect('/agencies/edit','refresh');	
		}
		*/
		
		$data = $this->getData('edit');
		if ($data)
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
			$this->load->dom_view('forms/passwords/edit', $this->theme_settings['ThemeViews'], $data);
		else
			//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
			echo 0;
	}
	
	public function View($msg=false) {
		$contact = $this->administration->getContact($this->input->post('view_id'));
		$contact->Name = $contact->FirstName . ' ' . $contact->LastName;
		$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
		$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone,false,true) : false;
		$contact->Email = mod_parser($contact->Email,false,true);
		$data = array(
			'display'=>$contact
		);
		$this->LoadTemplate('pages/passwords/view',$data);
	}
	
	public function Process_edit() {
		$pass_id = $_GET['pass_id'];
		$form = $this->input->post();
		
		// Get ID of Type. Add new Type if needed.
		if ($form['radioType'] == 'old')
			$typeID = $form['types'];
		else {
			$typeID = $this->administration->hasPasswordType($form['newType']);
			if (!$typeID) {
				$data = array('PASS_TYPE_Name' => $form['newType']);
				$typeID = $this->administration->addPasswordType($data);
			}
		}
		
		// Get ID of Vendor. Add new Vendor if needed.
		if ($form['radioVendor'] == 'old')
			$vendorID = $form['vendors'];
		else {
			$vendorID = $this->administration->hasVendor($form['newVendor']);
			if (!$vendorID) {
				$data = array('VENDOR_Name' => $form['newVendor']);
				$vendorID = $this->administration->addVendor($data);
			}
		}
			
		// Save information.
		$data = array(
			'PASS_TypeID' => $typeID,
			'PASS_VendorID' => $vendorID,
			'PASS_LoginAddress' => $form['login_address'],
			'PASS_Username' => $form['username'],
			'PASS_Password' => $form['password'],
			'PASS_Notes' => $form['notes']
		);
		$pwds = $this->administration->editPassword($data, $pass_id);
		
		// Throw error and redirect back to Passwords.
		if ($pwds) {
			echo '1';
		}else{
			echo '0';
		}
	}
	
	public function process_add() {
		$form = $this->input->post();
		
		// Get ID of Type. Add new Type if needed.
		if ($form['radioType'] == 'old')
			$typeID = $form['types'];
		else {
			$typeID = $this->administration->hasPasswordType($form['newType']);
			if (!$typeID) {
				$data = array('PASS_TYPE_Name' => $form['newType']);
				$typeID = $this->administration->addPasswordType($data);
			}
		}
		
		// Get ID of Vendor. Add new Vendor if needed.
		if ($form['radioVendor'] == 'old')
			$vendorID = $form['vendors'];
		else {
			$vendorID = $this->administration->hasVendor($form['newVendor']);
			if (!$vendorID) {
				$data = array('VENDOR_Name' => $form['newVendor']);
				$vendorID = $this->administration->addVendor($data);
			}
		}
		
		// Save information.
		$data = array(
			'PASS_ClientID' => $form['ClientID'],
			'PASS_TypeID' => $typeID,
			'PASS_VendorID' => $vendorID,
			'PASS_LoginAddress' => $form['login_address'],
			'PASS_Username' => $form['username'],
			'PASS_Password' => $form['password'],
			'PASS_Notes' => $form['notes']
		);
		$pwds = $this->administration->addPasswords($data);
		
		// Throw error and redirect back to Passwords.
		if ($pwds) {
			echo '1';
		}else{
			echo '0';
		}
	}

}
