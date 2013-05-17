<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="EditEmail" title="Edit Email Address">
        <div class="uiForm" style="text-align:left;">
			<style type="text/css">
				#EditEmail label, #EditEmail label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#EditEmail textarea,#EditEmail textarea {margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <!-- Form begins -->
                <?php
					$form = array(
						'name' => 'EditEmail',
						'id' => 'EditEmailForm',
						'class' => 'mainForm EditEmailForm',
						'style'=>'text-align:left;'
					);
					
					echo form_open('admin/user/submit_edit_user_email', $form);
				?>
                    <!-- Input text fields -->
                    <fieldset>
                    	<div class="rowElem noborder">
                        	<label style="padding-top:5px !important;">Type</label>
                            <div class="formRight noSearch">
                                <select name="type" class="chzn-select validate[required]" id="typeSelect" style="width:200px;">
                                    <option value=""></option>
                                    <option <?= (($email->EMAIL_Type == 'Work') ? 'selected="selected"' : ''); ?> value="Work">Work</option>
                                    <option <?= (($email->EMAIL_Type == 'Personal') ? 'selected="selected"' : ''); ?> value="Personal">Personal</option>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Email</label>
                            <div class="formRight">
                                <input id="email" name="email" type="text" class="validate[required,custom[email]]" value="<?= $email->EMAIL_Address; ?>" />
                                <span class="formNote">username@example.com</span>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="submitForm">
                            <input type="hidden" id="email_id" name="email_id" value="<?= $email->EMAIL_ID; ?>" />
                        </div>
                        <div class="fix"></div>
                    </fieldset>
                <?= form_close(); ?>
        	</div>
   	 	</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;
	$(".EditEmail").validationEngine({promptPosition : "right", scroll: true});
	$('.chzn-select').chosen();


	$('#EditEmailForm').submit(function(e) {
		e.preventDefault();
		var formData = $('#EditEmailForm').serialize();
		$.ajax({
			type:'POST',
			url:'/admin/users/update_user_email',
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert('The email address was updated successfully!',function() {
						document.location.reload(true);
					});
				}else {
					jAlert('The email address failed to update. Please try again.');
				}
			}
		});
	});
	
	
	$("#EditEmail").dialog({
		minWidth:300,
		width:500,
		height:275,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'redBtn',
				text:'Save',
				click:function() {$('#EditEmailForm').submit();}
			},
		] 

	});
	
</script>