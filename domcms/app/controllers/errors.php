<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Errors extends DOM_Controller {
		//All we need is the construct and all controllers will pass through this on every page.
		public function __construct() {
			parent::__construct();
		}
		
		public function File_not_found() {
			$this->LoadTemplate('pages/errors/404');
		}
		
		public function Access_denied() {
			
		}
	}
