<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	function dateToMonth($date) {return date('n',strtotime($date));}
	function dateToYear($date) {return date('Y',strtotime($date));}
	// Returns a date set to the specified bounds.
	function setToBounds($lowerDate, $upperDate, $date) {
		// If date is out of bounds, set it to the closest bounded date.
		if (strtotime($date) < strtotime($lowerDate))
			$boundDate = $lowerDate;
		elseif (strtotime($date) > strtotime($upperDate))
			$boundDate = $upperDate;
		else
			$boundDate = $date;
		return $boundDate;
	}
	// Creates a month-year set of date buttons, bounded by
	//  lowerDate and upperDate.
	function dateButtons($lowerDate, $upperDate, $date, $id) {
		$date = setToBounds($lowerDate, $upperDate, $date);
		
		if ($id == 'start')
			$startMonth = 1;
		else
			$startMonth = (dateToYear($lowerDate) == dateToYear($date)) ? dateToMonth($lowerDate) : 1;
		if ($id == 'end')
			$endMonth = 12;
		else
			$endMonth = (dateToYear($upperDate) == dateToYear($date)) ? dateToMonth($upperDate) : 12;
		monthButton($startMonth,$endMonth,dateToMonth($date),$id.'Month');
		
		$startYear = (dateToYear($lowerDate) == dateToYear($date)) ? dateToYear($date) : dateToYear($lowerDate);
		$endYear = (dateToYear($upperDate) == dateToYear($date)) ? dateToYear($date) : dateToYear($upperDate);
		yearButton($startYear,$endYear,dateToYear($date),$id.'Year');
	}
	
	function monthButton($start, $end, $currentMonthNum, $id) {
		echo '<select id="'.$id.'" name="'.$id.'" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a month...">';
		$months = array('January','Febuary','March','April','May','June','July','August','September','October','November','December');
		for ($i = $start; $i <= $end; $i++)
			echo '<option value="'.$i.'"' . (($currentMonthNum == $i) ? ' selected' : '') . '>'.$months[$i-1].'</option>';
		echo '</select>';
	}
	
	function yearButton($start, $end, $currentYear, $id) {
		echo '<select id="'.$id.'" name="'.$id.'" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a year...">';
		for ($i = $start; $i <= $end; $i++)
			echo '<option value="'.$i.'"' . (($currentYear == $i) ? ' selected' : '') . '>'.$i.'</option>';
		echo '</select>';
	}