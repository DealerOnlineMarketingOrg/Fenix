<?php

	function db_connect() {
		return mysql_connect('mysql51-016.wc1.ord1.stabletransit.com','718973_jeremyd','Q?noodle*09');
	}
	
	function debug($data) {
		echo '<pre>';
		print_r($data);
		echo '</pre>';	
	}

	function import_data() {
		$key = '10000004725';
		
		//collect the results in an array.
		$results = array();
		
		
		//Connect to the database to get client information
		$con = db_connect();
		
		//if the connection established, loop through and toss the data to the db
		if(!$con) :
			//The db couldnt connect show an error.
			die('Could not connect: ' . mysql_error());
		else :
			//select a database
			mysql_select_db('718973_ci_domcms', $con);
			
			//select statement query that grabs the listing ids from the db
			$sql = 'SELECT cg.Key,c.CLIENT_ID as ClientID FROM Clients c INNER JOIN CityGridKeys cg ON c.CLIENT_ID = cg.CLIENT_ID ORDER BY cg.KEY_ID ASC';
			$result = mysql_query($sql);
			$data = array();
			//iterate through each client key and hit each URL and import our data.
			while($row = mysql_fetch_array($result)) {
				//load the xml file with our keys from the db and our customer key
				$reviews = array();
				$reviews['listing_id'] = $row['Key'];
				$reviews['client_id'] = $row['ClientID'];
				$myData = simplexml_load_file("http://api.citygridmedia.com/content/reviews/v2/search/where?listing_id=" . $row['Key'] . "&rpp=50&sort=createdate&customer_only=false&publisher=" . $key);
				
				$reviews['db'] = iterate($myData);
				
				array_push($data,$reviews);
			}

		endif;
		
		echo '<pre>';
		print_r($data);
		echo '</pre>';
		
	}
	
	function iterate($item) {
		$high = 0;
		$mid  = 0;
		$low  = 0;
		$avg  = 0;
		$sum  = 0;

		$collection = array();
		$ratings    = array();
		
		foreach($item as $reviewData) {
			$review = $reviewData->review;
			foreach($review as $entry) {
				if($entry->review_rating >= 8) {
					$high = $high + 1;	
				}elseif($entry->review_rating <= 5) {
					$low = $low + 1;	
				}elseif($entry->review_rating <= 7 OR $entry->review_rating >= 4) {
					$mid = $mid + 1;	
				}
				
				$avg = $avg + $entry->review_rating;
				$sum = $sum + 1;
			}
		}
		
		$avg = round($avg / $sum);
		$collection['high'] = $high;
		$collection['mid']  = $mid;
		$collection['low']  = $low;
		$collection['avg']  = $avg;
		$collection['sum']  = $sum;
		
		//array_push($dbData,$collection);
			
		return $collection;	
	}
	
	import_data();
	
?>