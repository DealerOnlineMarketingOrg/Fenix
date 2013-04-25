<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Reviews extends DOM_Model {
		
		function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
		}
		
		public function getReviewURL($service,$client_id) {
			$sql = 'SELECT URL from Reputations WHERE ClientID = "' . $client_id . '" AND ServicesID = "' . $service . '"';
			$result = ($this->db->query($sql)) ? $this->db->query($sql)->row() : false;
			return (($result) ? $result->URL : false);
		}
		
	}
