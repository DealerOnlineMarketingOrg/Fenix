<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends DOM_Controller {
	
	public $level;
	public $user_id;
	
    public function __construct() {
        parent::__construct();
        $this->load->model(array('members','administration','utilities'));
        $this->load->helper(array('template','msg','html','file','contactinfo','websites'));
        $this->level = $this->user['DropdownDefault']->LevelType;
		$this->activeNav = 'admin';
		
		if(isset($_GET['uid'])) {
			$this->user_id = $_GET['uid'];	
		}else {
			$this->user_id = FALSE;	
		}
    }

	public function Load_table($return = false) {
		$this->load->dom_view('pages/users/table', $this->theme_settings['ThemeViews']);
	}

    public function Index() {
		$this->LoadTemplate('pages/users/listing');
    }
	
	public function Add() {
		$html = '';
		$data = array(
		  'html' => $html
		);
		$this->LoadTemplate('forms/users/add',$data);
	}
	
	public function Edit_avatar_form() {
		$data = array(
			'user_id'=>$this->user_id
		);
		$this->load->dom_view('forms/users/edit_avatar', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Upload_avatar() {
		$user = $this->administration->getMyUser($this->user_id);
		$profile_url = base_url() . 'profile/' . strtolower($user->FirstName . $user->LastName);
		//print_object($this->input->post());
		$rootpath = $_SERVER['DOCUMENT_ROOT'];
		
		$config['upload_path'] = $rootpath . '/uploads/avatars/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '1000';
		$config['max_width'] = '640';
		$config['max_height'] = '480';
		$config['file_name'] = $this->user_id . '_' . strtolower($user->FirstName) . '_' . strtolower($user->LastName) . "_" . createRandomString(30,"ALPHANUM") . '.jpg';
		
		$this->load->library('upload',$config);
		$avatar = 'avatar';
		if(!$this->upload->do_upload($avatar)) {
			$error = $this->upload->display_errors();
			redirect('/users?trigger=' . $this->user_id . '&cem=' . $error,'location');
		}else {
			$data = array('upload_data' => $this->upload->data());
			$filename = $data['upload_data']['file_name'];
			// the current logged in users profile url
			//log the url to the database
			$updateAvatar = $this->members->avatar_update($this->user_id,$filename);
			// if the query was successfull, redirect back to the profile.
			if($updateAvatar) :
				redirect('/users?trigger=' . $this->user_id,'location');
			else :
			    redirect('/users?trigger=' . $this->user_id . '&e=2','location');
			endif;
		}
	}
	
	public function Import_google_avatar() {
		$import = $this->members->activateGoogleAvatar($this->user_id);
		if($import) {
			echo '1';
		}else {
			echo '0';	
		}
	}
	
	public function Add_user_Form() {
		$this->load->model('mlist');
		$data = array(
			'dealerships'=>$this->mlist->getClients(),
			'SecurityLevels'=>$this->members->getSecurityLevels(),
			'tags'=>$this->administration->getAllTags(),
		);
		$this->load->dom_view('forms/users/add', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Submit_add_user() {
		$this->load->helper('pass');
		$form = $this->input->post();
		$modules = $this->members->getDefaultModules($form['security_level']);
		
		$user_update = array(
			'USER_Name'=>$form['username'],
			'Team'=>$form['team']
		);
		
		$directory_update = array(
			'DIRECTORY_Type'=>3,
			'DIRECTORY_FirstName'=>$form['first_name'],
			'DIRECTORY_LastName'=>$form['last_name']
		);
		
		$address_update = array(
			'OWNER_Type' => 3,
			'ADDRESS_Street' => $form['street'],
			'ADDRESS_City'=>$form['city'],
			'ADDRESS_State'=>$form['state'],
			'ADDRESS_Zip'=>$form['zipcode'],
			'ADDRESS_Type'=>'Home',
			'ADDRESS_Active'=>1,
			'ADDRESS_Primary'=>1,
			'ADDRESS_Created'=>date('Y-m-d H:i:s')
		);
		
		$email_update = array(
			'OWNER_Type'=>3,
			'EMAIL_Address' => $form['username'],
			'EMAIL_Primary' => 1,
			'EMAIL_Type' => 'Work',
			'EMAIL_Active' => 1,
			'EMAIL_Created' => date('Y-m-d H:i:s')
		);
		
		$user_info_update = array(
			'ACCESS_ID' => $form['security_level'],
			'USER_Modules'=>$modules,
			'CLIENT_ID'=>$form['dealership'],
			'USER_Active'=>1,
			'USER_Generated'=>1,
		);
		
		$data = array(
			'Users'=>$user_update,
			'Directories'=>$directory_update,
			'DirectoryAddresses'=>$address_update,
			'EmailAddresses'=>$email_update,
			'Users_Info'=>$user_info_update
		);
		
		$add_user = $this->administration->addNewUser($data);
		
		if($add_user) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Edit() {
		$this->load->model('system_contacts','syscontacts');
		$user = $this->administration->getMyUser($this->user_id);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $this->members->get_user_avatar($user->ID);
		$data = array(
			'user'=>$user,
			'avatar'=>$avatar,
			'allMods'=>$this->administration->getAllModules(),
			'websites'=>true,
			'show_mods'=>((isset($_GET['modules'])) ? $_GET['modules'] : FALSE)
		);
		$this->load->dom_view('forms/users/edit_add_view', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function load_phone_table() {
		$uid = $_GET['uid'];
		$data = array(
			'uid'=>$uid
		);
		$this->load->dom_view('pages/users/phone_listing',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function load_email_table() {
		$uid = $_GET['uid'];
		$data = array(
			'uid'=>$uid
		);
		$this->load->dom_view('pages/users/email_listing',$this->theme_settings['ThemeViews'],$data);
	}
	
	public function Edit_details_form() {
		//gonna use the clients from the masterlist function because it auto filters based on the dealer dropdown
		$this->load->model('mlist');
		$dealerships = $this->mlist->getClients();
		$user = $this->administration->getMyUser($this->user_id);
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Email = mod_parser($user->Emails,false,true);
		$user->Phone = mod_parser($user->Phones,false,true);
		$data = array(
			'user'=>$user,
			'dealerships'=>$dealerships
		);	
		$this->load->dom_view('forms/users/edit_details', $this->theme_settings['ThemeViews'], $data);
	}
	
	public function Submit_user_details_form() {
		$form = $this->input->post();
		
		$did = $this->administration->getDirectoryID($this->user_id);
		
		if($did) {
			$directory_data = array(
				'DIRECTORY_FirstName' => $form['first_name'],
				'DIRECTORY_LastName'=>$form['last_name'],
				'DIRECTORY_Address'=>'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zipcode']
			);	
			
			$user_data = array(
				'USER_Name'=>$form['username']
			);
			
			$update_directory = $this->administration->updateDirectory($did,$directory_data);
			$update_users = $this->administration->udpateUserName($this->user_id,$user_data);
			
			if($update_directory AND $update_users) {
				echo '1';	
			}elseif($update_directory AND !$update_users) {
				echo '2';	
			}elseif(!$update_directory AND $update_users) {
				echo '3';	
			}else {
				echo '0';	
			}
		}
	}
	
	public function Submit_user_edit_modules() {
		$form = $this->input->post();
		$modules = '';
		$i = 1;
		$count = count($form['modules']);
		
		foreach($form['modules'] as $key => $value) {
			$modules .= $key . ':' . $value . (($i < $count) ? ',' : '');
			$i++;
		}
		
		$update_mods = $this->administration->updateUserModules($this->user_id,$modules);
		
		if($update_mods) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function Edit_phone_form() {
		$this->load->model('system_contacts','syscontacts');
		$pid = $_GET['pid'];
		$phone = $this->syscontacts->getSingleContactPhoneNumber($pid);
		$data = array(
			'phone'=>$phone
		);
		//print_object($phones);
		$this->load->dom_view('forms/users/edit_phone', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Add_phone_form() {
		$this->load->model('system_contacts','syscontacts');
		$did = $_GET['did'];
		
		$data = array(
			'did'=>$did
		);
		//print_object($phones);
		$this->load->dom_view('forms/users/add_phone', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Add_email_form() {
		$this->load->model('system_contacts','syscontacts');
		$did = $_GET['did'];
		
		$data = array(
			'did'=>$did
		);
		//print_object($phones);
		$this->load->dom_view('forms/users/add_email', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Edit_email_form() {
		$this->load->model('system_contacts','syscontacts');
		$eid = $_GET['eid'];
		$email = $this->syscontacts->getSingleContactEmailAddress($eid);
		$data = array(
			'email'=>$email
		);
		//print_object($phones);
		$this->load->dom_view('forms/users/edit_email', $this->theme_settings['ThemeViews'], $data);	
	}
	
	public function Update_primary_phone() {
		$this->load->model('system_contacts','syscontacts');
		$form = $this->input->post();
		//$pid,$did,$primary
		$reset_primary = $this->syscontacts->updatePrimaryPhone($form['phone_id'],$form['directory_id'],1);
		
		if($reset_primary) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function Update_primary_email() {
		$this->load->model('system_contacts','syscontacts');
		$form = $this->input->post();
		//$pid,$did,$primary
		$reset_primary = $this->syscontacts->updatePrimaryEmail($form['email_id'],$form['directory_id'],1);
		
		if($reset_primary) {
			echo '1';	
		}else {
			echo '0';	
		}
	}
	
	public function Update_phone_number() {
		$this->load->model('system_contacts','syscontacts');
		$pid = $this->input->post('phone_id');
		$number = $this->input->post('number');
		$type = $this->input->post('type');	
		
		$data = array(
			'PHONE_Number'=>$number,
			'PHONE_Type'=>$type
		);
				
		$update = $this->syscontacts->updateSingleContactPhoneNumber($pid,$data);
		if($update) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Add_phone_number() {
		$this->load->model('system_contacts','syscontacts');
		$did = $_GET['did'];
		$number = $this->input->post('number');
		$type = $this->input->post('type');	
		
		$data = array(
			'DIRECTORY_ID'=>$did,
			'OWNER_Type'=>3,
			'OWNER_ID'=>$did,
			'PHONE_Number'=>$number,
			'PHONE_Type'=>$type,
			'PHONE_Created'=>date('Y-m-d H:i:s'),
			
		);
	
		$add = $this->syscontacts->addSingleContactPhoneNumber($data);
		if($add) {
			echo '1';	
		}else {
			echo '0';
		}
	}
	
	public function Add_user_email() {
		$this->load->model('system_contacts','syscontacts');
		$did = $_GET['did'];
		$email = $this->input->post('email');
		$type = $this->input->post('type');	
		
		$data = array(
			'OWNER_Type'=>3,
			'OWNER_ID'=>$did,
			'EMAIL_Address'=>$email,
			'EMAIL_Type'=>$type,
			'EMAIL_Created'=>date('Y-m-d H:i:s'),
			
		);
	
		$add = $this->syscontacts->addSingleEmailAddress($data);
		if($add) {
			echo '1';	
		}else {
			echo '0';
		}
	}

	public function Update_user_email() {
		$this->load->model('system_contacts','syscontacts');
		$eid = $this->input->post('email_id');
		$email = $this->input->post('email');
		$type = $this->input->post('type');	
		
		$data = array(
			'EMAIL_Address'=>$email,
			'EMAIL_Type'=>$type
		);
		
				
		$update = $this->syscontacts->updateSingleEmailAddress($eid,$data);
		if($update) {
			echo '1';	
		}else {
			echo '0';
		}
		
	}
	
	public function Change_pass_form() {
		$user = $this->administration->getMyUser($this->user_id);
		$data = array(
			'user'=>$user
		);
		$this->load->dom_view('forms/users/change_password',$this->theme_settings['ThemeViews'],$data);
	}
	
	/*This will change the users password to whatever the user wants it to be*/
	public function Change_user_password() {
		$this->load->helper('pass');
		$user = $this->administration->getMyUser($this->user_id);
		//password
		$old_pass = $this->input->post('oldPassword');
		$newPass = $this->input->post('newPassword');
		$confirmNewPass = $this->input->post('confirmNewPassword');
		if($newPass == $confirmNewPass) {
			//check if old password is corrent
			$verify = $this->members->validateUser($user->Username,$old_pass);
			if($verify) {
				//validate if the password is in the correct format
				$valid_new_pass = checkPasswordCharacters($newPass);
				if($valid_new_pass) {
					$change_pass = $this->members->simple_pass_change($user->ID,$newPass);
					if($change_pass) {
						echo '1';
					}else {
						echo '6';	
					}
				}else {
					echo '7';	
				}
			}else {
				echo '8';	
			}
		}else {
			echo '9';	
		}
	}
	
	public function Reset_user_password() {
		$this->load->helper('pass');
		$user = $this->administration->getMyUser($this->user_id);
		// Locate primary.
		$primaryEmail = $user->Username;
		if(!empty($user->Emails)) {
			foreach ($user->Emails as $email) {
				if($email->EMAIL_Primary == 1) {
					$primaryEmail = $email->EMAIL_Address;
				}
			}
			//generate a new password
			$newPass = createRandomString();
			//this will also email the user their email address, this is sent to the users PRIMARY email, which could differ from the email they are logging in with.
			$reset = $this->members->simple_reset_pass($primaryEmail,$user->ID,$newPass);
			if($reset) {
				echo $newPass;	
			}else {
				echo '0';	
			}
		}else {
			echo '0';	
		}
	}
	
	public function View() {
		$this->load->helper('websites');
		$this->load->model('system_contacts','syscontacts');
		$user = $this->administration->getMyUser($this->user_id);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $this->members->get_user_avatar($this->user_id);
		
		$data = array(
			'user'=>$user,
			'avatar'=>$avatar,
			'allMods'=>$this->administration->getAllModules(),
			'websites'=>true,
		);
		$this->load->dom_view('forms/users/view', $this->theme_settings['ThemeViews'], $data);
	}
	
}