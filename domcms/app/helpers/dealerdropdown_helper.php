<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function collect() {
		$ci =& get_instance();
		$ci->load->model('dropdown');
		//the logged in users active session
		$user_sess = $ci->session->userdata('valid_user');
		//convert the user_sess into an object
		$user_sess = (object)$user_sess;
		//the logged in users permission level
		$perm_level = $user_sess->AccessLevel;
		
		//The ids of the client, group and agency the user belogs to
		$user_cid = $user_sess->ClientID;
		$user_gid = $user_sess->GroupID;
		$user_aid = $user_sess->AgencyID;
		
		$agencies = $ci->dropdown->getAllAgencies();
		
		foreach($agencies as $agency) {
			if($ci->dropdown->getGroupByAgencyID($agency->AgencyID)) {
				$agency->Groups = $ci->dropdown->getGroupByAgencyID($agency->AgencyID);	
				foreach($agency->Groups as $group) {
					if($ci->dropdown->getClientsByGroupID($group->GroupID)) {
						$group->Clients = $ci->dropdown->getClientsByGroupID($group->GroupID);
					}
				}
			}
		}
		
		return $agencies;
	}
	
	