<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Utilities extends DOM_Model {
		
		public function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
		}
		
		public function getStates() {
			$sql = "SELECT STATE_NAME as Name, STATE_Abbrev as Abbrev FROM xStates ORDER BY STATE_NAME ASC";
			return query_results($this,$sql);
		}
		
		public function getAccessLevels() {
			$sql = "SELECT ACCESS_Name as Name, ACCESS_Level as Level FROM xSystemAccess ORDER BY ACCESS_LEVEL DESC";
			return query_results($this,$sql);	
		}
		
		public function clientList() {
			$this->load->helper('form');
			$clientList = array();
			
			//Get the selected item
			$type = $this->usr['DropdownDefault']->LevelType;
			//user permission level
			$permLevel = $this->usr['DropdownDefault']->PermLevel;
			//get the selected class name
			if($type == 1 OR $type == 'a') {
				$selected = 'AID_' . $this->usr['DropdownDefault']->SelectedAgency;
				$val = 'a';
			}elseif($type == 2 OR $type == 'g') {
				$selected = 'GID_' . $this->usr['DropdownDefault']->SelectedGroup;	
				$val = 'g';
			}elseif($type ==3 OR $type == 'c') {
				$selected = 'CID_' . $this->usr['DropdownDefault']->SelectedClient;	
				$val = 'c';
			}else {
				$selected = '';	
			}
			
			//Grab all agencies,groups and clients and return them to an array
			if($this->user['AccessLevel'] >= 600000) {
				$a_sql = 'SELECT AGENCY_ID as AID, AGENCY_Name as AName, AGENCY_Active as AStatus, AGENCY_Tags as ATags from Agencies ORDER BY AGENCY_Name ASC';
				$agents = $this->db->query($a_sql)->result();
				$g_sql = 'SELECT AGENCY_ID as AID, GROUP_Name as GName, GROUP_ID as GID, GROUP_Active as GStatus, GROUP_Tags as GTags FROM Groups ORDER BY GROUP_Name ASC';
				$groups = $this->db->query($g_sql)->result();
				$c_sql = 'SELECT CLIENT_ID as CID, GROUP_ID as GID, CLIENT_Name as CName, CLIENT_Code as Code, CLIENT_Tag as CTag, CLIENT_Active as CStatus FROM Clients ORDER BY CLIENT_Name ASC';
			}else {
				$a_sql = 'SELECT AGENCY_ID as AID, AGENCY_Name as AName, AGENCY_Active as AStatus, AGENCY_Tags as ATags from Agencies WHERE AGENCY_Active = 1 ORDER BY AGENCY_Name ASC';
				$agents = $this->db->query($a_sql)->result();
				$g_sql = 'SELECT AGENCY_ID as AID, GROUP_Name as GName, GROUP_ID as GID, GROUP_Active as GStatus, GROUP_Tags as GTags FROM Groups WHERE GROUP_Active = 1 ORDER BY GROUP_Name ASC';
				$groups = $this->db->query($g_sql)->result();
				$c_sql = 'SELECT CLIENT_ID as CID, GROUP_ID as GID, CLIENT_Name as CName, CLIENT_Code as Code, CLIENT_Tag as CTag, CLIENT_Active as CStatus FROM Clients WHERE CLIENT_Active = 1 ORDER BY CLIENT_Name ASC';
			}
			
			$clients = $this->db->query($c_sql)->result();
			foreach($agents as $agent) {
				$agent->Class = 'AID_' . $agent->AID;
				$agent->ATags = explode(',',$agent->ATags);
				$agent->isSelected = (($agent->Class == $selected) ? TRUE : FALSE);
				$agent->Label = $agent->AName;
				$agent->Value = 'a' . $agent->AID;
				//array for groups under the top level agency
				$agent->Groups = array();	
				//loop through each group 
				foreach($groups as $group) {
					//check to see if the group is under this agency
					if($agent->AID == $group->AID) {
						$group->Class = 'GID_' . $group->GID;
						$group->GTags = explode(',',$group->GTags);
						$group->isSelected = (($group->Class == $selected) ? TRUE : FALSE);
						$group->Label = $group->GName;
						$group->Value = 'g' . $group->GID;
						//create array to hold all clients
						$group->Clients = array();
						$i = 0; //need to count how many clients meet our request so we can move the client up a level if they are by themselves.						
						//loop through each client 
						foreach($clients as $client) {
							//check to see if the client belongs to this group
							if($client->GID == $group->GID) {
								//push the client to the group
								$client->Class = 'CID_' . $client->CID;
								$client->isSelected = (($client->Class == $selected) ? TRUE : FALSE);
								$client->Label = $client->CName;
								$client->Value = 'c' . $client->CID;
								array_push($group->Clients,$client);	
								$i++;
							}
						}
						$group->Count = $i;
						if($i > 1) : 
							//push data to the groups container
							array_push($agent->Groups,$group);
						else :
							//if we only have one client in the group, push the client up a level.
							if(isset($group->Clients[0])) {
								array_push($agent->Groups,$group->Clients[0]);
							}
						endif;
					}
				}
			}
			array_push($clientList,$agents);
			$select = '<select id="dealerDropDown" data-placeholder="Dealer Dropdown" class="chzn-select" style="width:300px">' . "\n";
			$select .= '<option value=""></option>';
			foreach($clientList as $org) {
				foreach($org as $list) {
					if(count($list->Groups) >= 0) { 
						$select .= "\t" . '<option ' . 
									(($list->isSelected) ? 'selected="selected"' : '') . 
									'id="' . $list->Class . '" 
									 class="tagElementOn no-indent ' . $list->Class . '" 
									 value="' . $list->Value . '">' . 
										$list->AName . 
									'</option>' . "\n";
									
						foreach($list->Groups as $listGroup) {
							
							$select .= "\t" . '<option ' . 
										(($listGroup->isSelected) ? 'selected="selected"' : '') . 
										'id="' . $listGroup->Class . '" 
										 class="tagElementOn single-indent ' . $listGroup->Class . '" 
										 value="' . $listGroup->Value . '">' . 
											$listGroup->Label . 
										'</option>' . "\n";	
										
							if(isset($listGroup->Clients) AND (count($listGroup->Clients) >= 1)) {
								$i = 0;
								foreach($listGroup->Clients as $listClients) {
									$i++;
									$select .= "\t" . '<option ' . 
												(($listClients->isSelected) ? 'selected="selected"' : '') . 
												'id="' . $listClients->Class . '" 
												 class="tagElementOn double-indent ' . $listClients->Class . (($i == count($listGroup->Clients)) ? ' last' : '') . '" 
												 value="' . $listClients->Value . '">' . 
													$listClients->Label . 
												'</option>' . "\n";	
								}
							}
						}
					}
				}
			}
			$select .= '</select>';
			$select .= '<script type="text/javascript">
						jQuery("#dealerDropDown").change(function() {
							//alert("change");
							var name = jQuery(this).find("option:selected").text();
							var level = jQuery(this).find("option:selected").val();
							jQuery.ajax({
								url:"/ajax/name_changer",
								data:({Agency:name,Level:level}),
								method:"POST",
								success:function(data) {
									//jQuery("#clientInformation").html(data);
									location.reload();
								}
							});
	
						});</script>';

			return $select;
		}
		
		public function getTags() {
			$sql = "SELECT TAG_ID as TagID, TAG_Color as Color, TAG_Name as TagName, TAG_Notes as TagNotes, TAG_Active as Status, TAG_ClassName as ClassName FROM xTags ORDER BY TAG_Name;";
			return query_results($this,$sql);	
		}
		
		public function getTagCss() {
			$query = $this->getTags();
			$css = '<style type="text/css">' . "\n";
			$css .= "\t\t" . 'tr.tagElementOff,div.tagElementOff,.tagElementOff{display:none;}' . "\n";
			$css .= "\t\t" . 'tr.tagElementOn{display:table-row;}' . "\n";
			$css .= "\t\t" . 'div.tagElementOn,.tagElementOn{display:block;}' . "\n";
			foreach($query as $tag) {
				$css .= "\t\t" . 'div.' . $tag->ClassName. ' { background-color:' . $tag->Color . '; height:25px;width:25px;display:block;margin:0 auto;clear:both;}' . "\n";
			}
			$css .= "\t" . '</style>' . "\n";
			
			return $css;
		}
		
		public function getTagSelect() {
			$query = $this->getTags();
			$select = "\t" . '<select id="tagFilter" data-placeholder="Add Tag" style="width:100%;" class="chzn-select" multiple="multiple">';
			$select .= "\t\t" . '<option value=""></option>';
			foreach($query as $tags) {
				$select .= "\t\t" . '<option value="' . $tags->ClassName . '">' . $tags->TagName . '</option>';
			}
			$select .= "\t" . '</select><a style="display:none;" href="javascript:void(0);" id="AddTags"><img src="' . base_url() . 'imgs/icons/dark/add.png" alt="Add/Edit Tags" /></a>';
			return $select;
		}
	}
