<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

	class Io extends DOM_Controller {
		
		public function __construct() {
			parent::__construct();

			$this->load->helper('err_helper');
		}
		
		// $data is in the form:
		// data [ data, destPath ]
		// Returns: true if successful, false if not.
		public function saveDataURL() {
			$form = $this->input->post();
			
			$img = str_replace('data:image/png;base64,', '', $form['data']);
			$img = str_replace(' ', '+', $img);
			$img = base64_decode($img);
			if (file_put_contents($form['destPath'], $img))
				echo true;
			else
				echo false;
		}
		
		// Compresses a list of files and adds them to the specified zip file.
		public function zipFiles() {
			// Get a new zip initialization for each call.
			$this->load->library('zip');

			$form = $this->input->post();
			
			foreach ($form['file_list'] as $file)
				// Store file in zip.
				$this->zip->read_file($file);
				
			// Save zip to drive.
			$this->zip->archive($form['zip_file']);
		}
		
		// Sends an email to the specified recipients, along with attached files.
		// Required: sender_email, sender_name, to, subject, message.
		// to, cc, bcc should be comma-delimited lists.
		// attachements is a comma-delimited list of server-local file paths
		//   (not url) for the attachments.
		// signatures is a comma-delimited list of signature line labels.
		public function sendEmail() {
			$this->load->library('email');
			$this->load->model('administration');
			
			$form = $this->input->post();
			
			$config = array (
				'mailtype' => 'html',
				'crlf' => "\n",
				'newline' => "\n"
			);
			$this->email->initialize($config);
			
			$this->email->from($form['sender_email'], $form['sender_name']);
			
			$this->email->to($form['to']);
			if (isset($form['cc']))
				if ($form['cc'] != '')
					$this->email->cc($form['cc']);
			if (isset($form['bcc']))
				if ($form['bcc'] != '')
					$this->email->bcc($form['bcc']);
			
			if (isset($form['reply_to_email'])) {
				$name = (isset($form['reply_to_name'])) ? $form['reply_to_name'] : $form['sender_name'];
				$this->email->reply_to($form['reply_to_email'], $name);
			} else {
				$this->email->reply_to($form['sender_email'], $form['sender_name']);
			}
			
			$this->email->subject($form['subject']);
			$message = $form['message'];
			if (isset($form['signature'])) {
				// attach signatures to bottom of message.
				$tokens = explode(',', $form['signature']);
				foreach ($tokens as $token)
					$message .= str_repeat("\n", 3) . $token . ' ' . str_repeat('_', 35);
			}
			$this->email->message($message);
			
			if (isset($form['attachments'])) {
				$tokens = explode(',', $form['attachments']);
				foreach ($tokens as $token)
					$this->email->attach($token);
			}
			
			$this->email->send();
		}
		
	}