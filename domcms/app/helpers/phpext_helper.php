<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// Extensions to php functionality.
	
	// Converts a number to a string, with a specified floating-point precision.
	// Strips all trailing floating point 0's beyond precision.
	// If $floatPrecision = TRUE, will trim all trailing decimal 0's.
	// Allows conversions of the following:
	//   All strings. Be careful what data the string has. It will convert it, even if it's not numeric.
	//   All integers or floating numbers.
	//   Strings ending with % are treated as percentage.
	//   Strings starting with $ are treated as currency.
	// For rounding purposes, function truncates.
	function numberToString($number, $precision, $floatPrecision = FALSE) {		
		// Pre-processing: determine data-type if it's a general string, an integer, or a floating-point.
		// As gettype does, we'll use double for floats.
		// Also determine number-type if it's a number, currency or percentage.
		$data_type = '';
		$number_type = '';
		$int_part = '';
		$float_part = '';
		
		$sNum = (string)$number;
		$num_part = '';
		// Currency.
		if (substr($sNum,0,1) == '$') {
			$num_part = substr($sNum,1);
			if (is_numeric($num_part))
				$number_type = 'currency';
		}
		// Percentage.
		else if (substr($sNum,strlen($sNum)-1,1) == '%') {
			$num_part = substr($sNum,0,strlen($sNum)-1);
			if (is_numeric($num_part))
				$number_type = 'percentage';
		}
		// General number.
		else if (is_numeric($sNum)) {
			$num_part = $sNum;
			$number_type = 'number';
		}
		if ($num_part != '') {
			$dpLoc = strpos($num_part, '.');
			if ($dpLoc == FALSE) {
				$data_type = 'integer';
				$int_part = $num_part;
			} else {
				$data_type = 'double';
				$int_part = substr($num_part, 0, $dpLoc);
				$float_part = substr($num_part, $dpLoc+1);
			}
		} else {
			$data_type = 'string';
			// no int or float parts, but attempt to convert anyways.
			$int_part = (string)$sNum;
		}
		
		if ($float_part != '') {
			// Trim floating point back to $precision.
			$float_part = substr($float_part, 0, $precision);
			
			// Trim off trailing 0's, dependent on $floatPrecision.			
			if ($floatPrecision)
				$minLen = 0;
			else
				$minLen = $precision;
			while (strlen($float_part) > $minLen) {
				$len = strlen($float_part);
				if (substr($float_part, $len-1, 1) == '0')
					$float_part = substr($float_part, 0, $len-1);
				else
					break;
			}
		}
		
		// Build string back up.
		if ($number_type == "currency")
			$numString = '$' . $int_part . ($float_part == '' ? '' : '.' . $float_part);
		else if ($number_type == "percentage")
			$numString = $int_part . ($float_part == '' ? '' : '.' . $float_part) . '%';
		else if ($number_type == 'number')
			$numString = $int_part . ($float_part == '' ? '' : '.' . $float_part);
		else
			$numString = $int_part;
		
		return $numString;
		
	}
	
	// array_merge that can merge empty arrays as well as filled ones.
	function array_merge_(array $array1, array $array2) {
		if (IsSet($array1))
			$mergedArray = array_merge($array1, $array2);
		else
			$mergedArray = array_merge($array2);
		return $mergedArray;
	}
	
	// array_merge_recursive that can merge empty arrays as well as filled ones.
	function array_merge_recursive_(array $array1, array $array2) {
		if (IsSet($array1))
			$mergedArray = array_merge_recursive($array1, $array2);
		else
			$mergedArray = array_merge_recursive($array2);
		return $mergedArray;
	}