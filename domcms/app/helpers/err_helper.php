<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	// Created a custom error and returns it. Can be used with throwError.
	// Page: the name of the page or file that produced/catches the error
	//       (not the processing page).
	// Error_Level is the level value of the error (positive:success, negative:failure).
	//   1=Success (data)
	//  -1=Failure (data)
	//  -2=Failure (system)
	// Element_List: stores a list of elements that require change (not currently used):
	//   {'name' => 'element_error_msg', ..}
	// Exception_Line and Exemption_Trace are used by system errors.
	function newError($Page, $Error_Level, $Error_Message, $Exemption_Line, $Exemption_Trace) {
		// This is for communicating errors (both error and successes) around pages.
		// Used with data adds, updates and removal.
		// Pages can react to successes/errors from other pages, such as data input models.
		// This is stored as an array of errors on session object 'err' for error stacking.
		// Individual errors will be removed from the err array when lifetime ends.
		$Element_List = array();
		$err = array(
			// Used internally to determine how many pages the error should persist across.
			//  Usually only persists by 1 page, unless rethrowError() is used.
			'Lifetime' => 1,
			// The name of the page that the error was created for.
			'Page' => $Page,
			// Error_Level is the level value of the error:
			//   1=Success (data)
			//  -1=Failure (data)
			//  -2=Failure (system)
			'Level' => $Error_Level,
			// Message being sent about the error/success.
			'Message' => $Error_Message,
			// An array of element names which are affected by the error.
			// Typically the list of elements which need to be corrected.
			// The keys are the element name, while the values are the
			//  error messages for those keys.
			'ElementList' => $Element_List,
			// Exemptions:
			// This holds the line number in file $Page where the exemption occured.
			'ExemptionLine' => $Exemption_Line,
			// This holds the stack trace of the exemption.
			'ExemptionTrace' => $Exemption_Trace
		);
		
		return (object)$err;
	}
				
	// Throws the custom error.
	function throwError($error) {
		$ci =& get_instance();
		
		// Add error to error array.
		$ci->err[] = $error;

		$err = array('err' => $ci->err);
		$ci->session->set_userdata($err);
		$ci->session->sess_write();
	}
	
	// ReThrows the current error(s) to the next page.
	function rethrowError() {
		$ci =& get_instance();
		
		if (!empty($ci->err))
			foreach ($ci->err as &$err)
				$err->Lifetime++;
		
		$ci->session->sess_write();
	}
	
	// Notifies the user of the error(s).
	// Function is inline. Uses itsbrain success and failure elements.
	// Outputs nothing if no error or success.
	// Failure and successes are taken care of here.
	// Error still lives after notification.
	// All messages are placed in a div of global class 'messageService',
	//  even if there's no messages to notify (guaranteeing the div).
	function notifyError() {
		$ci =& get_instance();
		
		echo '<div class="messageService">';
		if (!empty($ci->err)) {
			foreach ($ci->err as $err) {
				if ($err->Level == 1)
					// Notify success.
					echo '<div class="nNote nSuccess hideit" style="margin:0"><p><strong>SUCCESS: </strong>' . $err->Message . '</p></div>';
				if ($err->Level == -1)
					// Notify failure.
					echo '<div class="nNote nFailure hideit" style="margin:0"><p><strong>FAILURE: </strong>' . $err->Message . '</p></div>';
				if ($err->Level == -2) {
					// Notify system error.
					echo '<div class="nNote nFailure hideit" style="margin:0"><p><strong>SYSTEM ERROR: </strong>' . $err->Message . '</p></div>';
				}
				
				// Log error.
				logError();
			}
		}
		echo '</div>';
	}
	
	// Logs the error(s) in the database.
	// Gets ran automatically everytime a user gets notified of an error.
	function logError() {
		$ci =& get_instance();
		
		// Log only failure errors.
		if (!empty($ci->err))
			foreach ($ci->err as $err) {
				if ($err->Level < 0) {
					$data = array(
						'ERROR_UserID' => $ci->user['UserID'],
						'ERROR_DateTime' => date("Y-m-d H:i:s"),
						'ERROR_ErrObj' => serialize($err),
						'ERROR_DropdownObj' => serialize($ci->user['DropdownDefault'])
					);
					
					$ci->db->insert('ErrorLog', $data);
				}
			}
	}
	
	// Called on each page load (by DOM_Controller) to
	//  reduce the lifetime of each error.
	function ageError() {
		$ci =& get_instance();
		
		if (!empty($ci->err)) {
			foreach ($ci->err as &$err)
				$err->Lifetime--;
			
			$ci->session->sess_write();
		}
	}
	
	// Called automatically to clear out
	//  errors which have gone past end-of-life.
	function clearError() {
		$ci =& get_instance();
		
		if (!empty($ci->err)) {
			foreach ($ci->err as $key => $err)
				if ($err->Lifetime < 0)
					unset($ci->err[$key]);
		
			$ci->session->sess_write();
		}
	}