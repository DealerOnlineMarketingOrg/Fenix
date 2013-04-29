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
		$vendor = $this->administration->getVendor($vid);
		$vendor->TypeCode = 'VID';
		$vendor->TypeID = $vid;
		$data = array(
			'vendor' => $vendor,
			'contacts'=>true,
			'websites'=>WebsiteListingTable($vid, 'VID'),
			'contactInfo'=>ContactInfoListingTable($vendor, 'VID', true),
		);
		$this->load->dom_view('forms/vendors/add_edit_view', $this->theme_settings['ThemeViews'],$data);
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

