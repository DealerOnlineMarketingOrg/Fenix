<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reputation_api extends CI_Model {
	
	public $keys;
	
	public function __construct() {
		parent::__construct();
		$this->keys = array(
			'yelp' => 'zTlBHWGPcRKWN6GufxFPLw',
			'bing' => '10000004725'
		);
		$this->load->model('clients');
		$this->load->helper('template');

	}
	
	public function getYelpData() {
		$client_phone = $this->clients->getClientPhone($this->user['DropdownDefault']->SelectedClient);
		$phone = strip_chars_from_phone($client_phone);
		if(!$client_phone) :
			return FALSE;
		else :
			return json_decode(file_get_contents('http://api.yelp.com/phone_search?phone=' . $phone . '&ywsid=' . $this->keys['yelp']));
		endif;
	}
	
	public function getBingData() {
		$listing_id = $this->clients->getCityGridListingID()->Key;
		if($listing_id) :
			$bing = simplexml_load_file("http://api.citygridmedia.com/content/reviews/v2/search/where?listing_id=" . $listing_id . "&rpp=50&customer_only=false&publisher=" . $this->keys['bing']);	
			
			//$bing = simplexml_load_file("http://api.citygridmedia.com/content/reviews/v2/search/where?listing_id=7150733&rpp=50&customer_only=false&publisher=" . $this->keys['bing']);
			if($bing) :
				$ratings = array();
				$reviews = array();
				$highRatings = 0;
				$midRatings  = 0;
				$lowRatings  = 0;
				
				//Loop through feed and grab some values and build a array we can work with.
				foreach($bing->reviews as $review) :
					foreach($review as $reviewData) {
						array_push($reviews,$reviewData);
					}
				endforeach;
				
				//push all ratings in
				foreach($reviews as $review) {
					foreach($review->review_rating as $rating) {
						array_push($ratings, (int)$rating);
					}
					
				}
				
				
				//catigorize the ratings
				foreach($ratings as $rating) {
					if($rating >= 8) {
						$highRatings = $highRatings + 1;	
					}elseif($rating <= 5) {
						$lowRatings = $lowRatings + 1;	
					}elseif($rating <= 7 OR $rating >= 4) {
						$midRatings = $midRatings + 1;	
					}
				}
				
				$data = array(
					'high' => $highRatings,
					'low' => $lowRatings,
					'mid' => $midRatings,
					'total' => count($reviews),
					'content' => $reviews
				);
				
				return $data;
				
			endif;
		else :
			return FALSE;
		endif;
	}
}