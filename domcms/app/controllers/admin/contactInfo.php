<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactInfo extends DOM_Controller {

	public $agency_id;
	public $id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->client_id = $this->user['DropdownDefault']->SelectedClient;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }
	
    public function index() { }
	
	public function Load_table($id=false,$type=false) {
		if(isset($_GET['id']))
			$id = $_GET['id'];
		if(isset($_GET['type']))
			$type = $_GET['type'];
		if(isset($_GET['page']))
			$page = $_GET['page'];			
		
		$contact = $this->administration->getContactByTypeID($type,$id);
		$contact->Email = mod_parser($contact->Email,false,true);
		$contact->Phone = mod_parser($contact->Phone,false,true);
		$data = array(
			'contact'=>$contact,
			'type'=>$type,
			'page'=>$page,
		);
		$this->load->dom_view('pages/contactInfo/table', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function FormPhone() {
		$form = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 'add';
			$id = '';
		}

		$id = $form['contact_id'];		
		$type = $form['type'];
		$oldValue = $form['old'];
		$newValue = $form['phone'];
		$phone = $type.':'.$newValue;

		if ($page == 'edit')
			$ret = $this->administration->editContactInfoPhone($id, $oldValue, $phone);
		else
			$ret = $this->administration->addContactInfoPhone($id, $phone);
		if($id && $ret) {
			echo '1';
		}else {
			echo '0';
		}
	}
	
	public function FormEmail() {
		$form = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['page'])) {
			$page = $_GET['page'];
		} else {
			$page = 'add';
			$id = '';
		}

		$id = $form['contact_id'];		
		$type = $form['type'];
		$oldValue = $form['old'];
		$newValue = $form['email'];
		$email = $type.':'.$newValue;

		if ($page == 'edit')
			$ret = $this->administration->editContactInfoEmail($id, $oldValue, $email);
		else
			$ret = $this->administration->addContactInfoEmail($id, $email);
		if($id && $ret) {
			echo '1';
		}else {
			echo '0';
		}
	}

	public function PhoneAdd() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		
		$contact = $this->administration->getContact($id);
		$contact->TypeCode = substr($contact->Type,0,3);
		$contact->TypeID = substr($contact->Type,4);
		
		$data = array(
			'page'=>'add',
			'contact'=>$contact,
			'type'=>'',
		);
		
		$this->load->dom_view('forms/contactInfo/edit_add_phone', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function PhoneEdit() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$type = $_GET['type'];
			$value = $_GET['value'];
		}
		
		$contact = $this->administration->getContact($id);
		$contact->TypeCode = substr($contact->Type,0,3);
		$contact->TypeID = substr($contact->Type,4);
		
		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'type'=>$type,
				'value'=>$value,
			);	
			$this->load->dom_view('forms/contactInfo/edit_add_phone', $this->theme_settings['ThemeViews'], $data);
		}else {
			echo '0';	
		}		
	}
	
	public function EmailAdd() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
		}
		
		$contact = $this->administration->getContact($id);
		$contact->TypeCode = substr($contact->Type,0,3);
		$contact->TypeID = substr($contact->Type,4);
		
		$data = array(
			'page'=>'add',
			'contact'=>$contact,
			'type'=>'',
		);
		
		$this->load->dom_view('forms/contactInfo/edit_add_email', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function EmailEdit() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$type = $_GET['type'];
			$value = $_GET['value'];
		}
		
		$contact = $this->administration->getContact($id);
		$contact->TypeCode = substr($contact->Type,0,3);
		$contact->TypeID = substr($contact->Type,4);

		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'type'=>$type,
				'value'=>$value,
			);
			$this->load->dom_view('forms/contactInfo/edit_add_email', $this->theme_settings['ThemeViews'], $data);
		}else {
			echo '0';	
		}		
	}

	public function FormPrimary() {
		
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$phonePrimary = $_GET['phone'];
			$emailPrimary = $_GET['email'];
		} else
			$id = '';
			
		$ret = $this->administration->editPrimaryPhoneEmail($id, $phonePrimary, $emailPrimary);
		
		if($id && $ret) {
			echo '1';	
		}else {
			echo '0';
		}
	}
}
