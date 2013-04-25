<div class="uDialog" id="ChangePasswordForm">
    <div id="reset" class="dialog-message" title="Change Password">
        <p><img src="<?= base_url(); ?>imgs/lock50.png" style="float:left;margin:5px;"  alt="Reset Password" />Fill the form out below to change your password back to something easier to remember.</p>
        <div class="uiForm">
            <?= form_open(base_url().'change', array('id' => 'changePassword','class'=>'valid')); 
                echo form_password(array('id' => 'oldPass','name'=>'oldPass','placeHolder'=>'Your Temporary Password','class'=>'validate[required]','style'=>'margin-top:5px;')); 
                echo form_password(array('id' => 'newPass','name'=>'newPass','placeHolder'=>'Your New Password','class'=>'validate[required]','style'=>'margin-top:5px;'));
                echo form_password(array('id' => 'newPassMatch','name'=>'matchPass','placeHolder'=>'Confirm Your New Password','class'=>'validate[required,equels[newPass]]','style'=>'margin-top:5px;'));
				echo form_hidden('email',$email);
                echo form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	jQuery('#changePassword').validationEngine({promptPosition : "right", scroll: true});
	
	jQuery('#changePassword').submit(function(e) {
		e.preventDefault();
	});

	jQuery("#reset").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Change: function() {
				ChangePassword();
			}
		}
	});

	function ChangePassword() {
		$.ajax({
			type:'POST',
			url:'<?= base_url(); ?>change_pass',
			data:{email:'<?= $email; ?>',oldPass:$('#oldPass').val(),newPass:$('#newPass').val()},
			success:function(data) {
				if(data == '1') {
					jAlert('Your password has been successfully changed. The system will automatically login you in in 5 seconds','Success');
					$('#changePassword').delay(5000,function() {
						LogMeIn();
					});
				}else {
					jAlert(data, 'Password Error');
				}
			}
		});
	}
	
	function LogMeIn() {
		window.location.href="<?= base_url(); ?>";		
	}
</script>
