<!-- Login form area -->
<div class="loginWrapper">
	<div class="loginLogo"><img src="<?php echo  base_url(); ?>imgs/loginLogo.png" alt="" style="height:100%; " /></div>
    <div class="loginRow noborder error" style="display:none;width:320px;margin-top:-50px;float:left;" id="errorMessage"></div>
	<div class="fix"></div>
    <div class="loginPanel">
        <div class="head"><h5 class="iUser">Login</h5></div>
        <?
        $form = array(
            'name' => 'login_form',
            'class' => 'mainForm',
            'id' => 'valid',
            'autocomplete' => 'off'
		);
		
		$email = array(
            'name' => 'email',
            'id' => 'req1',
            'class' => 'validate[required, custom[email]]',
            'value' => (($email != '') ? $email : ''),
		);
		
		$password = array(
            'name' => 'password',
            'id' => 'req2',
            'class' => 'validate[required,onlyLetterNumber]',
            'value' => '',
            'autocomplete' => 'off'
		);
		
		$submit = array(
			'value' => 'Log Me In',
			'class' => 'redBtn submitForm'
		);
		echo form_open('authenticate', $form); ?>
            <fieldset>
                <div class="loginRow noborder">
                    <label for="req1">Username:</label>
                    <div class="loginInput"><?php echo  form_input($email); ?></div>
                    <div class="fix"></div>
                </div>
                
                <div class="loginRow">
                    <label for="req2">Password:</label>
                    <div class="loginInput"><?php echo  form_password($password); ?></div>
                    <div class="fix"></div>
                </div>
                
                <div class="loginRow" style="padding-bottom:0;">
                    <div class="rememberMe"><input type="checkbox" <?= (($checkBox) ? 'checked="checked"' : ''); ?> id="remember_me" name="remember_me" /><label for="check2">Remember me</label></div>
                    <?php echo  form_submit($submit); ?>
                    <div class="fix"></div>
                </div>
                <div class="loginRow noborder" style="padding-top:0;">
                	<div class="rememberMe"><input id="resetPass" type="button" value="Reset Password" class="greyishBtn" style="margin:0 auto;" /></div>
                    <div class="fix"></div>
                </div>
            </fieldset>
        <?php echo  form_close(); ?>
        <div id="result"></div>
    </div>
    <div id="thirdPartyConnect" style="text-align:center;margin-top:10px;">
    	<a href="<?= base_url(); ?>auth/google/connect"><img id="googleConnect" src="<?= base_url() . THEMEIMGS; ?>google.png" alt="Connect With Google" style="border:1px solid #d5d5d5;cursor:pointer;" /></a>
    </div>
    <div id="loadedContent"></div>
    
    <script type="text/javascript">
        $('#req1').focus();
		//$('#thirdPartyConnect').load('<?= base_url(); ?>google_connect');	
		$('#resetPass').click(function(e) {
			e.preventDefault();
			$('#loadedContent').empty();
			$.ajax({
				type:'GET',
				url:'/reset_password_form',
				success:function(code) {
					$('#loadedContent').html(code);	
				}
			});
		});
	
		$('#valid').submit(function(e) {
			var remember_me = (($('#remember_me').is(':checked')) ? 1 : 0);
			e.preventDefault();
			var emailAddy = $('#req1').val();
			var pass = $('#req2').val();
			
			// Always make sure the error message is hidden after login submits, and empty out the error message.
			$('#errorMessage').fadeOut('slow').empty();
			
			// If the username or email is left blank, we need to let the validation do its thing
			if(emailAddy != '' && pass != '') {
				
				// If the username and pass is set we show the loader
				$('#whiteout').fadeIn('slow',function() {
					$('#loader').fadeIn('slow',function() {
						
						// Check credentials with ajax
						$.ajax({
							type:'POST',
							url:'<?= base_url(); ?>signin', 
							// Pass the input values to the controller
							data:{email:emailAddy,password:pass,remember:remember_me},
							// The success function is what handles the return.
							success:function(data) {
								// If the return is not 1, this means the login failed.
								if(data != '1') {
									
									// Let php log the errors.
									$.ajax({
										type:'POST',
										url:'<?= base_url(); ?>log',
										data:{email:$('#req1').val()},
										success:function(data) {
											
											// If we have less than 3 failed attempts, just show a login error
											if(data <= 2) {
												$('#loader').fadeOut('slow',function() {
													$('#whiteout').fadeOut('slow',function() {
														jAlert('The username and password are incorrect. Please try again.','Password Error');
													});
												});
												
											// If we have more than 3 failed attempts, we need to lock the user out.
											}else if(data >= 3) {
												$('#loader').fadeOut('slow',function() {
													$('#whiteout').fadeOut('slow',function() {
														$.ajax({
															type:'POST',
															url:'<?= base_url(); ?>lock_user',
															data:{email:$('#req1').val(),password:$('#req2').val()},
															success:function(data) {
																if(data == 1) {
																	$('#loader').fadeOut('slow',function() {
																		$('#whiteout').fadeOut('slow',function() {
																			var p = '';
																			$.ajax({
																				type:'POST',
																				url:'<?= base_url(); ?>change_password_form',
																				data:{email:$('#req1').val()},
																				success:function(html) {
																					$('#loadedContent').html(html);	
																				}
																			});
																		});
																	});
																}else if(!data) {
																	jAlert('The email address provided cannot be found in our system. Please try again.', 'Login Error');
																	
																}else {
																	jAlert('This account has been locked due to too many failed login attempts. A new password has been emailed to the users email address on file. Please check the email address to retrieve the generated password. If you are experiencing issues reseting your password, please contact support at 1.864.248.0886.', 'Warning');
																}
															}
														});
													});
												});
											}
										}
									});
								}else {
									
									//check password
									//check password to see if it is a generated password. if it is, we need to change the password before we login.
									$.ajax({
										type:'POST',
										url:'<?= base_url(); ?>check_pass',
										data:{email:$('#req1').val()},
										success:function(data) {
											if(data != 1) {
												$('#loader').fadeOut('slow',function() {
													$('#whiteout').fadeOut('slow',function() {
														window.location.href="<?= base_url(); ?>";
													});
												});
											}else if(data == 1){
												$('#loader').fadeOut('slow',function() {
													$('#whiteout').fadeOut('slow',function() {
														var p = '';
														$.ajax({
															type:'POST',
															url:'<?= base_url(); ?>change_password_form',
															data:{email:$('#req1').val()},
															success:function(html) {
																$('#loadedContent').html(html);	
															}
														});
													});
												});
											}
										}
									});
								}
							}
						});
					});
				});
			}
		});
	</script>
</div>

<div id="whiteout" style="display:none;">&nbsp;</div>
<div id="loader" style="display:none;"><img src="<?= base_url(); ?>imgs/loaders/loader2.gif" alt="Loading" /></div>
