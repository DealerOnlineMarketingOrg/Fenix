<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends DOM_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->helper(array('form','url','pass','file','template'));
		$this->load->model(array('members','administration'));
    }

    public function index() {
		$this->View();
    }

	public function View($msg = false) {
		$this->load->model('domwebsites');
		if($this->input->post('user_id')) {
			$user_id = $this->input->post('user_id');
		}else {
			$user_id = $this->user['UserID'];
		}
		
		$user = $this->administration->getMyUser($user_id);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $this->members->get_user_avatar($user->ID);
		
		$data = array(
			'user'=>$user,
			'avatar'=>$avatar,
			'allMods'=>$this->administration->getAllModules(),
			'websites'=>$this->domwebsites->getUserWebsites($user->ID),
			'edit'=>(($user->ID == $this->user['UserID'] || $this->user['AccessLevel'] >= 600000) ? TRUE : FALSE),
			//'contact'=>$user,
			//'contactInfo'=>$this->syscontacts->getUserContactInfo($uid),
		);

		$this->LoadTemplate('pages/users/profile',$data);
	}
	
	public function Upload_avatar() {
		$profile_url = base_url() . 'profile/' . strtolower($this->user['FirstName'] . $this->user['LastName']);
		//print_object($this->input->post());
		$rootpath = $_SERVER['DOCUMENT_ROOT'];
		
		$config['upload_path'] = $rootpath . '/uploads/avatars/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = '1000';
		$config['max_width'] = '640';
		$config['max_height'] = '480';
		$config['file_name'] = $this->user['UserID'] . '_' . strtolower($this->user['FirstName']) . '_' . strtolower($this->user['LastName']) . "_" . createRandomString(30,"ALPHANUM") . '.jpg';
		
		$this->load->library('upload',$config);
		$avatar = 'avatar';
		if(!$this->upload->do_upload($avatar)) {
			$error = $this->upload->display_errors();
			redirect($profile_url . '/upload_avatar_error?error=' . $error);
		}else {
			$data = array('upload_data' => $this->upload->data());
			$filename = $data['upload_data']['file_name'];
			// the current logged in users profile url
			//log the url to the database
			$updateAvatar = $this->members->avatar_update($this->user['UserID'],$filename);
			// if the query was successfull, redirect back to the profile.
			if($updateAvatar) :
				redirect($profile_url,'refresh');
			else :
			    redirect($profile_url . '/upload_avatar_error','refresh');
			endif;
			
		}
	}

	public function Reset_password() {
		$this->load->model('members');
		$this->load->helper('pass');
		$new_pass = createRandomString(10,'ALPHANUMSYM');
		$email = $this->input->post('userEmail');
		
		//echo $new_pass;
		if($new_pass) {
			$reset_password = $this->members->manual_reset_pass($email,$new_pass);
			if($reset_password) {
				echo $new_pass;
			} else {
				echo '0';
			}
		}else {
			echo '0';
		}
	}
	
	private function initialize(&$form, &$user, &$profile_url) {
		$form = $this->input->post();
		
		if($this->input->post('user_id')) {
			$user_id = $this->input->post('user_id');
		}else {
			$user_id = $this->user['UserID'];
		}
		
		$user                   = $this->administration->getUsers($user_id);
		$user->UserID           = $user->ID;
		$user->Edit       		= ($this->user['UserID'] == $user->UserID) ? TRUE : FALSE;
		
		$profile_url = base_url() . 'profile/' . strtolower($this->user['FirstName'] . $this->user['LastName']);
	}
	
	public function update_UserInfo() {
		$this->initialize($form, $user, $profile_url);
		
		$update = array(
			'Users' => array(
				'USER_Name' 			=> $form['username'],
			),
			'Directories' => array(
				'DIRECTORY_ID'			=> $user->DirectoryID,
				'DIRECTORY_FirstName'   => $form['firstname'],
				'DIRECTORY_LastName'    => $form['lastname'],
				'DIRECTORY_Address'		=>'street:' . $form['street'] . 'city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zipcode']
			),
			'Users_Info' => array(
				'USER_ID'               => $user->UserID,
				'DIRECTORY_ID'          => $user->DirectoryID,
				'ACCESS_ID'             => $form['permissionlevel'],
			)
		);
			
		$update = $this->administration->updateUser($update);
		
		throwError(newError('User Info Edit',
				($update) ? 1 : -1,
				($update) ? 'Your User Info has been successfully updated!'
						  : 'Something went wrong. Please try again or contact your admin.',
				0, ''));
		redirect($profile_url,'refresh');
	}
	
	public function update_UserContactInfo() {
		$this->initialize($form, $user, $profile_url);
		
		$emails = array();
		if ($form['home_email']) $emails['home'] = 'home:'.$form['home_email'];
		if ($form['work_email']) $emails['work'] = 'work:'.$form['work_email'];
		$phones = array();
		if ($form['work_phone']) $phones['work'] = 'work:'.$form['work_phone'];
		if ($form['cell_phone']) $phones['cell'] = 'cell:'.$form['cell_phone'];
		if ($form['fax_phone']) $phones['fax'] = 'fax:'.$form['fax_phone'];
			
		$update = array(
			'Users' => array(
				'USER_Name' 			=> $user->Username,
			),
			'Directories' => array(
				'DIRECTORY_ID'			=> $user->DirectoryID,
										   
				'DIRECTORY_Email'       => implode(',', $emails),
				'DIRECTORY_Phone'       => implode(',', $phones),
			),
			'Users_Info' => array(
				'USER_ID'               => $user->UserID,
				'DIRECTORY_ID'          => $user->DirectoryID,
			)
		);
		
		$update = $this->administration->updateUser($update);
		
		throwError(newError('User Contact Info Edit',
				($update) ? 1 : -1,
				($update) ? 'Your User Contact Info has been successfully updated!'
						  : 'Something went wrong. Please try again or contact your admin.',
				0, ''));
		redirect($profile_url,'refresh');
	}
	
	public function Edit() {
		if(isset($_GET['UID'])) {
			$uid = $_GET['UID'];
		}
		
		$user = $this->administration->getMyUser($uid);
		$user->ContactID = $user->DirectoryID;
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Email = mod_parser($user->Emails,false,true);
		$user->Phone = mod_parser($user->Phones,false,true);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $this->members->get_user_avatar($user->ID);
		$data = array(
			'user'=>$user,
			'avatar'=>$avatar,
			'allMods'=>$this->administration->getAllModules(),
			'websites'=>WebsiteListingTable($uid, 'UID'),
			'contact'=>$user,
			'contactInfo'=>ContactInfoListingTable($user, 'UID', true),
		);
		$this->load->dom_view('forms/userProfile/edit_add_view', $this->theme_settings['ThemeViews'],$data);
	}
}
