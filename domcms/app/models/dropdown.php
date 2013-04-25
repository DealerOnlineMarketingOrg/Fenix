<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dropdown extends CI_Model {
	function __construct(){
		// Call the Model constructor
        parent::__construct();
		$this->load->helper('query');
    }
	
	public function getAllAgencies() {
		$sql = "SELECT 
				a.AGENCY_ID as AgencyID, 
				a.AGENCY_Name as AgencyName, 
				a.AGENCY_Active as AgencyStatus
			    from Agencies a WHERE a.AGENCY_Active = '1'
				ORDER BY a.AGENCY_ID ASC";
		return query_results($this,$sql);
	}
	
	public function getGroupByAgencyID($id) {
		$sql = "SELECT g.GROUP_ID as GroupID, g.GROUP_Name as GroupName,g.GROUP_Active as GroupStatus from Groups g WHERE g.AGENCY_ID = '" . $id . "' AND g.GROUP_Active = '1' ORDER BY g.GROUP_ID ASC;";
		return query_results($this,$sql);
					
	}
	
	public function getClientsByGroupID($id) {
		$sql = "SELECT c.CLIENT_ID as ClientID,c.CLIENT_Name as ClientName,c.CLIENT_Active as ClientStatus from Clients c WHERE c.GROUP_ID = '" . $id . "' AND c.CLIENT_Active = '1' ORDER BY c.CLIENT_ID ASC;";
		return query_results($this,$sql);
	}

	public function AgenciesQuery($a_id = false, $expect_row = false) {
		$sql ="SELECT * FROM Agencies WHERE AGENCY_Active = 1 " . (($a_id) ? "AND AGENCY_ID = '" . $a_id . "'" : '') . " ORDER BY AGENCY_Name";
		return query_results($this,$sql);
	}

	public function GroupsQuery($g_id = false, $a_id = false, $expect_row = false) {
		$sql = "SELECT * FROM Groups WHERE " . (($g_id) ? "GROUP_ID = '" . $g_id . "' " : " AGENCY_ID = '" . $a_id . "' ") . "AND GROUP_Active = '1' ORDER BY GROUP_Name;";
		return query_results($this,$sql);
	}

	//WHY cant we use the query helper here?? This needs to be fixed to use the query helper so we can log db errors to a table for support down the road.
	public function ClientQuery($c_id = false ,$g_id = false, $expect_row = false) {
		$sql = "SELECT * FROM Clients WHERE";
		
		if($c_id) { 
			$sql .= " CLIENT_ID = '" . $c_id . "'";	
		}else { 
			$sql .= " GROUP_ID = '" . $g_id . "'";
		}
		
		$sql .= " AND CLIENT_Active = '1' ORDER BY CLIENT_Name;";
		
		$query = $this->db->query($sql);
		if(!$expect_row) {
			return $query->result();
		}else {
			return $query->row();	
		}
		
	}

	public function TagQuery(){
		$sql = "SELECT * FROM xTags WHERE TAG_Active= 1";
		return query_results($this,$sql);
	}

	public function Group_Selected_Check($id){
		$sql = "SELECT CLIENT_ID FROM Clients WHERE GROUP_ID='".$id."'";
		return query_results($this,$sql);
	}
}
