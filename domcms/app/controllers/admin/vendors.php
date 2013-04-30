<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendors extends DOM_Controller {
	
	//this is used when we know the vendor
	public $vid;
	
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('administration'));
        $this->load->helper(array('msg','html'));
		$this->activeNav = 'admin';
		
		$this->vid = ((isset($_GET['vid'])) ? $_GET['vid'] : FALSE);
    }
	
	public function index() {
		$this->LoadTemplate('pages/vendors/listing');
	}
	
	public function Load_table() {
		$this->load->dom_view('pages/vendors/table', $this->theme_settings['ThemeViews']);	
	}
	
	public function add() {
		//were loading this page on a popup
		$this->load->dom_view('forms/vendors/add_edit_view', $this->theme_settings['ThemeViews']);
	}
	
	public function edit() {
		$vendor = $this->administration->getVendors($_GET['vid']);
		
		$data = array(
			'vendor' => $vendor[0],
		);
		$this->load->dom_view('forms/vendors/add_edit_view', $this->theme_settings['ThemeViews'],$data);
	}
	
	public function edit_vendor() {
		$this->vid = $_GET['vid'];
		$vendor = $this->input->post();
		$vendor_update = array(
			'VENDOR_ID'=>$this->vid,
			'VENDOR_Name' => $vendor['name'],
			'VENDOR_Notes' => $vendor['notes'],
			'VENDOR_Active'=>1
		);
		
		if(!isset($vendor['newPhone'])) {
			$phonePrimary = array();
			foreach($vendor['phone'] as $key => $value) {
				$phones = array(
					'PHONE_Primary' => (isset($value['primary']) ? 1 : 0),
					'PHONE_Type' => $value['type'],
					'PHONE_Number'=>$value['number'],
					'PHONE_ID'=>$key
				);
				
				array_push($phonePrimary,$phones);
			}
		}else {
			$phonePrimary = array(
				array(
					'PHONE_Primary' => 1,
					'PHONE_Type'=>$vendor['phone']['new']['type'],
					'PHONE_Number'=>$vendor['phone']['new']['number'],
					'OWNER_Type'=>2,
					'OWNER_ID'=>$this->vid,
					'PHONE_Created'=>date('Y-m-d H:i:s')
				)
			);
		}
		
		if(!isset($vendor['newAddress'])) {
			$addressPrimary = array();
			foreach($vendor['address'] as $key => $value) {
				$address = array(
					'ADDRESS_Primary' => (isset($value['primary']) ? 1 : 0),
					'ADDRESS_Type' => $value['type'],
					'ADDRESS_Street'=>$value['street'],
					'ADDRESS_City'=>$value['city'],
					'ADDRESS_State'=>$value['state'],
					'ADDRESS_Zip'=>$value['zip'],
					'ADDRESS_ID'=>$key
				);	
				
				array_push($addressPrimary,$address);
			}
		}else {
			$addressPrimary = array(
				array(
					'ADDRESS_Primary'=>1,
					'OWNER_ID'=>$this->vid,
					'OWNER_Type'=>2,
					'ADDRESS_Type'=>$vendor['address']['new']['type'],
					'ADDRESS_Street'=>$vendor['address']['new']['street'],
					'ADDRESS_City'=>$vendor['address']['new']['city'],
					'ADDRESS_State'=>$vendor['address']['new']['state'],
					'ADDRESS_Zip'=>$vendor['address']['new']['zip'],
					'ADDRESS_Created'=>date('Y-m-d H:i:s')
				)
			);	
		}
		
		$vendor_update = array(
			'Vendors'=>$vendor_update,
			'PhoneNumbers'=>$phonePrimary,
			'DirectoryAddresses'=>$addressPrimary
		);
		
		$update = $this->administration->updateVendorPhonesAddresses($vendor_update);
		
		if($update) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function view() {
		if(isset($_GET['VID'])) {
			$vid = $_GET['VID'];
		}
		
		$vendor = $this->administration->getVendor($vid);
		$vendor->ContactID = $vendor->ID;
		$vendor->Phone = (isset($vendor->Phone)) ? mod_parser($vendor->Phone,false,true) : false;
		$data = array(
			'vendor'=>$vendor,
			'contacts'=>true,
			'websites'=>WebsiteListingTable($vid, 'VID'),
			'contactInfo'=>ContactInfoListingTable($vendor, 'VID', true),
			'view'=>true
		);	
		$this->load->dom_view('forms/vendors/add_edit_view', $this->theme_settings['ThemeViews'],$data);
	}
	
	public function remove($which) {
		$vid = $this->input->post('vid');
		$disable = $this->administration->disableVendor($vid,$which);
		if($disable) :
			return TRUE;
		else :
			return FALSE;
		endif;
	}
	
	public function form() {
		$form = $this->input->post();

		$address = 'street:' . $this->security->xss_clean($form['street']) . ',city:' . $this->security->xss_clean($form['city']) . ',state:' . $this->security->xss_clean($form['state']) . ',zipcode:' . $this->security->xss_clean($form['zipcode']);
		$primaryPhone = $form['phone'];
		$phone   = $primaryPhone;
		 
		$data = array(
			'VENDOR_Name' 		=> $form['name'],
			'VENDOR_Address' 	=> $address,
			'VENDOR_Phone' 		=> $phone,
			'VENDOR_Primary_Phone' => $primaryPhone,
			'VENDOR_Notes' 		=> $form['notes'],
			'VENDOR_Active' 	=> 1,
			'VENDOR_ActiveTS' 	=> date('Y-m-d H:i:s'),
			'VENDOR_Created' 	=> date('Y-m-d H:i:s')
		);
		
		$vid = isset($form['ID']) ? $form['ID'] : FALSE;
		
		if(!isset($form['ID'])) { 
			$addVendor = $this->administration->addVendor($data);
			if($addVendor) {
				echo '1';	
			}else {
				echo '0';	
			}
		}else {
			$editVendor = $this->administration->editVendor($vid,$data);	
			if($editVendor) {
				echo '1';	
			}else {
				echo '0';
			}
		}
	}
	
}

