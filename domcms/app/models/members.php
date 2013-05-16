<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Members extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->helper('pass');
		$this->load->helper('string_parser');
        $this->load->helper('query');
    }
	
	public function logGoogleToken($email,$token) {
		$data = array(
			'oAuth_token' => $token
		);
		$this->db->where('USER_Name',$email);
		return ($this->db->update('Users',$data)) ? TRUE : FALSE;
	}
	
	public function avatar_update($user_id,$filename) {
		$data = array(
			'USER_Avatar' => $filename,
			'Google_Avatar'=>0
		);
		$this->db->where('USER_ID',$user_id);
		return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
	}
	
	public function plus_avatar($email,$url) {
		
		$this->db->select('USER_ID as ID');
		$this->db->from('Users');
		$this->db->where('USER_Name', $email);
		
		$email = $this->db->get();
		
		if($email) {
			$data = array(
				'USER_Avatar' => $url,
				'Google_Avatar' => 1
			);
		
			$row = $email->row();
		
			$this->db->where('USER_ID',$row->ID);
			return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
		}else {
			return FALSE;
		}
	}
	
	
	public function get_user_avatar($user_id = false) {
		$this->load->helper('url');
		if($user_id) {
			$query = $this->db->select('Google_Avatar,USER_Avatar as Avatar')->get_where('Users_Info',array('USER_ID'=>$user_id));
			if($query) {
				$user_info = $query->row();
				if($user_info->Google_Avatar == '1') {
					$google_query = $this->db->select('Avatar')->get_where('GoogleAvatars',array('USER_ID'=>$user_id));
					if($google_query) {
						$google_avatar = ((isset($google_query->row()->Avatar)) ? $google_query->row()->Avatar : base_url() . 'imgs/icons/middlenav/user2.png');
						return $google_avatar;
					}else {
						return base_url() . 'imgs/icons/middlenav/user2.png';	
					}
				}else {
					if($user_info->Avatar != '') {
						return base_url() . 'uploads/avatars/' . $user_info->Avatar;	
					}else {
						return base_url() . 'imgs/icons/middlenav/user2.png';
					}
				}
			}
		}else {
			return base_url() . 'imgs/icons/middlenav/user2.png';
		}
	}
    
	public function validate($email,$password = false,$oAuth_token = false,$skip_encrypt = false) {
		$email = $this->security->xss_clean($email);
		
		if($password AND !$skip_encrypt) {
			$password = encrypt_password($this->security->xss_clean($password));
		}
		
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->join('Users_Info','Users.USER_ID = Users_Info.USER_ID');
		$this->db->join('Directories','Directories.DIRECTORY_ID = Users_Info.DIRECTORY_ID');
		$this->db->join('xSystemAccess','xSystemAccess.ACCESS_ID = Users_Info.Access_ID');
		$this->db->join('Clients','Clients.CLIENT_ID = Users_Info.CLIENT_ID');
		$this->db->join('Groups','Groups.GROUP_ID = Clients.GROUP_ID');
		$this->db->join('Agencies','Agencies.AGENCY_ID = Groups.AGENCY_ID');
		$this->db->where('Users.USER_Name',$email);
		
		if($password) {
			$this->db->where('Users_Info.USER_Password',$password);
		}elseif(!$password AND $oAuth_token) {
			$this->db->where('Users.oAuth_token',$oAuth_token);	
		}

		$query = $this->db->get();
			
	    if($query->num_rows() == 1) {
		   $row = $query->row();
		   //This array becomes our session array, any data we want to travel from page to page, needs to be defined here.
		   //Start drop down default insert for session
		   $ClientID 	= $row->CLIENT_ID;
		   $GroupID 	= $row->GROUP_ID;
		   $AgencyID 	= $row->AGENCY_ID;
		   $AccessLevel = $row->ACCESS_Level;
		   
		   //process levels of users for drop down
		   //If the access level is greater than or equel to 600,000 (SuperAdmin)
		   if($AccessLevel >= 600000) : 
				$dropdown_defaults = array(
				   'LevelID' 		=> $AgencyID,
				   'PermType' 		=> 'SuperAdmin',
				   'PermLevel'		=> 600000,
				   'LevelType'      => 'a',
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> FALSE
				);
			//If the access level is less than 600,000 and greater than or equel to 500,000
			elseif($AccessLevel < 600000 && $AccessLevel >= 500000) : 
				$dropdown_defaults = array(
				   'LevelID' 		=> $AgencyID,
				   'PermType' 		=> 'AgencyAdmin',
				   'PermLevel'		=> 500000,
				   'LevelType'      => 'a',
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> FALSE
				);
			//If the access level is less than 500,000 and greater than or equel to 400,000
			elseif($AccessLevel < 500000 && $AccessLevel >= 400000) :
				$dropdown_defaults = array(
				   'LevelID' 		=> $GroupID,
				   'PermType' 		=> 'GroupAdmin',
				   'PermLevel'		=> 400000,
				   'LevelType'      => 'g',
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
			elseif($AccessLevel < 400000 AND $AccessLevel >= 300000) :
				$dropdown_defaults = array(
				   'LevelID' 		=> $ClientID,
				   'PermType' 		=> 'ClientAdmin',
				   'PermLevel'		=> 300000,
				   'LevelType'      => 'c',
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
			elseif($AccessLevel < 300000 AND $AccessLevel >= 200000) :
				$dropdown_defaults = array(
				   'LevelID' 		=> $ClientID,
				   'PermType' 		=> 'Manager',
				   'PermLevel'		=> 200000,
				   'LevelType'      => 'c',
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
			else:
				$dropdown_defaults = array(
				   'LevelID' 		=> $ClientID,
				   'PermType' 		=> 'User',
				   'PermLevel'		=> 100000,
				   'LevelType'      => 'c',
				   'SelectedID' 	=> FALSE,
				   'SelectedAgency' => FALSE,
				   'SelectedGroup' => FALSE,
				   'SelectedClient' => FALSE,
				   'SelectedTag'	=> 'noshow'
				);
		   endif;

		   $data = array(
			   'Username' 		 => (string)$row->USER_Name,
			   'FirstName' 		 => (string)$row->DIRECTORY_FirstName,
			   'LastName' 		 => (string)$row->DIRECTORY_LastName,
			   'Emails' 		 => ((!empty($row->DIRECTORY_Email)) ? (object)mod_parser($row->DIRECTORY_Email,false,true) : FALSE),
			   'Gravatar'		 => (string)$row->USER_GravatarEmail,
			   'Avatar'			 => (string)$row->USER_Avatar,
			   'UserID' 		 => (int)$row->USER_ID,
			   'DirectoryID' 	 => (int)$row->DIRECTORY_ID,
			   'ClientID' 	     => (int)$row->CLIENT_ID,
			   'GroupID' 	     => (int)$row->GROUP_ID,
			   'AgencyID' 	     => (int)$row->AGENCY_ID,
			   'ClientName' 	 => (string)$row->CLIENT_Name,
			   'ClientAddress' 	 => ((!empty($row->CLIENT_Address)) ? (object)group_parser($row->CLIENT_Address) : FALSE),
			   'ClientPhone' 	 => ((!empty($row->CLIENT_Phone)) ? (object)group_parser($row->CLIENT_Phone) : FALSE),
			   'ClientNotes' 	 => (string)$row->CLIENT_Notes,
			   'ClientCode' 	 => (string)$row->CLIENT_Code,
			   'ClientActive' 	 => (bool)$row->CLIENT_Active,
			   'ClientActiveTS'  => date(FULL_MILITARY_DATETIME, strtotime($row->CLIENT_ActiveTS)),
			   'AccessLevel' 	 => (int)$row->ACCESS_Level,
			   'AccessName' 	 => (string)$row->ACCESS_Name,
			   'UserModules' 	 => $this->UserModules(((!empty($row->USER_Modules)) ? mod_parser($row->USER_Modules) : $this->getSystemDefaultModules($row->ACCESS_Level))),
			   'isActive' 		 => (bool)$row->USER_Active,
			   'TimeActive' 	 => date(FULL_MILITARY_DATETIME, strtotime($row->USER_ActiveTS)),
			   'isGenerated' 	 => (int)$row->USER_Generated,
			   'CreatedOn' 		 => date(FULL_MILITARY_DATETIME, strtotime($row->USER_Created)),
			   'validated' 		 => (bool)TRUE,
			   'DropdownDefault' => (object)$dropdown_defaults,
			   'google_token' => (($oAuth_token) ? $oAuth_token : FALSE)
		   );
		   		   
		   $this->session->set_userdata('valid_user', $data);
		   //$this->session->sess_write();
		   return (object)$data;

	   }
   }  
   
   public function getSystemDefaultModules($access_level) {
	   $query = $this->db->select('ACCESS_Perm as Modules')->from('xSystemAccess')->where('ACCESS_Level',$access_level)->limit(1)->get();
	   return ($query) ? mod_parser($query->row()->Modules) : FALSE;
   }

	public function AuthenticateGoogleUser($email,$token) {
		$log = FALSE;
		
		//log token
		if(isset($_SESSION['token'])) {
			$data = array(
				'oAuth_token' => $token
			);
		
			$this->db->where('USER_Name',$email);
			$log = $this->db->update('Users',$data);
		}
		
		if($log) {
		
			$query = $this->db->select('u.USER_Name as Email,ui.USER_Password as Password')->
					 from('Users u')->
		             join('Users_Info ui','u.USER_ID = ui.USER_ID')->
		             where('u.USER_Name',$email)->
		             get();
		
			if($query) {
				$user = $query->row();		
				$valid_user = $this->validate($user->Email,$user->Password,false,true);
				if($valid_user) {
					return TRUE;
				}else {
					return FALSE;	
				}
			}else {
				return FALSE;	
			}
		}
	}

	public function Save_google_avatar($email,$avatar) {
		$uid = $this->db->select('USER_ID as ID')->from('Users')->where('USER_Name',$email)->get();
		$google_avatar = array(
			'Avatar'=>$avatar
		);
	 //find if user has a avatar already set
	 if($uid) {
		$uid = $uid->row()->ID;
		
		//check to see if the user has a avatar set...if not we can make the google avatar the users active avatar automatically after signin.
		$isCustomAvatar = $this->db->select('USER_Avatar')->from('Users_Info')->where('USER_ID',$uid)->get();
		
		$isGoogleAvatar = ($this->db->select('ID')->from('GoogleAvatars')->where('USER_ID',$uid)->get()->row()) ? TRUE : FALSE;
		if($isGoogleAvatar) { //yes there is an instance for the user. 
		
			//check to see if the avatar is the same as the one we have.
			$sys_avatar = $this->db->select('Avatar')->from('GoogleAvatars')->where('USER_ID',$uid)->get()->row()->Avatar;
			
			if($sys_avatar != $avatar) {
				//if the avatar is different we update it
				$this->db->where('USER_ID',$uid);
				if($this->db->update('GoogleAvatars',$google_avatar)) {
					//no avatar set for the user, so lets make our google avatar the users active avatar, else...we need to wait for the user to activate the avatar.
					if(!$isCustomAvatar) {
						return $this->activateGoogleAvatar($uid);
					}else {
						return TRUE;	
					}
				}else {
					return FALSE;	
				}
			}
			//if the avatar is the same as we have, then we dont need to do anything so just tell the system it was a success
			return TRUE;
		}else {
			$google_avatar['USER_ID'] = $uid;
			//no google avatar found so insert one	
			if($this->db->insert('GoogleAvatars',$google_avatar)) {
				//no avatar set for the user, so lets make our google avatar the users active avatar, else...we need to wait for the user to activate the avatar.
				if(!$isCustomAvatar) {
					return $this->activateGoogleAvatar($uid);
				}else {
					return TRUE;	
				}
			}else {
				return FALSE;	
			}
		}
	 }
   }
   
	public function activateGoogleAvatar($uid) {
		$data = array(
			'Google_Avatar'=>1
		);	
		$this->db->where('USER_ID',$uid);
		return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
	}
   
   public function checkPasswordGeneration($email) {
		$sql = 'SELECT u.USER_ID as ID, ui.USER_Generated as IsGenerated FROM Users u INNER JOIN Users_Info ui ON u.USER_ID = ui.USER_ID WHERE u.USER_Name = "' . $email . '"';
		$query = $this->db->query($sql)->row();
		if($query) {
			return $query->IsGenerated;	
		}else {
			return 'ERROR';	
		}
   }
   
   public function checkExistingUsername($username) {
		$query = $this->db->select('USER_Name as Username')->from('Users')->where('USER_Name',$username)->get();
		return ($query) ? TRUE : FALSE;   
   }
   
   public function UserModules($mods) {
		$modules = array();
		foreach($mods as $key => $value) {
			if($value != 0) {
				$sql = "SELECT * FROM xModules WHERE MODULE_ID = '" . $key . "';";	
				$query = $this->db->query($sql);
				if($query) {
					array_push($modules,$query->row());	
				}
			}
		}
		return $modules;
   }
   
   public function valid_email($email) {
		$sql = 'SELECT USER_Name from Users WHERE USER_Name = "' . $email . '";';
		$query = $this->db->query($sql);
		if($query)
			return TRUE;
		else
			return FALSE;   
   }
   
   public function manual_reset_pass($email,$password) {
	   	$this->load->helper('msg_helper');
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where('USER_Name',$email);
		$user = $this->db->get();
		
		if($user) {
			$user = $user->row();
			$user_id = $user->USER_ID;
			$this->db->where('USER_ID',$user_id);
			if($this->db->update('Users_Info',array('USER_Password'=>encrypt_password($password),'USER_Generated'=>1))) {
				$subject = 'Password Reset';
				$msg = email_reset_msg($password);
				$emailed = $this->email_results($email,$subject,$msg);
				if($emailed) {
					return TRUE;
				}else {
					return FALSE;
				}
			}else {
				return FALSE;
			}
		}else {
			return FALSE;
		}
   }
   
   //function only works with one column in the db. doesnt have to search by email because the controller already knew the users id.
   //this function will email the given email address the new password to this given account.
   /*
   	@email = the users account that is being changed.
	@uid = the users USER_ID 
	@pass = the random string created by the system
   */
   public function simple_reset_pass($email,$uid,$pass) {
	    //prepare the data for the update
		$data = array(
			'USER_Password'=>encrypt_password($pass),
			'USER_Generated'=>1
		);   
		
		//THE Active Record where clause 'WHERE USER_ID = "$uid"'
		$this->db->where('USER_ID',$uid);
		
		//This returns true or false if the update failed or passed.
		$reset = $this->db->update('Users_Info',$data);
		
		//if the reset is true email the new password to the user.
		if($reset) {
			//this is the email subject
			$subject = 'Password Reset';
			//this is the email message. this comes from the  msg_helper.php in /domcms/helpers folder
			$msg = email_reset_msg($pass);	
			
			//run the email function in this file that emails the password with the given message
			$emailed = $this->email_results($email,$subject,$msg);
			
			//if the email was sent succesfully, return true back to the system
			if($emailed) {
				return TRUE;	
			}else { //if the email failed to send, return false to the system
				return FALSE;	
			}
		}else {
			//if the update failed, return false to the system.
			return FALSE;	
		}
   }
   
   public function reset_password($email, $password = false) {
		$this->load->helper('msg_helper');
		$email = $this->security->xss_clean($this->input->post('email'));
		if($password) {
			$new_pass = $password;
		}else {
			$new_pass = createRandomString(10,'ALPHANUMSYM');
		}
		
        $sql = 'SELECT * FROM Users WHERE USER_Name = "' . $email . '";';
        $query = $this->db->query($sql);
        
        if($query->num_rows() == 1) {
            $user_row = $query->row();
            $user_id = $user_row->USER_ID;
			
			$this->db->where('USER_ID',$user_id);
			if($this->db->update('Users_Info',array('USER_Password'=>encrypt_password($new_pass),'USER_Generated'=>'1'))) {
                 //return TRUE;
                $subject = 'Password Reset';
				$msg = email_reset_msg($new_pass);
                $emailed = $this->email_results($email, $subject, $msg);
                if($emailed) {
                    return TRUE;
                }else {
                    return FALSE;
                }
            }else {
                return FALSE;
            }
        }
        return FALSE;
    }
	
	public function validateUser($email, $password) {
		$this->load->helper('pass');
		$password = encrypt_password($password);
		
		//validate against the database
		$sql = 'SELECT u.USER_ID as ID FROM Users u RIGHT JOIN Users_Info ui ON u.USER_ID = ui.USER_ID WHERE u.USER_Name = "' . $email . '" AND ui.USER_Password = "' . $password . '"';
		
		$query = $this->db->query($sql)->row();
		
		//if the query found a match, return true, return false.
		if($query) : 
			return $query->ID;
		else : 
			return FALSE;
		endif;
				
	}
	
	public function simple_pass_change($uid,$pass) {
		$data = array(
			'USER_Password'=>encrypt_password($pass)
		);
		$new_pass = encrypt_password($pass);
		$this->db->where('USER_ID',$uid);
		return ($this->db->update('Users_Info',$data)) ? TRUE : FALSE;
	}
	
	public function change_password($email, $oldPass, $newPass) {
		$this->load->helper('pass');
		$savedPass = $newPass;
		$newPass = encrypt_password($newPass);
		//validate users old credentials
		$isValidUser = $this->validateUser($email,$oldPass);
		
		if(!$isValidUser) :
			return FALSE;
		else :
			$data = array(
				'USER_Password' => $newPass,
				'USER_Generated' => 0
			);
			$this->db->where('USER_ID', $isValidUser);
			
			// If the update succeeds, create the session and return the object to the system.
			if(!$this->db->update('Users_Info',$data)) {
				return FALSE;
			}else {
				$user = $this->validate($email,$savedPass);
				return $user;
			}
		endif;
	}
	
    public function email_results($email, $subject, $msg) {
        $this->load->library('email');
        $this->email->from('no-reply@dealeronlinemarketing.com','Dealer Online Marketing');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($msg);
        if($this->email->send()) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function addUsers($data) {
        $insert = $this->db->insert('Users',$data);
        if($insert) {
            $sql = 'SELECT USER_ID as ID FROM Users WHERE USER_Name = "' . $data['USER_Name'] . '" LIMIT 1';
            $results = query_results($this,$sql);
            return $results;
        }else {
            return false;
        }
    }
    
    public function addDirectory($data) {
        $insert = $this->db->insert('Directories',$data);
        if($insert) {
            //get id of inserted
            $sql = 'SELECT DIRECTORY_ID as ID FROM Directories WHERE DIRECTORY_FirstName = "' . $data['DIRECTORY_FirstName'] . '" AND DIRECTORY_LastName = "' . $data['DIRECTORY_LastName'] . '" AND DIRECTORY_Email ="' . $data['DIRECTORY_Email'] . '" LIMIT 1;';          
            $results = query_results($this, $sql);
            return $results;
        }else {
            return false;
        }    
    }
    
    public function addUserInfo($data) {
        $insert = $this->db->insert('Users_Info',$data);
        if($insert) {
            return true;
        }else {
            return false;
        }
    }

	public function disable_user($user_id) {
		$this->db->where('USER_ID',$user_id);
		return $this->db->update('Users_Info',array('USER_Active'=>0)) ? TRUE : FALSE;
	}
	
	public function enable_user($user_id) {
		$this->db->where('USER_ID',$user_id);
		return $this->db->update('Users_Info',array('USER_Active'=>1)) ? TRUE : FALSE;
	}
	
	public function getSecurityLevels() {
		$query = $this->db->select('ACCESS_ID as ID,ACCESS_Name as Name,ACCESS_Level as PermLevel,ACCESS_Perm as Modules')->from('xSystemAccess')->order_by('ACCESS_Level','desc')->get();
		return ($query) ? $query->result() : FALSE;
	}
	
	public function getDefaultModules($sid) {
		$query = $this->db->select('ACCESS_Perm as Modules')->from('xSystemAccess')->where('ACCESS_ID',$sid)->get();
		if($query) {
			$mods = $query->row();
			$modules = $mods->Modules;
			return $modules;	
		}else {
			return FALSE;	
		}
	}
    
}