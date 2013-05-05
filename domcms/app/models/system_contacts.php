<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * =======================================================
 * 	 CONTACT TYPE LEGEND
 * =======================================================
 * @CID:  = Clients
 * @VID:  = Vendors
 * @UID:  = Users
 * @GID:  = Group Contact
 * @WID:  = Website Contact
 * @DID:  = General Contact (makes no sense but ok)
 */



class System_contacts extends DOM_Model {

	var $level;

    function __construct() {
        // Call the Model constructor
        parent::__construct();
		$this->load->model('administration','adminQueries');
    }
	
	public function getDirectoryInformation($type = false,$id = false) {
		$select = 'd.DIRECTORY_ID as ContactID,
				   d.OWNER_ID as OwnerID,
				   d.TITLE_ID as TitleID,
				   ti.TITLE_Name as TitleName,
				   d.JobTitle as JobTitle,
				   d.DIRECTORY_Type as OwnerType,
				   d.DIRECTORY_FirstName as FirstName,
				   d.DIRECTORY_LastName as LastName,
				   d.DIRECTORY_Notes as Notes';
				   
		$this->db->select($select)->from('Directories d')->join('xTitles ti','d.TITLE_ID = ti.TITLE_ID');
		if($type and !$id) {
			$this->db->where('d.DIRECTORY_Type',$type);	
		}elseif(!$type and $id) {
			$this->db->where('d.OWNER_ID',$id);	
		}else {
			$this->db->where('d.OWNER_ID',$id)->where('d.DIRECTORY_Type',$type);	
		}
		
		$query = $this->db->get();
		return ($query) ? $query->result() : FALSE;
	}
	
	function getContactsForTable($owner_type = false,$owner_id = false) {
		
	}
	
	//get websites
	public function buildContactTable() {
		
		$dsql = 'SELECT d.DIRECTORY_ID as DID,
					(SELECT 
						TITLE_Name as JobTitle 
						FROM xTitles 
						WHERE 
						d.TITLE_ID = xTitles.TITLE_ID
					) AS Title,
					(SELECT 
						xc.TYPE_Name 
						FROM xContactType xc 
						WHERE 
						d.DIRECTORY_Type = xc.TYPE_ID 
							AND 
						xc.TYPE_Active = 1
					) AS ContactType,
					CONCAT(d.DIRECTORY_LastName,", ", d.DIRECTORY_FirstName) AS Name,
					CONCAT(da.ADDRESS_Street," ",da.ADDRESS_City,", ",da.ADDRESS_State," ",da.ADDRESS_Zip) as Address,
					ph.PHONE_Number as PhoneNumber,
					em.EMAIL_Address as EmailAddress
					
					FROM Directories d 
					JOIN DirectoryAddresses da ON da.OWNER_ID = d.OWNER_ID AND da.OWNER_Type = d.DIRECTORY_Type AND da.ADDRESS_Primary = 1
					JOIN PhoneNumbers ph ON ph.OWNER_ID = d.OWNER_ID AND ph.OWNER_Type = d.DIRECTORY_Type AND ph.PHONE_Primary = 1
					JOIN EmailAddresses em ON em.OWNER_ID = d.OWNER_ID AND em.OWNER_Type = d.DIRECTORY_Type AND em.EMAIL_Primary = 1';
					
					$bkup = '					(SELECT 
						xc.TYPE_Name 
						FROM xContactType xc 
						WHERE 
						d.DIRECTORY_Type = xc.TYPE_ID 
							AND 
						xc.TYPE_Active = 1
					) AS ContactType,
					CONCAT(d.DIRECTORY_LastName,", ", d.DIRECTORY_FirstName) AS Name,
					(SELECT 
						CONCAT(ADDRESS_Street, " " , ADDRESS_City, ", " , ADDRESS_State, " ", ADDRESS_Zip) AS Address 
						FROM DirectoryAddresses 
						WHERE 
						OWNER_Type = d.DIRECTORY_Type 
							AND 
						OWNER_ID = d.OWNER_ID 
							AND 
						ADDRESS_Primary = 1 
							AND 
						ADDRESS_Active = 1
					) AS Address,
					(SELECT 
						EMAIL_Address 
						FROM EmailAddresses 
						WHERE 
						OWNER_Type = d.DIRECTORY_Type 
							AND 
						OWNER_ID = d.OWNER_ID 
							AND 
						EMAIL_Primary = 1 
							AND EMAIL_Active = 1
					) AS EmailAddress,
					(SELECT
						PHONE_Number
						FROM PhoneNumbers
						WHERE 
						OWNER_Type = d.DIRECTORY_Type
							AND
						OWNER_ID = d.OWNER_ID
							AND
						PHONE_Primary = 1
							AND
						PHONE_Active = 1
					) AS PhoneNumber,
					d.DIRECTORY_Notes AS Notes,
					(SELECT TAG_ClassName FROM xTags WHERE TAG_ID = d.DIRECTORY_Tag) AS ClassName	
';
		$query = $this->db->query($dsql);
		return ($query) ? $query->result() : FALSE;
	}	
	
	//Should cover all contacts
	function getSystemContacts($type,$id) {
		$directories = $this->getDirectoryInformation($type,$id);
		foreach($directories as $directory) {
			$directory->Phones = $this->getContactPhoneNumbers($directory->OwnerID,$type);	
			$directory->Emails = $this->getContactEmailAddresses($directory->OwnerID,$type);
			$directory->Addresses = $this->getContactPhysicalAddresses($directory->OwnerID,$type);
		}
	}
		
	//get user information from directory
	function getUserDirectoryInfo($uid) {		
		//query using active record codeigniter method
		$query = $this->db->select('DIRECTORY_ID as did,DIRECTORY_FirstName as FirstName,DIRECTORY_LastName as LastName,DIRECTORY_Tag as tid,OWNER_ID as OwnerID,DIRECTORY_Type as OwnerType')->
				 from('Directories')->
				 where('OWNER_ID',$uid)->
				 where('DIRECTORY_Type',3)->
				 get();
		
		//we should only find one result because the user id is unique to the user so we only return the row()
		//if we are returned a match we return the object back to the caller.
		return ($query) ? $query->row() : FALSE;
	}
	
	function getUserContactInfo($uid) {
		//collection array
		$contact_info = array();
		
		//directory information for the user
		$directory_info = $this->getUserDirectoryInfo($uid);
		
		//if the directory information is found and results are returned
		if(!empty($directory_info)) {
			//users contact numbers
			$phoneNumbers   = (($this->getContactPhoneNumbers($directory_info->OwnerID,3))      ? $this->getContactPhoneNumbers($directory_info->OwnerID,3)         : FALSE);
			//users email addresses
			$emails 	    = (($this->getContactEmailAddresses($directory_info->OwnerID,3))    ? $this->getContactEmailAddresses($directory_info->OwnerID,3)    : FALSE);
			//users physical addresses
			$address 		= (($this->getContactPhysicalAddresses($directory_info->OwnerID,3)) ? $this->getContactPhysicalAddresses($directory_info->OwnerID,3) : FALSE);
			
			$contact_info['directory'] = $directory_info;
			$contact_info['phones']    = (!empty($phoneNumbers)) ? $phoneNumbers : FALSE;
			$contact_info['address']   = (!empty($address))      ? $address      : FALSE;
			$contact_info['emails']    = (!empty($emails))       ? $emails       : FALSE;
			
			//return the info back to the caller
			return (!empty($contact_info)) ? $contact_info : FALSE;
		}else {
			return FALSE;
		}
	}
	
	function getVendorDirectoryInfo($vid) {
		//the where array, multiple where statements require the arguments to be presented from an array.
		$where = array('OWNER_ID'=>$vid,'DIRECTORY_Type',2);
		
		//query using the active record codeigniter method
		$query = $this->db->select('d.DIRECTORY_FirstName as FirstName,d.DIRECTORY_LastName as LastName,d.DIRECTORY_Tag as tid')->where($where)->get();
		
		//we should only find one result because the vendor id is unique to the vendor so we only return the row()
		//if we are returned a match we return the object back to the caller.
		return ($query) ? $query->row() : FALSE;
	}
	
	//get contact phone numbers as object (could be multiple, could be none or one)
	function getContactPhoneNumbers($oid,$otype) {
		//query using the active record codeigniter method
		$query = $this->db->select('*')->
				 from('PhoneNumbers')->
				 where('OWNER_ID',$oid)->
				 where('OWNER_Type',$otype)->
				 where('PHONE_Active',1)->
				 get();
		
		//we return the results back to the caller...if found return the object, if not return FALSE		 
		return ($query) ? $query->result() : FALSE;
	}
	
	//get contact phone numbers as object (could be multiple, could be none or one)
	function getSingleContactPhoneNumber($pid) {
		//query using the active record codeigniter method
		$query = $this->db->select('*')->
				 from('PhoneNumbers')->
				 where('PHONE_ID',$pid)->
				 get();
		
		//we return the results back to the caller...if found return the object, if not return FALSE		 
		return ($query) ? $query->row() : FALSE;
	}
	
	//update the phone number in question with the changes the user made from the front end form.
	function updateSingleContactPhoneNumber($pid,$data) {
		$this->db->where('PHONE_ID',$pid);
		return ($this->db->update('PhoneNumbers',$data)) ? TRUE : FALSE;
	}
	
	//update the phone number in question with the changes the user made from the front end form.
	function addSingleContactPhoneNumber($data) {
		return ($this->db->insert('PhoneNumbers',$data)) ? TRUE : FALSE;
	}
	
	function addSingleEmailAddress($data) {
		return ($this->db->insert('EmailAddresses',$data)) ? TRUE : FALSE;	
	}
	
	//update the phone number in question with the changes the user made from the front end form.
	function updateSingleEmailAddress($eid,$data) {
		$this->db->where('EMAIL_ID',$eid);
		return ($this->db->update('EmailAddresses',$data)) ? TRUE : FALSE;
	}
	
	//get contact email addresses as object (could be multiple, could be none or one)
	function getContactEmailAddresses($oid,$otype) {
		$query = $this->db->select('*')->
				 from('EmailAddresses')->
				 where('OWNER_ID',$oid)->
				 where('OWNER_Type',$otype)->
				 where('EMAIL_Active',1)->
				 get();	
				 
		return ($query) ? $query->result() : FALSE;
	}
	
	//get contact email addresses as object (could be multiple, could be none or one)
	function getSingleContactEmailAddress($eid) {
		$query = $this->db->select('*')->
				 from('EmailAddresses')->
				 where('EMAIL_ID',$eid)->
				 where('EMAIL_Active',1)->
				 get();	
				 
		return ($query) ? $query->row() : FALSE;
	}
	
	//get contact physical address as object (could be multiple, could be none or one)
	function getContactPhysicalAddresses($oid,$otype) {
		$query = $this->db->select('*')->
				 from('DirectoryAddresses')->
				 where('OWNER_ID',$oid)->
				 where('OWNER_Type',$otype)->
				 where('ADDRESS_Active',1)->
				 get();
				 
		return ($query) ? $query->result() : FALSE;	
	}
	
	function getDefaultContacts() {
		//empty container array
		$contacts = array();
		
		//client level contacts
		$clientLevel = $this->getClientContacts();
		
		//as long as the clientLevel didnt return an empty result we should run this loop
		if(!empty($clientLevel)) {
			//loop through client contacts and toss them into the container array
			foreach($clientLevel as $contact) { array_push($contacts,$contact); }
		}
		
		//vendor contacts
		$vendorLevel = $this->getVendorContacts($client->ClientID);
		
		//as long as the vendors didnt return an empty result we shoud run this loop.
		if(!empty($vendorLevel)) {
			//loop through vendor contacts and toss them into the container array
			foreach($vendorLevel as $vendor) { array_push($contacts,$vendor); }
		}
		
		//return all contacts
		return $contacts;
	}
	
	function getUserContacts() {
		$userTypeID = 3;
	}
	
	function getVendorContacts($cid) {
		//keep this at the top incase it changes, we wont have to go searching through code.
		$vendorTypeID = 2;
		
		$websites = $this->getOwnerWebsites($cid,$vendorTypeID);		
		//contact collection array
		$contacts = array();
		
		foreach($websites as $website):
			$vendorQuery = $this->buildDirectory(2,$website->VendorID);
			
			//if the websites has results from the db
			if($vendorQuery) {
				$vendorContacts = $vendorQuery->result();
				
				//loop through each vendor and collect them in our contacts array
				foreach($vendorContacts as $vendorContact) : $vendorContact->ClientORVendorName = $vendorContact->VendorName; array_push($clientContacts,$vendorContact); endforeach;
			}
		endforeach;
		
		//return all contacts from function.
		return $contacts;
	}
	
	//All contacts in the system based on the dealer dropdown
	function getContacts($type = false,$specific_owner_id = false) { 
	
		//instead of querying strings we can just query a table and look for an id. i know that the id for CID: is 1
		if(!$type) {
			$TypeID = 1;
		}else {
			$TypeID = $type;	
		}
	
		//this gets all clients from agencies,groups, or clients no matter what level your on
		//if your on agency, it gets every group in that agency along with every client in that group
		$myParentContainer = $this->adminQueries->getClientsByDDLevel();
		
		//empty array for all contacts to return 
		$myContacts = array();
		
		//loop through each client and get the contacts associated to the client from the db.
		//we have to grab them by the contact type. each contact has a column type string (ex:CID:1, VID:1)
		foreach($myParentContainer as $parent) :
			
			//get each contact on the client level (CID);
			$levelContacts = array();
			
			$myQuery = $this->buildDirectory($TypeID,($specific_owner_id) ? $specific_owner_id : 1);					 
			if($myQuery) {
				$contacts = $myQuery->result();
				
				foreach($contacts as $contact) : 
					$contact->ClientORVendorName = $myParentContainer->Name ; 
					array_push($contacts,$contact); 
				endforeach;
				
				//as long as the clientContacts didnt return empty, we push it anyway.
				if(!empty($contacts)) {
					array_push($myContacts,$contacts);	
				}
			}
			
		endforeach;
		
		//return contacts to the system
		return $contacts;
	}
	
	function buildDirectory($type,$owner) {
		$where = array('OWNER_ID'=>$ownder,'TYPE_ID'=>$type);
		$select = 'cl.DIRECTORY_ID as DirectoryID,
				   d.DIRECTORY_Type as ContactType,
				   d.DIRECTORY_FirstName as FirstName,
				   d.Directory_LastName as LastName,
				   d.DIRECTORY_Primary_Email as PrimaryEmail,
				   d.DIRECTORY_Primary_Phone as PrimaryPhone,
				   t.TITLE_Name as JobTitle,
				   type.TYPE_Name as ContactType';
				   
		$sql = $this->db->select($select)->
			   from('ContactLinkage cl')->
			   join('Directories d','d.DIRECTORY_ID = ci.DIRECTORY_ID')->
			   join('xTitles t','d.TITLE_ID = t.TITLE_ID')->
			   join('xContactType type','cl.TYPE_ID = type.TYPE_ID')->
			   where($where)->
			   get();
			   
		return ($sql) ? $sql->result() : FALSE;
	}
	
	
	//we need to get the websites associated to the parent to get the vendors contacts
	function getOwnerWebsites($oid,$type) {
		$sql = 'SELECT WEB_Vendor as VendorID FROM Websites WHERE OWNER_Type = "' . $type . '" AND OWNER_ID = "' . $oid . '";';
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
	function updatePrimaryPhone($pid,$did,$primary) {
		$reset_primary = 'UPDATE PhoneNumbers SET PHONE_Primary = "0" WHERE OWNER_ID = "' . $did . '"';
		$reset_query = $this->db->query($reset_primary);
		if($reset_query) {
			$sql = 'UPDATE PhoneNumbers SET PHONE_Primary = "' . $primary . '" WHERE PHONE_ID = "' . $pid . '"';
			$query = $this->db->query($sql);
			if($query) {
				return TRUE;	
			}
		}else {
			return FALSE;	
		}
	}
	
	function updatePrimaryEmail($eid,$did,$primary) {
		$reset_primary = 'UPDATE EmailAddresses SET EMAIL_Primary = "0" WHERE OWNER_ID = "' . $did . '"';
		$reset_query = $this->db->query($reset_primary);
		if($reset_query) {
			$sql = 'UPDATE EmailAddresses SET EMAIL_Primary = "' . $primary . '" WHERE EMAIL_ID = "' . $eid . '"';
			$query = $this->db->query($sql);
			if($query) {
				return TRUE;	
			}
		}else {
			return FALSE;	
		}
	}
}
	
