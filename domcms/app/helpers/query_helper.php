<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

	//USE THIS HELPER TO CHECK AND COUNT ROWS
	function query_results($context = false,$sql) {
		$query = $context->db->query($sql);
		if(!$query) :
			//If no results are found return false to the system
			return FALSE;	
		else :
			//else if more than one result is detected we use the result() function
			if($query->num_rows() > 1) {
				return $query->result();
			}else {
				return $query->row();	
			}
		endif;
	}
	
	function query_row($context,$sql) {
		$query = $context->db->query($sql);
		if(!$query) :
			return FALSE;
		else :
			return $query->row();
		endif;	
	}
	
	