<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagdrop extends CI_Model {
	
	function __construct(){
		// Call the Model constructor
        parent::__construct();
		$this->load->helper('query');
    }
	
	public function TagsQuery($t_id) {
		$sql ="SELECT CLIENT_IDS FROM ClientTags WHERE TAG_ID =" . ($t_id);
		$query = $this->db->query($sql);
		return ($query) ? $query->result() : FALSE;
	}
	
}
?>