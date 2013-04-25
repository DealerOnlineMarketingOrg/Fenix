<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Release_model extends CI_Model {

	var $user;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('query');
        $this->load->helper('string_parser');
		$this->load->library('session');
    }
	
	public function update_user_release() {
		$data = array(
			'USER_Release' => date('Y-m-d H:i:s')
		);
		$this->user = $this->session->userdata['valid_user']['UserID'];
		$this->db->where('USER_ID',$this->user);
		return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
	}
	
	public function add_change($data) {
		return ($this->db->insert('ReleaseUpdates',$data)) ? TRUE : FALSE;
	}
	
	public function get_changes() {
		$sql = 'SELECT * FROM ReleaseUpdates ORDER BY release_date DESC';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function get_user_release_date() {
		$this->user = $this->session->userdata['valid_user']['UserID'];
		//get the users last visit time
		$u_sql = 'SELECT USER_Release as LastRelease FROM Users_Info WHERE USER_ID = "' . $this->user . '";';
		$u_query = $this->db->query($u_sql);
		return ($u_query) ? $u_query->row() : FALSE;
	}
	
	public function compair_read() {
		$u_query = $this->get_user_release_date();
		if($u_query) {
			$usersRelease = strtotime($u_query->LastRelease);
			//Get latest change from system
			$this->db->select_max('release_date');
			$sys_query = $this->db->get('ReleaseUpdates');
			if($sys_query) {
				$sysRelease = strtotime($sys_query->row()->release_date);
				if($usersRelease <= $sysRelease) {
					return FALSE;	
				}else {
					return TRUE;	
				}
			}
		}else {
			return FALSE;	
		}
	}
	
	public function remove_change($change_id) {
		return ($this->db->delete('ReleaseUpdates',array('id'=>$change_id))) ? TRUE : FALSE;
	}
	
	public function get_changes_count() {
		$changes = $this->get_changes();
		$userDate = strtotime($this->get_user_release_date()->LastRelease);
		$release = array();
		if($changes) {
			foreach($changes as $change) {
				$date = strtotime($change->release_date);
				
				if($userDate <= $date) {
					array_push($release,$change);	
				}
			}
			
			return $release;
		}else {
			return FALSE;	
		}
	}
	
	public function update_changes($data) {
		$this->db->where('id',$data['id']);
		return ($this->db->update('ReleaseUpdates',	$data)) ? TRUE : FALSE;
	}
	
	public function get_change($id) {
		$sql = 'SELECT * FROM ReleaseUpdates WHERE id="' . $id . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;	
	}

}