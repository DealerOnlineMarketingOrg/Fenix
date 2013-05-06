<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contacts extends DOM_Controller {

	public $agency_id;
	public $contact_id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');
		$this->load->model('system_contacts');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
		$this->group_id = $this->user['DropdownDefault']->SelectedGroup;
		$this->client_id = $this->user['DropdownDefault']->SelectedClient;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

	private function formatContactInfo(&$contact) {
		$contact->Name = $contact->FirstName . ' ' . $contact->LastName;
		$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
		$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone,false,true) : false;
		// Locate primary.
		$contact->PrimaryPhone = '';
		foreach ($contact->Phone as $contactPhone) foreach ($contactPhone as $type => $phone) {
			if ($phone == $contact->PrimaryPhoneType) {
				$contact->PrimaryPhone = $phone;
				break;
			}
		}
		$contact->Email = mod_parser($contact->Email,false,true);
		// Locate primary.
		$contact->PrimaryEmail = '';
		foreach ($contact->Email as $contactEmail) foreach ($contactEmail as $type => $email) {
			if ($email == $contact->PrimaryEmailType) {
				$contact->PrimaryEmail = $email;
				break;
			}
		}
		$contact->Parent = $contact->Dealership;
		$contact->TypeCode = substr($contact->Type,0,3);
		$contact->TypeID = substr($contact->Type,4);
	}
	
    public function index() {
		$this->load->helper('contacts');
		$this->LoadTemplate('pages/contacts/listing');
    }
	
	public function test() {
		$table = $this->system_contacts->buildContactTable();
		print_object($table);
	}
	
	public function load_table() {
		$contacts = $this->administration->getAllContactsInAgency($this->agency_id);
		$data = array(
			'contacts'=>$contacts
		);
		$this->load->dom_view('pages/contacts/table', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function View() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$type = $_GET['type'];
		}
		
		$contact = $this->administration->getContactByTypeID($type, $id);
		$contact_id = $contact->ContactID;
		$this->formatContactInfo($contact);
		
		if($contact) {
			$data = array(
				'contact' => $contact,
				'level' => $this->user['DropdownDefault']->LevelType,
				'websites'=>WebsiteListingTable($id, $type, false),
				'contactInfo'=>ContactInfoListingTable($contact, $type),
			);
			$this->load->dom_view('pages/contacts/view', $this->theme_settings['ThemeViews'], $data);
		}
	}
	
	public function Form() {
		$contact_data = $this->security->xss_clean($this->input->post());
		
		if(isset($_GET['GID']))
			$contact_id = $_GET['GID'];
		else
			$contact_id = '';
		
		$type      = $contact_data['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
		$firstname = $contact_data['firstname'];
		$lastname  = $contact_data['lastname'];
		$address   = 'street:' . $contact_data['street'] . ',city:' . $contact_data['city'] . ',state:' . $contact_data['state'] . ',zipcode:' . $contact_data['zip'];
		$notes     = $contact_data['notes'];

		//prepare the add/update
		$data = array(
			'TITLE_ID' => $contact_data['jobTitleType'],
			'DIRECTORY_Type' => $type,
			'CLIENT_Owner' => ($contact_data['type'] == 'CID') ? $contact_data['parentClient'] : (($contact_data['type'] == 'VID') ? $contact_data['parentVendor'] : NULL),
			'DIRECTORY_FirstName' => $firstname,
			'DIRECTORY_LastName' => $lastname,
			'DIRECTORY_Address' => $address,
			'DIRECTORY_Notes' => $notes,
		);
		if ($contact_id) {
			$contact = $this->administration->updateContact($contact_id,$data);
		} else {
			$primaryEmail = $contact_data['email'];
			$email     = 'work:' . $primaryEmail;
			$primaryPhone = $contact_data['phone'];
			$phone     = 'work:' . $primaryPhone;

			$data['DIRECTORY_Created'] = date(FULL_MILITARY_DATETIME);
			$data['DIRECTORY_Email'] = $email;
			$data['DIRECTORY_Primary_Email'] = $primaryEmail;
			$data['DIRECTORY_Phone'] = $phone;
			$data['DIRECTORY_Primary_Phone'] = $primaryPhone;
			$contact = $this->administration->addContact($data);
		}
		
		if($contact) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Add() {
		$clients = $this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$vendors = $this->administration->getAllVendors();
		$types = $this->administration->getTypeList();
		$tags = $this->administration->getAllTags();  

		$data = array(
			'page'=>'add',
			'contact'=>false,
			'clients'=>$clients,
			'vendors'=>$vendors,
			'types'=>$types,
			'tags'=>$tags,
			'websites'=>'',
			'contactInfo'=>'',
		);
		
		$this->load->dom_view('forms/contacts/edit_add', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function Edit() {
		if(isset($_GET['id'])) {
			$id = $_GET['id'];
			$type = $_GET['type'];
		}
		
		$contact = $this->administration->getContactByTypeID($type, $id);
		$contact_id = $contact->ContactID;
		$this->formatContactInfo($contact);
		$clients = $this->administration->getAllClientsInAgency($this->user['DropdownDefault']->SelectedAgency);
		$vendors = $this->administration->getAllVendors();
		$types = $this->administration->getTypeList();
		$tags = $this->administration->getAllTags();

		if($contact) {
			$data = array(
				'page'=>'edit',
				'contact'=>$contact,
				'clients'=>$clients,
				'vendors'=>$vendors,
				'types'=>$types,
				'tags'=>$tags,
				'websites'=>WebsiteListingTable($id, $type),
				'contactInfo'=>ContactInfoListingTable($contact, $type, true),
			);
			$this->load->dom_view('forms/contacts/edit_add', $this->theme_settings['ThemeViews'], $data);
		}else {
			echo '0';	
		}
	}
	
}
