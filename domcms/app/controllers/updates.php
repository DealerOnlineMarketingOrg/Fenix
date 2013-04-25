<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Updates extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('release_model','beta');
		$this->load->library('security');
    }

    public function index() {
		$updateUserRelease = $this->beta->update_user_release();
		$this->LoadTemplate('pages/updates/view');
    }
	
	public function add() {
		$area = $this->security->xss_clean($this->input->post('area'));
		$desc = $this->security->xss_clean($this->input->post('desc'));
		
		$data = array(
			'area' => $area,
			'desc' => $desc
		);
		
		$change = $this->beta->add_change($data);
		if($change) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function load_table() {
		$changes = $this->beta->get_changes();
        $table = '<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
            <thead>
                <tr>
                	<th>Release Date</th>
                	<th style="width:150px;">Area</th>
                    <th>Description</th>';
					if($this->user['AccessLevel'] >= 600000) {
                    	$table .= '<th class="edit">Edit</th>
                    			   <th class="delete">Delete</th>';
					}
        $table .= '</tr>
            </thead>
			<tbody>';
		
		foreach($changes as $change) {
			$table .= '<tr id="update_' . $change->id . '">
						<td>' . date('m/d/Y', strtotime($change->release_date)) . '</td>
						<td style="text-align:center">' . $change->area . '</td>
						<td><p>' . $change->desc . '</p></td>';
						if($this->user['AccessLevel'] >= 600000) {
                    		$table .= '<td class="edit">
							<a href="javascript:editChange(' . $change->id . ');" class="edit_' . $change->id . ' edit"><img src="' . base_url() . 'imgs/icons/color/pencil.png" alt="Edit Change" /></a>
						</td>
                    	<td class="delete">
							<a href="javascript:deleteChange(' . $change->id . ');" class="delete_' . $change->id . ' delete"><img src="' . base_url() . 'imgs/icons/color/cross.png" alt="Delete Change" /></a>
						</td>'; 
						}
			$table .='</tr>';
		}
		$table .= '</table>';
		
		echo $table;
	}
	
	public function check_db_for_new_updates() {
		$isNew = $this->beta->compair_read();
		if(!$isNew) {
			echo FALSE;	
		}else {
			echo TRUE;	
		}
	}
	
	public function get_count() {
		$c = $this->beta->get_changes_count();
		if($c) {
			echo count($c);	
		}
	}
	
	public function remove_change() {
		$change_id = $this->input->post('id');
		$delete = $this->beta->remove_change($change_id);
		if($delete) {
			echo '1';
		}else {
			echo '0';
		}
	}
	
	public function load_change_form() {
		$change_id = $this->input->post('rowID');
		$form_data = $this->beta->get_change($change_id);
		$data = array(
			'change' => $form_data
		);
		$this->load->dom_view('forms/updates/edit', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function update_change() {
		$form = $this->input->post();
		$update = $this->beta->update_changes($form);
		
		if($update) {
			echo '1';	
		}else {
			echo '0';	
		}
	}

}
