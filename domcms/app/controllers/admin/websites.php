<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Websites extends DOM_Controller {
	
	//should always have this
	public $id;
	
	//when working with a vendor, we fill this
	public $vendor_id;
	
	//if we're editing we should always have this
	public $website_id;
	
	protected $type = '';

    public function __construct() {
        parent::__construct();
		//load the admin model
		$this->load->model('administration');

		if(isset($_POST['VID'])) {
			$this->id = $_POST['VID'];
			$this->type = 'VID';
		}elseif(isset($_GET['VID'])) {
			$this->id = $_GET['VID'];
			$this->type = 'VID';
		}
		if(isset($_POST['CID'])) {
			$this->id = $_POST['CID'];
			$this->type = 'CID';
		}elseif(isset($_GET['CID'])) {
			$this->id = $_GET['CID'];
			$this->type = 'CID';
		}
		if(isset($_POST['GID'])) {
			$this->id = $_POST['GID'];
			$this->type = 'GID';
		}elseif(isset($_GET['GID'])) {
			$this->contact_id = $_GET['GID'];
			$this->type = 'GID';
		}
		if(isset($_POST['UID'])) {
			$this->id = $_POST['UID'];
			$this->type = 'UID';
		}elseif(isset($_GET['UID'])) {
			$this->id = $_GET['UID'];
			$this->type = 'UID';
		}
	}
	
	public function Load_table($id=false,$type=false,$actions=true,$isVendor=false) {
		if(isset($_GET['id']))
			$id = $_GET['id'];
		if(isset($_GET['type']))
			$type = $_GET['type'];
		if(isset($_GET['actions']))
			$actions = ($_GET['actions'])?true:false;
		if(isset($_GET['isVendor']))
			$isVendor = $_GET['isVendor'];
		
		$data = array(
			'id'=>$id,
			'type'=>$type,
			'actions'=>$actions,
			'isVendor'=>$isVendor,
		);
		$this->load->dom_view('pages/websites/table', $this->theme_settings['ThemeViews'],$data);
	}
	
	public function add() {
		$form = $this->input->post();
		if(isset($_GET['VID'])) {
			$add = $this->administration->addKnownVendorWebsite($form,$_GET['VID']);
			if($add) {
				print 1;	
			}else {
				print 0;
			}
		}elseif(isset($_GET['CID']) || isset($_GET['GID']) || isset($_GET['UID'])) {
			//var_dump($form);
			$add = $this->administration->addWebsiteInfo($form,$this->type);
			if($add) {
				print 1;
			}else {
				print 0;
			}
		}else {
			print 0;
		}
	}
	
	public function edit() {
		$form = $this->input->post();
		if(isset($_GET['wid'])) {
			$data = array(
				'web_id' => $form['web_id'],
				'ID' => $form['ID'],
				'WEB_Url'=>$this->administration->formatUrl($form['url']),
				'WEB_Notes'=>$form['notes'],
				'WEB_ActiveTS'=>date(FULL_MILITARY_DATETIME),
				//'WEB_Created'=>date(FULL_MILITARY_DATETIME)
			);
			if (isset($form['vendor']))
				$data['WEB_Vendor'] = ($form['vendor']) ? $form['vendor'] : NULL;
			if ($this->type != 'GID' && $this->type != 'UID') {
				$otherData = array(
					'WEB_GoogleUACode'=>$form['ua_code'],
					'WEB_GoogleWebToolsMetaCode'=>$form['meta_code_number'],
					'WEB_GooglePlusCode'=>$form['gplus_code'],
					'WEB_BingCode'=>$form['bing_code'],
					'WEB_YahooCode'=>$form['yahoo_code'],
					'WEB_GlobalScript'=>$form['global_code'],
				);
			}
			if ($this->type != 'GID' && $this->type != 'UID')
				$data = array_merge($data,$otherData);
			
			$add = $this->administration->editWebsiteInfo($data,$this->type);
			if($add) {
				print 1;
			}else {
				print 0;
			}
		}elseif(isset($_GET['VID'])) {
		
		}else {
			print 0;
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
				case 'CID': $caller = $this->administration->getClient($this->id); break;
				case 'GID': $caller = $this->administration->getContactByTypeID($this->type,$this->id); $caller->ID = $caller->ContactID; break;
				case 'UID': $caller = $this->administration->getContactByTypeID($this->type,$this->id); $caller->ID = $caller->ContactID; break;
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
