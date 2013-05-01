<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends DOM_Controller {
	
	//should always have this
	public $id;
	
	//when working with a vendor, we fill this
	public $vendor_id;
	
	//if we're editing we should always have this
	public $website_id;
	
	public $owner_type;
	
	public $owner_id;
	
	protected $type = '';

    public function __construct() {
        parent::__construct();
		//load the admin model
		$this->load->model('administration');
		$this->load->model('domwebsites');
		$this->load->helper('websites');
		
		if(isset($_GET['owner_type'])) {
			$this->owner_type = $_GET['owner_type'];
		}
		
		if(isset($_GET['owner_id'])) {
			$this->owner_id = $_GET['owner_id'];	
		}
	}
	
	public function Load_table() {
		$data = array(
			'owner_id'=>$this->owner_id,
			'owner_type'=>$this->owner_type,
		);
		$this->load->dom_view('pages/websites/table', $this->theme_settings['ThemeViews'],$data);
	}
	
	public function add_website_form() {
		$data = array(
			'owner_id' =>   $_GET['owner_id'],
			'owner_type' => $_GET['owner_type'],
			'vendors' => $this->domwebsites->fillDropdownVendors(),
		);
		$this->load->dom_view('forms/websites/add',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function add() {
		$form = $this->input->post();
		$add = $this->domwebsites->addWebsite($form,$this->owner_id,$this->owner_type);
		if($add) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function edit_website_form() {
		$web_id = (int)$_GET['web_id'];
		$website = $this->domwebsites->getWebsite($web_id);
		$vendors = $this->domwebsites->fillDropdownVendors();
		$data = array(
			'website' => $website,
			'vendors' => $vendors,
			'web_id'=>$web_id
		);
		$this->load->dom_view('forms/websites/edit',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function edit() {
		$form = $this->input->post();
		$web_id = $_GET['web_id'];
		
		$data = array(
			'WEB_Vendor'=>$form['vendor'],
			'WEB_Url'=>$form['url'],
			'WEB_GoogleUACode'=>$form['ua_code'],
			'WEB_GoogleWebToolsMetaCode'=>$form['meta_code_number'],
			'WEB_GooglePlusCode'=>$form['gplus_code'],
			'WEB_BingCode'=>$form['bing_code'],
			'WEB_YahooCode'=>$form['yahoo_code'],
			'WEB_GlobalScript'=>$form['global_code'],
			'WEB_Notes'=>$form['notes'],
			'WEB_Active'=>1,
			'WEB_Created'=>date('Y-m-d H:i:s')
		);
		
		$edit = $this->domwebsites->updateWebsite($data,$web_id);
		if($edit) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function disable() {
		if(isset($_GET['wid'])) {
			$disable = $this->administration->disableWebsite($this->website_id);
			if($disable) {
				print $disable;	
			}else {
				print 0;	
			}
		}else {
			print 0;	
		}
	}
	
	public function enable() {
		if(isset($_GET['wid'])) {
			$enable = $this->administration->enableWebsite($this->website_id);
			if($enable) {
				print $enable;	
			}else {
				print 0;	
			}
		}else {
			print 0;	
		}
	}
	
	public function form() {
		if(isset($_GET['VID'])) {
			$this->vendor_id = $_GET['VID'];
			$vendor = $this->administration->getVendor($_GET['VID']);
			$data = array(
				'caller'=>$vendor,
				'selectedVendor'=>$vendor->ID,
				'typeID'=>$this->id,
				'type'=>$this->type,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : FALSE)
			);
		}elseif(isset($_GET[$this->type])) {
			$this->id = $_GET[$this->type];
			//get the right client info
			switch ($this->type) {
				case 1: $caller = $this->administration->getClient($this->id); break;
				case 8: $caller = $this->administration->getContactByTypeID($this->type,$this->id); $caller->ID = $caller->ContactID; break;
				case 3: $caller = $this->administration->getContactByTypeID($this->type,$this->id); $caller->ID = $caller->ContactID; break;
			}
			//vendors are not associated per client
			$vendors = $this->administration->getAllVendors();
			//prepare the array
			$data = array(
				'caller'=>$caller,
				'typeID'=>$this->id,
				'type'=>$this->type,
				'vendors'=>$vendors,
				'website'=>((isset($_GET['wid'])) ? $this->administration->getWebsite($_GET['wid']) : false),
			);
		}
		//print_object($this->administration->getWebsite($_GET['wid']));
		if ($data['website']) {
			$data['page'] = 'edit';
			$this->load->dom_view('forms/websites/add_edit', $this->theme_settings['ThemeViews'],$data);
		} else {
			$data['page'] = 'add';
			$this->load->dom_view('forms/websites/add_edit', $this->theme_settings['ThemeViews'],$data);
		}
	}
	
}
