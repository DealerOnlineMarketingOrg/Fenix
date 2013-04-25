<?php

	/*

		YOU CAN USE THIS HELPER TO PRINT DATA TO THE SCREEN
		COMMENT OUT ANY LOADING OF VIEWS FIRST.

	*/


	function print_object($data) {
		if(!empty($data)) :
			echo 'Code: <br />' . "\n";
			echo '<pre>';
				print_r($data);
			echo '</pre>';
			echo '<br />--------------------<br />';
		else :
			echo 'CODE: The object is empty';
		endif;
	}
	function print_rr($data)
	{print_object($data);}

	/*
		CREATE AN ARRAY OF ARRAYS TO LOOP THROUGH AND PRINT TO SCREEN
		SO DATA[ARRAY] => OBJECT , DATA[ARRAY] => OBJECT
	*/

	function print_multiple_objects($data) {

		echo 'Code: <br />' . "\n";
			//used for counting
			$i = 1;

			foreach($data as $array) {
				if(!empty($array)) :
					echo 'Array[' . $i . ']:<br />';
					echo '<pre>';
					print_r($array);
					echo '</pre>';
					echo '<br />===================<br />';
					//increment
					$i = $i + 1;
				else :
					echo 'Array[' . $i . ']: The Object is empty. Moving On';
				endif;
			}
		//ending
		echo '<br />--------------------<br />';

	}

	/*
		DUMP SINGLE OBJECT TO SCREEN
	*/

	function dump_object($data) {
		echo 'Dump Code: <br />' . "\n";
		echo '<pre>';
		if(!empty($data)) :
			var_dump($data);
		else :
			echo 'The Object is empty';
		endif;
		echo '<pre>';
		echo '<br />----------------------';
	}


	/*
		CREATE AN ARRAY OF OBJECTS TO LOOP THROUGH AND DUMP TO SCREEN
		SO DATA[ARRAY] => OBJECT, DATA[ARRAY] => OBJECT
	*/
	function dump_multiple_objects($data) {

		echo 'Code: <br />' . "\n";
			//used for counting
			$i = 1;

			foreach($data as $array) {
				if(!empty($array)) :
					echo 'Array[' . $i . ']:<br />';
					echo '<pre>';
					var_dump($array);
					echo '<pre>';
					echo '===================<br />';
					//increment
					$i = $i + 1;
				else :
					echo 'Array[' . $i . ']: The Object is empty.';
				endif;
			}
		//ending
		echo '<br />--------------------';

	}

?>