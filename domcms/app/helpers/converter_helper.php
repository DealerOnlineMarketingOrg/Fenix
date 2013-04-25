<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/*	$file_name : The path and name of the excel file being created.
		$isExcel2007 : Set True if an Excel 2007 file is required (.xlsx).
	                   False if Excel 5 (.xls). Defaults to Excel 2007.
	*/
	function CreateExcel($file_name, &$objPHPExcel, $isExcel2007 = TRUE) {
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		
		if ($isExcel2007) {
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		} else
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save($file_name);
	}
	
	/*	$file_name : The path and name of the PDF file being created.
		Creates a PDF from a PHPExcel object.
		Used for converting html code to pdf.
	*/
	function CreatePDF($file_name, &$objPHPExcel) {
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';

		$objWriter = new PHPExcel_Writer_PDF($objPHPExcel);
		$objWriter->setPreCalculateFormulas(false);
		$objWriter->writeAllSheets();
		$objWriter->save($file_name);
	}
	
	/*	$file_name : The path and name of the PDF file being created.
		Creates a PDF from a single image.
	*/
	function CreatePDFFromImage($file_name, $img_name, $scale) {
		require_once('domcms/libraries/tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		//set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		//set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// add a page
		$pdf->AddPage();

		list($width, $height) = getimagesize($img_name);
		$scaledWidth = (float)($width * $scale);
		$scaledHeight = (float)($height * $scale);
		$pdf->Image($img_name, '', (float)15, (float)$scaledWidth, (float)$scaledHeight, 'PNG', '', '', 2, 200, 'C', false, false, 0, false, false, true, false);
		
		//Close and output PDF document
		$pdf->Output($file_name, 'f');
	}
	
	// Returns the style markup for the specified cell.
	// $globals (array) are values that are global to the sheet, but can be modified relative to the cell,
	//   such as font-size (can markup to %150, for example. Excel doesn't allow 150%. This has
	//   to be calculated manually from $globals).
	function GetStyleMarkup(&$worksheet, $globals, $col, $row, $nodeName, $attrName, $attrValue) {
		$styleArray = array();
		
		// Clean up whitespaces.
		$nodeName = trim($nodeName);
		$attrName = trim($attrName);
		$attrValue = trim($attrValue);
		
		switch ($nodeName) {
			case 'table':
				switch ($attrName) {
				}
				break;
			case 'col':
			case 'colgroup':
			case 'caption':
			case 'thead':
			case 'tbody':
			case 'tfoot':
				// Table elements not yet implemented.
				break;
			case 'tr':
				switch ($attrName) {
				}
				break;
			case 'th':
				switch ($attrName) {
				}
				break;
			case 'td':
				switch ($attrName) {
					// Background
					case 'background-color':
					    $styleArray['fill'] = array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
				            'color' => array('rgb' => ColorToHex($attrValue)));
						break;
					// Dimensions
					case 'height':
						break;
					case 'width':
						break;
					// Font
					case 'font-family':
						$styleArray['font'] = array(
							'name' => $attrValue);
						break;
					case 'font-size':
						if (strpos($attrValue, '%') == -1)
							$val = intval($attrValue);
						else
							$val = intval(intval($globals['font-size']) * intval($attrValue) / 100);
						$styleArray['font'] = array(
							'size' => $val);
						break;
					case 'font-weight':
						if ($attrValue == 'bold')
							$styleArray['font'] = array(
								'bold' => true);
						break;
					// Text
					case 'color':
						break;
					case 'text-align':
						if ($attrValue == 'left')
							$styleArray['alignment'] = array(
								 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
						else if ($attrValue == 'right')
							$styleArray['alignment'] = array(
								 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						else if ($attrValue == 'center')
							$styleArray['alignment'] = array(
								 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						else if ($attrValue == 'justify')
							$styleArray['alignment'] = array(
								 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
						break;
					case 'vertical-align':
						if ($attrValue == 'top')
							$styleArray['alignment'] = array(
								 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP);
						else if ($attrValue == 'middle')
							$styleArray['alignment'] = array(
								 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER);
						else if ($attrValue == 'bottom')
							$styleArray['alignment'] = array(
								 'vertical' => PHPExcel_Style_Alignment::VERTICAL_BOTTOM);
						break;
				}
				break;
		}
		
		return $styleArray;
	}
	
	// Returns an array of each attribute in style, with the
	//  dom-standard nodeName and nodeValue fields.
	// Extra spaces are stripped.
	function GetStyleAttributes($style) {
		$attrs = array();
		// Split apart each attribute in styles.
		$all_attrs = explode(';', $style);
		foreach ($all_attrs as $attr) {
			// Split apart the name and value parts of the attribute.
			$parts = explode(':', $attr);
			// If both parts exist..
			if (count($parts) == 2)
				// ..add to attrs array.
				$attrs[] = (object)array('nodeName' => $parts[0], 'nodeValue' => $parts[1]);
		}
		return $attrs;
	}
	
	// Sets the markup on the specified cell.
	// $defaultStyleArray only applies to the worksheet itself.
	// This is done in the table markup, since the worksheet represents the table.
	// 
	function SetMarkup(&$worksheet, $globals, $col, $row, $nodeName, $attrName, $attrValue) {
		$ci =& get_instance();
		
		$ci->load->helper('phpext_helper');
		
		// Get style markups.
		$defaultStyleArray = array();
		$styleArray = array();
		
		// Clean up whitespaces.
		$nodeName = trim($nodeName);
		$attrName = trim($attrName);
		$attrValue = trim($attrValue);
		
		// If $attrName is 'style', we need to parse $attrValue into style name and value.
		if ($attrName == 'style')
			$styles = GetStyleAttributes($attrValue);

		switch ($nodeName) {
			case 'table':
				switch ($attrName) {
					// TODO: there's a worksheet default class. Check it out for table.
					case 'defaults':
						$dsa = array(
							'font'=>array(
								'name'      =>  'Arial',
								'size'      =>  9,
								'bold'      => false
							),
							'borders' => array(
								'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN,
									'color' => array('rgb' => 'FFFFFF'))
							),
							'alignment' => array(
								'wrap' => false
							)
						);
						$defaultStyleArray = array_merge_recursive_($defaultStyleArray, $dsa);
						break;
					case 'border':
						break;
					case 'style':
						//$styleArray[] = GetStyleMarkup($worksheet, $col, $row, $nodeName, $attrName, $attrValue);
						break;
				}
				break;
			case 'col':
			case 'colgroup':
			case 'caption':
			case 'thead':
			case 'tbody':
			case 'tfoot':
				// Table elements not yet implemented.
				break;
			case 'tr':
				switch ($attrName) {
					case 'defaults':
						break;
					case 'style':
						foreach ($styles as $style) {
							$sa = GetStyleMarkup($worksheet, $globals, $col, $row, $nodeName, $style->nodeName, $style->nodeValue);
							$styleArray = array_merge_recursive_($styleArray, $sa);
						}
						break;
				}
				break;
			case 'th':
				switch ($attrName) {
					case 'defaults':
						break;
					case 'colspan':
						break;
					case 'rowspan':
						break;
					case 'style':
						foreach ($styles as $style) {
							$sa = GetStyleMarkup($worksheet, $globals, $col, $row, $nodeName, $style->nodeName, $style->nodeValue);
							$styleArray = array_merge_recursive_($styleArray, $sa);
						}
						break;
				}
				break;
			case 'td':
				switch ($attrName) {
					case 'defaults':
						$sa = GetStyleMarkup($worksheet, $globals, $col, $row, $nodeName, 'text-align', 'left');
						$styleArray = array_merge_recursive_($styleArray, $sa);
						break;
					case 'colspan':
						break;
					case 'rowspan':
						break;
					case 'style':
						foreach ($styles as $style) {
							$style->nodeName = trim($style->nodeName);
							$sa = GetStyleMarkup($worksheet, $globals, $col, $row, $nodeName, $style->nodeName, $style->nodeValue);
							$styleArray = array_merge_recursive_($styleArray, $sa);
						}
						break;
				}
				break;
		}
		
		// Set style markup.
		if (count($defaultStyleArray) > 0) {
			$worksheet->getDefaultStyle()->applyFromArray($defaultStyleArray);
		}
		if (count($styleArray) > 0)
			$worksheet->getStyle($col.$row)->applyFromArray($styleArray);
	}
	
	function setImage($worksheet, $source, $col, $row, $offset, $width, $height) {
		$objDrawing = new PHPExcel_Worksheet_Drawing();
		$objDrawing->setName('Report');
		$objDrawing->setDescription('Report');
		$objDrawing->setPath($source);
		$objDrawing->setWidth($width);
		$objDrawing->setHeight($height);
		$objDrawing->setCoordinates($col.$row);
		$objDrawing->setOffsetX($offset);
		$objDrawing->setWorksheet($worksheet);
	}
	
	// Creates a new worksheet for a table.
	function CreateWorksheet(&$objPHPExcel) {
		// Sheets are 0-indexed.
		$sheet = $objPHPExcel->getSheetCount() - 1;
		if ($sheet > 0)
			// Sheet 0 is created by default.
			$objPHPExcel->createSheet($sheet);
		$suf = $sheet + 1;
		$wksheetname = 'Worksheet' . $suf;
		$objPHPExcel->setActiveSheetIndex($sheet);
		// Worksheet tab name.
		$objPHPExcel->getActiveSheet()->setTitle($wksheetname);
		$worksheet = $objPHPExcel->getActiveSheet();

		return $worksheet;
	}
	
	// isMarkup = true, only cell/data markup is applied.
	// isMarkup = false, data is set.
	function ProcessTable(&$objPHPExcel, &$worksheet, &$col, &$row, $node, &$dataLayout, $isMarkup) {
		// Process the different table tags.
		// Change cols and rows after processing this cell.
		$newCol = $col;
		$newRow = $row;
		switch (strtolower($node->nodeName)) {
			case 'table':
				// New table. Create new worksheet from it.
				$worksheet = CreateWorksheet($objPHPExcel);
				break;
			case 'col':
			case 'colgroup':
			case 'caption':
			case 'thead':
			case 'tbody':
			case 'tfoot':
				// Table elements not yet implemented.
				break;
			case 'tr':
				// New row.
				$row++;
				$col = '';
				break;
			case 'th':
			case 'td':
				// New column.
				if ($col == '')
					$col = 'A';
				else
					$col++;
				break;
			case '#text':
				if (!$isMarkup) {
					// Apply only if not markup.
					// Data node.
					$worksheet->setCellValue($col.$row, $node->nodeValue);
					// Get text width and set dataLayout for this cell.
					// Retrieve the value back in case excel modified it for any reason.
					// Width calculation from http://phpexcel.codeplex.com/workitem/10375
					$val = $worksheet->getCell($col.$row)->getValue();
					$fontSize = $worksheet->getStyle($col.$row)->getFont()->getSize();
					$width = ((strlen($val) * $fontSize + 5) / $fontSize * 256 ) / 256;
					// Height is set automatically.
					$dataLayout[$col][$row]['width'] = (int)$width;
					$dataLayout[$col][$row]['height'] = -1;
					// Set offset for possible floating image in the cell right after the width.
					$dataLayout[$col][$row]['offset'] = ((int)$width)+1;
				}
				break;
			case 'img':
				// This is an image. src will contain the path.
				if (!$isMarkup) {
					$path = $node->getAttribute('src');
					// Set image with offset.
					if (isset($dataLayout[$col][$row]))
						$offset = $dataLayout[$col][$row]['offset'];
					else
						$offset = 0;
					// Get image width and height and use for the layout.
					list($width, $height) = getimagesize($path);
					setImage($worksheet, $path, $col, $row, $offset, $width, $height);
					// The width should be auto-sized (for now).
					// Width and height are in pixels. Excel stores them in points though.
					//  Conversion needed.
					$dataLayout[$col][$row]['width'] = -1;
					$dataLayout[$col][$row]['height'] = $height * 72 / 96;
					// Determine next offset to float next image to the right.
					$dataLayout[$col][$row]['offset'] = $offset + $width;
				}
				break;
			default:
				// Other node. Ignore.
				break;
		}
		
		if ($isMarkup) {
			$globals = array('font-size' => 9);
			// Apply attribute markup, only if specified.
			// Currently implemented: <th> and <td> markup.
			// Inline styles have precedence.
			//
			// Set the default markups first.
			SetMarkup($worksheet, $globals, $col, $row, $node->nodeName, 'defaults', '');
			// Set the coded attribute markups.
			if ($node->hasAttributes()) {
				$attrs = $node->attributes;
				foreach ($attrs as $attr) {
					// style attributes are parsed in SetMarkup.
					SetMarkup($worksheet, $globals, $col, $row, $node->nodeName, $attr->nodeName, $attr->nodeValue);
				}
			}
		}
		
		// Go through the child nodes recursively.
		if ($node->hasChildNodes()) {
			$children = $node->childNodes;
			foreach ($children as $child)
				ProcessTable($objPHPExcel, $worksheet, $col, $row, $child, $dataLayout, $isMarkup);
		}
	}
	
	// Sets the column widths and row heights from the data layout.
	function SetCellSizes(&$worksheet, $dataLayout) {
		// The worksheet cols and rows.
		$wsCols = array();
		$wsRows = array();
		
		// Go through the layout and determine column and row sizes.
		// A specified width or height size will override the auto-size for the respective
		//  column and row.
		foreach ($dataLayout as $col => $rows) {
			foreach ($rows as $row => $cell) {
				// Store cell width.
				if (!isset($wsCols[$col])) {
					// We don't have a width yet for this column.
					$wsCols[$col] = $cell['width'];
				} else {
					// We already have a width. See which is bigger.
					if ($cell['width'] > $wsCols[$col]) {
						$wsCols[$col] = $cell['width'];
					}
				}
				// Store cell height.
				if (!isset($wsRows[$row])) {
					// We don't have a height yet for this row.
					$wsRows[$row] = $cell['height'];
				} else {
					// We already have a height. See which is bigger.
					if ($cell['height'] > $wsRows[$row]) {
						$wsRows[$row] = $cell['height'];
					}
				}
			}
		}
		
		// Set the width of each column if not auto.
		foreach ($wsCols as $col => $width) {
			if ($width != -1)
				// Pad width a bit for font compensation.
				$worksheet->getColumnDimension($col)->setWidth((int)($width * 1.25));
		}
		// Set the height of each row if not auto.
		foreach ($wsRows as $row => $height) {
			if ($height != -1)
				$worksheet->getRowDimension($row)->setRowHeight($height);
		}
	}
	
	// Processes the tables in the HTML.
	function ProcessHTML(&$objPHPExcel, $dom) {
		$tables = $dom->getElementsByTagName('table');
		foreach ($tables as $table) {
			// dataLayout is a 2-D array representing the
			//  size layout of the rows and columns.
			// Structure is = [colA] {row1{width:,height:,offset:},row2{width:,height:,offset:},..}
			// Rows without data are left out of the layout.
			// dataLayout is filled in by ProcessTable on data input (it's skipped
			//  on markup input).
			// Since markup is always done first, we can trust text-measurements in
			//  the layout.
			// A width or height of -1 indicates that it will be set automatically.
			// Offset is the current starting point of an image when set in the cell.
			// Multiple images can be placed side-by-side using this, as the offset
			//  is set at the end of each prior image.
			$dataLayout = array();
			// Go through all elements in the tables to
			//  produce cells in the worksheets.
			// Columns start at A. Rows start at 1.
			// We're starting off the page so the first new data cell will
			//  start on A1.
			$col = '';
			$row = 0;
			// We'll traverse the table twice.
			// First we'll do the markup, then we'll do the data.
			// We do this because when markup is changed in PHPExcel,
			//  all data is rechecked. This can slow large worksheets down.
			ProcessTable($objPHPExcel, $worksheet, $col, $row, $table, $dataLayout, true);
			// Reset column and row for data input.
			$col = '';
			$row = 0;
			ProcessTable($objPHPExcel, $worksheet, $col, $row, $table, $dataLayout, false);
			// Set all the sizes for the columns and rows.
			SetCellSizes($worksheet, $dataLayout);
		}
	}
	
	function CreateExcelWorkbook($user_id) {
		//
		// Create new PHPExcel object with default attributes
		//
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
		$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
		
		$objPHPExcel->getProperties()->setCreator($user_id)
									 ->setLastModifiedBy($user_id)
									 ->setTitle("Automated Export")
									 ->setSubject("Automated Report Generation")
									 ->setDescription("Automated Report Generation.")
									 ->setKeywords("Report")
									 ->setCompany("Dealer Online Marketing")
									 ->setCategory("Report");
									 
		return $objPHPExcel;
	}
	
	function CreateDom($html) {
		$htmltable = $html;
		// Check table validity.
		if(strlen($htmltable) == strlen(strip_tags($htmltable)) ) {     // anything left after we strip HTML?
		  echo "<br />Invalid HTML Table after Stripping Tags, nothing to Export.";
		  exit;
		}
		
		// Prepare bare html for use with loadHTML.
		$htmltable = strip_tags($htmltable, "<table><tr><th><thead><tbody><tfoot><td><br><b><span><img><div>");
		$htmltable = str_replace("<br />", "\n", $htmltable);
		$htmltable = str_replace("<br/>", "\n", $htmltable);
		$htmltable = str_replace("<br>", "\n", $htmltable);
		$htmltable = str_replace("&nbsp;", " ", $htmltable);
		$htmltable = str_replace("\n\n", "\n", $htmltable);
		
		$dom = new domDocument;
		$dom->loadHTML($htmltable);
		if ($dom)
			// remove redundant whitespace
			$dom->preserveWhiteSpace = false;
		return $dom;
		
	}
	
	function HTMLToobjPHPExcel($user_id, $html) {
		$ci =& get_instance();
		$ci->load->helper('html_codes_helper');
		require_once 'domcms/libraries/PHPExcel.php';
		require_once 'domcms/libraries/PHPExcel/IOFactory.php';
		
		$dom = CreateDom($html);
		if(!$dom) {
		  echo "<br />Invalid HTML DOM, nothing to Export.";
		  exit;
		}
		
		$objPHPExcel = CreateExcelWorkbook($user_id);
		ProcessHTML($objPHPExcel, $dom);
		// set to first worksheet before close
		$objPHPExcel->setActiveSheetIndex(0);
		
		return $objPHPExcel;
	}