<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="addEmail" title="Edit Email Address">
        <div class="uiForm" style="text-align:left;">
			<style type="text/css">
				#addEmail label, #addEmail label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#addEmail textarea,#addEmail textarea {margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <!-- Form begins -->
                <?php
					$form = array(
						'name' => 'addEmail',
						'id' => 'addEmailForm',
						'class' => 'mainForm addEmailForm',
						'style'=>'text-align:left;'
					);
					
					echo form_open('admin/user/submit_add_user_email', $form);
				?>
                    <!-- Input text fields -->
                    <fieldset>
                    	<div class="rowElem noborder">
                        	<label style="padding-top:5px !important;">Type</label>
                            <div class="formRight noSearch">
                                <select name="type" class="chzn-select validate[required]" id="typeSelect" style="width:200px;">
                                    <option value=""></option>
                                    <option value="Work">Work</option>
                                    <option value="Personal">Personal</option>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label>Email</label>
                            <div class="formRight">
                                <input id="email" name="email" type="text" class="validate[required,custom[email]]" value="" />
                                <span class="formNote">username@example.com</span>
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
	$(".addEmail").validationEngine({promptPosition : "right", scroll: true});
	$('.chzn-select').chosen();


	$('#addEmailForm').submit(function(e) {
		e.preventDefault();
		var formData = $('#addEmailForm').serialize();
		$.ajax({
			type:'POST',
			url:'/admin/users/add_user_email?did=<?=$did;?>',
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert('The emai address was added successfully!',function() {
						load_email_table();
					});
				}else {
					jAlert('The email address failed to add. Please try again.');
				}
			}
		});
	});
	
	
	$("#addEmail").dialog({
		minWidth:300,
		width:500,
		height:275,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greenBtn',
				text:'Add',
				click:function() {$('#addEmailForm').submit();}
			},
		] 

	});
	
</script>