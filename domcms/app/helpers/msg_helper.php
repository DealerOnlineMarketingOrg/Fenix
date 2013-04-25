<?php

    function email_reset_msg($pass) {
        return '<p><img src="' . base_url() . 'imgs/loginLogo.png" alt="" style="width:175px;" /></p><p>Your password at ' . base_url() . ' has been reset to:<br /><br /> ' . $pass . '</p><p>This password will only be active for one login session. When you log in using this temporary password, you will be prompted to select a new personal password. When choosing a new personal password, please select one that includes at least one uppercase letter, one number, one special character and is at least 8 characters in length. Follow the link below to login in with your temporary password. After that, you will be prompted to change your password.</p><p>Log in at: <a href="' . base_url() . '">' . base_url() . '</a></p>';
    }
	
	function email_new_user($username, $pass) {
		return '<p><img src="' . base_url() . 'imgs/loginLogo.png" alt="" style="width:175px;" /></p><p>Welcome to the Dealer Online Marketing Content Management System! This email contains your password into the system. This is very sensitive information that would allow anyone to gain access as you into the system. Please keep this where no one would have access but you.</p><p>Username: ' . $username . '<br />Password: ' . $pass . '<br />Login Page: ' . base_url() . '</p>';	
	}
    
    function error_msg($msg = false) {
		if(!$msg) :
       		return '<div class="nNote nFailure hideit"><p><strong>Error:</strong> Something went wrong. Please try again or contact your admin.</p></div>';
		else :
			return  '<div class="nNote nFailure hideit"><p><strong>Error:</strong> ' . $msg . '</p></div>';
		endif;
    }
    
    function success_msg($msg) {
        return '<div class="nNote nSuccess hideit"><p><strong>SUCCESS:</strong> ' . $msg . '</p></div>';
    }
	
	function failed_form_submit() {
		return '<div class="nNote">The form your trying to submit failed. Please try again.</div>';	
	}
	
	function login_failed() {
		return '<div class="nNote nFailure"><p><strong>Error:</strong>The username and/or password are incorrect. Please try again.</p></div>';	
	}
	
	function exceeded_attempts() {
		return '<div class="nNote nFailure"><p><strong>Error:</strong>The username has exceeded 3 login attempts. The password has automatically been reset and emailed to the users email address on file. Check your email for a new password and the system will ask you to reset your password after a successfull login attempt.</p></div>';	
	}

?>