<div class="uDialog">
    <div class="dialog-message popper" id="viewContactInfo" title="View Contact">
        <div class="uiForm">
			<style type="text/css">
				#editClient label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editClient .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:0px;padding-top:0;margin-bottom:10px;">
                <ul class="tabs">
                    <li class="activeTab"><a href="javascript:void(0);" rel="contacts_contactDetails">Contact Details</a></li>
                    <li><a href="javascript:void(0);" rel="contacts_websites">Websites</a></li>
                    <li><a href="javascript:void(0);" rel="contacts_contactInfo">Contact Info</a></li>
                </ul>
                <div class="tab_container">
            		<div id="contacts_contactDetails" class="tab_content">
						<?= form_open('/admin/contacts/edit_details?did=' . $contact->ContactID,array('id'=>'editContactDetails','class'=>'validate mainForm formPop','style'=>'text-align:left')); ?>
                            <fieldset>
								<div class="rowElem noborder">
                                    <label><span class="req">*</span>Name</label>
                                    <div class="formRight">
										<div style="position:relative;float:left;width:48%;">
											<?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'firstname','id'=>'firstname','value'=> $contact->FirstName,'style'=>'margin:0','style'=>'width:22em !important')); ?>
                                            <span class="formNote" style="margin-top:-10px;">First Name</span>
                                        </div>
                                    	<div style="position:relative;float:left;margin-left:5px;width:48%;">
											<?= form_input(array('class'=>'required validate[required,custom[onlyLetterSp]]','name'=>'lastname','id'=>'lastname','value'=>$contact->LastName,'style'=>'margin:0','style'=>'width:22em !important',)); ?>
                                            <span class="formNote" style="margin-top:-10px;">Last Name</span>
                                        </div>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                                <div class="rowElem noborder noSearch">
                                    <label><span class="req">*</span>Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="contactType" class="chzn-select validate[required]" style="width:auto" name="owner_type" <?= (($contact->OwnerType == 3) ? 'disabled' : ''); ?>>
                                            <option <?= (($contact->OwnerType == 1) ? 'selected="selected"' : ''); ?> value="1">Client</option>
                                            <option <?= (($contact->OwnerType == 2) ? 'selected="selected"' : ''); ?> value="2">Vendor</option>
                                            <option <?= (($contact->OwnerType == 3) ? 'selected="selected"' : ''); ?> value="3">User</option>
                                            <option <?= (($contact->OwnerType == 4) ? 'selected="selected"' : ''); ?> value="4">General</option>
                                        </select>
                                        <?php if($contact->OwnerType == 3) { ?>
                                        	<input type="hidden" name="owner_type" value="3" />
                                        <?php } ?>
                                    </div>
                                    <input id="owner_id" type="hidden" name="owner_id" value="<?= $contact->OwnerID; ?>" />
                                    <input id="directory_id" type="hidden" name="directory_id" value="<?= $contact->ContactID; ?>" />
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder" id="client_dropdown" style=" <?= (($contact->OwnerType == 1) ? 'display:block;' : 'display:none;'); ?>">
                                    <label style="padding-top:5px !important;"><span class="req">*</span>Client</label>
                                    <div class="formRight noSearch">
                                        <select class="chzn-select" name="client_id" style="width:175px !important" id="ClientID">
                                            <option value="">Choose a Client</option>
                                            <?php foreach($clients as $client) : ?>
                                                <option <?= (($client->ClientID == $contact->OwnerID) ? 'selected="selected"' : ''); ?> value="<?= $client->ClientID; ?>"><?= $client->Name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                	<div class="fix"></div>
                                </div>
                                <div class="rowElem noborder" id="vendor_dropdown" style=" <?= (($contact->OwnerType == 2) ? 'display:block;' : 'display:none;'); ?>">
                                	<label style="padding-top:5px !important;"><span class="req">*</span>Vendor</label>
                                    <div class="formRight noSearch">
                                    	<select class="chzn-select" style="width:175px !important" name="vendor_id" id="VendorID">
                                        	<option value="">Choose a Vendor</option>
                                            <?php foreach($vendors as $vendor):?>
                                            	<option <?= (($vendor->ID == $contact->OwnerID) ? 'selected="selected"' : ''); ?> value="<?=$vendor->ID;?>">
													<?=$vendor->Name;?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Title</label>
                                    <div class="formRight">
                                        <select class="chzn-select validate[required]" style="width:auto" name="job_title" id="JobTitle">
                                            <?php
                                                foreach ($jobtitles as $jobtitle) { ?>
                                                   <option value="<?= $jobtitle->Id; ?>" <?= (($contact->TitleID == $jobtitle->Id) ? 'selected="selected"' : ''); ?>><?= $jobtitle->Name; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <?php if(!empty($contact->Addresses)) { ?>
                                	<?php foreach($contact->Addresses as $address) { ?>
                                    	<?php if($address->ADDRESS_Primary == 1) { ?>
                                            <div class="rowElem noborder">
                                                <label>Address</label>
                                                <div class="formRight">
                                                    <input type="hidden" name="address_id" id="address_id" value="<?= $address->ADDRESS_ID; ?>" />
                                                    <?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => $address->ADDRESS_Street,'style'=>'margin:0','placeholder'=>'Enter Street')); ?>
                                                </div>
                                                <div class="fix"></div>
                                             </div>
                                             <div class="rowElem noborder">
                                                <label>City</label>
                                                <div class="formRight">
													<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value' => $address->ADDRESS_City,'style'=>'margin:0')); ?>
                                                </div>
                                                <div class="fix"></div>
                                             </div>
                                             <div class="rowElem noborder">
                                                <label>State</label>
                                                <div class="formRight searchDrop noSearch" style="margin-top:15px;margin-bottom:10px">
													<?= showStates($address->ADDRESS_State); ?>
                                                </div>
                                                <div class="fix"></div>
                                             </div>
                                             <div class="rowElem noborder">
                                                <label>Zip</label>
                                                <div class="formRight">
													<?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'zip','id'=>'zip','value' => $address->ADDRESS_Zip,'style'=>'margin:0')); ?>
                                                </div>
                                                <div class="fix"></div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php }else { ?>
                                    <div class="rowElem noborder">
                                        <label>Address</label>
                                        <div class="formRight">
                                            <?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'street','id'=>'address','value' => '','style'=>'margin:0','placeholder'=>'Enter Street')); ?>
                                        </div>
                                        <div class="fix"></div>
                                     </div>
                                     <div class="rowElem noborder">
                                        <label>City</label>
                                        <div class="formRight">
                                            <?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'city','id'=>'city','value' => '','style'=>'margin:0')); ?>
                                        </div>
                                        <div class="fix"></div>
                                     </div>
                                     <div class="rowElem noborder">
                                        <label>State</label>
                                        <div class="formRight searchDrop noSearch" style="margin-top:15px;margin-bottom:10px">
                                            <?= showStates(''); ?>
                                        </div>
                                        <div class="fix"></div>
                                     </div>
                                     <div class="rowElem noborder">
                                        <label>Zip</label>
                                        <div class="formRight">
                                            <?= form_input(array('class'=>'validate[custom[onlyLetterNumberSp]]','name'=>'zip','id'=>'zip','value' => '','style'=>'margin:0')); ?>
                                        </div>
                                        <div class="fix"></div>
                                    </div>
                                <?php } ?>
                                <div class="rowElem noborder">
                                	<label>Notes</label>
                                    <div class="formRight">
										<?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes','value'=>$contact->Notes)); ?>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                                <div class="submitForm">
                                	<?php if(!empty($contact->Addresses)) { ?>
                                    	<?php foreach($contact->Addresses as $address) { ?>
                                        	<?php if($address->ADDRESS_Primary == 1) { ?>
                                            	<input type="hidden" name="address_id" value="<?= $address->ADDRESS_ID; ?>" />
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    <input type="hidden" name="contact_id" value="<?= $contact->ContactID; ?>" />
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
                    <div id="contacts_websites" class="tab_content" style="display:none;">
                    	<?php if($contact->OwnerType != 3) { ?>
                    		<?= WebsiteListingTable($contact->OwnerID,$contact->OwnerType,false); ?>
                        <?php }else { ?>
                    		<?= WebsiteListingTable($contact->OwnerID,3,false); ?>
                        <?php } ?>
                    </div>
                    <div id="contacts_contactInfo" class="tab_content" style="display:none;">
						<style type="text/css">
                            #contactInfo div.head {background:none;border:none;width:100%;margin:0 auto;}
                            #contactInfo div.head h5 {width:115px;margin:0 auto;display:block;float:none;}
                        </style>
                        <div id="phone_table">
                        	<?php if($contact->OwnerType != 3) { ?>
                            	<?= LoadUserPhoneNumberTable(true,$contact->ContactID, $contact->OwnerType,$contact->Phones); ?>
                            <?php }else { ?>
                            	<?= LoadUserPhoneNumberTable(true,$contact->OwnerID,$contact->OwnerType,$contact->Phones); ?> 
                            <?php } ?>
                        </div>
                        <div id="email_table">
                        	<?php if($contact->OwnerType != 3) { ?>
                            	<?= LoadUserEmailAddresses(true,$contact->ContactID,$contact->OwnerType,$contact->Emails); ?>
                            <?php }else { ?>
                            	<?= LoadUserEmailAddresses(true,$contact->OwnerID,$contact->OwnerType,$contact->Emails); ?>
                            <?php } ?>
                        </div>
                    	<div class="fix"></div>
                    </div>
				</div>
                <div class="fix"></div> 
        	</div> <? //end widget ?>
		</div>
	</div>
</div>

<div id="addContactInfoPhonePop"></div>
<div id="editContactInfoPhonePop"></div>
<div id="addContactInfoEmailPop"></div>
<div id="editContactInfoEmailPop"></div>
<div id="UserEmailPop"></div>
<div id="UserPhonePop"></div>

<style type="text/css">
.rowElem > label {padding-top:5px;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	$('select,input,textarea').attr('disabled','disabled');
	
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
	
	$('#client_dropdown select,#vendor_dropdown select').change(function() {
		var owner_id = $(this).val();
		$('#owner_id').val(owner_id);
	});
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	
	
	$('.change_primary_email').click(function() {
		var email = $(this).val();
		$('span#priEmail').find('a').attr('href',email);
		$('span#priEmail').find('a').text(email);
	});
	
	$('.change_primary_phone').click(function() {
		var phone = $(this).val();
		$('span#priPhone').text(phone);
	});
	
	$('#viewContactInfo ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('#viewContactInfo ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#viewContactInfo div.tab_container div.tab_content').hide();
		$('#viewContactInfo div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		//alert(content);
	});
	
	$('#viewContactDetails').submit(function(e) {
		e.preventDefault();
	});
	
	$(".chzn-select").chosen();
	
	$("#viewContactInfo").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
				{
					class:'greyBtn',
					text:"Close",
					click:function() { $('#viewContactInfo').dialog('close'); }
				}
		]
	});
	
	
</script>
