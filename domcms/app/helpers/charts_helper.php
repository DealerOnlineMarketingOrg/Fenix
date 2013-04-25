<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		// Sets a style attribute in a style string.
		// If it doesn't exist, adds it.
		// If it exists, replaces the value.
		// Returns the modified style.
		function SetStyle($style, $attribute, $value) {
			if (preg_match('/(^|;)' . $attribute . ':/', $style))
				$style = preg_replace('/(^|;)' . $attribute . ':[^;]+/', '$1' . $attribute . ':' . $value, $style);
			else {
				if ($style == '')
					$style = $attribute . ':' . $value;
				else
					$style .= ';' . $attribute . ':' . $value;
			}
			
			return $style;
		}
		
		// Creates a line chart from a report.
		// First column in each row is the tag name for that series of data.
		// 2nd row on are the individual points of data.
		// This routine uses flot line graph charts, and creates the
		//   script for one.
		// Returns the HTML snippet for the chart, and sets $javascript to the
		//   javascript snippet needed. Script is functionless. It can be
		//   placed in an anonymous function, or under a function name.
		function generateLineChart($report, $report_id, &$report_element_start, &$javascript) {
			$report_html = '<div id="lineChart" class="chart" style="height:230px"></div>';
			
			// Create series arrays.
			$data = '[';
			foreach ($report as $report_row) {
				if ($report_row['ReportID'] == $report_id) {
					$keys = array_keys($report_row['Cells']);
					// First column is label name.
					$data .= '{label: "' . $report_row['Cells'][$keys[0]]['data'] . '",';
					// Second column on are data columns for the chart.
					// Each column is a 2-dimensional data item, comma-delimited.
					// First value is X-axis, second value is Y-axis.
					$data .= 'data: [';
					for ($i = 1; $i < count($keys); $i++) {
						$data .= '[' . $report_row['Cells'][$keys[$i]]['data'] . '],';
					}
					$data .= ']},';
				}
			}
			// Strip trailing comma.
			$data = substr($data, 0, strlen($data)-1);
			$data .= ']';
			
			$options = '{' .
					   'series:{lines:{show:true}},' .
					   'legend: {position:"ne",backgroundColor:"#ff4",backgroundOpacity:0.35},' .
					   'xaxis: {min:1, max:12, ticks: [[1,"Jan"],[2,"Feb"],[3,"Mar"],[4,"Apr"],[5,"May"],[6,"Jun"],[7,"Jul"],[8,"Aug"],[9,"Sep"],[10,"Oct"],[11,"Nov"],[12,"Dec"]]}' .
					   '}';
					   
			$plot = '$.plot($("#lineChart"),' . $data . ',' . $options . ');';

			$javascript = $plot;
			return $report_html;
		}
		
		// Creates a pie chart from a report.
		// First column in each row is the tag name for that series of data.
		// 2nd row is the individual data point.
		// This routine uses flot line graph charts, and creates the
		//   script for one.
		// Returns the HTML snippet for the chart, and sets $javascript to the
		//   javascript snippet needed. Script is functionless. It can be
		//   placed in an anonymous function, or under a function name.
		function generatePieChart($report, $report_id, &$report_element_start, &$javascript) {
			$report_html = '<div id="pieChart" class="pieWidget" style="height:220px"></div><div id="pieLegend" style="width:150px"></div>';
			
			// Create series arrays.
			$data = '[';
			foreach ($report as $report_row) {
				if ($report_row['ReportID'] == $report_id) {
					$keys = array_keys($report_row['Cells']);
					// First column is label name.
					$data .= '{label: "' . $report_row['Cells'][$keys[0]]['data'] . '",';
					// Second column is the data point for the pie slice.
					// Each column is a single data point.
					$data .= 'data: ' . $report_row['Cells'][$keys[1]]['data'] . '},';
				}
			}
			// Strip trailing comma.
			$data = substr($data, 0, strlen($data)-1);
			$data .= ']';
			
			$options = '{canvas:false,' .
					   'series:{pie:{show:true,' .
					                'radius:1,' .
									'label:{show:true,radius:2/3,' .
										'formatter: function(label, series){' .
                        					'return \'<div style="font-size:8pt;text-align:center;padding:2px;color:white;">\'+Math.round(series.percent)+\'%</div>\';' .
										'},' .
	                    				'threshold: 0.1' .
									'}' .
						'}},' .
						'legend:{container:$("#pieLegend")}' .
						'}';
					   
			$plot = '$.plot($("#pieChart"),' . $data . ',' . $options . ');';

			$javascript = $plot;
			return $report_html;
		}
		
		// Creates an HTML table containing the report specified by $report_id.
		// $report_element_start is where the id numbering (int) should start for this report.
		// $report_element_start will return with the next available id.
		// Returns a string containing the table code.
		function generateTableChart($report, $report_id, &$report_element_start) {
			$ci =& get_instance();
			
			$ci->load->model('getdpr');
			
			$report_element_i = $report_element_start;
			$report_html = '';
			$style = '';
			$id = "reportID_" . $report_element_i; $report_element_i++;
			$report_html .= "<table id='" . $id . "' border='0' width='100%' style='color:black'>";
			// Report element counter. Gives each element in the report a unique id.
			foreach ($report as $report_row) {
				if ($report_row['ReportID'] == $report_id) {
					// If this row isn't hidden..
					if ($report_row['Hidden'] != true) {
						// ..create row.
						$id = "reportID_" . $report_element_i; $report_element_i++;
						$report_html .= "<tr id='" . $id . "'>";
						//print_object($report);
						foreach ($report_row['Cells'] as $item) {
							$style = '';
							$classes = explode(',', $item['format']['class']);
							foreach ($classes as $class) {
								switch (trim($class)) {
									case 'Header':
										$style = SetStyle($style,'font-weight','bold');
										break;
									case 'Name':
										$style = SetStyle($style,'font-weight','bold');
										$style = SetStyle($style,'font-size','150%');
										break;
									case 'Year':
										$style = SetStyle($style,'background-color','yellow');
										break;
									case 'ThisYear':
										/* No extra style set */;
										break;
									case 'Total':
										$style = SetStyle($style,'font-weight','bold');
										 break;
									case 'Seperator':
										$style = SetStyle($style,'height','0px');
										$style = SetStyle($style,'border-bottom','solid 1px black');
										break;
								}
							}
							$format = '';
							// Strip out all errors and 0's.
							if ($item['data'] == '' || $item['data'] === 0 || $item['data'] == '#DIV/0!' || $item['data'] == '#VALUE!')
								$data = '';
							else
								$data = $ci->rep->formatData($item['data'], $item['format']['functions']);
							// Create item on row and set style.
							$id = "reportID_" . $report_element_i; $report_element_i++;
							// Convert data to floating-point string to force output of needed precision.
							if (substr($data, strlen($data)-1, 1) == '%')
								$data = numberToString($data, 2);
							else if (substr($data, 0, 1) == '$')
								$data = numberToString($data, 2);
							else
								$data = numberToString($data, 2, TRUE);
							$report_html .= "<td id='" . $id . "' style='" . $style . "'>" . $data . "</td>";
						}
						$report_html .= "</tr>";
					}
				}
			}
			$report_html .= "</table>";
			
			$report_element_start = $report_element_i + 1;
			return $report_html;
		}
