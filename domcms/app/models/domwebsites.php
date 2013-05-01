<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Domwebsites extends DOM_Model {
    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function getWebsite($web_id) {
		$sql = 'SELECT WEB_ID as ID, OWNER_ID as OwnerID,OWNER_Type as OwnerType,WEB_Url as URL,WEB_Notes as Notes,WEB_GoogleUACode as GoogleUACode,WEB_GoogleWebToolsMetaCode as GoogleWebToolsMetaCode,WEB_GooglePlusCode as GooglePlusCode,WEB_BingCode as BingCode,WEB_YahooCode as YahooCode,WEB_GlobalScript as GlobalScript,WEB_CallTrackingCode as CallTrackingCode,WEB_GoogleUA_Universal as GoogleUAUniversal,WEB_GoogleUA_Universal_Name as GoogleUAUniversalName, WEB_Vendor as VendorID FROM Websites WHERE WEB_ID = "' . $web_id . '"';
		$query = $this->db->query($sql);
		return (($query) ? $query->row() : FALSE);
	}
	
	//This is to build the listing tables
	public function getWebsites($oid = false,$oType = false) {
		//select statement
		$select = 'w.WEB_ID as ID, w.OWNER_ID as OwnerID,w.OWNER_Type as OwnerType,v.VENDOR_Name as VendorName,v.VENDOR_ID as VendorID,w.WEB_Url as URL,w.WEB_Notes as Notes';
		
		//using the active record method, select and join the correct tables.
		$query = $this->db->select($select)->from('Websites w')->join('Vendors v','w.WEB_Vendor = v.VENDOR_ID')->where('w.OWNER_ID',$oid)->where('w.OWNER_Type',$oType)->get();		
		return ($query) ? $query->result() : FALSE;
	}
	
	function fillDropdownVendors() {
		$query = $this->db->select('VENDOR_Name as VendorName,VENDOR_ID as VendorID')->from('Vendors')->where('VENDOR_Active',1)->get();
		return ($query) ? $query->result() : FALSE;	
	}
	
	public function addWebsite($formdata,$owner_id,$owner_type) {
		$data = array(
			'OWNER_ID' => $owner_id,
			'OWNER_Type'=>$owner_type,
			'WEB_Vendor' => $formdata['vendor'],
			'WEB_GoogleUACode'=>$formdata['ua_code'],
			'WEB_GoogleWebToolsMetaCode'=>$formdata['meta_code_number'],
			'WEB_GooglePlusCode'=>$formdata['gplus_code'],
			'WEB_BingCode'=>$formdata['bing_code'],
			'WEB_YahooCode'=>$formdata['yahoo_code'],
			'WEB_GlobalScript'=>$formdata['global_code'],
			'WEB_Url'=>$formdata['url'],
			'WEB_Active'=>1,
			'WEB_Notes'=>$formdata['notes'],
			'WEB_Created'=>date(FULL_MILITARY_DATETIME)
		);
		return ($this->db->insert('Websites',$data) ? TRUE : FALSE);
	}
	
	public function updateWebsite($data,$web_id) {
		$this->db->where('WEB_ID',$web_id);
		return ($this->db->update('Websites',$data)) ? TRUE : FALSE;	
	}
}
