<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="editPhone" title="Edit Phone Number">
        <div class="uiForm" style="text-align:left;">
			<style type="text/css">
				#editPhone label, #editPhone label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editPhone textarea,#editPhone textarea {margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <!-- Form begins -->
                <?php
					$form = array(
						'name' => 'EditPhoneNumber',
						'id' => 'EditPhoneNumberForm',
						'class' => 'mainForm editphoneForm',
						'style'=>'text-align:left;'
					);
					
					echo form_open('admin/user/submit_edit_user_phone', $form);
				?>
                    <!-- Input text fields -->
                    <fieldset>
                    	<div class="rowElem noborder">
                        	<label style="padding-top:5px !important;">Type</label>
                            <div class="formRight noSearch">
                                <select name="type" class="chzn-select validate[required]" id="typeSelect" style="width:200px;">
                                    <option value=""></option>
                                    <option <?= (($phone->PHONE_Type == 'Work') ? 'selected="selected"' : ''); ?> value="Work">Work</option>
                                    <option <?= (($phone->PHONE_Type == 'Mobile') ? 'selected="selected"' : ''); ?> value="Mobile">Mobile</option>
                                    <option <?= (($phone->PHONE_Type == 'Home') ? 'selected="selected"' : ''); ?> value="Home">Home</option>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Phone Number</label>
                            <div class="formRight">
                                <input id="phoneNumber" name="number" type="text" class="validate[required,custom[phone]] maskPhoneExt" value="<?= $phone->PHONE_Number; ?>" />
                                <span class="formNote">(888) 888-8888 x888</span>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="submitForm">
                            <input type="hidden" id="phone_id" name="phone_id" value="<?= $phone->PHONE_ID; ?>" />
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
	$(".editphoneForm").validationEngine({promptPosition : "right", scroll: true});
	$('.chzn-select').chosen();
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");


	$('#EditPhoneNumberForm').submit(function(e) {
		e.preventDefault();
		var formData = jQuery(this).serialize();
		$.ajax({
			type:'POST',
			url:'/admin/users/update_phone_number',
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert('The phone number was updated successfully!',function() {
						load_phone_table();
					});
				}else {
					jAlert('The phone number failed to update. Please try again.');
				}
			}
		});
	});
	
	
	$("#editPhone").dialog({
		minWidth:300,
		width:500,
		height:275,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'redBtn',
				text:'Save',
				click:function() {$('#EditPhoneNumberForm').submit();}
			},
		] 

	});
	
</script>