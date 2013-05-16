<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactInfo extends DOM_Controller {
	
	public $user_id;
	public $directory_id;
	public $type;
	public $phone_id;
	public $email_id;
	public $address_id;
	
	public $owner;
	
	//auto get the user if the uid is detected
	public $user;
	public $contact;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');
		$this->load->model('system_contacts','syscontacts');
		
		$this->user_id = ((isset($_GET['uid'])) ? $_GET['uid'] : FALSE);
		$this->directory_id = ((isset($_GET['did'])) ? $_GET['did'] : FALSE);
		$this->type = ((isset($_GET['type'])) ? $_GET['type'] : FALSE);
		$this->phone_id = ((isset($_GET['pid'])) ? $_GET['pid'] : FALSE);
		$this->email_id = ((isset($_GET['eid'])) ? $_GET['eid'] : FALSE);
		$this->address_id = ((isset($_GET['aid'])) ? $_GET['aid'] : FALSE);
		
		$this->user = ((isset($_GET['uid'])) ? $this->administration->getMyUser($this->user_id) : FALSE);
		
		if((isset($_GET['did']))) { 
			$this->contact = $this->syscontacts->preparePopupInfo($this->directory_id);
			if(!empty($this->contact)) {
				$this->owner = $this->contact->OwnerID;	
				$this->type = $this->contact->OwnerType;
				$this->directory_id = $this->contact->ContactID;
			}
		}
    }
	
	public function load_phone_table() {
		$this->load->helper('contactinfo');
		$data = array(
			'contact' => (!empty($this->user)) ? $this->user : $this->contact,
		);
		$this->load->dom_view('pages/users/phone_listing',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function load_email_table() {
		$this->load->helper('contactinfo');
		$data = array(
			'uid'=>$this->user_id
		);
		$this->load->dom_view('pages/users/email_listing',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function Edit_phone_form() {
		$phone = $this->syscontacts->getSingleContactPhoneNumber($this->phone_id);
		$data = array(
			'phone'=>$phone
		);
		//print_object($phones);
		$this->load->dom_view('forms/users/edit_phone', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Add_phone_form() {
		
		$data = array(
			'did'=>$this->directory_id,
			'type'=>((isset($_GET['type'])) ? $_GET['type'] : $this->domcontacts->getContactType($this->directory_id))
		);
		//print_object($phones);
		$this->load->dom_view('forms/contactInfo/add_phone', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Add_email_form() {
		$data = array(
			'did'=>$this->directory_id,
			'type'=>$this->syscontacts->getContactType($this->directory_id)
		);
		
		$this->load->dom_view('forms/contactInfo/add_email', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Edit_email_form() {
		$email = $this->syscontacts->getSingleContactEmailAddress($this->email_id);
		$data = array(
			'email'=>$email
		);
		//print_object($phones);
		$this->load->dom_view('forms/contactInfo/edit_email', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Update_primary_phone() {
		$form = $this->input->post();
		//$pid,$did,$primary
		$reset_primary = $this->syscontacts->updatePrimaryPhone($form['phone_id'],$form['directory_id'],1);
		
		if($reset_primary) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function Update_primary_email() {
		$form = $this->input->post();
		//$pid,$did,$primary
		$reset_primary = $this->syscontacts->updatePrimaryEmail($form['email_id'],$form['directory_id'],1);
		
		if($reset_primary) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function Update_phone_number() {
		$pid = $this->input->post('phone_id');
		$number = $this->input->post('number');
		$type = $this->input->post('type');	
		
		$data = array(
			'PHONE_Number'=>$number,
			'PHONE_Type'=>$type
		);
				
		$update = $this->syscontacts->updateSingleContactPhoneNumber($pid,$data);
		if($update) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Add_phone_number() {
		$owner_id = $this->syscontacts->getOwnerIDByDID($this->directory_id);
		$owner_type = $this->syscontacts->getOwnerTypeBYDID($this->directory_id);
		$number = $this->input->post('number');
		$type = $this->input->post('type');	
		
		$data = array(
			'OWNER_Type'=>$owner_type,
			'OWNER_ID'=>$owner_id,
			'DIRECTORY_ID'=>$this->directory_id,
			'PHONE_Number'=>$number,
			'PHONE_Type'=>$type,
			'PHONE_Created'=>date('Y-m-d H:i:s'),
			'PHONE_Primary'=>(($this->syscontacts->checkIfContactPhoneExists($this->directory_id,$owner_id,$owner_type)) ? 0 : 1),
		);
		
		
		$add = $this->syscontacts->addSingleContactPhoneNumber($data);
		if($add) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Add_user_email() {
		
		$email = $this->input->post('email');
		$type = $this->input->post('type');	
		$otype = $this->input->post('owner_type');
		$oid = $this->syscontacts->getOwnerIDByDID($this->input->post('directory_id'));
		
		
		$data = array(
			'DIRECTORY_ID'=>$this->directory_id,
			'OWNER_Type'=>$otype,
			'OWNER_ID'=>$oid,
			'EMAIL_Address'=>$email,
			'EMAIL_Type'=>$type,
			'EMAIL_Created'=>date('Y-m-d H:i:s'),
			
		);
	
		print_object($data);
	/*
		$add = $this->syscontacts->addSingleEmailAddress($data);
		if($add) {
			echo '1';	
		}else {
			echo '0';
		}
		*/
	}

	
	public function Update_user_email() {
		$eid = $this->input->post('email_id');
		$email = $this->input->post('email');
		$type = $this->input->post('type');	
		
		$data = array(
			'EMAIL_Address'=>$email,
			'EMAIL_Type'=>$type
		);
		
				
		$update = $this->syscontacts->updateSingleEmailAddress($eid,$data);
		if($update) {
			echo '1';	
		}else {
			echo '0';
		}
		
	}
		
}
