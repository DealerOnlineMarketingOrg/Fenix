<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Getdpr extends CI_Model {
	function __construct(){
		// Call the Model constructor
        parent::__construct();
		$this->load->model('rep');
		$this->load->helper('msg_helper');
		$this->load->helper('err_helper');
   }

	// Outputs $data into an <option list.
	// Insert command where $data should be inserted.
	// value will be set to $data->Name
	public function output_as_options($data) {
		$output = '';
		foreach ($data as $item)
		  $output .= '<option value="' . $item->ID . '">' . $item->Name . '</option> ';
		return $output;
	}
	
	// Retrieves the Provider or Service data from the DRP
	//  db and returns the data
	public function get($which, $id = FALSE) {
		switch ($which)
		{
			case ('Provider'):
				if ($id)
					$data = $this->rep->getProvider($id, 'PROVIDER_ID');
				else
					$data = $this->rep->getProviders('PROVIDER_ID');
				break;
			case ('Service'):
				if ($id)
					$data = $this->rep->getService($id, 'SERVICE_ID');
				else
					$data = $this->rep->getServices('SERVICE_ID');
				break;
		}
		
		return $data;
	}
	
}