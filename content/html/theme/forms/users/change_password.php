<div class="uDialog">
    <div class="dialog-message" id="changePassword" title="Change Password" style="padding-top:0;">
        <div class="uiForm">
			<style type="text/css">
				div#changePassword div.widget {margin-top:0;padding-top:15px;margin-bottom:10px;}
				div#changePassword label{margin-top:0px;float:left;padding-top:0px;}
				div#changePassword div.formError{z-index:2000 !important;}
				div.tab_content div.title {border:1px solid #d5d5d5;padding:5px;margin-bottom:5px;background:url('<?= base_url(); ?>imgs/leftNavBg.png') repeat-x scroll 0 0 transparent;}
				div.tab_content div.title h5{padding-left:30px;margin-top:3px;}
				div#changePassword a.actions_link{float:right;margin-top:-19px;margin-right:3px;}
				div.password_buttons{text-align:right;margin-top:10px;}
				div.password_buttons a {color:#fff;}
				div#changePassword div.submitForm{text-align:center !important;width:auto;float:none;margin-right:0;margin-top:15px;}
			</style>
            <div class="widget">
                <?= form_open('admin/users/edit_information?uid=' . $user->ID,array('id'=>'editUserInformation','class'=>'mainForm valid','style'=>'text-align:left;')); ?>
                <fieldset>
                	<div class="rowElem noborder">
                    	<label><span class="req">*</span> Current</label>
                        <div class="formRight">
                        	<?= form_password(array('name'=>'oldPassword','id'=>'oldPassword','class'=>'validate[required]')); ?>
                            <p class="formNote">Your Current Password</p>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div class="rowElem noborder">
                    	<label><span class="req">*</span> New</label>
                        <div class="formRight">
                        	<?= form_password(array('name'=>'newPassword','id'=>'newPassword','class'=>'validate[required]')); ?>
                            <p class="formNote">Your New Password</p>
                        </div>
                    </div>
                    <div class="rowElem noborder">
                    	<label><span class="req">*</span> Confirm</label>
                        <div class="formRight">
                        	<?= form_password(array('name'=>'confirmNewPassword','id'=>'confirmNewPassword','class'=>'validate[required,equals[newPassword]]')); ?>
                            <p class="formNote">Confirm Your New Password</p>
                        </div>
                    </div>
                    <div class="fix"></div>
                    <div class="submitForm">
                        <input type="submit" value="Save" class="redBtn" />
                    </div> 
                </fieldset>
                <?= form_close(); ?>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;
	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});
	
	$('#editUserInformation').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/users/change_user_password?uid=<?= $user->ID; ?>',
			success:function(resp) {
				if(resp == '1') {
					jAlert('The Password was successfully changed!','Change Password confirmation',function() {
						$('#changePassword').dialog('close');
					});
				}else if(resp == '9') {
					jAlert('The passwords do not match. Please try again.','Change Password Error');
				}else if(resp == '8') {
					jAlert('The old password doesnt match the password in our system. Please try again.','Change Password Error');
				}else if(resp == '7') {
					jAlert('The password does not meet our requirements. Your new password must contain at least 1 upper case letter, at least 2 lower case letters, 1 number, 1 special character and is at least 8 characters long. Please try again.','Change Password Error');
				}else if(resp == '6') {
					jAlert('There was a problem changing the password. Please try again.','Change Password Error');
				}
			}
		});
	});
	
	$("#changePassword").dialog({
		minWidth:300,
		width:500,
		height:305,
		autoOpen: true,
		modal: true
	});
</script>
