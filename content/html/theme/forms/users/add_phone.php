<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="addPhone" title="Edit Phone Number">
        <div class="uiForm" style="text-align:left;">
			<style type="text/css">
				#addPhone label, #addPhone label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#addPhone textarea,#addPhone textarea {margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <!-- Form begins -->
                <?php
					$form = array(
						'name' => 'addPhoneNumber',
						'id' => 'addPhoneNumberForm',
						'class' => 'mainForm addPhoneForm',
						'style'=>'text-align:left;'
					);
					
					echo form_open('admin/user/submit_add_user_phone', $form);
				?>
                    <!-- Input text fields -->
                    <fieldset>
                    	<div class="rowElem noborder">
                        	<label style="padding-top:5px !important;">Type</label>
                            <div class="formRight noSearch">
                                <select name="type" class="chzn-select validate[required]" id="typeSelect" style="width:200px;">
                                    <option value=""></option>
                                    <option value="Work">Work</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Home">Home</option>
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
                        <div class="fix"></div>
                    </fieldset>
                <?= form_close(); ?>
        	</div>
   	 	</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;
	$(".addPhoneForm").validationEngine({promptPosition : "right", scroll: true});
	$('.chzn-select').chosen();
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");


	$('#addPhoneNumberForm').submit(function(e) {
		e.preventDefault();
		var formData = jQuery(this).serialize();
		$.ajax({
			type:'POST',
			url:'/admin/users/add_phone_number?did=<?=$did;?>',
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert('The phone number was added successfully!',function() {
						load_phone_table();
					});
				}else {
					jAlert('The phone number failed to update. Please try again.');
				}
			}
		});
	});
	
	
	$("#addPhone").dialog({
		minWidth:300,
		width:500,
		height:275,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'redBtn',
				text:'Save',
				click:function() {$('#addPhoneNumberForm').submit();}
			},
		] 

	});
	
</script>