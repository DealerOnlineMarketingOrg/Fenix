<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class DropdownGen {
		
		var $ValidUser;
		var $DropdownDefault;
		var $ci;
		public $str;
		
		public function __construct() {
			$this->ci 				=& get_instance();
			$this->ci->load->model('dropdown');
			$this->ci->load->model('tagdrop');
			
			$this->ValidUser 		= $this->ci->session->userdata('valid_user');
			$this->DropdownDefault  = $this->ValidUser['DropdownDefault'];
		}
		
		public function drivedrop() {
			//GRAB defaults from session
			$PermType 		= $this->DropdownDefault->PermType;
			$LevelID 		= $this->DropdownDefault->LevelID;
			$LevelType 		= $this->DropdownDefault->LevelType;
			$SelectedID 	= $this->DropdownDefault->SelectedID;
			$SelectedTag 	= $this->DropdownDefault->SelectedTag;
			
			//set var defualt
			$TagView 		= FALSE;
			
			//If there is no selected tag then default the Tagview to false
			//else run a query to get the data for the selectedtags
			if(!$SelectedTag OR $SelectedTag == 'noshow') {
				$TagView = FALSE;	
			}else {
				$TagView = $this->ci->tagdrop->TagsQuery($SelectedTag);	
			}
			
			//If the $SelectedID isn't set or is NULL, set the defaults, if not split the strings accordingly
			if(!$SelectedID):
				$type = $SelectedID[0];
				$id = substr($SelectedID,-1);
			else:
			//set defaults
				$type = $LevelType;
				$id = $LevelID;
			endif;
			
			//set specialtagsid into array for later use below.
			if(!empty($TagView)) {$specialtagids = client_id_parser($TagView->CLIENT_IDS);}
			
			//check to see if current selection is in tag if not reset to highest level
			if(($type == 3 OR $type == 2) AND isset($specialtagids) AND !$specialtagids AND !empty($specialtagids) ){
				if($type == 3 AND !in_array($id, $specialtagids)){
					$type 		 = $LevelType;
					$id 	     = $LevelID;
				    $selected_id = $type . $id;
					$this->ci->session->userdata['valid_user']['DropdownDefault']->SelectedID = $selected_id;
					
					//write the changes to the session
					$this->ci->session->sess_write();
				}
				if($type == 2){
					$check 			 	 = $this->ci->dropdown->Group_Selected_Check($id);
					foreach ($check as &$value) {
    					if(!in_array($value->CLIENT_ID, $specialtagids)){
							$type 		 = $LevelType;
							$id 		 = $LevelID;
							$selected_id = $type . $id;
							$this->ci->session->userdata['valid_user']['DropdownDefault']->SelectedID = $selected_id;
							
							//write the changes to the session
							$this->ci->session->sess_write();
						}
					}
				}
			}
			
			//set based on PermType
		    $str = '';
			if($PermType == 'SuperAdmin') {
				if($TagView){
					$str 	.= $this->SuperAdmin($type,$id,$specialtagids);
				}
				else{
					$str 	.= $this->SuperAdmin($type,$id,false);	
				}
			}else if($PermType == 'GroupAdmin') {
				$str 		.= $this->GroupAdmin($type,$id);	
			}else if($PermType == 'ClientAdmin') {
				$str 		.= $this->Client($type,$id);	
			}
			return $str;
		}
		
		public function SuperAdmin($type, $id, $tag_c_ids) {
			$DropString 		= '';
			$selected 			= FALSE;
			$aQuery 			= $this->ci->dropdown->AgenciesQuery();
			
			if(count($aQuery) > 1) {
				foreach ($aQuery as $aRow){
					// And no-indent and  agency style
					$agentstyle 	= 'no-indent agency break';
					//check for default agency
					$selected 		= FALSE;
					
					if($aRow->AGENCY_ID == $id AND $type == 1):
						$selected 	= TRUE;
					else:
						$selected 	= FALSE;
					endif;
					
					//write out a string like: a:1;Name of agency^css_name,true|
					$DropString .= 'a:' . $aRow->AGENCY_ID . ';' . $aRow->AGENCY_Name . '^' . $agentstyle . ',' . $selected . '|';
					$gQuery 	 = $this->ci->dropdown->GroupsQuery(false, $aRow->AGENCY_ID);
					
					foreach ($gQuery as $gRow) {
						$selected 		  = FALSE;
						if($gRow->GROUP_ID == $id AND $type == 2):
							$selected 	  = TRUE;
						else:
							$selected 	  = FALSE;
						endif;
						
						//styles to append to string
						$groupstyle  	  = 'single-indent group';
						//write out a string
						$DropString 	 .= 'g:' . $gRow->GROUP_ID . ';' . $gRow->GROUP_Name . '^' . $groupstyle . ',' . $selected . '|';
						//query
						$cQuery 		  =  $this->ci->dropdown->ClientQuery(false, $gRow->GROUP_ID);
						
						$clientstyle = 'double-indent client';
						//And style client as double 
						foreach ($cQuery as $cRow){
							$selected = FALSE;
							
							if($cRow->CLIENT_ID == $id AND $type == 3):
								$selected = TRUE;
							else:
								$selected = FALSE;
							endif;
							
							if (!isset($tag_c_ids) OR $tag_c_ids==false) {
								$DropString .= 'c:' . $cRow->CLIENT_ID . ';' . $cRow->CLIENT_Name . '^' . $clientstyle . ',' . $selected . '|';
							}
							else if(isset($tag_c_ids) AND in_array($cRow->CLIENT_ID, $tag_c_ids)){
								$DropString .= 'c:' . $cRow->CLIENT_ID . ';' . $cRow->CLIENT_Name . '^' . $clientstyle . ',' . $selected . '|';
							}
						}//end cQuery
					}//end gQuery
				}//end aQuery
			}else {
				// And no-indent and  agency style
				$agentstyle 	= 'no-indent agency break';
				//check for default agency
				$selected 		= FALSE;
				
				if($aQuery->AGENCY_ID == $id AND $type == 1):
					$selected 	= TRUE;
				else:
					$selected 	= FALSE;
				endif;
				
				//write out a string like: a:1;Name of agency^css_name,true|
				$DropString .= 'a:' . $aQuery->AGENCY_ID . ';' . $aQuery->AGENCY_Name . '^' . $agentstyle . ',' . $selected . '|';
				$gQuery 	 = $this->ci->dropdown->GroupsQuery(false, $aQuery->AGENCY_ID);
				
				foreach ($gQuery as $gRow) {
					$selected 		  = FALSE;
					if($gRow->GROUP_ID == $id AND $type == 2):
						$selected 	  = TRUE;
					else:
						$selected 	  = FALSE;
					endif;
					
					//styles to append to string
					$groupstyle  	  = 'single-indent group';
					//write out a string
					$DropString 	 .= 'g:' . $gRow->GROUP_ID . ';' . $gRow->GROUP_Name . '^' . $groupstyle . ',' . $selected . '|';
					//query
					$cQuery 		  =  $this->ci->dropdown->ClientQuery(false, $gRow->GROUP_ID);
					
					$clientstyle = 'double-indent client';
					//And style client as double 
					foreach ($cQuery as $cRow){
						$selected = FALSE;
						
						if($cRow->CLIENT_ID == $id AND $type == 3):
							$selected = TRUE;
						else:
							$selected = FALSE;
						endif;
						
						if (!isset($tag_c_ids) OR $tag_c_ids==false) {
							$DropString .= 'c:' . $cRow->CLIENT_ID . ';' . $cRow->CLIENT_Name . '^' . $clientstyle . ',' . $selected . '|';
						}
						else if(isset($tag_c_ids) AND in_array($cRow->CLIENT_ID, $tag_c_ids)){
							$DropString .= 'c:' . $cRow->CLIENT_ID . ';' . $cRow->CLIENT_Name . '^' . $clientstyle . ',' . $selected . '|';
						}
					}//end cQuery
				}//end gQuery
			}
			return $DropString;			
		}
		
		public function GroupAdmin($type,$id) {
			$DropString = '';
			$selected = 0;
			$gQuery = $this->ci->dropdown->GroupsQuery($this->DropdownDefault['LevelID']);
			foreach ($gQuery as $gRow){
				$cQuery = $this->ci->dropdown->ClientQuery(false,$gRow->GROUP_ID);
				if($cQuery > 1) :
					//Put Group
					//And single-indent group style
					$groupstyle   = 'no-indent group';
					$clientstyle  = 'single-indent client';
					//And style client as double if more than one.
					$selected 	  = 0;
					if($gRow->GROUP_ID == $id AND $type == 'g'):
						$selected = 1;
					else:
						$selected = 0;
					endif;
					$DropString  .= 'g:' . $gRow->GROUP_ID . ';' . $gRow->GROUP_Name . '^' . $groupstyle . ',' . $selected . '|';
				else:
					$clientstyle  = 'no-indent';
					//use this style for single client groups
				endif;
				//counting for last client 
				$i = 0;
				$n = count($cQuery);
				foreach ($cQuery->result() as $cRow){
					$selected = 0;
					$i++;
					if($cRow->CLIENT_ID == $id AND $type == 'c'):
						$selected = 1;
					else:
						$selected = 0;
					endif;
					if ($i == $n):
						$clientstyle .= ' break';
					endif;
					$DropString      .= 'c:' . $cRow->CLIENT_ID . ';' . $cRow->CLIENT_Name . '^' . $clientstyle . ',' . $selected . '|';
				}
			} 
			return $DropString;
		}
		
		public function Client($type, $id){
			$DropString = '';
			$selected = 0;
			$cQuery = $this->ci->dropdown->ClientQuery($this->DropdownDefault['LevelID']);
			$clientstyle = 'no-indent client' . ' break';
			$selected = 1;
			foreach ($cQuery as $cRow){
				$DropString .= 'c:' . $cRow->CLIENT_ID . ';' . $cRow->CLIENT_Name . '^' .$clientstyle . ',' . $selected . '|';
			}
			return $DropString;
		}
	}