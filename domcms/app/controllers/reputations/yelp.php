<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Yelp extends DOM_Controller {
	public $selected_client;
    public function __construct() {
        parent::__construct();
        //loading the member model here makes it 
        //available for any member of the dashboard controller.
		$this->selected_client = $this->user['DropdownDefault']->SelectedClient;
    }
	
	public function Reviews() {
		$this->load->model('reviews');
		$client_id = ($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : $this->user['DropdownDefault']->SelectedGroup;
		$service = 2;
		$url = $this->reviews->getReviewURL($service,$client_id);
		$data = array(
			'url' => (($url) ? $url : false)
		);
		$this->LoadTemplate('pages/reputations/yelp', $data);
	}
}
