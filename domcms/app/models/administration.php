<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Administration extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('query');
        $this->load->helper('string_parser');
    }
	
	public function getClientsByDDLevel() {
		$level = $this->user['DropdownDefault']->LevelType;
		switch($level) {
			case 1:
				return $this->getAllClientsInAgency($this->agency_id);
			break;
			case 2:
				return $this->getAllClientsInGroup($this->group_id);
			break;
			case 3:
				return $this->getClientByID($this->user['DropdownDefault']->SelectedClient);
			break;
			case 'a':
				return $this->getAllClientsInAgency($this->agency_id);
			break;
			case 'g':
				return $this->getAllClientsInGroup($this->group_id);
			break;
			case 'c':
				return $this->getClientByID($this->user['DropdownDefault']->SelectedClient);
			break;	
			default:
				return $this->getAllClientsInAgency($this->agency_id);
			break;
		}
	}
	
	//disable all clients in a group when a group is disabled...only super admins can do this
	public function clientGroupedStatus($gid) {
		$data = array(
			'CLIENT_Active'=>0
		);
		$this->db->where('GROUP_ID',$gid);
		return ($this->db->update('Clients',$data)) ? TRUE : FALSE;	
	}
	
	public function getAllModules() {
		$query = $this->db->select('*')->from('xModules')->where('MODULE_Active',1)->get();
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getDirectoryID($uid) {
		$sql = 'SELECT d.DIRECTORY_ID as ID FROM Directories d INNER JOIN Users_Info ui ON ui.DIRECTORY_ID = d.DIRECTORY_ID WHERE ui.USER_ID = "' . $uid . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->row()->ID : FALSE;
	}
	
	public function updateDirectory($did,$data) {
		$this->db->where('DIRECTORY_ID',$did);
		return ($this->db->update('Directories',$data)) ? TRUE : FALSE;
	}
	
	public function udpateUserName($uid,$data) {
		$this->db->where('USER_ID',$uid);
		return ($this->db->update('Users',$data)) ? TRUE : FALSE;	
	}
	
	public function updateUserModules($uid,$modules) {
		$data = array(
			'USER_Modules'=>$modules
		);
		
		$this->db->where('USER_ID',$uid);
		return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
	}
	
	public function disableWebsite($wid) {
		//we need the client id
		$this->db->select('ID as ClientID');
		$this->db->from('Websites');
		$this->db->where('WEB_ID',$wid);
		$client_id = $this->db->get();
		if($client_id) {
			$client_id = $client_id->row()->ClientID;
			$this->db->where('WEB_ID',$wid);
			$update = ($this->db->update('Websites',array('WEB_Active'=>0)) ? TRUE : FALSE);	
			if($update) {
				return $client_id;	
			}else {
				return FALSE;	
			}
		}else {
			return FALSE;	
		}
	}
	
	public function enableWebsite($wid) {
		//we need the client id
		$this->db->select('ID as ClientID');
		$this->db->from('Websites');
		$this->db->where('WEB_ID',$wid);
		$client_id = $this->db->get();
		if($client_id) {
			$client_id = $client_id->row()->ClientID;
			$this->db->where('WEB_ID',$wid);
			$update = ($this->db->update('Websites',array('WEB_Active'=>1)) ? TRUE : FALSE);	
			if($update) {
				return $client_id;	
			}else {
				return FALSE;	
			}
		}else {
			return FALSE;	
		}
	}
	
	// Formats a url so it has a valid scheme attached to it.
	// Returns the formatted url. Assumes http if unknown or missing scheme.
	public function formatUrl($url) {
		$url = trim($url);
		// All valid schemes. Includes likely malformed schemes.
		$schemes = array (
			'http'  => '~^(((http|ttp|htp|tp|p)))(:?/+)(.*)$~i',
			'https' => '~^(((http|ttp|htp|tp|p)s))(:?/+)(.*)$~i',
			'ftp'   => '~^(((ftp|ft|fp)))(:?/+)(.*)$~i',
			'sftp'  => '~^(s?(ftp|ft|fp)|(ftp|ft|fp)s?)(:?/+)(.*)$~i',
			'file'  => '~^(((file|fle|fil|ile|le|e)))(:?/+)(.*)$~i',
		);
		// If url scheme doesn't match valid schemes, assume http.
		$newUrl = '';
		foreach ($schemes as $key => $scheme) {
			$isMatch = preg_match($scheme, $url, $matches);
			if ($isMatch) {
				// This is the most likely intended scheme.
				//  $key contains scheme name.
				$newUrl = $key . '://' . $matches[5];
				break;
			}
		}
		if ($newUrl == '') {
			// Valid schemes wasn't matched. Attempt a more general match.
			// Also check for missing scheme name.
			$schemeGeneral = '~^([^:/]+)?(:?/+)(.*)$~i';
			$isMatch = preg_match($schemeGeneral, $url, $matches);
			if ($isMatch)
				// General scheme found. Assume http.
				$newUrl = 'http://' . $matches[3];
			else
				// url is likely missing the scheme altogether.
				$newUrl = 'http://' . $url;
		}
		
		return $newUrl;
	}
	
	public function editWebsiteInfo($data,$type) {		
		$this->db->where('WEB_ID',$data['web_id']);
		return ($this->db->update('Websites',$data) ? TRUE :FALSE);
	}
	
	public function editKnownVendorWebsite($formdata,$vid) {
		$data = array(
			'ID' => $vid,
			'WEB_Vendor' => $vid,
			'WEB_GoogleUACode'=>$formdata['ua_code'],
			'WEB_GoogleWebToolsMetaCode'=>$formdata['meta_code_number'],
			'WEB_GooglePlusCode'=>$formdata['gplus_code'],
			'WEB_BingCode'=>$formdata['bing_code'],
			'WEB_YahooCode'=>$formdata['yahoo_code'],
			'WEB_GlobalScript'=>$formdata['global_code'],
			'WEB_Url'=>$this->formatUrl($formdata['url']),
			'WEB_Notes'=>$formdata['notes'],
			'WEB_ActiveTS'=>date(FULL_MILITARY_DATETIME),
			//'WEB_Created'=>date(FULL_MILITARY_DATETIME)
		);
		$this->db->where('WEB_Type','VID:' . $vid);
		return ($this->db->update('Websites',$data) ? TRUE :FALSE);
	}
	
	public function addKnownVendorWebsite($formdata,$vid) {
		$data = array(
			'ID' => $formdata['VendorID'],
			'WEB_Vendor' => $vid,
			'WEB_GoogleUACode'=>$formdata['ua_code'],
			'WEB_GoogleWebToolsMetaCode'=>$formdata['meta_code_number'],
			'WEB_GooglePlusCode'=>$formdata['gplus_code'],
			'WEB_BingCode'=>$formdata['bing_code'],
			'WEB_YahooCode'=>$formdata['yahoo_code'],
			'WEB_GlobalScript'=>$formdata['global_code'],
			'WEB_Type'=>'VID:' . $vid,
			'WEB_Url'=>$this->formatUrl($formdata['url']),
			'WEB_Active'=>'1',
			'WEB_Notes'=>$formdata['notes'],
			'WEB_ActiveTS'=>date(FULL_MILITARY_DATETIME),
			'WEB_Created'=>date(FULL_MILITARY_DATETIME)
		);
		return ($this->db->insert('Websites',$data) ? TRUE : FALSE);
	}
	
	public function addWebsiteInfo($formdata,$type) {
		$vid = FALSE;
		if($formdata['custom_vendor'] != '') {
			$vendor = array(
				'VENDOR_Name' => $formdata['custom_vendor'],
				'VENDOR_Active' => 1,
				'VENDOR_ActiveTS' => date(FULL_MILITARY_DATETIME),
				'VENDOR_Created' => date(FULL_MILITARY_DATETIME)
			);	
			$vendor = $this->db->insert('Vendors',$vendor);
			if($vendor) {
				$this->db->select('VENDOR_ID as ID');
				$this->db->from('Vendors');
				$this->db->where('VENDOR_Name',$formdata['custom_vendor']);
				$vid = $this->db->get();
				if($vid) {
					$vid = $vid->row()->ID;	
				}
			}
		}
		$data = array(
			'ID' => $formdata['ID'],
			'WEB_Vendor' => ($vid) ? $vid : $formdata['vendor'],
			'WEB_Url'=>$this->formatUrl($formdata['url']),
			'WEB_Active'=>'1',
			'WEB_Notes'=>$formdata['notes'],
			'WEB_ActiveTS'=>date(FULL_MILITARY_DATETIME),
			'WEB_Type'=>strtoupper($type).':' . $formdata['ID'],
			'WEB_Created'=>date(FULL_MILITARY_DATETIME)
		);
		if ($type != 'GID' && $type != 'UID') {
			$otherData = array(
				'WEB_GoogleUACode'=>$formdata['ua_code'],
				'WEB_GoogleWebToolsMetaCode'=>$formdata['meta_code_number'],
				'WEB_GooglePlusCode'=>$formdata['gplus_code'],
				'WEB_BingCode'=>$formdata['bing_code'],
				'WEB_YahooCode'=>$formdata['yahoo_code'],
				'WEB_GlobalScript'=>$formdata['global_code'],
			);
		}
		if ($type != 'GID' && $type != 'UID')
			$data = array_merge($data,$otherData);
		
		return ($this->db->insert('Websites',$data) ? TRUE : FALSE);
	}
	
	public function getWebsite($wid) {
		$get = 'w.WEB_ID as ID,
				w.WEB_Vendor as Vendor,
				w.WEB_GoogleUACode as GoogleUACode,
				w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,
				w.WEB_GooglePlusCode as GooglePlusCode,
				w.WEB_BingCode as BingCode,
				w.WEB_YahooCode as YahooCode,
				w.WEB_GlobalScript as GlobalScript,
				w.WEB_Type as Type,
				w.WEB_Url as URL,
				w.WEB_Notes as Description,
				w.WEB_Active as Status,
				w.WEB_ActiveTS as LastUpdate,
				w.WEB_Created as Created,
				v.VENDOR_Name as VendorName,
				v.VENDOR_Address as VendorAddress,
				v.VENDOR_Phone as VendorPhone,
				v.Vendor_Notes as VendorDescription,
				v.VENDOR_Active as VendorStatus';
		
		$this->db->select($get);
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID','left outer');
		$this->db->where('w.WEB_ID',$wid);
		//$this->db->where('w.CLIENT_ID',$cid);
		$website = $this->db->get();
		
		return ($website) ? $website->row() : FALSE;
	}
	
	public function getClientWebsites($cid,$wid=false) {
		$this->db->select('w.WEB_ID as ID,w.WEB_Vendor as Vendor,w.WEB_GoogleUACode as GoogleUACode,w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,w.WEB_GooglePlusCode as GooglePlusCode,w.WEB_BingCode as BingCode,w.WEB_YahooCode as YahooCode,w.WEB_GlobalScript as GlobalScript,w.WEB_Type as Type,w.WEB_Url as URL,w.WEB_Notes as Description,w.WEB_Active as Status,w.WEB_ActiveTS as LastUpdate,w.WEB_Created as Created,v.VENDOR_Name as VendorName,v.VENDOR_Address as VendorAddress,v.VENDOR_Phone as VendorPhone,v.Vendor_Notes as VendorDescription,v.VENDOR_Active as VendorStatus');
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID');
		$this->db->where('w.WEB_Type','CID:'.$cid);
		if($wid) {
			$this->db->where('w.WEB_ID',$wid);
		}
		$website = $this->db->get();
		
		return ($website) ? $website->result() : FALSE;
	}
	public function getVendorWebsites($vid,$wid=false) {
		$this->db->select('w.WEB_ID as ID,w.WEB_Vendor as Vendor,w.WEB_GoogleUACode as GoogleUACode,w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,w.WEB_GooglePlusCode as GooglePlusCode,w.WEB_BingCode as BingCode,w.WEB_YahooCode as YahooCode,w.WEB_GlobalScript as GlobalScript,w.WEB_Type as Type,w.WEB_Url as URL,w.WEB_Notes as Description,w.WEB_Active as Status,w.WEB_ActiveTS as LastUpdate,w.WEB_Created as Created,v.VENDOR_Name as VendorName,v.VENDOR_Address as VendorAddress,v.VENDOR_Phone as VendorPhone,v.Vendor_Notes as VendorDescription,v.VENDOR_Active as VendorStatus');
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID');
		$this->db->where('w.WEB_Type','VID:'.$vid);
		if($wid) {
			$this->db->where('w.WEB_ID',$wid);
		}
		$website = $this->db->get();
		
		return ($website) ? $website->result() : FALSE;
	}
	public function getContactWebsites($id,$wid=false) {
		$this->db->select('w.WEB_ID as ID,w.WEB_Vendor as Vendor,w.WEB_GoogleUACode as GoogleUACode,w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,w.WEB_GooglePlusCode as GooglePlusCode,w.WEB_BingCode as BingCode,w.WEB_YahooCode as YahooCode,w.WEB_GlobalScript as GlobalScript,w.WEB_Type as Type,w.WEB_Url as URL,w.WEB_Notes as Description,w.WEB_Active as Status,w.WEB_ActiveTS as LastUpdate,w.WEB_Created as Created,v.VENDOR_Name as VendorName,v.VENDOR_Address as VendorAddress,v.VENDOR_Phone as VendorPhone,v.Vendor_Notes as VendorDescription,v.VENDOR_Active as VendorStatus');
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID','left outer');
		$this->db->where('w.WEB_Type','GID:'.$id);
		if($wid) {
			$this->db->where('w.WEB_ID',$wid);
		}
		$website = $this->db->get();
		
		return ($website) ? $website->result() : FALSE;
	}
	public function getUserWebsites($uid,$wid=false) {
		$this->db->select('w.WEB_ID as ID,w.WEB_Vendor as Vendor,w.WEB_GoogleUACode as GoogleUACode,w.WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,w.WEB_GooglePlusCode as GooglePlusCode,w.WEB_BingCode as BingCode,w.WEB_YahooCode as YahooCode,w.WEB_GlobalScript as GlobalScript,w.WEB_Type as Type,w.WEB_Url as URL,w.WEB_Notes as Description,w.WEB_Active as Status,w.WEB_ActiveTS as LastUpdate,w.WEB_Created as Created,v.VENDOR_Name as VendorName,v.VENDOR_Address as VendorAddress,v.VENDOR_Phone as VendorPhone,v.Vendor_Notes as VendorDescription,v.VENDOR_Active as VendorStatus');
		$this->db->from('Websites w');
		$this->db->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID','left outer');
		$this->db->where('w.WEB_Type','UID:'.$uid);
		if($wid) {
			$this->db->where('w.WEB_ID',$wid);
		}
		$website = $this->db->get();
		
		return ($website) ? $website->result() : FALSE;
	}
	
	public function getContactTitle($id) {
		$sql = 'SELECT TITLE_Name as Name FROM xTitles WHERE TITLE_ID = "' . $id . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->row()->Name : FALSE;
	}
	
	public function getUsersByUserInfo($client_id) {
		$sql = 'SELECT ui.*,d.*,u.*,t.* 
				FROM Users_Info ui 
				INNER JOIN Directories d ON ui.DIRECTORY_ID = d.DIRECTORY_ID 
				INNER JOIN Users u ON ui.USER_ID = u.USER_ID INNER JOIN xTags t ON u.Team = t.TAG_ID WHERE ui.CLIENT_ID = "' . $client_id . '" ORDER BY d.DIRECTORY_LastName, d.DIRECTORY_FirstName ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function addNewUser($user,$directory,$userinfo) {
		$this->load->helper('pass');
		$this->load->helper('msg');
		$this->load->model('members');
		//Insert user
		$user_insert = $this->db->insert('Users',$user);
		//grab the user id after the insert has taken place
		$user_id = $this->db->insert_id();
		//insert the directory entry
		$directory_insert = $this->db->insert('Directories',$directory);
		//grab the directory id after the insert has taken place
		$directory_id = $this->db->insert_id();
		//if both the user and directory has been inserted successfully we need to create the user info entry
		if($user_insert && $directory_insert) {
			//create a random string for the password
			$password = createRandomString();
			//add more data because we know more....
			$userinfo['USER_ID'] = $user_id;
			$userinfo['DIRECTORY_ID'] = $directory_id;
			$userinfo['USER_Password'] = encrypt_password($password);
			$ui_insert = $this->db->insert('Users_Info',$userinfo);
			if($ui_insert) {
				$subject = 'Welcome to the Dealer Online Marketing Content Management System';
				$msg = email_new_user($user['USER_Name'],$password);
                $emailed = $this->members->email_results($user['USER_Name'], $subject, $msg);

				return TRUE;	
			}else {
				return FALSE;	
			}
		}else {
			return FALSE;	
		}
	}
	
	public function generateNewUserPassword($email,$pass) {
		
	}
	
	public function getAllTags() {
		$sql = 'SELECT TAG_ID as ID,TAG_Name as Name,TAG_Color as Color,TAG_Notes as Notes,TAG_Active as Status,TAG_ClassName as ClassName FROM xTags ORDER BY TAG_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllContactsInAgency($id) {
		$this->load->helper('phpext');

		$all_contacts = array();
		$asql = 'SELECT 
				 AGENCY_ID as AID
				 FROM Agencies
				 WHERE AGENCY_ID = "' . $id . '"
				 ORDER BY AGENCY_Name ASC';
		$agencies = $this->db->query($asql)->result();
		
		foreach($agencies as $agency) :
			
			$gsql = 'SELECT GROUP_ID as GID FROM Groups WHERE AGENCY_ID = "' . $agency->AID . '" ORDER BY GROUP_Name ASC';
			$groups = $this->db->query($gsql)->result();
			
			foreach($groups as $group) :
				$csql = 'SELECT CLIENT_ID as CID,CLIENT_Tag as Tag,Client_Name as Dealership FROM Clients WHERE GROUP_ID = "' . $group->GID . '" ORDER BY CLIENT_Name ASC';
				$clients = $this->db->query($csql)->result();
				
				foreach($clients as $client) :
					$contactsClients = $this->getContactsByTypeID('CID', $client->CID);
					$contactsGeneral = $this->getContactsByType('GID');
					$contactsUsers = $this->getContactsByType('UID');
					$contacts = array_merge_($contactsClients, $contactsGeneral);
					$contacts = array_merge_($contacts, $contactsUsers);
				endforeach; //end clients foreach
				
			endforeach; //end group foreach
		
		endforeach;	//end agency foreach
		
		return $contacts;
	}

	public function getAllContactsInGroup($id) {
		$this->load->helper('phpext');

		$csql = 'SELECT CLIENT_ID as CID,CLIENT_Tag as Tag,CLIENT_Code as ClientCode,Client_Name as Dealership FROM Clients WHERE GROUP_ID = "' . $id . '" ORDER BY CLIENT_Name ASC';
		$clients = $this->db->query($csql)->result();
		
		foreach($clients as $client) :
			$contactsClients = $this->getContactsByTypeID('CID', $client->CID);
			$contactsGeneral = $this->getContactsByType('GID');
			$contactsUsers = $this->getContactsByType('UID');
			$contacts = array_merge_($contactsClients, $contactsGeneral);
			$contacts = array_merge_($contacts, $contactsUsers);
		endforeach; //end clients foreach
		
		return $contacts;
	}
	
	public function getAllContactsInClient($cid) {
		$this->load->helper('phpext');
	
		$contactsClients = $this->getContactsByTypeID('CID', $cid);
		$contactsGeneral = $this->getContactsByType('GID');
		$contactsUsers = $this->getContactsByType('UID');
		$contacts = array_merge_($contactsClients, $contactsGeneral);
		$contacts = array_merge_($contacts, $contactsUsers);
	
		return $contacts;
	}
	
	public function getContactsByType($type) {
		$contacts = $this->getContacts($type, false);
		return $contacts->result();
	}
	
	public function getContactsByTypeID($type, $id) {
		$contacts = $this->getContacts($type, $id);
		return $contacts->result();
	}

	public function getContactByTypeID($type, $id) {
		$contact = $this->getContacts($type, $id);
		return $contact->row();
	}
	
	public function getContact($id) {
		$contact = $this->getContacts(false, $id);
		return $contact->row();
	}
	
	private function getContacts($type = false, $id = false) {
		$sql = 'SELECT
				d.DIRECTORY_ID as ContactID,
				d.TITLE_ID as Title,
				ti.TITLE_Name as TitleName,
				d.JobTitle as JobTitle,
				d.DIRECTORY_Type as Type,
				d.DIRECTORY_FirstName as FirstName,
				d.DIRECTORY_LastName as LastName,
				d.DIRECTORY_Address as Address,
				d.DIRECTORY_Email as Email,
				d.DIRECTORY_Primary_Email as PrimaryEmailType,
				d.DIRECTORY_Phone as Phone,
				d.DIRECTORY_Primary_Phone as PrimaryPhoneType,
				c.CLIENT_Tag as TagID,
				c.CLIENT_Name as Dealership,
				c.CLIENT_ID as DealershipID,
				c.CLIENT_Code as ClientCode,
				v.VENDOR_Name as VendorName,
				v.VENDOR_ID as VendorID,
				t.TAG_Name as TagName,
				t.TAG_Color as TagColor,
				t.TAG_ClassName as Tag,
				d.DIRECTORY_Notes as Notes
				FROM Directories d
				LEFT OUTER JOIN Clients c ON c.CLIENT_ID = '.((strtolower($type) == 'cid') ? $id : 'd.CLIENT_Owner').'
				LEFT OUTER JOIN xTags t on c.CLIENT_Tag = t.TAG_ID
				LEFT OUTER JOIN Vendors v ON v.VENDOR_ID = '.((strtolower($type) == 'vid') ? $id : 'd.DIRECTORY_Type').'
				INNER JOIN xTitles ti on d.TITLE_ID = ti.TITLE_ID ';
				// If both $type and $id, $id refers to $type.
				if ($type && $id)
					$sql .= 'WHERE d.DIRECTORY_Type = "'.$type.':'.$id.'" ';
				elseif ($type)
					$sql .= 'WHERE d.DIRECTORY_Type LIKE "'.$type.':%" ';
				// If just $id, $id refers to the directory id.
				elseif ($id)
					$sql .= 'WHERE d.DIRECTORY_ID = '.$id.' ';
				
		$contacts = $this->db->query($sql);
		return $contacts;
	}
	
	public function addContact($data) {
        return $this->db->insert('Directories', $data); 
    }
	
	public function getMyUser($id) {
		//our user select
    	$selects = "u.USER_ID as ID,
				    u.USER_Name as Username,
					u.USER_Name as EmailAddress,
					ui.USER_Active as Status,
					ui.USER_Created as JoinDate,
					ui.USER_ActiveTS as LastUpdate,
					ui.USER_Modules as Modules,
					ui.USER_Avatar as Avatar,
					ui.Google_Avatar,
					ui.USER_GravatarEmail as Gravatar,
					d.DIRECTORY_ID as DirectoryID,
					d.DIRECTORY_Type as UserType,
					d.DIRECTORY_FirstName as FirstName,
					d.DIRECTORY_LastName as LastName,
					d.DIRECTORY_Address as Address,
					d.DIRECTORY_Email as Emails,
					d.DIRECTORY_Primary_Email as PrimaryEmailType,
					d.DIRECTORY_Phone as Phones,
					d.DIRECTORY_Primary_Phone as PrimaryPhoneType,
					d.DIRECTORY_Notes as Notes,
					d.TITLE_ID as TitleID,
					a.ACCESS_NAME as AccessName,
					a.ACCESS_Level as AccessLevel,
					a.ACCESS_ID as AccessID,
					c.CLIENT_ID as ClientID,
					c.CLIENT_Name as Dealership,
					c.CLIENT_Address as CompanyAddress,
					t.TAG_ID as TagID,
					t.TAG_Name as TeamName,
					t.TAG_ClassName as ClassName,
					t.TAG_Color as Color";
		
		$query = $this->db->select($selects)
			 	 ->from('Users u')
			 	 ->join('Users_Info ui','ui.USER_ID = u.USER_ID','inner')
			 	 ->join('xSystemAccess a','ui.ACCESS_ID = a.ACCESS_ID','inner')
			 	 ->join('Directories d','ui.DIRECTORY_ID = d.DIRECTORY_ID','inner')
			 	 ->join('Clients c','c.CLIENT_ID = ui.CLIENT_ID','inner')
			 	 ->join('xTags t','t.TAG_ID = u.Team','inner')
			 	 ->where('u.USER_ID',$id)->get();
		
		return ($query) ? $query->row() : FALSE;
	}
	
    public function getUsers($id = false,$client_id = false) {
		
    	$selects = "u.USER_ID as ID,u.USER_Name as Username,u.USER_Name as EmailAddress,ui.USER_Active as Status,ui.USER_Created as JoinDate,ui.USER_ActiveTS as LastUpdate,ui.USER_Modules as Modules,ui.USER_Avatar as Avatar,ui.Google_Avatar,ui.USER_GravatarEmail as Gravatar,d.DIRECTORY_ID as DirectoryID,d.DIRECTORY_Type as UserType,d.DIRECTORY_FirstName as FirstName,d.DIRECTORY_LastName as LastName,d.DIRECTORY_Address as Address,d.DIRECTORY_EMAIL as Emails,d.DIRECTORY_Phone as Phones,d.DIRECTORY_Notes as Notes,d.TITLE_ID as TitleID,a.ACCESS_NAME as AccessName,a.ACCESS_Level as AccessLevel,a.ACCESS_ID as AccessID,c.CLIENT_Name as Dealership,c.CLIENT_Address as CompanyAddress,t.TAG_ID as TagID,t.TAG_Name as TeamName,t.TAG_ClassName as ClassName,t.TAG_Color as Color";
    	$this->db->select($selects);
		$this->db->from('Users u');
		$this->db->join('Users_Info ui','ui.USER_ID = u.USER_ID','inner');
		$this->db->join('xSystemAccess a','ui.ACCESS_ID = a.ACCESS_ID','inner');
		$this->db->join('Directories d','ui.DIRECTORY_ID = d.DIRECTORY_ID','inner');
		$this->db->join('Clients c','c.CLIENT_ID = ui.CLIENT_ID','inner');
		$this->db->join('xTags t','t.TAG_ID = u.Team','inner');
		
		if($id) :
			$this->db->where('u.USER_ID',$id);
		else :
			if($client_id) :
				$this->db->where('ui.CLIENT_ID',$client_id);
			else :
				$this->db->order_by('d.DIRECTORY_LastName','ASC');
			endif;
		endif; 
        $users = $this->db->get();
        if ($id) {
			$users = $users->row();
            $users->Address = isset($users->Address) ? mod_parser($users->Address) : '';
            $users->Email   = isset($users->Emails)  ? mod_parser($users->Emails,false,true)  : '';
            $users->Phone   = isset($users->Phones)  ? mod_parser($users->Phones,false,true)  : '';
        }else {
			$users = $users->result();	
		}
        return ($users) ? $users : FALSE;
    }

    public function getPermissionsList($userLevel) {
    	$this->db->select('ACCESS_ID as ID,ACCESS_Name as Name,ACCESS_Level as Level,ACCESS_Perm as Modules');
    	$this->db->from('xSystemAccess');
    	$this->db->where('ACCESS_Level <=', $userLevel);
    	$this->db->order_by('ACCESS_LEVEL','DESC');
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
    }

    public function getAgencies($id = false) {
    	$this->db->select('AGENCY_ID as ID,AGENCY_Name as Name,AGENCY_Notes as Description, AGENCY_Active as Status');
		$this->db->from('Agencies');
		if($id) {$this->db->where('AGENCY_ID',$id);}	
		$query = $this->db->get();
		return($query) ? $query->result() : FALSE;	
    }

    public function getGroups($aid = false,$gid = false) {
    	$this->db->select('g.GROUP_ID as GroupId,g.AGENCY_ID as AgencyId,g.GROUP_Name as Name,g.GROUP_Notes as Description,g.GROUP_Active as Status,g.GROUP_Created as CreateDate,a.AGENCY_Name as AgencyName,a.AGENCY_ID as AgencyId');
		$this->db->from('Groups g');
		$this->db->join('Agencies a','g.AGENCY_ID = a.AGENCY_ID');
		if($gid) {
			$this->db->where('g.GROUP_ID',$gid);
		}else {
			$this->db->where('g.AGENCY_ID',$aid);
		}
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
    }
	
	public function updateGroup($gid,$data) {
		$this->db->where("GROUP_ID",$gid);
		return ($this->db->update('Groups',$data)) ? TRUE : FALSE;
	}
	
	public function getGroup($gid) {
		$this->db->select('g.GROUP_ID as GroupId,g.AGENCY_ID as AgencyId,g.GROUP_Name as Name,g.GROUP_Notes as Description,g.GROUP_Active as Status,g.GROUP_Created as JoinDate,a.AGENCY_Name as AgencyName,a.AGENCY_ID as AgencyId');
		$this->db->from('Groups g');
		$this->db->join('Agencies a','g.AGENCY_ID = a.AGENCY_ID','left');
		$this->db->where('g.GROUP_ID',$gid);	
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
	}
	
	/*
	 * Enabling and disabling a client
	 */ 
	 
	 public function changeClientStatus($cid,$which) {
		$data = array(
			'CLIENT_Active' => ($which) ? 1 : 0
		);
		$this->db->where('CLIENT_ID',$cid);
		return ($this->db->update('Clients',$data)) ? TRUE : FALSE;
	 }
    
	/*
	 * TO RETURN A SINGLE CLIENT
	 * @PARAM $id = Client ID passed from controller.
	 * Will return a single client if the id is found, else it will return false
	 */
    public function getClient($id) {
    	$this->db->select('CLIENT_ID as ID, CLIENT_Name as Name, CLIENT_Address as Address, CLIENT_Phone as Phone, CLIENT_Primary_Phone as PrimaryPhoneType, CLIENT_Notes as Notes, GROUP_ID as GroupdID');
		$this->db->from('Clients');
		$this->db->where('CLIENT_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }
	
	public function getAllVendors() {
		$this->db->select('VENDOR_ID as ID,VENDOR_Name as Name,VENDOR_Address as Address,VENDOR_Phone as Phone,VENDOR_Primary_Phone as PrimaryPhoneType,VENDOR_Notes as Description');
		$this->db->from('Vendors');
		$this->db->where('VENDOR_Active','1');
		$vendors = $this->db->get();
		return ($vendors) ? $vendors->result() : FALSE;
	}

    public function getTypeList() {
    	$this->db->select('TITLE_ID as Id, TITLE_Name as Name');
		$this->db->from('xTitles');
		$this->db->order_by('TITLE_Name','ASC');
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
    }

    public function addAgencies($data) {
        if ($this->db->insert('Agencies', $data))
            return TRUE;
        else
            return FALSE;
    }
	
	public function addGroup($data) {
		if($this->db->insert('Groups',$data))
			return TRUE;
		else
			return FALSE;	
	}
	
	public function editGroup($data) {
       $this->db->where('GROUP_ID', $data['GROUP_ID']);
       return $this->db->update('Groups', $data);
	}
	
	public function addClient($data) {
		return ($this->db->insert('Clients',$data)) ? TRUE : FALSE;
		/*if($this->db->insert('Clients',$data)) {
			return $this->db->insert_id();
		}else {
			return FALSE;
		}*/
	}
	
	public function addReputation($group) {
		return ($this->db->insert_batch('Reputations',$group)) ? TRUE : FALSE;
	}
	
	public function editClient($data, $id) {
		$this->db->where('CLIENT_ID',$id);
		return $this->db->update('Clients',$data);	
	}

    public function getAgencyByID($id) {
    	$this->db->select('AGENCY_ID as ID,AGENCY_Name as Name,AGENCY_Notes as Description,AGENCY_Active as Status,AGENCY_Created as Created');
		$this->db->from('Agencies');
		$this->db->where('AGENCY_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    /*     * *********************************************************************
     *
     * These are for the session after you select something from the dropdown
     *
     * ********************************************************************** */

    public function getGroupID($id) { //Get the selected group id and the associated agency id
    	$this->db->select('GROUP_ID as GroupID,AGENCY_ID as AgencyID');
		$this->db->from('Groups');
		$this->db->where('GROUP_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    public function getClientID($id) { //get the client id with the associated group id
    	$this->db->select('CLIENT_ID as ClientID,GROUP_ID as GroupID');
		$this->db->from('Clients');
		$this->db->where('CLIENT_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    public function getAgencyID($id) { //get the agency id
    	$this->db->select('AGENCY_ID as AgencyID');
		$this->db->from('Agencies');
		$this->db->where('AGENCY_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }

    /* End session queries */

    /*     * ****************************************************************************************************
     *
     * These are the queries for the Admin/Groups section based on the selected item in the dealer dropdown
     *
     * **************************************************************************************************** */

    public function getSelectedGroup($id) { //Get the selected group while on group level
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName
                FROM Groups g
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.GROUP_ID = "' . $id . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->row() : FALSE;
    }
    
    public function getSelectedClient($id) {
		$this->db->select('c.CLIENT_ID as ClientID,
						   c.CLIENT_Name as Name,
						   c.CLIENT_Address as Address,
						   c.CLIENT_Phone as Phone,
						   c.CLIENT_Primary_Phone as PrimaryPhoneType,
						   c.CLIENT_Notes as Description,
						   c.CLIENT_Code as Code,
						   c.CLIENT_Tag as Tag,
						   c.CLIENT_Active as Status,
						   c.GROUP_ID as GroupID,
						   c.CLIENT_Created as JoinDate,
						   t.TAG_ClassName as ClassName');
		$this->db->from('Clients c');
		$this->db->join('xTags t','c.CLIENT_Tag = t.TAG_ID');
		$this->db->where('c.CLIENT_ID',$id);
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
    }
	
	public function getSelectedClientsReviews($client_id,$service_id) {
		//Using active record to select content from database
		$sql = 'SELECT ID,URL FROM Reputations WHERE ServicesID = "' . $service_id . '" AND ClientID = "' . $client_id . '" ORDER BY CreatedDate ASC LIMIT 1;';
		$result = $this->db->query($sql);
		$row = ($result) ? $result->row() : FALSE;
		return ($row) ? $row : FALSE;
	}	
	
	public function editReviews($data,$client_id,$services_id) {
		return ($this->db->update('Reputations', $data,array('ServicesID'=>$services_id,'ClientID'=>$client_id))) ? TRUE : FALSE; 
	}
	
	public function getClientByID($id) {
        $sql = 'SELECT
                g.GROUP_Name as GroupName,
                c.CLIENT_ID as ClientID,
                c.CLIENT_Name as Name,
                c.CLIENT_Code as ClientCode,
                c.CLIENT_Phone as PhoneNumber,
                c.CLIENT_Address as Address,
                c.CLIENT_Active as Status,
				t.TAG_ClassName as ClassName
                FROM Clients c
                INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
                WHERE c.CLIENT_ID = "' . $id . '" ORDER BY c.CLIENT_Name ASC;';		
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function updateClient($cid,$data) {
		$this->db->where('CLIENT_ID',$cid);
		return ($this->db->update('Clients',$data)) ? TRUE : FALSE;
	}
	
	public function updateSingleReputation($rep) {
		$this->db->where('ID',$rep['ID']);
		unset($rep['ID']);
		return ($this->db->update('Reputations',$rep)) ? TRUE : FALSE;
	}
	
	public function updateReputations($group) {
		if(count($group) > 0) {
			foreach($group as $reputation) {
				$update = $this->updateSingleReputation($reputation);
				if(!$update)
					break;
			}
		}
		$update = ($this->db->update_batch('Reputations',$group,'ID')) ? TRUE : FALSE;
		if($update) {
			return TRUE;	
		}else {
			$add = $this->addReviews($group);
			if($add) {
				return TRUE;	
			}else {
				return FALSE;	
			}
		}
	}
	
	public function doReviewsExist($client_id,$service_id) {
		$sql = 'SELECT ID FROM Reputations WHERE ClientID = "' . $client_id . '" AND ServicesID = "' . $service_id . '"';
		$query = $this->db->query($sql);
		return ($query) ? TRUE : FALSE;
	}

    public function getParentGroupOfClient($id) { //Get the selected clients parent group.
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName
                FROM Clients c
                INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE c.CLIENT_ID = "' . $id . '";';
		$query = $this->db->query($sql);
        return ($query) ? $query->row() : FALSE;
    }
    
    public function getAllClientsInAgency($agency_id) {
        //first we get the groups in the agency
        $groups = $this->getAllActiveGroupsInAgency($agency_id);
        
        $list = array();
        foreach($groups as $group) {
			
			$clients = $this->getAllClientsInGroup($group->GroupID);
			foreach($clients as $client) {
				array_push($list,$client);
			}
            //array_push($clients,$this->getAllClientsInGroup($group->GroupID));
        }
        return $list;
    }
    
    public function getAllActiveGroupsInAgency($id) { //get all groups in a agency
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName
                FROM Groups g
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.GROUP_Active = "1" AND g.AGENCY_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }


    public function getAllGroupsInAgency($id) { //get all groups in a agency
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName,
				t.TAG_ClassName as ClassName
                FROM Groups g
				INNER JOIN xTags t ON g.GROUP_Tags = t.TAG_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.AGENCY_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }
	
	public function getAllAgenciesForDropdown() {
		$query = $this->db->select('AGENCY_ID as ID,AGENCY_Name as Name,AGENCY_Active as Status')->from('Agencies')->where('AGENCY_Active',1)->get();
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getGroupsForDropdown($aid) {
		$query = $this->db->select('g.GROUP_ID as GroupID,g.GROUP_Name as Name,g.GROUP_Active as Status')->from('Groups g')->where('g.AGENCY_ID',$aid)->where('g.GROUP_Active',1)->get();
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getSelectedGroupResults($id) {
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Description,
				g.GROUP_Notes as Notes,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName,
				t.TAG_ClassName as ClassName
                FROM Groups g
				INNER JOIN xTags t ON g.GROUP_Tags = t.TAG_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.GROUP_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllGroupsInAgencyResults($id) {
        $sql = 'SELECT 
                g.GROUP_ID as GroupID,
                g.AGENCY_ID as AgencyID,
                g.GROUP_Name as Name,
                g.GROUP_Notes as Notes,
                g.GROUP_Active as Status,
                g.GROUP_Created as DateCreated,
                a.AGENCY_Name as AgencyName,
				t.TAG_ClassName as ClassName
                FROM Groups g
				INNER JOIN xTags t ON g.GROUP_Tags = t.TAG_ID
                INNER JOIN Agencies a ON g.AGENCY_ID = a.AGENCY_ID
                WHERE g.AGENCY_ID = "' . $id . '" ORDER BY g.GROUP_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
    
    public function getAllClientsInGroup($group_id) {
        $sql = 'SELECT
				c.GROUP_ID as GroupID,
                g.GROUP_Name as GroupName,
                c.CLIENT_ID as ClientID,
                c.CLIENT_Name as Name,
                c.CLIENT_Code as ClientCode,
                c.CLIENT_Phone as PhoneNumber,
                c.CLIENT_Address as Address,
                c.CLIENT_Active as Status,
				t.TAG_ClassName as ClassName
                FROM Clients c
                INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
                WHERE c.GROUP_ID = "' . $group_id . '" ORDER BY c.CLIENT_Name ASC;';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
    }
	
	public function getClientWebsitesByCID($cid) {
		$sql = 'SELECT url as URL,special_label	as SpecialLabel FROM ClientWebsites WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getClientCMSByCID($cid) {
		$sql = 'SELECT label as Label, url as URL, special_label as SpecialLabel FROM ClientCMS WHERE client_id = "' . $cid . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getClientCRMByCID($cid) {
		$sql = 'SELECT label as Label, url as URL FROM ClientCRM WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getClientDocsByCID($cid) {
		$sql = 'SELECT url as URL FROM ClientDocs WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getClientCrazyEggByCID($cid) {
		$sql = 'SELECT url as URL, special_label as SpecialLabel, label as Label FROM ClientCrazyEgg WHERE client_id = "' . $cid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}

    /* End Admin/Group Queries */

    public function updateAgencyInformation($id, $data) {
        $this->db->where('AGENCY_ID', $id);
        return ($this->db->update('Agencies', $data)) ? TRUE : FALSE;
    }
	
	public function updateUser($data) {
		if($this->db->update('Users',$data['Users'],'USER_ID = "' . $data['Users_Info']['USER_ID'] . '"') AND $this->db->update('Directories',$data['Directories'],'DIRECTORY_ID = "' . $data['Directories']['DIRECTORY_ID'] . '"') AND $this->db->update('Users_Info',$data['Users_Info'],'USER_ID = "' . $data['Users_Info']['USER_ID'] . '"')) {
			return TRUE;	
		}else {
			return FALSE;	
		}
	}
	
	public function updateContact($id, $data) {
		if($this->db->update('Directories',$data,'DIRECTORY_ID ="' . $id . '"')) {
			return TRUE;	
		}else {
			return FALSE;	
		}
	}
	
	public function getClientIDSInGroup($id) {
		$sql = 'SELECT CLIENT_ID as ID FROM Clients WHERE GROUP_ID = "' . $id . '"';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function getUsersOfClient($cid) {
		$this->db->select('u.USER_ID as ID,u.USER_Name as Username,d.DIRECTORY_FirstName as FirstName,d.DIRECTORY_LastName as LastName,ui.USER_Created as MemberSince');
		$this->db->from('Users u');
		$this->db->join('Users_Info ui','ui.USER_ID = u.USER_ID','right');
		$this->db->join('Directories d','d.DIRECTORY_ID = ui.DIRECTORY_ID','right');
		$this->db->where('d.CLIENT_Owner',$cid);
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
	}
	
	public function addReviews($data) {
		foreach($data as $review) {
			if($this->db->insert('Reputations',$review)) {
				return TRUE;	
			}else {
				return FALSE;	
			}
		}
	}
	
	public function addReview($data) {
		return ($this->db->insert('Reputations',$data)) ? TRUE : FALSE;	
	}
	
	/*Masterlist*/
	public function getMasterlist() {
		$agency = $this->session->userdata['valid_user']['DropdownDefault']->SelectedAgency;
		$sql = 'SELECT 
				w.special_label as Dealership,
				w.url as DealershipURL,
				w.doc as Doc,
				w.xls as Xls,
				w.egg as NewEgg,
				cms.service as CMSLabel,
				cms.url as CMSUrl,
				crm.service as CRMLabel,
				crm.url as CRMUrl,
				c.CLIENT_Code as Code,
				c.CLIENT_Active as Status,
				t.TAG_ClassName as Class,
				g.AGENCY_ID as AgencyID
				FROM ClientWebsites w
				INNER JOIN MasterlistBank cms ON w.cms = cms.id
				INNER JOIN MasterlistBank crm ON w.crm = crm.id
				INNER JOIN Clients c ON w.client_id = c.CLIENT_ID
				INNER JOIN xTags t ON c.CLIENT_Tag = t.TAG_ID
				INNER JOIN Groups g ON c.GROUP_ID = g.GROUP_ID
				WHERE g.AGENCY_ID = ' . $agency . '
				ORDER BY w.special_label ASC';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getAllTypes() {
		$sql = 'SELECT
				p.PASS_TYPE_ID as ID,
				p.PASS_TYPE_Name as Name
				FROM xPasswordTypes p
				ORDER BY p.PASS_TYPE_Name';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;		
	}
	
	// Returns ID of type if exist, else FALSE.
	public function hasPasswordType($typeName) {
		$this->db->where('PASS_TYPE_Name', $typeName);
		$query = $this->db->get('xPasswordTypes');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return ($result[0]->PASS_TYPE_ID);
		} else
			return FALSE;
	}
	
	public function addPasswordType($data) {
		$this->db->insert('xPasswordTypes',$data);
		return $this->hasPasswordType($data['PASS_TYPE_Name']);
	}
	
	public function addPasswords($data) {
		return $this->db->insert('Passwords',$data);
	}
	
	public function getPasswords($cid = FALSE) {
		return $this->getPasswordsList($cid, FALSE);
	}
	
	public function getPasswordsByID($id = FALSE) {
		return $this->getPasswordsList(FALSE, $id);
	}
	
	private function getPasswordsList($cid, $pwdid) {
		$this->db->select('
			c.CLIENT_ID as ClientID,
			p.PASS_ID as ID,
			tag.TAG_ClassName as Tag,
			t.PASS_TYPE_Name as Type,
			p.PASS_VendorID as VendorID,
			v.VENDOR_Name as Vendor,
			p.PASS_Rep as Rep,
			p.PASS_BestPhone as BestPhone,
			p.PASS_LoginAddress as LoginAddress,
			p.PASS_Username as Username,
			p.PASS_Password as Password,
			p.PASS_LeadRouting as LeadRouting,
			p.PASS_RoutingPhone as RoutingPhone,
			p.PASS_Terms as Terms,
			p.PASS_Budget as Budget,
			p.PASS_Notes as Notes'
		);
		$this->db->from('Passwords p');
		$this->db->join('xPasswordTypes t', 'p.PASS_TypeID = t.PASS_TYPE_ID', 'inner');
		$this->db->join('Vendors v', 'p.PASS_VendorID = v.VENDOR_ID', 'inner');
		$this->db->join('Clients c', 'p.PASS_ClientID = c.CLIENT_ID', 'inner');
		$this->db->join('xTags tag', 'c.CLIENT_Tag = tag.TAG_ID', 'inner');
		if ($cid) $this->db->where('p.PASS_ClientID', $cid);
		if ($pwdid) $this->db->where('p.PASS_ID', $pwdid);
		$this->db->order_by('p.PASS_Username', 'ASC');
		
		$query = $this->db->get();
		switch ($query->num_rows) {
			case 0:  return FALSE;
			default: return $query->result();
		}
	}
	
	public function editPassword($data, $id) {
		$this->db->where('PASS_ID',$id);
		return $this->db->update('Passwords',$data);
	}
	
	public function getVendors() {
		$this->db->select('VENDOR_ID as ID,VENDOR_Name as Name,VENDOR_Address as Address,VENDOR_Phone as Phone,VENDOR_Notes as Notes,VENDOR_Active as Status,VENDOR_ActiveTS as LastUpdate,VENDOR_Created as Created');
		$this->db->from('Vendors');
		$query = $this->db->get();
		
		if($query) {
			return $query->result();
		}else {
			return FALSE;
		}
		
	}

	public function hasVendor($vendorName) {
		$this->db->where('VENDOR_Name', $vendorName);
		$query = $this->db->get('Vendors');
		if ($query->num_rows() > 0) {
			$result = $query->result();
			return ($result[0]->VENDOR_ID);
		} else
			return FALSE;
	}
	
	public function addVendor($data) {
		$this->db->insert('Vendors',$data);
		return $this->hasVendor($data['VENDOR_Name']);
	}
	
	public function editVendor($vid,$data) {
		$this->db->where('VENDOR_ID',$vid);
		return ($this->db->update('Vendors',$data)) ? TRUE : FALSE;	
	}
	
	public function getVendor($id) {
		$this->db->select('VENDOR_ID as ID,VENDOR_Name as Name,VENDOR_Address as Address,VENDOR_Phone as Phone,VENDOR_Notes as Notes,VENDOR_Active as Status,VENDOR_ActiveTS as LastUpdate,VENDOR_Created as Created');
		$this->db->from('Vendors');
		$this->db->where('VENDOR_ID',$id);
		
		$query = $this->db->get();
		return ($query) ? $query->row() : FALSE;
	}

	public function disableVendor($id,$which = 'disable') {
		$data = array(
			'VENDOR_Active' => (($which != 'enable') ? 0 : 1)
		);
		$this->db->where('VENDOR_ID',$id);
		return ($this->db->update('Vendors',$data) ? TRUE : FALSE);
	}
	
	public function getSignatureFragment($user_id) {
		$this->db->select('USER_PREFS_Signature as Signature');
		$this->db->from('User_Prefs');
		$this->db->where('USER_ID',$user_id);

		$query = $this->db->get();
		return ($query) ? $query->row()->Signature : FALSE;
	}
	
	public function editContactInfoPhone($id, $oldPhone, $newPhone) {
		$this->db->set('DIRECTORY_Phone','REPLACE(DIRECTORY_Phone,"'.$oldPhone.'","'.$newPhone.'")',false);
		$this->db->where('DIRECTORY_ID',$id);
		return $this->db->update('Directories');
	}

	public function addContactInfoPhone($id, $newPhone) {
		$this->db->set('DIRECTORY_Phone','CONCAT(DIRECTORY_Phone,",'.$newPhone.'")',false);
		$this->db->where('DIRECTORY_ID',$id);
		return $this->db->update('Directories');
	}

	public function editContactInfoEmail($id, $oldEmail, $newEmail) {
		$this->db->set('DIRECTORY_Email','REPLACE(DIRECTORY_Email,"'.$oldEmail.'","'.$newEmail.'")',false);
		$this->db->where('DIRECTORY_ID',$id);
		return $this->db->update('Directories');
	}

	public function addContactInfoEmail($id, $newEmail) {
		$this->db->set('DIRECTORY_Email','CONCAT(DIRECTORY_Email,",'.$newEmail.'")',false);
		$this->db->where('DIRECTORY_ID',$id);
		return $this->db->update('Directories');
	}
	
	public function editPrimaryPhoneEmail($id, $phonePrimary, $emailPrimary) {
		$data = array(
			'DIRECTORY_Primary_Email' => $emailPrimary,
			'DIRECTORY_Primary_Phone' => $phonePrimary,
		);
		$this->db->set($data);
		$this->db->where('DIRECTORY_ID',$id);
		return $this->db->update('Directories');
	}
	
}