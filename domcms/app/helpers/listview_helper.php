<?php

	/*
	 * This helper is for loading tables for list views. 
	 * should be able to pass data to a function to load any table as long as we know what model to use and what function to call with what data to call with column names for the table
	 * @params = object;
	   @params->permLevel = the permission level required to view the table.
	   @params->modelFile = file to call for the data queries (so we can load the model)
	   @params->colHeaders = array of arrays (params->colHeaders[0]['ClassName'], params->colHeaders[0]['Label'] the headers in the table
	   @params->tableData = this should be the function to call to get the data (ex: administration->getClient(data)) hint: leave out the context because we will add that in. this will return an array of columns, which the below keys are required.
	   		required -> tableData[index]->Label. (optional) tableData[index]->colClass, tableData[index]->colWidth, tableData[index]->href (for email addresses and such), tableData[index]->inlineStyles, tableData[index]->TagClass
	   @params->tableClass = Any additional classes needed to be added to the table itself. 
	   @params->useDataTables = boolean to turn off the datatables classes.
	   @params->enableTags = Boolean to turn the tags column on or off. (the data for the table must have the tags peice as well, as a fail safe, the function will check if both are true
	   @params->buttons = this is an array of boolean vals.
			@params->buttons['edit'] = true;
			@params->buttons['add'] = true;
			@params->buttons['disable'] = true;
			@params->buttons['enable'] = true;
			@params->buttons['view'] = true;
	   @params->miscCSS = any <style type="text/css"> to add to the page.
	   @params->miscJS = any javascript include files
			
	 */
	 
	 function ListTable($params) {
		 //check to see if params is an obj, if you pass an array, it will still work too, it will just turn your array into an object.
		 if(!is_object($params)) {
			$params = (object)$params; 
		 }
		 //This is the gateKeeper, it checks for access.
		 $gainAccess = GateKeeper($params->permLevel,$params->userPermLevel);
		 if($gainAccess) {
			 //variable to hold html
			 $html .= '<table cellpadding="0" cellspacing="0" border="0" class="' . isset($params->useDataTables) ? (($params->useDataTables) ? 'display' : 'tableStatic') : 'tableStatic' . ' ' . isset($params->tableClass) ? $params->tableClass : '' . '" id="example" width="100%">';
			 $html .= '<thead><tr>';
			 //loop through column headers
			 foreach($params->colHeaders as $col) {
				//each header will do write out this code to the html
				$html .= '<td ' . ((isset($col['codedAttributes'])) ? $col['codedAttributes'] : '') . ' class="' . ((isset($col['ClassName'])) ? $col['ClassName'] : '') . '">' . $col['Label'] . '</td>';
			 }
			 $html .= '</tr></thead><tbody>';
			 $tableData = $ci->$params->tableData;
			 foreach($params->tableData as $row) {
				//start the row 
				if($params->enableTags) {
					$html .= '<tr class="tagElement ' . $row['TagClass'] . '">';
				}else {
					$html .= '<tr>';	
				}
				
				$html .= '<td class="' . $row['TagClass'] . '">';
				foreach($row['cols'] as $col) {
					//fill the cells with expected and optional data/properties
					$html .= '<td class="' . ((isset($col['colClass'])) ? $col['colClass'] : '') . '"' . ((isset($col['inlineStyles'])) ? ' style="' . $col['inlineStyles'] . '"': '') . ((isset($col['colWidth'])) ? ' colspan="' . $col['colWidth'] . '"' : '') . '>';
					$html .= ((isset($col['href'])) ? '<a href="' . $col['href'] . '">' : '') . $col['Label'] . ((isset($col['href'])) ? '</a>' : '');
					$html .= '</td>';
				}
				//end the row
				$html .= '</tr>';
			 }
			 //cose the body and table
			 $html .= '</tbody></table>';
		 }
		 
	 }

?>