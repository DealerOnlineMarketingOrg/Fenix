<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	// Gives a set of functions that allow a user-defined report-table to be generated.
	
	// table_specs: a user-defined table, which contains the data to pull,
	// as well as what data to base it off of.
	function CreateGenTable($table_specs) {
		$table_specs = array (
			array (
				'0' => 'row',
				'1' => '"Lead Provider"',
				'2' => '"Jan"',
				'3' => '"Feb"',
				'4' => '"YTD"'
			),
			array (
				'0' => ''
			)
		);
	}