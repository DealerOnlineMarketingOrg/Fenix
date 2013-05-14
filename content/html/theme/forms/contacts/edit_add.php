<div class="uDialog">
    <div class="dialog-message popper" id="addContactInfo" title="Add Contact">
        <div class="uiForm">
			<style type="text/css">
				#addContactInfo label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editClient .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:0px;padding-top:0;margin-bottom:10px;">
                <ul class="tabs">
	               <li class="activeTab"><a href="javascript:void(0);" rel="contactDetails">Contact Details</a></li>
                </ul>
                <div class="tab_container">
            		<div id="contactDetails" class="tab_content">
						<?= form_open('/admin/contacts/add_details',array('id'=>'addContactDetails','class'=>'validate mainForm formPop','style'=>'text-align:left')); ?>
                            <fieldset>
								<div class="rowElem noborder">
                                    <label><span class="req">*</span>Name</label>
                                    <div class="formRight">
										<div style="position:relative;float:left;width:48%;">
											<?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=> '')); ?>
                                            <span class="formNote" style="margin-top:-10px;">First Name</span>
                                        </div>
                                    	<div style="position:relative;float:left;margin-left:5px;width:48%;">
											<?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>'')); ?>
                                            <span class="formNote" style="margin-top:-10px;">Last Name</span>
                                        </div>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                                <?php if(!isset($owner_type)) { ?>
                                    <div class="rowElem noborder noSearch">
                                        <label style="padding-top:5px !important;"><span class="req">*</span>Type</label>
                                        <div class="formRight searchDrop">
                                            <select id="contactType" class="chzn-select validate[required]" style="width:26%;" name="owner_type">
                                                <option value=""></option>
                                                <option value="1">Client Contact</option>
                                                <option value="2">Vendor Contact</option>
                                                <option value="4">General Contact</option>
                                            </select>
                                            
                                        </div>
                                        <div class="fix"></div>
                                    </div>
                                <?php }else { ?>
                                	<input type="hidden" name="owner_type" value="<?= $owner_type; ?>" />
                                <?php } ?>
                                <?php if(!isset($client_id)) { ?>
                                    <div class="rowElem noborder" id="client_dropdown" style="display:none;">
                                        <label style="padding-top:5px !important;"><span class="req">*</span>Client</label>
                                        <div class="formRight noSearch">
                                            <select class="chzn-select" name="client_id" style="width:175px !important" id="ClientID">
                                                <option value="">Choose a Client</option>
                                                <?php foreach($clients as $client) : ?>
                                                    <option value="<?= $client->ClientID; ?>"><?= $client->Name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="fix"></div>
                                    </div>
                                <?php }else { ?>
                                	<input type="hidden" name="client_id" value="<?= $client_id; ?>" />
                                <?php } ?>
                                <?php if(!isset($vendor_id)) { ?>
                                <div class="rowElem noborder" id="vendor_dropdown" style="display:none;">
                                	<label style="padding-top:5px !important;"><span class="req">*</span>Vendor</label>
                                    <div class="formRight noSearch">
                                    	<select class="chzn-select" style="width:175px !important" name="vendor_id" id="VendorID">
                                        	<option value="">Choose a Vendor</option>
                                            <?php foreach($vendors as $vendor):?>
                                            	<option value="<?=$vendor->ID;?>"><?=$vendor->Name;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php }else { ?>
                                	<input type="hidden" value="<?= $vendor_id; ?>" name="vendor_id" />
                                <?php } ?>
                                <div class="rowElem noborder">
                                    <label style="padding-top:5px !important;">Title</label>
                                    <div class="formRight noSearch">
                                        <select class="chzn-select validate[required]" style="width:26%" name="job_title" id="JobTitle">
                                        	<option value=""></option>
                                            <?php foreach ($jobtitles as $jobtitle) { ?>
                                               <option value="<?= $jobtitle->Id; ?>"><?= $jobtitle->Name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                	<label style="padding-top:5px !important;">Phone</label>
                                    <div class="formRight">
                                    	<div style="float:left;width:48%;margin-right:5px;">
                                            <select id="contactPhoneType" class="chzn-select validate[required]" style="width:40%;" name="phone_type">
                                                <option value="mobile">Mobile</option>
                                                <option value="home">Home</option>
                                                <option value="work" selected="selected">Work</option>
                                            </select>
                                            <span class="formNote" style="margin-top:-10px;">Phone Type</span>
                                        </div>
                                        <div style="float:left;width:48%;">
                                    		<?= form_input(array('class'=>'validate[custom[phone]] maskPhoneExt','name'=>'phone','id'=>'phone','value'=>'','style'=>'margin-top:0;')); ?>
                                            <span class="formNote" style="margin-top:-10px;">Phone Number</span>
                                        </div>
                                        <div class="fix"></div>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Address</label>
                                    <div class="formRight">
										<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value'=>'')); ?> 
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>City</label>
                                    <div class="formRight">
										<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value'=>'')); ?> 
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label style="padding-top:5px !important;">State</label>
                                    <div class="formRight searchDrop noSearch">
										<?= showStates(); ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Zip</label>
                                    <div class="formRight">
										<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'zip','id'=>'zip','value'=>'')); ?>                       
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                	<label>Notes</label>
                                    <div class="formRight">
										<?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes','value'=>'')); ?>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
                    <div class="fix"></div>
				</div>
                <div class="fix"></div> 
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
	var $ = jQuery.noConflict();
	$('#client_dropdown,#vendor_dropdown').find('div.chzn-container').css({'width':'200px'});
	$('#contactType').change(function() {
		if($(this).val() == '1') {
			$('#client_dropdown').slideDown('fast');
			$('#vendor_dropdown').slideUp('fast');
			$('#client_dropdown').find('select').addClass('validate[required]');
			$('#vendor_dropdown').find('select').removeClass('validate[required]');
		}
		
		if($(this).val() == '2') {
			$('#client_dropdown').slideUp('fast');
			$('#vendor_dropdown').slideDown('fast');	
			$('#client_dropdown').find('select').removeClass('validate[required]');
			$('#vendor_dropdown').find('select').addClass('validate[required]');
		}
		
		if($(this).val() != '1' && $(this).val() != '2') {
			$('#client_dropdown,#vendor_dropdown').slideUp('fast');	
			$('#client_dropdown,#vendor_dropdown').find('select').removeClass('validate[required]');
		}
	});
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	
	$('#addContactDetails').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/contacts/process_add',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = 'Your edit was made succesfully';
					jAlert(msg,'Success',function() {
						$("#addContactInfo").dialog('close');
						contactListTable();
					}); 
				}else {
					msg = 'There was a problem with editing the contact requested. Please try again.';
					jAlert(msg,'Error');
				}
			}
		});
	});
	
	$(".chzn-select").chosen();
	$("#addContactInfo").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
				{
					class:'greenBtn saveContactBtn',
					text:"Add",
					click:function() { $('#addContactDetails').submit(); }
				},
		]
	});
	
	
</script>
