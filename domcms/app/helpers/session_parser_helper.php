<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function get_level_type() {
		$ci =& get_instance();
		$dropdown = $ci->session->userdata['valid_user']['DropdownDefault'];
		$level_type = (($dropdown->SelectedID != 'null') ? $dropdown->SelectedID[0] : $dropdown->LevelType);
		return $level_type;
	}
	
	function get_tag_id() {
		$ci =& get_instance();
		$tagdrop = $ci->session->userdata['valid_user']['DropdownDefault'];
		$tag_id = (($tagdrop->SelectedTag != FALSE) ? $tagdrop->SelectedTag : $tagdrop=FALSE);
		return $tag_id;
	}
	
	
	function get_level_id() {
		$ci =& get_instance();
		$dropdown = $ci->session->userdata['valid_user']['DropdownDefault'];
		$level_id = (($dropdown->SelectedID != 'null') ? substr($dropdown->SelectedID,-1) : $dropdown->LevelID);
		return $level_id;
	}
	
	function get_level_access() {
		//return level access
	}
	
	