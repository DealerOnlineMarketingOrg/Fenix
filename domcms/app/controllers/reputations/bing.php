<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bing extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->activeNav = 'reputation';
    }
	
    public function Dashboard() {
		$feed = simplexml_load_file("http://api.citygridmedia.com/content/reviews/v2/search/where?listing_id=10636826&publisher=10000004725");
		$ratings = array();
		$reviews = array();
		
		$highRatings = 0;
		$midRatings = 0;
		$lowRatings = 0;
		
		//print_object($feed->reviews);
		
		//Loop through feed and grab some values and build a array we can work with.
		foreach($feed->reviews as $review) :
			foreach($review as $reviewData) {
				array_push($reviews,$reviewData);
			}
		endforeach;
		foreach($reviews as $review) {
			foreach($review->review_rating as $rating) {
				array_push($ratings, (int)$rating);
			}
		}
		
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
			'bingReviewCount' => count($reviews),
			'bingAvgRating' => (array_sum($ratings) / (count($ratings))),
			'highRatings' => $highRatings,
			'midRatings' => $midRatings,
			'lowRatings' => $lowRatings
		);
		
		$this->LoadTemplate('pages/reputations/bing/dashboard',$data);
    }

}
