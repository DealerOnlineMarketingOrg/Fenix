<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Mods extends DOM_Model {
		
		function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
		}
		
		public function getModLevelByName($name) {
			$sql = 'SELECT 
					MODULE_Level as Level, 
					MODULE_Active as Status, 
					MODULE_Name as Name, 
					MODULE_ID as Id 
					FROM xModules 
					WHERE MODULE_Name = "' . $name . '" AND MODULE_Active = 1;';
			$query = $this->db->query($sql);
			return ($query) ? $query->row() : FALSE;
		}
		
		public function getModLevelByID($id) {
			$sql = 'SELECT 
				    MODULE_Level as Level, 
					MODULE_Active as Status, 
					MODULE_Name as Name, 
					MODULE_ID as Id 
					FROM xModules 
					WHERE MODULE_ID = "' . $id . '";';
			$query = $this->db->query($sql);
			return ($query) ? $query->row() : FALSE;
		}
		
		public function getModulesByAccessLevel($level) {
			$sql = 'SELECT 
				    MODULE_Level as Level, 
					MODULE_Active as Status, 
					Module_Name as Name, 
					Module_ID as Id 
					FROM xModules WHERE MODULE_Level >= "' . $level . '"; 
					AND MODULE_Active = "1" 
					ORDER BY Module_Name';	
			$query = $this->db->query($sql);
			return ($query) ? $query->result() : FALSE;
		}
		
		public function getAccessLevelByHref($href) {
			$sql = 'SELECT NAV_Level as Level FROM xSystemNav WHERE NAV_Ref = "' . $href . '";';
			$query = $this->db->query($sql);
			return ($query) ? $query->row() : FALSE;	
		}
		
	}