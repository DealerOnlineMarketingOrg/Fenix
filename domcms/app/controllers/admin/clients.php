<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends DOM_Controller {

	public $agency_id;
	public $group_id;
	public $client_id;

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->helper('msg');
        $this->load->helper('template');
        $this->load->helper('string_parser');
		$this->load->model('administration');
		$this->load->helper('websites');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->activeNav = 'admin';
		
		if(isset($_GET['cid'])) {
			$this->client_id = $_GET['cid'];
		}
    }
	
	public function load_table($return = false) {
		$this->load->helper('template');
		$html = '';
		$level = $this->user['DropdownDefault']->LevelType;
		$clients = $this->_getClientsByDropdownLevel($level);		
		
		$data = array(
			'clients'=>$clients
		);
		
		$this->load->dom_view('pages/clients/table', $this->theme_settings['ThemeViews'], $data);
	}
	
	private function _getClientsByDropdownLevel($level = false) {
		switch($level) {
			case 1:
				return $this->administration->getAllClientsInAgency($this->agency_id);
			break;
			case 2:
				return $this->administration->getAllClientsInGroup($this->group_id);
			break;
			case 3:
				return $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient);
			break;
			case 'a':
				return $this->administration->getAllClientsInAgency($this->agency_id);
			break;
			case 'g':
				return $this->administration->getAllClientsInGroup($this->group_id);
			break;
			case 'c':
				return $this->administration->getClientByID($this->user['DropdownDefault']->SelectedClient);
			break;	
			default:
				return $this->administration->getAllClientsInAgency($this->agency_id);
			break;
		}
	}

    public function index() {
    	$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		$clients = $this->_getClientsByDropdownLevel($level);		

		$data = array(
			'clients'=>$clients			
		);
		
		//LOAD THE TEMPLATE
		$this->LoadTemplate('pages/clients/listing', $data);
    }
	
	public function form() {
		$address_data = array(
			'ADDRESS_Street'=>$this->security->xss_clean($this->input->post('street')),
			'ADDRESS_State'=>$this->security->xss_clean($this->input->post('state')),
			'ADDRESS_City'=>$this->security->xss_clean($this->input->post('city')),
			'ADDRESS_Zip'=>$this->security->xss_clean($this->input->post('zip')),
			'ADDRESS_Type'=>'work',
			'ADDRESS_Primary'=>1
		);
		
		$phone_data = array(
			'PHONE_Number'=>$this->security->xss_clean($this->input->post('phone')),
			'PHONE_Primary'=>1,
			'PHONE_Type'=>'work'
		);
		
		$client_data = array(
			'CLIENT_Name'=>$this->security->xss_clean($this->input->post('ClientName')),
			'CLIENT_Notes'=>$this->security->xss_clean($this->input->post('Notes')),
			'CLIENT_Code'=>$this->security->xss_clean($this->input->post('ClientCode')),
			'CLIENT_Tag'=>$this->security->xss_clean($this->input->post('tags')),
			'GROUP_ID'=>$this->security->xss_clean($this->input->post('Group')),
			'CLIENT_Active'=>$this->security->xss_clean($this->input->post('status')),
		);		
		
		if(isset($_POST['ClientID'])) {
			$client_data['CLIENT_ID'] = $this->security->xss_clean($_POST['ClientID']);	
			$phone_data['PHONE_ID'] = $this->security->xss_clean($this->input->post('phone_id'));
			$address_data['ADDRESS_ID'] = $this->security->xss_clean($this->input->post('address_id'));
		}else {
			$client_data['CLIENT_Created'] = date(FULL_MILITARY_DATETIME);	
			$address_data['ADDRESS_Created'] = date(FULL_MILITARY_DATETIME);
			$phone_data['PHONE_Created'] = date(FULL_MILITARY_DATETIME);
		}
		
		$data = array(
			'DirectoryAddresses' => $address_data,
			'PhoneNumbers'=>$phone_data,
			'Clients'=>$client_data
		);
		
		if(isset($_POST['ClientID'])) {
			$update = $this->administration->updateClient($_POST['ClientID'],$data);
			if($update) {
				echo '1';	
			}else {
				echo '0';	
			}
		}else {
			$add = $this->administration->addClient($data);
			if($add) {
				echo '1';	
			}else {
				echo '0';	
			}
		}
	}
	
	public function Add() {
		$html = '';
		$tags = $this->administration->getAllTags();  
		$groups = $this->administration->getAllGroupsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$data = array(
			'html' => $html,
			'tags' => $tags,
			'groups'=>$groups
		);

		$this->LoadTemplate('forms/clients/edit_add',$data);
	}
    
    public function add_form() {
		$tags = $this->administration->getAllTags();    
		$groups = $this->administration->getGroupsForDropdown($this->user['DropdownDefault']->SelectedAgency);

		$data = array(
			'client'=>false,
			'tags'=>$tags,
			'groups'=>$groups
		);
		
		$this->load->dom_view('forms/clients/edit_add', $this->theme_settings['ThemeViews'], $data);
    }
	
	public function Edit() {
		$this->load->helper('contacts');
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
		$html = '';
	
		if(isset($_POST['client_id'])) {
			$client_id = $this->input->post('client_id');
		}elseif(isset($_GET['cid'])) {
			$client_id = $this->input->get('cid');
		}else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;
		}
	
		 //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		//$client_id = ($this->input->post('client_id'))?$this->input->post('client_id'):$this->user['DropdownDefault']->SelectedClient;
		$this->load->model('administration');
		$client = $this->administration->getSelectedClient($client_id);
		$client->Addresses = 
		$client->ContactID = $client_id;
		$tags = $this->administration->getAllTags();    
		$groups = $this->administration->getAllGroupsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$client->TypeCode = 'CID';
		$client->TypeID = $client_id;
		
		if($client) {
			$client->Addresses = $this->administration->getSelectedClientAddress($client_id);
			$client->Phones = $this->administration->getSelectedClientPhones($client_id);
			
			$client->Reviews = array(
				'Google'   => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->URL : FALSE,
				'GoogleID' => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->ID  : FALSE,
				'Yelp'     => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->URL : FALSE,
				'YelpID'   => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->ID  : FALSE,
				'Yahoo'    => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->URL : FALSE,
				'YahooID'  => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->ID  : FALSE
			);
			
			$data = array(
				'groups' => $groups,
				'client' => $client,
				'client_id'=>$client->ClientID,
				'html' => $html,
				'tags'=>$tags,
				'websites'=>true,
			);
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
			$this->load->dom_view('forms/clients/edit_add', $this->theme_settings['ThemeViews'], $data);		
		}else {
			//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
			print 0;
		}
	}
	
	
	public function View() {	
		$this->load->helper('contacts');		
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
		$html = '';
	
		if(isset($_POST['client_id'])) {
			$client_id = $this->input->post('client_id');
		}elseif(isset($_GET['cid'])) {
			$client_id = $this->input->get('cid');
		}else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;
		}
	
		 //WE POST WHAT AGENCY WERE EDITING, THIS IS THE ID IN THE DB.
		//$client_id = ($this->input->post('client_id'))?$this->input->post('client_id'):$this->user['DropdownDefault']->SelectedClient;
		$this->load->model('administration');
		$client = $this->administration->getSelectedClient($client_id);
		$client->ContactID = $client_id;
		$tags = $this->administration->getAllTags();    
		$groups = $this->administration->getAllGroupsInAgency($this->user['DropdownDefault']->SelectedAgency);
		
		$client->TypeCode = 'CID';
		$client->TypeID = $client_id;
		
		if($client) {
			$client->Address = (isset($client->Address)) ? mod_parser($client->Address) : false;
			$client->Phone = (isset($client->Phone)) ? mod_parser($client->Phone,false,true) : false;
			$client->Reviews = array(
				'Google'   => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->URL : FALSE,
				'GoogleID' => ($this->administration->getSelectedClientsReviews($client_id,1)) ? $this->administration->getSelectedClientsReviews($client_id,1)->ID  : FALSE,
				'Yelp'     => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->URL : FALSE,
				'YelpID'   => ($this->administration->getSelectedClientsReviews($client_id,2)) ? $this->administration->getSelectedClientsReviews($client_id,2)->ID  : FALSE,
				'Yahoo'    => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->URL : FALSE,
				'YahooID'  => ($this->administration->getSelectedClientsReviews($client_id,3)) ? $this->administration->getSelectedClientsReviews($client_id,3)->ID  : FALSE
			);
			$data = array(
				'view'=>true,
				'groups' => $groups,
				'client' => $client,
				'html' => $html,
				'tags'=>$tags,
				'websites'=>true,
				'contacts'=>true,
				'contactInfo'=>ContactInfoListingTable($client, 'CID'),
			);
			//
      		$this->load->dom_view('forms/clients/edit_add', $this->theme_settings['ThemeViews'], $data);
		}
	}

}
