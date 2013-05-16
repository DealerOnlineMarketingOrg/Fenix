<div class="uDialog">
    <div class="dialog-message popper" id="addContactEmail" title="Add Email">
        <div class="uiForm">
        <style type="text/css">
			.rowElem > label {padding-top:5px;}
			.ui-datepicker-append{float:left;}
			div.formError{z-index:2000 !important;}
        </style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <div class="tab_container">
            		<div id="contactEmail" class="tab_content">
						<?= form_open('/admin/contactInfo/addEmail',array('id'=>'addContactEmailForm','class'=>'mainForm formPop addemail','style' => 'text-align:left')); ?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span>Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="email_type" class="chzn-select validate[required]" style="width:350px" name="type">
                                        	<option value=""></option>
                                            <option value="Home">Home</option>
                                            <option value="Work">Work</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="fix"></div>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span>Email</label>
                                    <div class="formRight">
                                        <?= form_input(array('class'=>'validate[required,custom[email]]','name'=>'email','id'=>'email')); ?>
										<span class="formNote">example@example.com</span>
                                    </div>
                                </div>
                                <div class="fix"></div>          
                            </fieldset>
                            <input type="hidden" name="owner_type" value="<?= $type; ?>" id="owner_type" />
                            <input type="hidden" name="directory_id" value="<?= $did; ?>" id="directory_id" />
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
				</div> 
        	</div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery;
	
	jQuery("#addContactEmailForm").validationEngine({promptPosition : "right", scroll: true});
	$('#addContactEmailForm').submit(function(e) {
		//alert(jQuery(this).validationEngine('validate'));
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/contactInfo/add_user_email?did=<?= $did; ?>',
			success:function(code) {
				
				alert(code);
				/*
				var msg;
				if(code == '1') {
					msg = 'Your add was made successfully';
					jAlert(msg,'Success',function() {
						//contactListTable();
					});
				}else {
					msg = 'There was a problem adding the contact. Please try again.';
					jAlert(msg,'Error');
				}
				*/
			}
		});
	});
	
	$(".chzn-select").chosen();
	$("#addContactEmail").dialog({
		minWidth:300,
		width:500,
		height:275,
		autoOpen: true,
		modal: true,
		buttons: [
				{
					class:'greenBtn addEmailBtn',
					text:"Add",
					click:function() { $('#addContactEmailForm').submit(); }
				},
		]
	});
</script>
