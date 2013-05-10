<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends DOM_Controller {

	public $agency_id;
	public $contact_id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');
		$this->load->model('system_contacts');
		$this->load->helper('websites');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->client_id = $this->user['DropdownDefault']->SelectedClient;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
		
		$this->contact_id = ((isset($_GET['did'])) ? $_GET['did'] : FALSE);
    }
	
    public function index() {
		$this->load->helper('contacts');
		$this->LoadTemplate('pages/contacts/listing');
    }
	
	public function load_table() {
		$this->load->helper('contacts');
		$this->load->dom_view('pages/contacts/table', $this->theme_settings['ThemeViews']);
	}
	
	public function View() {
		$this->load->model('system_contacts','domcontacts');
		$this->load->helper('contactinfo');
		$data = array(
			'clients'=>$this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency),
			'vendors'=>$this->administration->getVendors(),
			'contact'=>$this->domcontacts->preparePopupInfo($this->contact_id),
			'jobtitles'=>$this->domcontacts->getJobTitles()
		);
		$this->load->dom_view('forms/contacts/view',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function Add() {
		$this->load->model('system_contacts','domcontacts');
		$clients = $this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$types = $this->administration->getTypeList();

		$data = array(
			'clients'=>$this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency),
			'vendors'=>$this->administration->getVendors(),
			'types'=>$types,
			'jobtitles'=>$this->domcontacts->getJobTitles()
		);
		
		$this->load->dom_view('forms/contacts/edit_add', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function Edit() {
		$this->load->model('system_contacts','domcontacts');
		$this->load->helper('contactinfo');
		$data = array(
			'clients'=>$this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency),
			'vendors'=>$this->administration->getVendors(),
			'contact'=>$this->domcontacts->preparePopupInfo($this->contact_id),
			'jobtitles'=>$this->domcontacts->getJobTitles()
		);
		$this->load->dom_view('forms/contacts/edit',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function Process_add() {
		$this->load->model('system_contacts','domcontacts');
		//the post of the form vars
		$form = $this->input->post();
		
		//prepare the directories table
		$directory_add = array(
			'TITLE_ID' 				=> $form['job_title'],
			'JobTitle' 				=> $this->domcontacts->getJobTitleText($form['job_title']),
			'DIRECTORY_Type' 		=> $form['owner_type'],
			'OWNER_ID' 				=> (($form['owner_type'] <= 2) ? (($form['owner_type'] == 1) ? $form['client_id'] : $form['vendor_id']) : $form['owner_id']),
			'DIRECTORY_FirstName' 	=> $form['firstname'],
			'DIRECTORY_LastName' 	=> $form['lastname'],
			'DIRECTORY_Notes' 		=> $form['notes'],
		    'DIRECTORY_Created' 	=> date('Y-m-d H:i:s')
		);
		
		//prepare the phone table
		$phone_add = array(
			'OWNER_ID'				=> $directory_add['OWNER_ID'],
			'OWNER_Type'			=> $form['owner_type'],
			'PHONE_Number'			=> $form['phone'],
			'PHONE_Type'			=> $form['phone_type'],
			'PHONE_Primary'			=> 1,
			'PHONE_Active'			=> 1,
			'PHONE_Created'			=> date('Y-m-d H:i:s')
		);	
		
		//prepare the address table
		$address_add = array(
			'OWNER_ID'				=> $directory_add['OWNER_ID'],
			'OWNER_Type'			=> $form['owner_type'],
			'ADDRESS_Street'		=> $form['street'],
			'ADDRESS_City'			=> $form['city'],
			'ADDRESS_State'			=> $form['state'],
			'ADDRESS_Zip'			=> $form['zip'],
			'ADDRESS_Type'			=> 'work',
			'ADDRESS_Active'		=> 1,
			'ADDRESS_Primary'		=> 1,
			'ADDRESS_Created'		=> date('Y-m-d H:i:s')
		);
		$data = array(
			'PhoneNumbers' 			=> $phone_add,
			'DirectoryAddresses' 	=> $address_add,
			'Directories'		 	=> $directory_add
		);
		
		$add = $this->domcontacts->addContact($data);
		
		if($add) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Process_edit() {
		$this->load->model('system_contacts','domcontacts');
		$form = $this->input->post();
		
		$address_id   = (($form['address_id'] != '-1') ? $form['address_id'] : $this->domcontacts->insertBlankAddress($form['owner_id'],$form['owner_type'],$form['directory_id']));
		$directory_id = $form['directory_id'];
		
		$address = array(
			'ADDRESS_Street' 	  => $form['street'],
			'ADDRESS_City' 		  => $form['city'],
			'ADDRESS_State' 	  => $form['state'],
			'ADDRESS_Zip' 		  => $form['zip'],
			'ADDRESS_Active' 	  => 1
		);
		
		$directory_info = array(
			'DIRECTORY_FirstName' => (string)$form['firstname'],
			'DIRECTORY_LastName'  => (string)$form['lastname'],
			'DIRECTORY_Type'      => (int)$form['owner_type'],
			'OWNER_ID'            => (int)(($form['owner_type'] <= 2) ? (($form['owner_type'] == 1) ? $form['client_id'] : $form['vendor_id']) : $form['owner_id']),
			'TITLE_ID'            => (int)$form['job_title'],
			'JobTitle'            => (string)$this->domcontacts->getJobTitleText($form['job_title']),
			'DIRECTORY_Notes'     => (string)$form['notes']
		);
		
		$update_address = $this->domcontacts->updatePrimaryAddress($address_id,$address);
		$updateInfo 	= $this->domcontacts->updateDirectoryInformation($directory_id,$directory_info);
		
		//echo 'Did the address update? ' . (($update_address) ? 'Yes!' : 'No :(') . ' Did the Directory Information update? ' . (($updateInfo) ? 'Yes!' : ' No :(');		
		if($update_address AND $updateInfo) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
}
