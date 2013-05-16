<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Groups extends DOM_Controller {

	public $agency_id;
	public $group_id;

    public function __construct() {
        parent::__construct();
		$this->load->model('administration');

        $this->agency_id = $this->user['DropdownDefault']->SelectedAgency;
        $this->level     = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
    }

    public function index() {
		$this->LoadTemplate('pages/groups/listing');
    }
	
	public function load_table() {
		$this->load->dom_view('pages/groups/table', $this->theme_settings['ThemeViews']);
	}
	
	public function View() {
		if(isset($_GET['gid'])) {
			$group_id = $_GET['gid'];	
		}else {
			$group_id = $this->user['DropdownDefault']->SelectedGroup;	
		}
		
		$group = $this->administration->getGroup($group_id);
		if($group) {
			$data = array(
				'group' => $group,
			);
			$this->load->dom_view('pages/groups/view', $this->theme_settings['ThemeViews'], $data);
		}
	}
	
	public function Form() {
		$group_data = $this->security->xss_clean($this->input->post());
		if(isset($_GET['gid'])) {
			$group_id = $_GET['gid'];
			$group_data = $this->security->xss_clean($this->input->post());
			
			//we need to change all the clients Status if we disable a group.
			if($this->user['AccessLevel'] >= 600000 AND $group_data['status'] == '0') {
				$client_change = $this->administration->clientGroupedStatus($group_id);
			}
			//prepare the update
			$edit_data = array(
				'AGENCY_ID'=>$group_data['agency'],
				'GROUP_Name'=>$group_data['name'],
				'GROUP_Notes'=>$group_data['notes'],
				'GROUP_Active'=>$group_data['status'],
			);
			
			$update = $this->administration->updateGroup($group_id,$edit_data);
			
			if($update) {
				echo '1';	
			}else {
				echo '0';	
			}
			
		}else {
			//prepare the add
			$add_data = array(
				'AGENCY_ID'=>$this->user['DropdownDefault']->SelectedAgency,
				'GROUP_Name'=>$group_data['name'],
				'GROUP_Notes'=>$group_data['notes'],
				'GROUP_Active'=>1,
				'GROUP_Created'=>date(FULL_MILITARY_DATETIME),
			);
			
			$add = $this->administration->addGroup($add_data);
			
			if($add) {
				echo '1';	
			}else {
				echo '0';	
			}
		}
	}
	
	public function Add() {
		$agencies = $this->administration->getAllAgenciesForDropdown();
		$data = array(
			'agencies'=>$agencies
		);
		
		$this->load->dom_view('forms/groups/edit_add', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function Edit() {
		if(isset($_GET['gid'])) {
			$group_id = $_GET['gid'];	
		}else {
			$group_id = $this->user['DropdownDefault']->SelectedGroup;	
		}
		
		$group = $this->administration->getGroup($group_id);
		$agencies = $this->administration->getAllAgenciesForDropdown();
		if($group) {
			$data = array(
				'group'=>$group,
				'agencies'=>$agencies
			);	
			$this->load->dom_view('forms/groups/edit_add', $this->theme_settings['ThemeViews'], $data);
		}else {
			echo '0';	
		}
	}

}
