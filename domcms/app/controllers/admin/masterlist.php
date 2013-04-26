<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Masterlist extends DOM_Controller {

	var $myClientID;

    public function __construct() {
        parent::__construct();
		$this->load->model(array('mlist'));
		$this->activeNav = 'admin';
		//if we detect the get paramater 'cid' 
		//we know that the system already knows which client we need to work with.
		//set the global var for the class that is available for all methods.
		//if we do not detect the get parameter, then set the var to false
		if(isset($_GET['cid'])) {
			$this->myClientID = $_GET['cid'];
		}else {
			$this->myClientID = FALSE;	
		}
    }

    public function index() {
		//$client = $this->mlist->buildMasterList();
		//print_object($client);
		
		$this->LoadTemplate('pages/masterlist/listing');
    }
	
	public function Load_table() {
		$this->load->dom_view('pages/masterlist/table', $this->theme_settings['ThemeViews']);	
	}
	
	public function Edit_entry() {
		if($this->myClientID) :
			$client          = $this->mlist->getFormData($this->myClientID);
			$vendorOptions   = $this->mlist->getVendorOptions();
			$crazyEggOptions = $this->mlist->getCrazyEggOptions();
						
			$data = array(
				'client'=>$client,
				'vendorOptions'=>$vendorOptions,
				'crazyEggOptions'=>$crazyEggOptions
			);
			
			$this->load->dom_view('forms/masterlist/edit', $this->theme_settings['ThemeViews'], $data);
		else:
			echo 0;
		endif;	
	}
	
	public function form() {
		$form = $this->input->post();
		
		//print_object($form);
		
		/* Since we never know how many websites have been edited, we have to do some tricks to keep the right data organized. */
		/* Lets go ahead and prepare the data */
		
		$isEdit = TRUE;
		
		$client_id = $this->myClientID;
		$return = array();
		
		//always just one entry, never duplicated based on different websites
		$doc = $this->security->xss_clean((!empty($form['doc']) ? $form['doc'] : 'NULL'));
		$xsl = $this->security->xss_clean((!empty($form['xsl']) ? $form['xsl'] : 'NULL'));
		$crm = $this->security->xss_clean((!empty($form['crm']) ? $form['crm'] : 'NULL'));
		$crm_link = $this->security->xss_clean((!empty($form['crm_link']) ? $form['crm_link'] : 'NULL'));
		
		//cms,crazyegg and cms links can be multiples
		$cms = $this->security->xss_clean((!empty($form['cms']) ? $form['cms'] : 'NULL'));
		$cms_links = $this->security->xss_clean((!empty($form['cms_link']) ? $form['cms_link'] : 'NULL'));
		$crazyegg = $this->security->xss_clean((!empty($form['crazyegg']) ? $form['crazyegg'] : 'NULL'));
		
		$assets = array(
			'CRM_Vendor_ID'=>$crm,
			'CRM_Vendor_Link'=>$crm_link,
			'DOC_Link'=>$doc,
			'XLS_Link'=>$xsl
		);
		
		$asset_data = $this->mlist->addAssets($client_id,$assets);
		
		if($asset_data) {
			array_push($return,true);	
		}else {
			array_push($return,false);	
		}
		
		if(!empty($cms)) {
			foreach($cms as $key => $value) {
				$data = array(
					'WEB_ID'=>$key,
					'CLIENT_ID'=>$client_id,
					'CMS_Vendor_ID'=>$value['id'],
					'CMS_Vendor_Link'=>$value['link']
				);
				
				$cms_data = $this->mlist->addCMS($key,$data);	
				
				if($cms_data) {
					array_push($return,true);
				}else {
					array_push($return,false);	
				}
			}
		}
		
		if(!empty($crazyegg)) {
			foreach($crazyegg as $key =>$value) {
				$data = array(
					'WEB_ID'=>$key,
					'CrazyEggStatusID'=>$value
				);	
				
				$crazyegg_data = $this->mlist->addCrazyEgg($key,$data);
				if($crazyegg_data) {
					array_push($return,true);	
				}else {
					array_push($return,false);	
				}
			}
		}
		
		$var = '1';
		
		foreach($return as $item) {
			if(!$item) {
				$var .= '0';
				break;	
			}
		}
		
		if($var != '1') {
			echo '0';	
		}else {
			echo '1';	
		}
		
	}

}
