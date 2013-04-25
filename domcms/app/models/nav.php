<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Nav extends DOM_Model {
		
		public function __construct() {
			parent::__construct();	
			$this->load->helper('query');
		}
		
		//Main query
		private function get_nav($parent,$user_lvl,$group) {
			$sql = "SELECT 
					NAV_ID as Id, 
					NAV_Name as Label, 
					NAV_Parent as Parent, 
					NAV_Ref as Href, 
					NAV_Level as Level, 
					NAV_Active as Status, 
					NAV_Class as Class,
					NAV_ViewLevel as ViewLevel
					FROM xSystemNav
					WHERE NAV_Level <= " . (float)$user_lvl . " AND NAV_Parent = '" . $parent . "' AND NAV_Group = '" . $group . "' AND NAV_Active = '1' ORDER BY NAV_Order;";
			return $this->db->query($sql)->result();
		}
		
		public function main($user_lvl) {
			$top_level = $this->get_nav('0',$user_lvl,'1');
			$button = array();
			if($top_level) {
				foreach($top_level as $icon) {
					$button[$icon->Id] = array(
						'Label' => $icon->Label,
						'Href'  => $icon->Href,
						'Class' => $icon->Class,
						'Subnav' => (($this->get_nav($icon->Id,$user_lvl,'1')) ? $this->get_nav($icon->Id,$user_lvl,'1') : NULL),
						'ViewLevel' => $icon->ViewLevel
					);
				}
			}
			return $button;
		}
		
		public function user($user_lvl) {
			return $this->get_nav('0',$user_lvl,'2');
		}
		
	}