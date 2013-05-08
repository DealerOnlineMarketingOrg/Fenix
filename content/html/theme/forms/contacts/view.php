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
	                    <li class="activeTab"><a href="javascript:void(0);" rel="contactDetails">Contact Details</a></li>
                    	<li><a href="javascript:void(0);" rel="websites">Websites</a></li>
	                    <li><a href="javascript:void(0);" rel="contactInfo">Contact Info</a></li>
                </ul>
                <div class="tab_container">
            		<div id="contactDetails" class="tab_content">
						<?= form_open('/admin/contacts/edit_details?did=' . $contact->ContactID,array('id'=>'editContactDetails','class'=>'validate mainForm formPop','style'=>'text-align:left')); ?>
                            <fieldset>
								<div class="rowElem noborder">
                                    <label><span class="req">*</span> Name</label>
                                    <div class="formRight">
										<div style="position:relative;float:left;width:48%;">
                                        	<input type="text" value="<?= $contact->FirstName; ?>" disabled />
                                            <span class="formNote" style="margin-top:-10px;">First Name</span>
                                        </div>
                                    	<div style="position:relative;float:left;margin-left:5px;width:48%;">
                                        	<input type="text" value="<?= $contact->LastName; ?>" disabled />
                                            <span class="formNote" style="margin-top:-10px;">Last Name</span>
                                        </div>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                                <div class="rowElem noborder noSearch">
                                    <label><span class="req">*</span> Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="contactType" class="chzn-select validate[required]" style="width:auto" name="owner_type" disabled>
                                            <option <?= (($contact->OwnerType == 1) ? 'selected="selected"' : ''); ?> value="1">Client</option>
                                            <option <?= (($contact->OwnerType == 2) ? 'selected="selected"' : ''); ?> value="2">Vendor</option>
                                            <option <?= (($contact->OwnerType == 3) ? 'selected="selected"' : ''); ?> value="3">User</option>
                                            <option <?= (($contact->OwnerType == 4) ? 'selected="Selected"' : ''); ?> value="4">General</option>
                                        </select>
                                        <input type="hidden" name="owner_type" value="<?=$contact->OwnerType;?>" />
                                    </div>
                                    <div class="fix"></div>
                                 </div>
                                 <div class="rowElem noborder noSearch">
                                    <?php if($contact->OwnerType == 1) { ?>
                                        <div id="contactParentClient">
                                            <label><span class="req">*</span> Client</label>
                                            <div class="formRight searchDrop noSearch">
                                                <select class="chzn-select validate[required]" style="width:auto" name="owner_id" id="ClientID" disabled>
                                                    <option value=""></option>
                                                    <?php 
                                                        foreach($clients as $client) : ?>
                                                            <option <?= (($contact->OwnerID == $client->ClientID) ? 'selected="selected"' : ''); ?> value="<?= $client->ClientID; ?>"><?= $client->Name; ?></option>
                                                        <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php }elseif($contact->OwnerType == 2) { ?>
                                        <div id="contactParentVendor">
                                            <label><span class="req">*</span> Vendor</label>
                                            <div class="formRight searchDrop">
                                                <select class="chzn-select validate[required]" style="width:auto" name="owner_id" id="VendorID" disabled>
                                                    <option value=""></option>
                                                    <?php 
                                                        foreach($vendors as $vendor) : ?>
                                                            <option <?= (($contact->OwnerID == $vendor->ID) ? 'selected="selected"' : ''); ?> value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
                                                       <?php  endforeach;  ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php }else { ?>
                                    	<input type="hidden" value="<?= $contact->OwnerType; ?>" name="owner_id" />
                                    <?php } ?>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Title</label>
                                    <div class="formRight">
                                        <select class="chzn-select validate[required]" style="width:auto" name="job_title" id="JobTitle" disabled>
                                            <?php
                                                foreach ($jobtitles as $jobtitle) { ?>
                                                   <option value="<?= $jobtitle->Id; ?>" <?= (($contact->TitleID == $jobtitle->Id) ? 'selected="selected"' : ''); ?>><?= $jobtitle->Name; ?></option>
                                                <?php }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Address</label>
                                    <div class="formRight">
                                    	<?php if(!empty($contact->Addresses)) { ?>
											<?php foreach($contact->Addresses as $address) { ?>
                                                <?php if($address->ADDRESS_Primary == 1) { ?>
                                                    <input type="text" value="<?= $address->ADDRESS_Street ?>" disabled />
                                                <?php } ?>
                                            <?php } ?>
                                        <?php }else { ?>
                                            <input type="text" value="..." disabled />
                                        <?php } ?>
                                    </div>
                                    <div class="fix"></div>
                                 </div>
                                 <div class="rowElem noborder">
                                    <label>City</label>
                                    <div class="formRight">
                                    	<?php if(!empty($contact->Addresses)) { ?>
											<?php foreach($contact->Addresses as $address) { ?>
                                                <?php if($address->ADDRESS_Primary == 1) { ?>
                                                    <input type="text" value="<?= $address->ADDRESS_City; ?>" disabled />
                                                <?php } ?>
                                            <?php } ?>
                                        <?php }else { ?>
											<input type="text" value="<?= $address->ADDRESS_City; ?>" disabled />										
										<?php } ?>
                                    </div>
                                    <div class="fix"></div>
                                 </div>
                                 <div class="rowElem noborder">
                                    <label>State</label>
                                    <div class="formRight searchDrop noSearch" style="margin-top:15px;margin-bottom:10px">
                                    	<?php if(!empty($contact->Addresses)) { ?>
											<?php foreach($contact->Addresses as $address) { ?>
                                                <?php if($address->ADDRESS_Primary == 1) { ?>
                                                	<?= showStates($address->ADDRESS_State); ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php }else { ?>
											<?= showStates(); ?>
                                        <?php } ?>
                                    </div>
                                    <div class="fix"></div>
                                 </div>
                                 <div class="rowElem noborder">
                                    <label>Zip</label>
                                    <div class="formRight">
                                    	<?php if(!empty($contact->Addresses)) { ?>
											<?php foreach($contact->Addresses as $address) { ?>
                                                <?php if($address->ADDRESS_Primary == 1) { ?>
													<input type="text" value="<?= $address->ADDRESS_Zip; ?>" disabled />                                                
												<?php } ?>
                                            <?php } ?>
                                        <?php }else { ?>
											<input type="text" value="..." disabled />                                                
                        				<?php } ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                	<label>Notes</label>
                                    <div class="formRight">
										<?= form_textarea(array('class'=>'validate[custom[onlyLetterNumberSpAndPunctuation]]','name'=>'notes','id'=>'notes','value'=>$contact->Notes)); ?>
                                	</div>
                                	<div class="fix"></div>
                                </div>
                                <div class="submitForm">
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
                    <div id="websites" class="tab_content" style="display:none;">
                    	<?php if($contact->OwnerType != 3) { ?>
                    		<?= WebsiteListingTable($contact->OwnerID,$contact->ContactType,false); ?>
                        <?php }else { ?>
                    		<?= WebsiteListingTable($contact->OwnerID,3,false); ?>
                        <?php } ?>
                    </div>
                    <div id="contactInfo" class="tab_content" style="display:none;">
						<style type="text/css">
                            #contactInfo div.head {background:none;border:none;width:100%;margin:0 auto;}
                            #contactInfo div.head h5 {width:115px;margin:0 auto;display:block;float:none;}
                        </style>
                        <div id="phone_table">
                        	<?php if($contact->OwnerType != 3) { ?>
                            	<?= LoadUserPhoneNumberTable(true,$contact->ContactID, $contact->OwnerType); ?>
                            <?php }else { ?>
                            	<?= LoadUserPhoneNumberTable(true,$contact->OwnerID); ?> 
                            <?php } ?>
                        </div>
                        <div id="email_table">
                        	<?php if($contact->OwnerType != 3) { ?>
                            	<?= LoadUserEmailAddresses(true,$contact->ContactID,$contact->OwnerType); ?>
                            <?php }else { ?>
                            	<?= LoadUserEmailAddresses(true,$contact->OwnerID); ?>
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
	
		
	
	$('#editContactDetails').submit(function(e) {
		e.preventDefault();
	});
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#viewContactInfo div.tab_container div.tab_content').hide();
		$('#viewContactInfo div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		//alert(content);
	});
	
	$("#viewContactInfo").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
				{
					class:'greyButton saveContactBtn',
					text:"Close",
					click:function() { $('#viewContactInfo').dialog('close'); }
				},
		]
	});
	
</script>
