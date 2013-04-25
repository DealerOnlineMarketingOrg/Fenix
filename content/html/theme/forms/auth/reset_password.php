<div class="uDialog">
    <div id="resetPassForm" class="dialog-message" title="Reset Password">
        <p><img src="<?= base_url(); ?>imgs/lock50.png" style="float:left;margin:5px;" alt="Reset Password" />To reset your password, please give us the email address associated with your account.</p>
        <div class="uiForm">
            <?= form_open(base_url().'reset_password', array('id' => 'resetPassword','class'=>'valid')); ?>
                <input id="emailAddress" type="text" class="validate[required,custom[email]]" name="email" placeholder="Email Address" />
            <? form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery("#resetPassword").validationEngine({promptPosition : "right", scroll: true});
	jQuery('#resetPassword').submit(function(e) {
		e.preventDefault();
	});

	jQuery("#resetPassForm").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Reset: function() {
				if(jQuery("#emailAddress").val() != '') {
					jQuery('#loadedContent').empty();
					resetPass();
				}else {
					jAlert('Email address is required','Error',function() {
						
					});
				}
			}
		}
	});

	function resetPass() {
		jQuery.ajax({
			type:'POST',
			url:'<?= base_url(); ?>reset_password',
			data:{email:jQuery('#emailAddress').val()},
			success:function(data) {
				if(data == 1) {
					jAlert('The password for this account has been reset. A new password has been emailed to the email address provided.','Success',function() {
						jQuery("#resetPassForm").dialog('close');	
					});
				}else {
					jAlert('We could not find the email address provided in our system. Make sure the email address is the same email address you use to login.','Error',function() {
						jQuery("#resetPassForm").dialog('close');	
					});	
				}
			}
		});
	}
</script>
<style type="text/css">
	.formError{z-index:2000;}
</style>