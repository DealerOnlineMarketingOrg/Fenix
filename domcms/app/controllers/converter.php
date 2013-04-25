<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Converter extends DOM_Controller {
	
		public function __construct() {
		parent::__construct();	
			//loading the member model here makes it available for any member of the controller.
			$this->load->model('converters');
		}
		
		public function Index() {
			$form = $this->input->post();
			if (strtolower($form['type']) == 'excel')
				$this->excel($form['file'], $form['html']);
			if (strtolower($form['type']) == 'pdf')
				$this->pdf($form['file'], $form['img'], $form['scale']);
		}
		
		// Excel conversion.
		private function excel($file_name, $html) {
			$this->converters->html_to_excel($file_name, $html);
		}
		
		// PDF conversion.
		private function pdf($file_name, $img_name, $scale) {
			$this->converters->image_to_pdf($file_name, $img_name, $scale);
		}
		
	}