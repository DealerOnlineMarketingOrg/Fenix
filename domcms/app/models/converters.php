<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Converters extends DOM_Model {
		
		public function __construct() {
			parent::__construct();	
			$this->load->helper('query');
			$this->load->helper('converter_helper');
			
			// ===== PHPExcel ===== //
			/** Error reporting */
			error_reporting(E_ALL);
			ini_set('display_errors', TRUE);
			ini_set('display_startup_errors', TRUE);
			
			define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
			
			/** Include PHPExcel */
			require_once 'domcms/libraries/PHPExcel.php';
			require_once 'domcms/libraries/PHPExcel/IOFactory.php';
			
			// Set up PDF library
			$rendererName = PHPExcel_Settings::PDF_RENDERER_TCPDF;
			$rendererLibrary = 'tcpdf';
			$rendererLibraryPath = 'domcms/libraries/' . $rendererLibrary;
			if (!PHPExcel_Settings::setPdfRenderer(
					$rendererName,
					$rendererLibraryPath
				)) {
				die(
					'PHPExcel PDF: Please set the $rendererName and $rendererLibraryPath values' .
					PHP_EOL .
					' as appropriate for your directory structure'
				);
			}

		}
		
		public function html_to_excel($file_name, $table) {
			$user_id = 'DPR Report';
			$html = '<head><body>' . $table . '</body></head>';
			$objPHPExcel = HTMLToobjPHPExcel($user_id, $html);
			CreateExcel($file_name, $objPHPExcel, TRUE);
		}
		
		public function image_to_pdf($file_name, $img_name, $scale) {
			$user_id = 'DPR Report';
			CreatePDFFromImage($file_name, $img_name, $scale);
		}
		
		public function html_to_pdf($file_name, $table) {
			$user_id = 'DPR Report';
			$html = '<head><body>' . $table . '</body></head>';
			$objPHPExcel = HTMLToobjPHPExcel($user_id, $html);
			CreatePDF($file_name, $objPHPExcel);
		}
	}