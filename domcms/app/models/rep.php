<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//
	class Rep extends DOM_Model {
		
		function __construct() {
			// Call the Model constructor
			parent::__construct();
			$this->load->helper('query');
			$this->load->helper('converter_helper');
			$this->load->helper('phpext_helper');
		}

		// Returns the provider ID if exists, else FALSE.
		public function providerID($provider) {
			$sql = 'SELECT PROVIDER_ID as ID FROM DPRProviders WHERE PROVIDER_Name = "' . $provider . '"';
			$query = $this->db->query($sql);
			$result = $query->result();
			return ($query->num_rows) ? $result[0]->ID : FALSE;
		}
		
		public function getProvider($id, $order_col) {
			$sql = 'SELECT PROVIDER_ID as ID,PROVIDER_Name as Name,PROVIDER_URL as URL FROM DPRProviders WHERE PROVIDER_ID = ' . $id . ' ORDER BY ' . $order_col . ' ASC;';
			return query_results($this,$sql);
		}
		
		public function getProviders($order_col) {
			$sql = 'SELECT PROVIDER_ID as ID,PROVIDER_Name as Name,PROVIDER_URL as URL FROM DPRProviders ORDER BY ' . $order_col . ' ASC;';
			return query_results($this,$sql);
		}
		
		// Adds a Provider to the DPR db and returns the new ID.
		// If already exists, returns the current ID.
		public function addProvider($provider_data) {
			$data = array(
				'PROVIDER_Name' => $provider_data['provider'],
				'PROVIDER_URL' => $provider_data['providerURL']
			);
			// Insert data into table.
			$this->db->insert('DPRProviders',$data);
			// Get ID of new value added into table.
			return ($this->providerID($provider_data['provider']));
		}
		
		// Returns the service ID if exists, else FALSE.
		public function serviceID($service) {
			$sql = 'SELECT SERVICE_ID as ID FROM DPRReportServices WHERE SERVICE_Name = "' . $service . '"';
			$query = $this->db->query($sql);
			$result = $query->result();
			return ($query->num_rows) ? $result[0]->ID : FALSE;
		}
		
		// Get an array of all services.
		public function getService($id, $order_col ) {
			$sql = 'SELECT SERVICE_ID as ID,SERVICE_Name as Name FROM DPRReportServices WHERE SERVICE_ID = ' . $id . ' ORDER BY ' . $order_col . ' ASC;';
			return query_results($this,$sql);
		}
		
		// Get an array of all services.
		public function getServices($order_col) {
			$sql = 'SELECT SERVICE_ID as ID,SERVICE_Name as Name FROM DPRReportServices ORDER BY ' . $order_col . ' ASC;';
			return query_results($this,$sql);
		}
		
		// Adds a Service to the DPR db and returns the new ID.
		public function addService($service_data) {
			$data = array(
				'SERVICE_Name' => $service_data['service']
			);
			// Insert data into table. If success..
			$this->db->insert('DPRReportServices',$data);
			// Get ID of new value added into table.
			return $this->serviceID($service_data['service']);
		}
		
		// Adds a lead total to the dpr report table.
		public function addLeadTotal($lead_data) {
			// Update DPRReports with the lead data.
			$where = array(
				'REPORT_Provider' => $lead_data['providerID'],
				'REPORT_Service'  => $lead_data['serviceID'],
				'REPORT_Date'     => $lead_data['date'],
				'CLIENT_ID'       => $lead_data['clientID'],
			);
			$query = $this->db->get_where('DPRReports', $where);
			if ($query->num_rows > 0) {
				// Already exists. Update.
				$data = array(
					'REPORT_Value' => $lead_data['total']
				);
				$this->db->where($where);
				$this->db->update('DPRReports', $data);
			} else {
				// Doesn't exist yet. Insert.
				$data = array(
					'REPORT_Created'  => date(FULL_MILITARY_DATETIME),
					'REPORT_Provider' => $lead_data['providerID'],
					'REPORT_Service'  => $lead_data['serviceID'],
					'REPORT_Date'     => $lead_data['date'],
					'REPORT_Value'    => $lead_data['total'],
					'CLIENT_ID'       => $lead_data['clientID'],
				);
				$this->db->insert('DPRReports', $data);
			}
		}
		
		public function addLeadCost($lead_data) {
			// Update DPRProviderData with the cost data.
			$where = array(
				'PROVIDER_ID' => $lead_data['providerID'],
				'PROVIDERDATA_Month' => $lead_data['month'],
				'PROVIDERDATA_Year' => $lead_data['year'],
			);
			$query = $this->db->get_where('DPRProviderData', $where);
			if ($query->num_rows > 0) {
				// Already exists. Update.
				$data = array(
					'PROVIDERDATA_Cost' => $lead_data['cost']
				);
				$this->db->where($where);
				$this->db->update('DPRProviderData', $data);
			} else {
				// Doesn't exist yet. Insert.
				$data = array(
					'PROVIDER_ID' => $lead_data['providerID'],
					'PROVIDERDATA_Month' => $lead_data['month'],
					'PROVIDERDATA_Year' => $lead_data['year'],
					'PROVIDERDATA_Cost' => $lead_data['cost']
				);
				$this->db->insert('DPRProviderData', $data);
			}
		}
		
		//
		// Start of report generation functions
		//
		
		// Returns a list of functions from format:functions.
		private function getFunctions($functionList) {
			return explode(';', $functionList);
		}
		
		// Returns a list of arguments from a function.
		// The first argument ([0]) is the name of the function.
		private function getArguments($function) {
			$start = strpos($function, '(');
			$end = strpos($function, ')');
			$name = substr($function, 0, $start);
			$args = explode(',', substr($function, $start+1, $start-$end+1));
			$all_args[] = $name;
			$all_args = array_merge_($all_args, $args);
			return $all_args;
		}
		
		// Returns true if the function is in the function list.
		public function hasFunction($functionList, $functionName) {
			$functions = $this->getFunctions($functionList);
			foreach ($functions as $function) {
				$args = $this->getArguments($function);
				if ($args[0] == $functionName)
					return true;
			}
			
			return false;
		}
		
		// Processes the list of functions against data.
		// formatList syntax: functionName1(argument1,..); functionName2(..
		// Returns the data formated with the item's functions.\
		// Functions are processed in order in formatList. All functions are
		//   applied. Be careful which functions, in which order, are set.
		public function formatData($data, $formatList) {
			$fData = $data;
			$functions = $this->getFunctions($formatList);
			foreach ($functions as $function) {
				$args = $this->getArguments($function);
				switch ($args[0]) {
					// prepend(string)
					case 'prepend':
						$fData = $args[1] . $fData;
						break;
					// append(string)
					case 'append':
						$fData = $fData . $args[1];
						break;
					// round(digits)
					case 'round':
						if (is_numeric($fData))
							$fData = round($fData, $args[1]);
						break;
				}
			}
			return $fData;
		}
		
		// Creates a PHPExcel worksheet that'll hold the calculations on the report.
		// Returns the report with the calculations completed.
		// Any current data in the formula fields will be updated.
		// Returns the calculated report.
		public function CalculateData($report) {
			// Create PHPExcel worksheet for doing in-report calculations.
			// Report formulas should be in the same format as Excel.
			require_once 'domcms/libraries/PHPExcel.php';
			require_once 'domcms/libraries/PHPExcel/IOFactory.php';
			
			$objPHPExcel = CreateExcelWorkbook('report');
			$worksheet = CreateWorksheet($objPHPExcel);
			
			// Write data to worksheet.
			$row = 1;
			foreach ($report as $report_row) {
				$col = 'A';
				foreach ($report_row['Cells'] as $item) {
					if ($item['formula'] != '')
						$worksheet->setCellValue($col.$row, $item['formula']);
					else
						$worksheet->setCellValue($col.$row, $item['data']);

					$col++;
				}
				$row++;
			}
			
			// Test routine. Writes to excel file for formula checking.
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save("uploads/test.xlsx");
		
			// Retrieve data back from worksheet, in calculated form,
			//  and merge data back into report. Data will have the
			//  same structure 
			$highCol = $worksheet->getHighestColumn();
			$highRow = $worksheet->getHighestRow();
			// This lets us search to the last column.
			// Otherwise, != $highCol in the for would
			//   only go to $highCol - 1.
			$highCol++;
			$row = 1;
			foreach ($report as &$report_row) {
				$col = 'A';
				foreach ($report_row['Cells'] as &$item) {
					$item['data'] = $worksheet->getCell($col.$row)->getCalculatedValue();

					$col++;
				}
				$row++;
			}
			
			return $report;
		}
		
		// Returns a report from the query.
		// Data is stored in an array with the following:
		//  report: the id of the report the data belongs to.
		//  style: the visual style of the data.
		//  data: the actual data.
		//  formula: the formula for the data, if any, else an empty string.
		public function generateDPRReport($query, $report_id, $ending_year) {
			$query_result = $query->result();

			// Go through each row in the query and build the data array.
			// $data is an array of arrays (rows).
			$data_body = array ();
			$months = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
			$month_columns = array('Jan'=>'B','Feb'=>'C','Mar'=>'D','Apr'=>'E','May'=>'F','Jun'=>'G','Jul'=>'H','Aug'=>'I','Sep'=>'J','Oct'=>'K','Nov'=>'L','Dec'=>'M');
			// Generate empty array.
			// Each item is an array consisting of data, what report the data applies to, and its format.
			// The format is an array of the following:
			//   class: the class name of the format.
			//   style: the ccs style for the visual format of the data.
			//   dataType: the type of data.
			// calcID defines this cell as a cell in a calculation.
			// It will replace a part of a formula with the syntax {ID}.
			// If more then one cell has the same ID, will create a
			//   comma-delimited list for the formula.
			//  Example: A1:calcID=TotalJan, B1:calcID=TotalJan will convert
			//    {TotalJan} to A1,B1
			$empty_item = array('calcID' => NULL, 'format' => array('class'=>'', 'style'=>'', 'functions'=>''), 'data' => '', 'formula' => '');
			// Empty row contains the structure of the report and the data items for each.
			$empty_row = array();
				// Hidden determines if this is a hidden data row.
				// Usually used for calculation data.
				$empty_row['Hidden'] = false;
				$empty_row['ReportID'] = $report_id;
				$empty_cell['Name'] = $empty_item;
				foreach($months as $month)
					$empty_cell[$month] = $empty_item;
				$empty_cell['YTD'] = $empty_item;
				$empty_row['Cells'] = $empty_cell;
				
			// Headers.
			$header_row = array($empty_row);
			$header_descs = array(
				'Name' => 'Lead Provider',
				'Jan'  => 'Jan',
				'Feb'  => 'Feb',
				'Mar'  => 'Mar',
				'Apr'  => 'Apr',
				'May'  => 'May',
				'Jun'  => 'Jun',
				'Jul'  => 'Jul',
				'Aug'  => 'Aug',
				'Sep'  => 'Sep',
				'Oct'  => 'Oct',
				'Nov'  => 'Nov',
				'Dec'  => 'Dec',
				'YTD'  => 'Total YTD'
			);
			foreach ($header_descs as $key => $desc) {
				$header_row[0]['Cells'][$key]['format']['class'] = 'Header';
				$header_row[0]['Cells'][$key]['data'] = $desc;
			}
			$headers = $header_row;
			
			$data_body = array();
			$row_count = $query->num_rows();
			$month_first = $month_columns['Jan'];
			$month_last = $month_columns['Dec'];

			if ($row_count > 0) {
				// Initialize $current variables.
				$row = $query_result[0];
				$current = array (
					'provider' => $row->PName,
					'year' => $row->Year,
					'month' => $months[$row->Month-1],
					'service' => $row->SName,
					'serviceType' => $row->SType
				);
				
				$data_row = $empty_row;
				$cost_row = $empty_row;
				// Adjust for header rows. Affects formulas.
				$current_row = 1 + count($headers);
				$first_row = true;
				$write_header = true;
				$write_data = false;
				$write_seperator = false;
				$write_totals = false;
				$write_rates = false;
				$write_blank = false;
				
				// Populate data for table chart.
				for ($qi = 0; $qi <= $row_count; $qi++) {
					if ($qi == $row_count) {
						// Past the end of the query. Close out.
						$write_data = true;
						$write_seperator = true;
						$write_totals = true;
						$write_rates = true;
					} else {
						// Still processing query rows.
						$row = $query_result[$qi];
					
						// Month changed.
						if ($current['month'] != $months[$row->Month-1]) {

						}
						
						// Year changed.
						if ($current['year'] != $row->Year) {
							$write_data = true;
							$write_totals = true;
						}

						// Service changed.
						if ($current['service'] != $row->SName) {
							$write_data = true;
						}
						
						// Provider changed.
						if ($current['provider'] != $row->PName) {
							// Write current data line, then write new header.
							$write_data = true;
							$write_seperator = true;
							$write_totals = true;
							$write_rates = true;
							$write_header = true;
							$write_blank = true;
						}
					}
					
					// Visitors formula ID.
					$visitorsID = $current['year'] . '.' . $current['provider'] . '.visitors.';
					// Total visitors formula ID.
					$totalVisitorsID = $current['year'] . '.' . $current['provider'] . '.totalVisitors.';
					// Leads formula ID.
					$leadsID = $current['year'] . '.' . $current['provider'] . '.leads.';
					// Total Leads formula ID.
					$totalLeadsID = $current['year'] . '.' . $current['provider'] . '.totalLeads.';
					// Total Leads per year formula ID.
					$totalLeadsYearlyID = $current['year'] . '.totalLeads.';
					// Total Conversion formula ID.
					$conversionID = $current['year'] . '.' . $current['provider'] . '.conversion.';
					// Cost formula ID.
					$costID = $current['year'] . '.' . $current['provider'] . '.cost.';
					// Cost per lead formula ID.
					$costPerLeadID = $current['year'] . '.' . $current['provider'] . '.costPerLead.';
					
					if ($write_data) {
						// Name data row.
						$data_row['Cells']['Name']['data'] = $current['year'] . ' ' . $current['service'];
						
						// Hide all leads that don't fall into the current report year.
						// Total leads will continue to show.
						if ($current['serviceType'] == 2 && $current['year'] != $ending_year)
							$data_row['Hidden'] = TRUE;
						
						// Set class and clcIDs for data.
						foreach ($data_row['Cells'] as $key => &$item)
							$item['format']['class'] = ($current['year'] == $ending_year) ? 'ThisYear' : 'Year';
							
						// Tag each month as visitor or lead for row, per month, and per service (for end-of-year YTD).
						foreach ($months as $month) {
							if ($current['serviceType'] == 1) {
								// Visitor
								$data_row['Cells'][$month]['calcID'] = $visitorsID;
								$data_row['Cells'][$month]['calcID'] .= ',' . $visitorsID.$month;
								$data_row['Cells'][$month]['calcID'] .= ',' . $visitorsID.$current['service'];
							} else {
								// Lead
								$data_row['Cells'][$month]['calcID'] = $leadsID;
								$data_row['Cells'][$month]['calcID'] .= ',' . $leadsID.$month;
								$data_row['Cells'][$month]['calcID'] .= ',' . $leadsID.$current['service'];
							}
						}
						
						// Add YTD formula to row.
						if ($current['serviceType'] == 1)
							$data_row['Cells']['YTD']['formula'] = '=SUM({' . $visitorsID.$current['service'] .'})';
						else
							$data_row['Cells']['YTD']['formula'] = '=SUM({' . $leadsID.$current['service'] .'})';
						// Set class of YTD as total.
						if (isset($data_row['Cells']['YTD']['format']['class']))
							$data_row['Cells']['YTD']['format']['class'] .= ', Total';
						else
							$data_row['Cells']['YTD']['format']['class'] = 'Total';
	
						$data_body[] = $data_row;
						
						// Go to next row.
						$current_row++;
						$data_row = $empty_row;
						
						$write_data = false;
					}
					
					if ($write_seperator) {
						// Seperator line.
						$label_row = $empty_row;
						// This will be the horizontal rule seperator. Use a blank line for now.
						foreach ($label_row['Cells'] as &$item)
							$item['format']['class'] = 'Seperator';
						$label_row['Cells']['Name']['data'] = ' ';
						$data_body[] = $label_row;
						
						// Go to next row.
						$current_row++;
						
						$write_seperator = false;
					}

					if ($write_totals) {
						$total_row = $empty_row;

						// Name row.						
						$total_row['Cells']['Name']['data'] = $current['year'] . ' Total Visitors';
						// Total visitors are hidden rows.
						$total_row['Hidden'] = TRUE;
						
						// Set class for this row.
						foreach ($total_row['Cells'] as &$item)
							$item['format']['class'] = ($current['year'] == $ending_year) ? 'ThisYear, Total' : 'Year';
						
						foreach ($months as $month) {
							// Set the formula to the total leads for the month.
							$total_row['Cells'][$month]['formula'] = '=SUM({' . $visitorsID.$month . '})';
							// Tag each month as total lead for row and per month.
							$total_row['Cells'][$month]['calcID'] = $totalVisitorsID;
							$total_row['Cells'][$month]['calcID'] .= ',' . $totalVisitorsID.$month;
						}
						// Set YTD formula to total Leads ID.
						$total_row['Cells']['YTD']['formula'] = '=SUM({' . $totalVisitorsID .'})';
						// Set class of YTD as total.
						if (isset($total_row['Cells']['YTD']['format']['class']))
							$total_row['Cells']['YTD']['format']['class'] .= ', Total';
						else
							$total_row['Cells']['YTD']['format']['class'] = 'Total';
							
						$data_body[] = $total_row;
	
						// Go to next row.
						$current_row++;
						$total_row = $empty_row;

						// Name row.
						$total_row['Cells']['Name']['data'] = $current['year'] . ' Total Leads';

						// Set class for this row.
						foreach ($total_row['Cells'] as &$item)
							$item['format']['class'] = ($current['year'] == $ending_year) ? 'ThisYear, Total' : 'Year';
						
						foreach ($months as $month) {
							// Set the formula to the total leads for the month.
							$total_row['Cells'][$month]['formula'] = '=SUM({' . $leadsID.$month . '})';
							// Tag each month as total lead for row, per month and per year.
							$total_row['Cells'][$month]['calcID'] = $totalLeadsID;
							$total_row['Cells'][$month]['calcID'] .= ',' . $totalLeadsID.$month;
							$total_row['Cells'][$month]['calcID'] .= ',' . $totalLeadsYearlyID.$month;
						}
						// Set YTD formula to total Leads ID.
						$total_row['Cells']['YTD']['formula'] = '=SUM({' . $totalLeadsID .'})';
						// Set calcID to total leads YTD.
						$total_row['Cells']['YTD']['calcID'] = $totalLeadsID . 'YTD';
						// Set class of YTD as total.
						if (isset($total_row['Cells']['YTD']['format']['class']))
							$total_row['Cells']['YTD']['format']['class'] .= ', Total';
						else
							$total_row['Cells']['YTD']['format']['class'] = 'Total';
							
						$data_body[] = $total_row;
	
						// Go to next row.
						$current_row++;
						
						$write_totals = false;
					}
					
					if ($write_rates) {
						$total_row = $empty_row;
						
						// Conversion ratios
						$total_row['Cells']['Name']['data'] = $ending_year . ' Conversion Ratio';
						// Set class
						foreach ($total_row['Cells'] as &$item)
							$item['format']['class'] = 'Total';
						foreach ($months as $month) {
							// Set formula as total leads per month / total visitors per month
							$total_row['Cells'][$month]['formula'] = '=SUM({' . $totalLeadsID.$month . '})/SUM({' . $totalVisitorsID.$month . '})*100';
							// Tag each month as total conversion.
							$total_row['Cells'][$month]['calcID'] = $conversionID;
							// Set function to create a percentage.
							$total_row['Cells'][$month]['format']['functions'] = 'append(%)';
						}
						// Set YTD formula to average of total conversions.
						$total_row['Cells']['YTD']['formula'] = '=AVERAGE({' . $conversionID .'})';
						// Set function to create a percentage.
						$total_row['Cells']['YTD']['format']['functions'] = 'append(%)';
						$data_body[] = $total_row;
						
						$current_row++;
						$total_row = $empty_row;
						// Costs.
						$total_row['Cells']['Name']['data'] = $ending_year . ' Cost';
						// Set class
						foreach ($total_row['Cells'] as &$item)
							$item['format']['class'] = 'Total';
						foreach ($months as $month) {
							$total_row['Cells'][$month]['data'] = $cost_row['Cells'][$month]['data'];
							$total_row['Cells'][$month]['formula'] = '';
							// Tag each month as total cost for row and per month.
							$total_row['Cells'][$month]['calcID'] = $costID;
							$total_row['Cells'][$month]['calcID'] .= ',' . $costID.$month;
							$total_row['Cells'][$month]['format']['functions'] = 'prepend($)';
						}
						$total_row['Cells']['YTD']['formula'] = '=SUM({' . $costID . '})';
						$total_row['Cells']['YTD']['format']['functions'] = 'prepend($)';
						$data_body[] = $total_row;
						$cost_row = $empty_row;
						
						$current_row++;
						$total_row = $empty_row;
						// Costs per lead.
						$total_row['Cells']['Name']['data'] = $ending_year . ' Cost per lead';
						// Set class
						foreach ($total_row['Cells'] as &$item)
							$item['format']['class'] = 'Total';
						foreach ($months as $month) {
							$total_row['Cells'][$month]['data'] = '';
							$total_row['Cells'][$month]['formula'] = '={' . $costID.$month . '}/{' . $totalLeadsID.$month . '}';
							// Tag each month as total cost per lead for row and per month.
							$total_row['Cells'][$month]['calcID'] = $costPerLeadID;
							$total_row['Cells'][$month]['format']['functions'] = 'prepend($)';
						}
						$total_row['Cells']['YTD']['formula'] = '=SUM({' . $costPerLeadID . '})';
						$total_row['Cells']['YTD']['format']['functions'] = 'prepend($)';
						$data_body[] = $total_row;
						
						// Go to next row.
						$current_row++;
						
						$write_rates = false;
					}
					
					if ($write_blank) {
						// Blank line.
						$label_row = $empty_row;
						// Actual blank line.
						foreach ($label_row['Cells'] as &$item)
							$item['format']['class'] = 'Blank';
						$label_row['Cells']['Name']['data'] = '&nbsp;';
						$data_body[] = $label_row;
						
						// Go to next row.
						$current_row++;
						
						$write_blank = false;
					}
					
					if ($qi != $row_count) {
						// Set this query row's style to the same as it's Name.
						$data_row['Cells'][$months[($row->Month)-1]]['format']['class'] = $data_row['Cells']['Name']['format']['class'];
						// Get this query row's monthly value.
						$data_row['Cells'][$months[($row->Month)-1]]['data'] = $row->Value;
						// Get this query row's monthly value.
						$cost_row['Cells'][$months[($row->Month)-1]]['data'] = $row->Cost;
					}

					// Update $current.
					if ($current['month'] != $months[$row->Month-1])
						$current['month'] = $months[$row->Month-1];
					if ($current['year'] != $row->Year)
						$current['year'] = $row->Year;
					if ($current['service'] != $row->SName) {
						$current['service'] = $row->SName;
						$current['serviceType'] = $row->SType;
					}
					if ($current['provider'] != $row->PName)
						$current['provider'] = $row->PName;
					
					// Write header for new provider.
					if ($write_header) {
						// Blank line.
						$label_row = $empty_row;
						// Provider header line.
						$label_row['Cells']['Name']['data'] = $row->PName;
						foreach ($label_row['Cells'] as &$item)
							// This is a Name class row.
							$item['format']['class'] = 'Name';
						$data_body[] = $label_row;
						
						// Go to next row.
						$current_row++;
						
						$write_header = false;
					}
					
					if ($first_row)
						$first_row = false;
				}
			}
			
			// Footers.
			$footers = array();
			
			// Build table report.
			$report = array();
			$report = array_merge_($report, $headers);
			$report = array_merge_($report, $data_body);
			$report = array_merge_($report, $footers);
			
			// Create report rows for line graph. Data will be based off of above table report.
			// Line graph will have each year, each year will be broken into each month, and
			//   each month will represent the grand total leads for that year.
			if ($row_count > 0) {
				
				// Get a list of all years and sort.
				$current['year'] = '';
				$report_years = array();
				for ($qi = 0; $qi < $row_count; $qi++) {
					$row = $query_result[$qi];
					// New year
					if ($current['year'] != $row->Year)
					{
						$current['year'] = $row->Year;
						// If we haven't already calculated this year..
						if (array_search($current['year'], $report_years) === FALSE)
							$report_years[] = $current['year'];
					}
				}
				sort($report_years);
				
				// Go through all years and create report data.
				$lineChart_body = array();
				foreach ($report_years as $year) {
					$lineChart_row = $empty_row;
					// This belongs to the lineChart report.
					$lineChart_row['ReportID'] = 'lineChart';
					$lineChart_row['Hidden'] = FALSE;
					// Name the label for this row. Use the first row (Name in this report).
					$keys = array_keys($lineChart_row['Cells']);
					$lineChart_row['Cells'][$keys[0]]['data'] = $year;
					// This year's Total Leads formula ID.
					$totalLeadsID = $year . '.' . $current['provider'] . '.totalLeads.';
					// Set each data point's formula. Monthly in this report.
					// Use rows 2 on for data points for the line chart.
					foreach ($months as $month) {
						// Total Leads per year  formula ID.
						$totalLeadsYearlyID = $year . '.totalLeads.';
						// Line charts require a 2-dimensional data item, comma-delimited.
						// First value is X-axis, second value is Y-axis.
						// For this chart, X is the month number, and Y is the leads value.
						$month_num = array_search($month, $months)+1;
						$lineChart_row['Cells'][$month]['formula'] = '=CONCATENATE(' . $month_num . ',",",SUM({' . $totalLeadsYearlyID.$month . '}))';
					}
					
					// Add line chart row to $report.
					$lineChart_body[] = $lineChart_row;
				}

				// Add line chart to main $report.
				$report = array_merge_($report, $lineChart_body);
			}
			
			// Create report rows for pie chart. Data will be based off of above table report.
			// Pie graph will have each web-site provider, and the YTD value will be based off
			//   of the YTD of the Total Leads for that provider.
			if ($row_count > 0) {
				$row = $query_result[0];
				$current['provider'] = '';
				$pieChart_body = array();
				$processed_providers = array();
				
				for ($qi = 0; $qi < $row_count; $qi++) {
					$row = $query_result[$qi];
					
					// New year
					if ($current['provider'] != $row->PName)
					{
						$current['provider'] = $row->PName;
						
						// If we haven't already calculated this provider..
						if (array_search($current['provider'], $processed_providers) === FALSE) {
							$pieChart_row = $empty_row;
							// This belongs to the pieChart report.
							$pieChart_row['ReportID'] = 'pieChart';
							$pieChart_row['Hidden'] = TRUE;
							// Name the label for this row. Use the first row (Name in this report).
							$keys = array_keys($pieChart_row['Cells']);
							$pieChart_row['Cells'][$keys[0]]['data'] = $current['provider'];
							// This year's Total Leads formula ID.
							$totalLeadsID = $current['year'] . '.' . $current['provider'] . '.totalLeads.';
							// Set the formula for the pie data.
							// Only one column is required. Use the second column (Jan in this report).
							$pieChart_row['Cells'][$keys[1]]['formula'] = '=SUM({' . $totalLeadsID . 'YTD' . '})';
							
							// Add line chart row to $report.
							$pieChart_body[] = $pieChart_row;
							
							$processed_providers[] = $current['provider'];
						}
					}
				}
			
				// Add pie chart to main $report.
				$report = array_merge_($report, $pieChart_body);
			}
			
			// Replace all formula IDs with their respective cells.
			// Build up a list of IDs and their cells.
			$calcIDs = $this->getCalcList($report);
			// In all formulas, replace {calcID} with the cell list.
			$report = $this->setFormulasFromList($report, $calcIDs);
			
			// Calculate report.
			$report = $this->CalculateData($report);
			
			return $report;
		}
		
		// Gets a list of all the calculation tags in the report.
		public function getCalcList($report) {
			$row = 1;
			$calcIDs = array();
			foreach ($report as $report_row) {
				$col = 'A';
				foreach ($report_row['Cells'] as $item) {
					$calcIDList = explode(',', $item['calcID']);
					foreach ($calcIDList as $calcID) {
						if ($calcID != '')
							if (isset($calcIDs[$calcID]))
								$calcIDs[$calcID] .= ',' . $col.$row;
							else
								$calcIDs[$calcID] = $col.$row;
					}
					
					$col++;
				}
				$row++;
			}
			
			return $calcIDs;
		}
		
		// Using the list from getCalcList, sets all formulas to their respective
		//  cell ranges.
		public function setFormulasFromList($report, $calcIDs) {
			$row = 1;
			foreach ($report as &$report_row) {
				$col = 'A';
				foreach ($report_row['Cells'] as &$item) {
					if ($item['formula'] != '') {
						foreach ($calcIDs as $key => $calcID) {
							if (strpos($item['formula'], '{' . $key . '}') > -1)
								// Replace {calcID} with cell list if found.
								$item['formula'] = str_replace('{' . $key . '}', $calcID, $item['formula']);
						}
						// If there's still a formula variable in the formula, there's no data to support
						//  the formula. Replace with 0.
						if (preg_match('/\{[^}]+\}/', $item['formula']))
							$item['formula'] = preg_replace('/\{[^}]+\}/', '0', $item['formula']);
					}
					$col++;
				}
				$row++;
			}
			
			return $report;
		}
		
		public function getDPRReport($client_id, $beginning_month, $beginning_year, $ending_month, $ending_year) {
			// The first element in each row array indicates the type of data the row represents.
			// This will be used for formatting.
			
			$report_id = 'leads';
			
			// Get initial date to start report with.
			$begin_date = (string)($beginning_year) . '-' . (string)($beginning_month) . '-01';
			$end_date = (string)($ending_year) . '-' . (string)($ending_month) . '-31';
			// Pull data from last n years.
			$qu_report = "SELECT p.PROVIDER_Name AS PName, YEAR(r.REPORT_Date) as Year, s.SERVICE_Name AS SName, s.SERVICE_Type AS SType, MONTH(r.REPORT_Date) as Month, r.REPORT_Value AS Value, d.PROVIDERDATA_Cost as Cost, r.REPORT_Date as Date " .
			             "FROM DPRReports AS r " .
						 "LEFT JOIN DPRProviderData d ON (d.PROVIDER_ID = r.REPORT_Provider AND " .
						 "                                d.PROVIDERDATA_Month = MONTH(r.REPORT_Date) AND " .
						 "                                d.PROVIDERDATA_Year = YEAR(r.REPORT_Date)), " .
						 "  DPRProviders AS p, DPRReportServices AS s " .
						 "WHERE r.CLIENT_ID = " . $client_id .
						 "  AND r.REPORT_Date >= '" . $begin_date . "' AND r.REPORT_Date <= '" . $end_date . "'" .
						 "  AND r.REPORT_Provider = p.PROVIDER_ID" .
						 "  AND r.REPORT_Service = s.SERVICE_ID" .
						 "  AND (s.SERVICE_Type = 1 OR s.SERVICE_Type = 2)" .
						 "ORDER BY p.PROVIDER_Name, Year, s.SERVICE_Type, s.SERVICE_Name ASC, Month";
			$query = $this->db->query($qu_report);
			
			// Generate report from query data.
			$report = $this->generateDPRReport($query, $report_id, $ending_year);
			return $report;
		}
		
	}
