<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Passwords extends DOM_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('formwriter');
        $this->load->library('table');
        $this->load->helper('html');
        $this->load->model('administration');
        $this->load->helper('string_parser');
        $this->load->helper('msg');
		$this->activeNav = 'admin';
    }

    public function index($msg=false) {
			
		$table = '<table cellpadding="0" cellspacing="0" border="0" class="display" id="example">';
	
		// Password page will only be for clients.
		$level = $this->user['DropdownDefault']->LevelType;
		// If not on Client level, go back to dashboard.
		if ($level != 'c') {
			throwError(newError('Passwords', -1, 'Sorry, the Password page is not available for ' . (($level == 'a') ? 'agencies' : 'groups') . '.',0,''));
			redirect('/','refresh');
			exit;
		}
		
		$passwords = $this->administration->getPasswords($this->user['DropdownDefault']->SelectedClient);
		
		//table heading
		$html = '';
		$error_msg = '';
		
		//If there is a message to the user, present it as it should be.
		if ($msg AND $msg != 'error') {
			//The success message after a group was added or edited.
			$html .= success_msg('The Password was edited successfully!');
		} elseif($msg AND $msg != 'success') {
			//The error message after a group was added, or edited.
			$error_msg = error_msg();
		}
		
		$header = '<style type="text/css">a.actions_link{margin-right:5px;td.actionsCol{width:75px !important;text-align:center;}</style>';
		$header .= '<script type="text/javascript" src="' . base_url() . 'js/passwords_popups.js"></script>';

		$counter = 0;
		if($passwords) :
			$table .= '<thead><tr><th>Team</th><th>Type</th><th>Vendor</th><th>Login Address</th><th>Username</th><th>Password</th><th>Notes</th><th class="noSort" style="text-align:center">Actions</th></thead>';
			$table .= '<tbody>';
			foreach($passwords as $password) {
				//edit button
				$edit_img = '<img src="' . base_url() . THEMEIMGS . 'icons/dark/pencil.png" alt="Edit Passwords" />';
				$edit_a = '<a class="actions_link" href="javascript:editPasswords(\'' . $password->ID . '\',\'' . base_url() . '\');" title="Edit Passwords">' . $edit_img . '</a>';
				//view button
				$view_img = '<img src="' . base_url() . THEMEIMGS . 'icons/color/application.png" alt="View Passwords Information" />';
				$view_a = '<a class="actions_link" href="javascript:viewPasswords(\'' . $password->ID . '\');" title="View Passwords Information">' . $view_img . '</a>';
				//disable button
				$disable_img = '<img src="' . base_url() . THEMEIMGS . 'icons/color/cross.png" alt="Disable Passwords" />';
				$disable_a = '<a class="actions_link" href="javascript:disablePasswords(\'' . $password->ID . '\');" title="Disable Passwords">' . $disable_img . '</a>';
				//enable button
				$enable_img = '<img src="' . base_url() . THEMEIMGS . 'icons/notifications/accept.png" alt="Enable Passwords" />';
				$enable_a = '<a class="actions_link" href="javasript:enablePasswords(\'' . $password->ID . '\');" title="Enable Passwords">' . $enable_img . '</a>';
				
				$table .= '<tr class="tagElement ' . $password->Tag . '">';
				$table .= '<td class="tags"><div class="' . $password->Tag . '">&nbsp;</div></td>';
				$table .= '<td style="text-align:left;">' . $password->Type . '</td>';
				$table .= '<td style="text-align:left;">' . $password->Vendor . '</td>';
				$table .= '<td><a href="' . $password->LoginAddress . '">' . $password->LoginAddress . '</a></td>';
				
				$del = ",";
				$clipData = 'Login Address' . $del . $password->LoginAddress . $del . 'User Name' . $del . $password->Username . $del . 'Password' . $del . $password->Password;
				$table .= '<td style="white-space:nowrap"><span style="font-weight:bold;"><div id="username'.$counter . '" class="clipBoard" clipBoardData="' . $clipData . '" style="width:22px; height:22px; float:left; cursor:pointer; background: url(' .  base_url() . 'imgs/icons/dark/clipboard.png) no-repeat"></div><div style="float:left"><a href="mailto:' . $password->Username . '">' . $password->Username . '</a></div></span></td>';
				
				$table .= '<td style="white-space:nowrap"><div id="password'.$counter . '" class="clipBoard" clipBoardData="' . $clipData . '" style="width:22px; height:22px; float:left; cursor:pointer; background: url(' .  base_url() . 'imgs/icons/dark/clipboard.png) no-repeat"></div><div style="float:left">' . $password->Password . '</div></td>';
				
				$table .= '<td style="width:25%"><div style="overflow:hidden; max-height:37px"><div style="float:left;width:80%">' . $password->Notes . '</div><div onclick="javascript: openMore(\'' . $password->Notes . '\');" style="cursor:pointer;float:left;color:blue;top:0">...more</div></div></td>';
				
				$table .= '<td class="actionsCol" style="width:75px;text-align:center">';
				
				//put allowed action buttons in place
				if($this->CheckModule('Passwords_Edit')) {
					$table .= $edit_a;
				}
				//$table .= $view_a;
				/*
				// Disable/Enable not implemented yet.
				if($this->CheckModule('Passwords_Disable_Enable')) {
					if($password->Status) {
						$table .= $disable_a;
					}else {
						$table .= $enable_a;
					}
				}
				*/
				
				$table .= '</td>';
				$table .= '</tr>';
				
				$counter++;
			}
			$table .= '</tbody>';
		else :
			throwError(newError('Passwords', -1, 'Sorry, no passwords were found for this client. Please add a password or select a different client.',0,''));
		endif;
		$table .= '</table><div class="fix"></div>';
		//This builds the pages html out. We do this here so all our listing pages can have the same template view.
		$html .= ((count($passwords) > 0) ? $table . "\n":$error_msg);
		$html .= '<div class="fix"></div>';
		
		//If the user has permission to add a new group, then show a button to do so, top and bottom.
		$btn = '';
		if ($this->CheckModule('Passwords_Add') && $level == 'c') {
			$btn = '<input type="button" value="Add New Password" class="greenBtn" style="float:right;margin-top:10px;" id="add_password_btn" onclick="javascript:addPasswords(\'' . base_url() . '\');"></input>';
		}else if ($this->CheckModule('Passwords_Add')) {
			$btn = '<p style="float:right;">To add new password, please select a client from the Dealer Dropdown</p>';	
		}
		
		$popups = '</tbody></table><div id="addPasswordsInfo"></div><div id="editPasswordsInfo"></div><div id="viewPasswordsInfo"></div></div>' . "\n";
		
		$html = $header . $html . $btn . $popups;

		$scripts = '
			$(window).load (function() {
			});
			
			$(".clipBoard").click(function() {
				// Arguments need to be in the order of:
				//  Login Address Label, Login Address data,
				//  User Name label, User Name data,
				//  Password label, Password data
				var data = $(this).attr("clipBoardData");
				clipboardCopyDialog(data);
			});
			
			// Creates a clipboard-copy dialog.
			// The textList is an associative array:
			//   textLabel = textDescriptionToCopy
			function clipboardCopyDialog(textList) {
				var htmlHead = \'<div class="uDialog" title="Copy" style="text-align:left;"><div class="dialog-message" id="copy" title="Copy"><div class="uiForm"><style type="text/css">label{margin-top:5px;float:left;}</style><div class="widget" style="margin-top:0;padding-top:0;"><fieldset>\';
				
				var htmlBody = \'\';
				var args = textList.split(",");
				for (var i = 0; i < args.length; i = i + 2) {
					htmlBody += \'<div class="rowElem noborder"><label style="white-space:nowrap">\' + args[i] + \'</label><div class="formRight"><input type="text" class="clipBoard" value="\' + args[i+1] + \'" readonly><label style="font-size:75%;color:grey;margin:0">Click on box and press control+c to copy</label></div></div>\';
				}
				
				var htmlFoot = \'</fieldset></div></div></div></div>\';
				
				// We use <\/script> because some browsers have issues parsing this, even in a static string.
				var scripts = \'<script type="text/javascript">$(".clipBoard").click(function() {$(this).select();});<\/script>\';
				
				var dialogHtml = $(htmlHead + htmlBody + htmlFoot + scripts);
				// This syntax manually appends the script to the dialog window.
				// On some versions of dialog, there is a bug which will open a seperate window for
				//   the script fragment.
				dialogHtml.filter("div").dialog({width:500}).end().filter("script").appendTo("body");
			}
			
			function openMore(text) {
				var dialogHtml = $(\'<div id="notesDialog" title="Note"><p>\' + text + \'</p></div>\');
				dialogHtml.dialog();
			}
		';
		
		$data = array(
			'page_id' => 'Passwords',
			'page_html' => $html,
			'page_scripts' => $scripts
		);
		//LOAD THE TEMPLATE
		$this->LoadTemplate('pages/passwords/listings', $data);
    }
	
	private function getData($action) {
		$this->load->model('administration');

		$html = '';
		
		$types = $this->administration->getAllTypes();
		$vendors = $this->administration->getVendors();

		if ($action == 'edit') {
			$client_id = FALSE;
			$pwd_id = FALSE;
			if(isset($_POST['pwd_id'])) {
				$pwd_id = $this->input->post('pwd_id');
			}elseif(isset($_GET['pwdid'])) {
				$pwd_id = $this->input->get('pwdid');
			}else {
				$client_id = $this->user['DropdownDefault']->SelectedClient;
			}
		
			if ($client_id)
				$password = $this->administration->getPasswords($client_id);
			else
				$password = $this->administration->getPasswordsByID($pwd_id);

			if($password) {
				$data = array(
					'password' => $password[0],
					'html' => $html,
					'types' => $types,
					'vendors' => $vendors
				);
				return $data;
			} else
				return FALSE;
				
		} else {
			$client_id = $this->user['DropdownDefault']->SelectedClient;
			$data = array(
				'clientID' => $client_id,
				'html' => $html,
				'types' => $types,
				'vendors' => $vendors
			);
			return $data;
		}
	}
	
	public function Add($msg=false) {
		$data = $this->getData('add');

		if ($data)
			$this->load->dom_view('forms/passwords/add', $this->theme_settings['ThemeViews'], $data);
		else
			echo 0;
	}
	
	public function Edit($msg=false) {
		$this->load->helper('template');
		$level = $this->user['DropdownDefault']->LevelType;
		
		/*
		if($level == 'g') {
			redirect('/groups/edit','refresh');	
		}
		
		if($level == 'a') {
			redirect('/agencies/edit','refresh');	
		}
		*/
		
		$data = $this->getData('edit');
		if ($data)
			//THIS IS THE DEFAULT VIEW FOR ANY BASIC FORM.
			$this->load->dom_view('forms/passwords/edit', $this->theme_settings['ThemeViews'], $data);
		else
			//this returns nothing to the ajax call....therefor the ajax call knows to show a popup error.
			echo 0;
	}
	
	public function Delete($msg=false) {
		
	}
	
	public function View($msg=false) {
		$contact = $this->administration->getContact($this->input->post('view_id'));
		$contact->Name = $contact->FirstName . ' ' . $contact->LastName;
		$contact->Address = (isset($contact->Address)) ? mod_parser($contact->Address) : false;
		$contact->Phone = (isset($contact->Phone)) ? mod_parser($contact->Phone,false,true) : false;
		$contact->Email = mod_parser($contact->Email,false,true);
		$data = array(
			'display'=>$contact
		);
		$this->LoadTemplate('pages/passwords/view',$data);
	}

}
