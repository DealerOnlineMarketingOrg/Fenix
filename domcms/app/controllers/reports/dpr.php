<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	//
	class Dpr extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('getdpr');
			$this->load->helper('charts');
			$this->load->helper('controls');
			$this->load->model('administration');

			$this->activeNav = 'reports';
		}
		
		public function Index() {
			$endMonth = date('m');
			$endYear = date('Y');
			$startMonth = '1';
			$startYear = (string)($endYear - 1);
			$data = array(
				'startMonth' => $startMonth,
				'startYear' => $startYear,
				'endMonth' => $endMonth,
				'endYear' => $endYear
			);
			$this->Load('reports', $data);
		}
		
		public function add() {
			$report_data = $this->getdpr->get('Provider');
			$prov_options = $this->getdpr->output_as_options($report_data);
			$service_list = $this->getdpr->get('Service');
			$service_options = $this->getdpr->output_as_options($service_list);
			
			$data = array(
				'html' => '',
				'page' => 'add',
				'providers' => $prov_options,
				'services' => $service_options,
				'report_leads' => '',
				'report_lineChart' => '',
				'report_lineChart_script' => '{}'
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('forms/form_addDpr', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function import_step1() {
			$report_data = $this->getdpr->get('Provider');
			$prov_options = $this->getdpr->output_as_options($report_data);
			
			$data = array(
				'persistent' => array(
					'empty' => '',
				),
				'sources' => $prov_options,
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprImport_step1', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function import_step2() {
			$form = $this->input->post();
			
			$source = $this->getdpr->get('Provider', $form['source']);
			$service_list = $this->getdpr->get('Service');
			$service_options = $this->getdpr->output_as_options($service_list);
				
			$data = array(
				'persistent' => array(
					'source' => $source,
				),
				'source' => $source,
				'metrics' => $service_options,
			);
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprImport_step2', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function import_step3() {
			$form = $this->input->post();
			
			$provider = $form['source'];
			// Generate array of metrics.
			$metrics = array();
			$count = $form['metricCount'];
			for ($i = 0; $i < $count; $i++)
				$metrics[] = $this->getdpr->get('Service', $form['metrics_'.$i]);
			
			$data = array(
				'persistent' => array(
					'source' => $form['source'],
					'metrics' => $metrics,
				),
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprImport_step3', $this->theme_settings['ThemeViews'],$data);
		}
		
		// Returns array of (month/year)s in the specified date range.
		function getMonthYearRange($startDate, $endDate) {
			$startMonth = date('n', strtotime($startDate));
			$endMonth = date('n', strtotime($endDate));
			$startYear = date('y', strtotime($startDate));
			$endYear = date('y', strtotime($endDate));
			$range = array();
			for ($y = $startYear; $y <= $endYear; $y++) {
				if ($y == $startYear) {
					$sm = $startMonth;
					$em = ($y != $endYear) ? 12 : $endMonth;
				} else {
					$sm = 1;
					$em = ($y != $endYear) ? 12 : $endMonth;
				}
				for ($m = $sm; $m <= $em; $m++)
					$range[] = $m . '/' . $y;
			}
			return $range;
		}
		
		public function import_step4() {
			$form = $this->input->post();
		
			$startDate = $form['metricsStartMonth'].'/1/'.$form['metricsStartYear'];
			$endDate = $form['metricsEndMonth'].'/1/'.$form['metricsEndYear'];
			
			$colNames = $this->getMonthYearRange($startDate, $endDate);
			$rowNames = array();
			$metrics = $form['metrics'];
			$metrics[] = array('ID' => -1, 'Name' => 'Cost');
			foreach ($metrics as $metric)
				$rowNames[] = $metric['Name'];
			
			$dbStartDate = $form['metricsStartYear'].'/'.$form['metricsStartMonth'].'/1';
			$dbEndDate = $form['metricsEndYear'].'/'.$form['metricsEndMonth'].'/1';
			$sql = 'SELECT MONTH(r.REPORT_Date) as Month, YEAR(r.REPORT_Date) as Year, r.REPORT_Value as Value, s.SERVICE_Name as ServiceName, p.PROVIDERDATA_Cost as Cost ' .
				   'FROM DPRReports r ' .
				   'LEFT JOIN DPRProviderData p ON (r.REPORT_Provider = p.PROVIDER_ID ' .
				   '  AND p.PROVIDERDATA_Month = MONTH(r.REPORT_Date) ' .
				   '  AND p.PROVIDERDATA_Year = YEAR(r.REPORT_Date)) ' .
				   'LEFT JOIN DPRReportServices s ON r.REPORT_Service = s.SERVICE_ID ' .
				   'WHERE r.REPORT_Date >= "' . $dbStartDate . '" ' .
				   '  AND r.REPORT_Date <= "' . $dbEndDate . '" ' .
				   '  AND r.REPORT_Provider = ' . $form['source']['ID'].' ' .
				   'ORDER BY s.SERVICE_Name';
			$query = $this->db->query($sql);
			$results = $query->result();
			
			$c = 0;
			$values = array();
			foreach ($colNames as $col) {
				$tokens = explode("/", $col);
				$month = $tokens[0];
				$year = '20'.$tokens[1];
				$values[$c] = array();
				$r = 0;
				foreach ($rowNames as $row) {
					$values[$c][] = '';
					if ($query->num_rows() > 0) {
						if ($row == 'Cost') {
							$values[$c][$r]->value = $results[0]->Cost;
						} else {
							foreach ($results as $item) {
								if ($item->Month == $month && $item->Year == $year && $item->ServiceName == $row) {
									$values[$c][$r]->value = $item->Value;
									break;
								}
							}
						}
					}
					$r++;
				}
				$c++;
			}
			
			$data = array(
				'columns' => $colNames,
				'rows' => $rowNames,
				'values' => $values,
			);
			
			$spreadsheet = $this->load->dom_view('forms/form_spreadsheet', $this->theme_settings['ThemeViews'], $data, true);
			
			$data = array(
				'persistent' => array(
					'source' => $form['source'],
					'dates' => $colNames,
					'metrics' => $metrics,
				),
				'spreadsheet' => $spreadsheet,
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprImport_step4', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function import_stepSubmit() {
			$form = $this->input->post();
		
			$source = $form['source'];
			$dates = $form['dates'];
			$metrics = $form['metrics'];
			
			foreach ($dates as $column) {
				$tokens = explode("/", $column);
				$month = $tokens[0];
				$year = '20'.$tokens[1];
				$date = $year . '/' . $month . '/01';
				foreach ($metrics as $row) {
					$metric = $row;
					// Any keys with spaces on the form keys have been replaced by underscores.
					$value = trim($form[str_replace(" ", "_", $column.":".$row['Name'])]);
					
					if ($value != '') {
						if ($metric['Name'] == 'Cost')
							$cost = $value;
						else
							$cost = '';
							
						// Add total to report table.
						$lead_data = array(
							'providerID' => $source['ID'],
							'serviceID' => $metric['ID'],
							'month' => $month,
							'year' => $year,
							'date' => $date,
							'total' => $value,
							'cost' => $cost,
							'clientID' => $this->user['DropdownDefault']->SelectedClient
						);
						if ($metric['Name'] == 'Cost')
							$this->rep->addLeadCost($lead_data);
						else
							$this->rep->addLeadTotal($lead_data);
					}
				}
			}
			
			$err_level = 1;
			$err_msg = 'DPR Sources saved!';
			// Throw error and redirect back to DPR.
			throwError(newError("DPR Sources", $err_level, $err_msg, 0, ''));
			// Tell the wizard it's still good to go.
			echo '1';
		}
		
		public function add_step1() {
			$report_data = $this->getdpr->get('Provider');
			$prov_options = $this->getdpr->output_as_options($report_data);
			
			$data = array(
				'persistent' => array(
					'empty' => '',
				),
				'sources' => $prov_options,
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprAdd_step1', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function add_step2() {
			$form = $this->input->post();
			
			$data = array(
				'persistent' => array(
					'source' => $form['source'],
				),
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprAdd_step2', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function add_step3() {
			$form = $this->input->post();
			
			$date = $form['metricsStartYear'].'/'.$form['metricsStartMonth'].'/1';
			
			$source = $this->getdpr->get('Provider', $form['source']);
			$sql = 'SELECT s.SERVICE_ID as ID,s.SERVICE_Name as Name,p.PROVIDERDATA_Cost as Cost ' .
				   'FROM DPRReportServices s ' .
				   'LEFT JOIN DPRSourceMetrics sm ON (s.SERVICE_ID = sm.SOURCEMETRICS_MetricID) ' .
				   'RIGHT JOIN DPRProviderData p ON (p.PROVIDER_ID = sm.SOURCEMETRICS_SourceID) ' .
				   'WHERE ( ' .
				   		'SELECT COUNT(*) ' .
						'FROM DPRReports dr ' .
						'WHERE dr.REPORT_Date = "'.$date.'" ' .
						'  AND s.SERVICE_ID = dr.REPORT_Service ' .
						'  AND dr.REPORT_Provider = '.$source->ID.' ' .
						'  AND dr.CLIENT_ID = ' . $this->user['DropdownDefault']->SelectedClient.' ' .
				   		') = 0 ' .
				   '  AND sm.SOURCEMETRICS_SourceID = '.$source->ID.' ' .
				   'GROUP BY sm.SOURCEMETRICS_MetricID ' .
				   'ORDER BY s.SERVICE_Name ASC';
			echo $sql;
			$query = $this->db->query($sql);
			$service_list = $query->result();
			$service_options = $this->getdpr->output_as_options($service_list);
			
			$data = array(
				'persistent' => array(
					'source' => $source,
					'date' => $date,
					'month' => $form['metricsStartMonth'],
					'year' => $form['metricsStartYear'],
				),
				'source' => $source,
				'metrics' => $service_options,
				'cost' => (isset($service_list[0]->Cost)) ? $service_list[0]->Cost : '',
			);
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprAdd_step3', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function add_stepSubmit() {
			$form = $this->input->post();
		
			$source = $form['source'];
			$date = $form['date'];
			$count = $form['metricCount'];
			
			for ($i = 0; $i < $count; $i++) {
				$m['ID'] = $form['metrics_'.$i];
				$m['Total'] = $form['total_'.$i];
				$metric = $m;
				
				$total = $metric['Total'];
				
				if ($total != '') {
					$cost = $form['cost'];
					
					// Add total to report table.
					$lead_data = array(
						'providerID' => $source['ID'],
						'serviceID' => $metric['ID'],
						'month' => $form['month'],
						'year' => $form['year'],
						'date' => $date,
						'total' => $total,
						'cost' => $cost,
						'clientID' => $this->user['DropdownDefault']->SelectedClient
					);
					$this->rep->addLeadCost($lead_data);
					$this->rep->addLeadTotal($lead_data);
				}
			}
			
			$err_level = 1;
			$err_msg = 'DPR Sources saved!';
			// Throw error and redirect back to DPR.
			throwError(newError("DPR Sources", $err_level, $err_msg, 0, ''));
			// Tell the wizard it's still good to go.
			echo '1';
		}
		
		public function edit_step1() {
			$report_data = $this->getdpr->get('Provider');
			$prov_options = $this->getdpr->output_as_options($report_data);
			
			$data = array(
				'persistent' => array(
					'empty' => '',
				),
				'sources' => $prov_options,
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprEdit_step1', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function edit_step2() {
			$form = $this->input->post();
			
			$data = array(
				'persistent' => array(
					'source' => $form['source'],
				),
			);
			
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprEdit_step2',$data);
		}
		
		public function edit_step3() {
			$form = $this->input->post();
			
			$date = $form['metricsStartYear'].'/'.$form['metricsStartMonth'].'/1';
			
			$source = $this->getdpr->get('Provider', $form['source']);				   
			$sql = 'SELECT s.SERVICE_ID as ID,s.SERVICE_Name as Name,r.REPORT_Value as Value,p.PROVIDERDATA_Cost as Cost ' .
				   'FROM DPRReports r, DPRReportServices s ' .
				   'LEFT JOIN DPRSourceMetrics sm ON (s.SERVICE_ID = sm.SOURCEMETRICS_MetricID) ' .
				   'RIGHT JOIN DPRProviderData p ON (p.PROVIDER_ID = sm.SOURCEMETRICS_SourceID) ' .
				   'WHERE ( ' .
				   		'SELECT COUNT(*) ' .
						'FROM DPRReports dr ' .
						'WHERE dr.REPORT_Date = "'.$date.'" ' .
						'  AND s.SERVICE_ID = dr.REPORT_Service ' .
						'  AND dr.REPORT_Provider = '.$source->ID.' ' .
						'  AND dr.CLIENT_ID = ' . $this->user['DropdownDefault']->SelectedClient.' ' .
				   		') > 0 ' .
				   '  AND sm.SOURCEMETRICS_SourceID = '.$source->ID.' ' .
				   '  AND r.CLIENT_ID = ' . $this->user['DropdownDefault']->SelectedClient.' ' .
				   '  AND p.PROVIDERDATA_ID = r.REPORT_Provider ' .
				   'GROUP BY sm.SOURCEMETRICS_MetricID ' .
				   'ORDER BY s.SERVICE_Name ASC';
			$query = $this->db->query($sql);
			$service_list = $query->result();
			//$service_options = $this->getdpr->output_as_options($service_list);
			$names = array();
			$values = array();
			$ids = array();
			foreach ($service_list as $s) {
				$names[] = $s->Name;
				$values[] = $s->Value;
				$ids[] = $s->ID;
			}
			
			$data = array(
				'persistent' => array(
					'source' => $source,
					'date' => $date,
					'month' => $form['metricsStartMonth'],
					'year' => $form['metricsStartYear'],
				),
				'source' => $source,
				'cost' => (isset($service_list[0]->Cost)) ? $service_list[0]->Cost : '',
				'names' => $names,
				'values' => $values,
				'ids' => $ids,
			);
			//$this->load->dom_view('wizards/ReportDprAdd',$data);
			//$this->LoadTemplate('forms/form_addDpr',$data);
			$this->load->dom_view('wizards/ReportDprEdit_step3', $this->theme_settings['ThemeViews'],$data);
		}
		
		public function edit_stepSubmit() {
			$form = $this->input->post();
		
			$source = $form['source'];
			$date = $form['date'];
			$count = $form['metricCount'];
			
			for ($i = 0; $i < $count; $i++) {
				$m['ID'] = $form['metrics_'.$i];
				$m['Total'] = $form['total_'.$i];
				$metric = $m;
				
				$total = $metric['Total'];
				
				if ($total != '') {
					$cost = $form['cost'];
					
					// Add total to report table.
					$lead_data = array(
						'providerID' => $source['ID'],
						'serviceID' => $metric['ID'],
						'month' => $form['month'],
						'year' => $form['year'],
						'date' => $date,
						'total' => $total,
						'cost' => $cost,
						'clientID' => $this->user['DropdownDefault']->SelectedClient
					);
					$this->rep->addLeadCost($lead_data);
					$this->rep->addLeadTotal($lead_data);
				}
			}
			
			$err_level = 1;
			$err_msg = 'DPR Sources saved!';
			// Throw error and redirect back to DPR.
			throwError(newError("DPR Sources", $err_level, $err_msg, 0, ''));
			// Tell the wizard it's still good to go.
			echo '1';
		}
		
		public function reports() {
			$this->Load('reports');
		}
		
		public function editReport() {
			$this->Load('editReport');	
		}
		
		public function ajaxGetCost() {		
			$form = $this->input->post();
			
			$where = array(
				'PROVIDER_ID' => $form['provider'],
				'PROVIDERDATA_Month' => $form['month'],
				'PROVIDERDATA_Year' => $form['year'],
			);
			$query = $this->db->get_where('DPRProviderData', $where);
			$results = $query->result();
			
			$cost = '';
			if ($query->num_rows > 0) {
				foreach ($results as $row) {
					$cost = $row->PROVIDERDATA_Cost;
					// We only want the first result.
					break;
				}
			}
			
			echo $cost;
		}
		
		public function eMail() {
			$domGroup = 1;
			// Don't grab group contacts if we're already on the dom group.
			if ($this->user['DropdownDefault']->SelectedClient != $domGroup) {
				$group_contacts = $this->administration->getAllContactsInGroup($this->user['DropdownDefault']->SelectedClient);
				$dom_contacts = $this->administration->getAllContactsInGroup($domGroup);
				$all_contacts = array_merge($group_contacts, $dom_contacts);
			} else {
				$all_contacts = $this->administration->getAllContactsInGroup($domGroup);
			}
			
			$contacts = array();
			foreach ($all_contacts as $contact) {
				$name = '[' . $contact->ClientCode . '] ' . $contact->FirstName . ' ' . $contact->LastName;
				if ($contact->JobTitle != '')
					$name .= ' (' . $contact->JobTitle . ')';
				$c['text'] = $name;
				// Get work email from list.
				$emails = explode(',', $contact->Email);
				$c['value'] = '';
				foreach ($emails as $email) {
					$emailParts = explode(':', $email);
					if ($emailParts[0] == 'work')
						$c['value'] = $emailParts[1];
				}
				$contacts[] = $c;
			}
			
			$data = array (
				'items' => $contacts,
				'options' => $this->input->post()
			);
			
			if ($contacts)
				//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
				$this->load->dom_view('forms/form_input', $this->theme_settings['ThemeViews'], $data);
			else
				//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
				echo 0;
		}
		
		public function Load($page, $rdata = FALSE) {
			$form = $this->input->post();
			
			// Processing for dpr report page.
			if ($page == 'reports' || $page == 'editReport') {
				// If function was called posted instead of called, get posted data.
				if (!$rdata) $rdata = $form;
				
				$signature = '';
				if ($this->user['DropdownDefault']->SelectedClient < 1) {
					throwError(newError('Clients Add', -1, 'You must chose a dealership in order to view a DPR report.', 0, ''));
					$runReport = false;
					$report_leads = '';
					$report_lineChart = '';
					$report_lineChart_script = '';
					$report_pieChart = '';
					$report_pieChart_script = '';
					
				} else {
					$report = $this->rep->getDPRReport($this->user['DropdownDefault']->SelectedClient, $rdata['startMonth'], $rdata['startYear'], $rdata['endMonth'], $rdata['endYear']);
					// Wrap each chart in a div to keep them seperate.
					$report_element_start = 1;
					
					$report_lineChart_script = '';
					$report_html = generateLineChart($report, 'lineChart', $report_element_start, $report_lineChart_script);
					$report_lineChart = "<div id='reportBlock_lineChart' class='report'>" . $report_html . "</div>";
					
					$report_pieChart_script = '';
					$report_html = generatePieChart($report, 'pieChart', $report_element_start, $report_pieChart_script);
					$report_pieChart = "<div id='reportBlock_pieChart' class='report'>" . $report_html . "</div>";
					
					$report_html = generateTableChart($report, 'leads', $report_element_start);
					$report_leads = "<div id='reportBlock_tableChart' class='report'>" . $report_html . "</div>";
					
					$runReport = true;
					$signature = $this->administration->getSignatureFragment($this->user['UserID']);
				}
				
				$data = array(
					'runReport' => $runReport,
					'html' => '',
					'providers' => '',
					'services' => '',
					'report_leads' => $report_leads,
					'report_lineChart' => $report_lineChart,
					'report_lineChart_script' => $report_lineChart_script,
					'report_pieChart' => $report_pieChart,
					'report_pieChart_script' => $report_pieChart_script,
					// Send date range data to report form.
					'dateRange' => array (
						'startMonth' => $rdata['startMonth'],
						'startYear' => $rdata['startYear'],
						'endMonth' => $rdata['endMonth'],
						'endYear' => $rdata['endYear']
					),
					'signatureFragment' => ($signature) ? $signature : ''
				);
			}

			switch ($page) {
				case 'reports': $this->LoadTemplate('forms/form_reportDpr',$data); break;
				case 'editReport': $this->LoadTemplate('forms/form_reportDpr',$data); break;
			}	
			
		}
	
	}