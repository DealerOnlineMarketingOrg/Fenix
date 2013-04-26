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
}
	
