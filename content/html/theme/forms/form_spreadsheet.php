<?php
	class cell {
		public $name = '';
		public $value = '';
		public $selected = false;
		public $width = '65px';
		public $height = '15px';
	}
	
	$tableClass = 'mutable-table';
	$cellClass = 'mutable-table-cell';
	$editDivClass = 'mutable-table-cell-div';

	// Add a special empty column and row header.
	$columns = array_merge(array(''), $columns);
	$rows = array_merge(array(''), $rows);
	
	// Create 2D table array.
	$cells = array();
	$c = 0;
	foreach ($columns as $column) {
		$cells[$c] = array();
		$r = 0;
		foreach ($rows as $row) {
			$cells[$c][$r] = new cell();
			$r++;
		}
		$c++;
	}
	
	// Fill table array.
	$c = 0;
	foreach ($columns as $column) {
		$r = 0;
		foreach ($rows as $row) {
			$cells[$c][$r]->name = $column.':'.$row;
			// $values is for the body.
			if ($c == 0 || $r == 0) {
				$cells[$c][$r]->value = '&nbsp;';
			} else {
				if (isset($values))
					// $values is offset by the column and row headers.
					$cells[$c][$r]->value = $values[$c-1][$r-1]->value;
				else
					$cells[$c][$r]->value = '&nbsp;';
			}
			$r++;
		}
		$c++;
	}

	// The javascript file for the mutable table.
	echo '<script type="text/javascript" src="'.base_url().'js/mutableTable.js"></script>';

	// Generate table.
	$table = '<table class="'.$tableClass.'" style="border:solid 1px black">';
	$r = 0;
	foreach ($rows as $row) {
		$table .= '<tr>';
		$c = 0;
		foreach ($columns as $column) {
			// Common to all cells.
			$style = 'position:relative;border:solid 1px grey;text-align:center;padding:0;';
			$style .= 'height:'.$cells[$c][$r]->height.';';
			$class = $cellClass;
			$editable = false;
			// Upper right cell
			if ($c.$r == '00') {
				$class .= '';
				$value = '&nbsp;';
				$style .= 'width:auto;';
				$style .= 'white-space:nowrap;';
			// Column names
			} elseif ($r == '0') {
				$class .= '';
				$value = $column;
				$style .= 'min-width:'.$cells[$c][$r]->width.';';
				$style .= 'font-weight:bold;';
			// Row names
			} elseif ($c == '0') {
				$class .= '';
				$value = $row;
				$style .= 'width:auto;';
				$style .= 'font-weight:bold;';
				$style .= 'white-space:nowrap;';
			// All other (body) cells
			} else {
				$editable = true;
				$class .= ' editable';
				$style .= 'min-width:'.$cells[$c][$r]->width.';';
				$value = $cells[$c][$r]->value;
			}
			$style .= 'padding:3px;';
			$table .= '<td id="'.$cells[$c][$r]->name.'" name="'.$cells[$c][$r]->name.'" class="'.$class.'" style="'.$style.'">';
			$table .=  $value;
			$table .= '</td>';
			$c++;
		}
		$table .= '</tr>';
		$r++;
	}
	$table .= '</table>';
	
	// Send table out to view.
	echo $table;
?>