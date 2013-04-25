<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clients extends CI_Model {
	public function getClientPhone() {
		$sql = 'SELECT CLIENT_Yelp_ID as Phone from Clients WHERE CLIENT_ID = "' . $this->user['DropdownDefault']->SelectedClient . '";';
		$results = $this->db->query($sql);
		return ($results) ? $results->row()->Phone : false;
	}
	public function getCityGridListingID() {
		$sql = 'SELECT cg.Key FROM Clients c INNER JOIN CityGridKeys cg on c.CLIENT_ID = cg.CLIENT_ID';
		$results = $this->db->query($sql);
		return ($results) ? $results->row() : false;
	}
}