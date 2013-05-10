<div class="uDialog">
    <div class="dialog-message popper" id="addContactInfoPhone" title="Add Phone">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <div class="tab_container">
            		<div id="contactPhone" class="tab_content">
						<?= form_open('/admin/contactInfo/add_phone_number?',array('id'=>'addContactInfoPhoneForm','class'=>'validate mainForm formPop','style' => 'text-align:left'));	?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span>Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="contactPhoneType" class="chzn-select validate[required]" style="width:350px" name="type">
                                        	<option value=""></option>
                                            <option value="cell">Cell</option>
                                            <option value="home">Home</option>
                                            <option value="work">Work</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="fix"></div>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span>Phone</label>
                                    <div class="formRight">
                                        <?= form_input(array('class'=>'maskPhoneExt validate[required]','name'=>'phone','id'=>'phone','value'=>'','placeholder'=>'Enter Phone Number')); ?>
                                        <span class="formNote">(999) 999-9999 x99999</span>
                                    </div>
                                </div>
                                <div class="fix"></div>
                                <div class="submitForm">
                                    <input type="hidden" name="directory_id" value="<?= $did; ?>" />
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
				</div> 
        	</div> <? //end widget ?>
		</div>
	</div>
</div>
<style type="text/css">
.rowElem > label {padding-top:5px;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery;
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	
	$('#addContactInfoPhoneForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/contactInfo/add_phone_number?did='<?=$did; ?>,
			success:function($response) {
				if($response == '1') {
					jAlert('The phone number was added successfully.','Success!',function() {
						reload_phone_table('<?=$did;?>');
					});
				}else {
					
				}
			}
		});
		
		savePhone('<?= $page; ?>','<?= $contact->TypeID; ?>','<?= $contact->TypeCode; ?>',formData);
	});
	
	$(".chzn-select").chosen();
	
	
	$("#editContactInfoPhonePop").dialog({
		minWidth:600,
		width:600,
		minHeight:300,
		height:300,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close');}
			},
				{
					class:'greenBtn addPhoneBtn',
					text:"Add",
					click:function() { $("#editContactInfoPhoneForm").submit(); }
				},
		]
	});
</script>
