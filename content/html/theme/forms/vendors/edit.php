<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="editVendor" title="<?= ((isset($vendor)) ? 'Edit' : 'View'); ?> Vendor Details">
        <div class="uiForm">
			<style type="text/css">
				#editVendor label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editVendor .chzn-container,textarea{margin-top:12px;}
				#editVendor div.widget {margin-top:0;padding-top:0;margin-bottom:10px;}
				div.rowElem table.tableStatic tr{border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;}
				div.rowElem table.tableStatic tr.last{border-left:none;border-right:none;}
				div.rowElem table tr td {vertical-align:top;}
				div.rowElem table.tableStatic {border-bottom:1px solid #d5d5d5;}
				div.rowElem table.tableStatic tr td.last{border-left:none;border-right:none;border-bottom:none;}
				div.rowElem input[type=radio]{margin-top:16px;}
				div.rowElem table.tableStatic thead td {text-align:left;padding-left:10px;}
				.primaryCell{width:10%;text-align:center;}
				.typeCell{width:20%;}
				.addPhone{margin-top:10px;}
				div.rowElem span.formNote{margin-top:-10px;}
				div.rowElem textarea {overflow: hidden; word-wrap: break-word; resize: horizontal; height: 112px;}
			</style>
            <div class="widget">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="vendorInfo">Vendor Details</a></li>
                    <?php if(isset($websites)) { ?>
                    	<li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <?php } 
					/*
					if(isset($contacts)) { ?>
            			<li><a href="javascript:void(0);" rel="contactInfo">Contacts</a></li>
                    <?php } */?>
            	</ul>
            	<div class="tab_container">
            		<div id="vendorInfo" class="tab_content">
						<?php
                             echo form_open('/admin/vendor/process',array('id'=>'VendorForm','class' => 'validate mainForm formPop','style'=>'text-align:left;'));
                        ?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Vendor Name</label>
                                    <div class="formRight">
                                        <?php
											if(isset($view)) {
													echo form_input(array('disabled'=>'disabled','id'=>'vendor_name','name'=>'name','class'=>'validate[required]','value'=>$vendor->Name));
											}else {
												if(isset($vendor->Name)) {
													echo form_input(array('id'=>'vendor_name','name'=>'name','class'=>'validate[required]','value'=>$vendor->Name));
												}else {
													echo form_input(array('id'=>'group_name','name'=>'name','class'=>'validate[required]'));
												}	
											}
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <div class="head" style="border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;"><h5 class="iPhone">Phone Numbers</h5></div>
                                    <table width="100%" class="tableStatic" id="VendorPhones">
                                        <thead>
                                            <tr>
                                                <td>Primary</td>
                                                <td>Type</td>
                                                <td>Number</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php if($vendor->Phones) { ?>
                                        <?php foreach($vendor->Phones as $phone) { ?>
                                            <tr>
                                                <td class="primaryCell">
                                                    <input 
                                                        type="radio" 
                                                        name="phone[<?= $phone->PHONE_ID; ?>][primary]" 
                                                        value="<?= $phone->PHONE_Primary; ?>" 
                                                        class="primaryPhone"
                                                        <?= (($phone->PHONE_Primary == 1) ? 'checked' : ''); ?> />
                                                </td>
                                                <td class="typeCell">
                                                    <select name="phone[<?= $phone->PHONE_ID; ?>][type]" id="PhoneType<?=$phone->PHONE_ID; ?>" class="chzn-select" style="width:125px;">
                                                        <option value=""></option>
                                                        <option <?= (($phone->PHONE_Type == 'Support') ? 'selected="selected"' : ''); ?> value="Support">Support</option>
                                                        <option <?= (($phone->PHONE_Type == 'Sales') ? 'selected="selected"' : ''); ?>  value="Sales">Sales</option>
                                                        <option <?= (($phone->PHONE_Type == 'Customer Service') ? 'selected="selected"' : ''); ?>  value="Customer Service">Customer Service</option>
                                                        <option <?= (($phone->PHONE_Type == 'Rep') ? 'selected="selected"' : ''); ?> value="Rep">Rep</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" 
                                                        name="phone[<?= $phone->PHONE_ID; ?>][number]" 
                                                        id="PhoneNumber<?=$phone->PHONE_ID;?>" 
                                                        class="validate[custom[phone]] maskPhoneExt" 
                                                        value="<?= $phone->PHONE_Number; ?>" />
                                                 </td>
                                             </tr>
                                        <?php } ?>
                                    <?php }else { ?>
                                            <tr>
                                                <td class="primaryCell"><input class="primaryPhone" type="radio" name="phone[new][primary]" value="1" checked /></td>
                                                <td class="typeCell">
                                                    <select name="phone[new][type]" id="PhoneType" class="chzn-select" style="width:125px;">
                                                        <option value=""></option>
                                                        <option value="Support">Support</option>
                                                        <option value="Sales">Sales</option>
                                                        <option value="Customer Service">Customer Service</option>
                                                        <option value="Rep">Rep</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" 
                                                        name="phone[new][number]" 
                                                        id="PhoneNumber" 
                                                        class="validate[custom[phone]] maskPhoneExt" 
                                                        value="" />
                                                        
                                                     <input type="hidden" name="newPhone" value="1" />
                                                </td>
                                            </tr>
                                    <?php } ?>
                                        </tbody>
                                    </table>
								</div>
                                <div class="rowElem noborder">
                                    <div class="head" style="border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;"><h5 class="iBuilding">Address</h5></div>
                                    <table width="100%" class="tableStatic" id="VendorPhones">
                                        <thead>
                                            <tr>
                                                <td>Primary</td>
                                                <td>Type</td>
                                                <td>Address</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php if(!empty($vendor->Addresses)) { ?>
                                        <?php foreach($vendor->Addresses as $address) { ?>
                                            <tr>
                                                <td class="primaryCell">
                                                    <input class="primaryAddress" type="radio" name="address[<?= $address->ADDRESS_ID; ?>][primary]" value="<?= $address->ADDRESS_Primary; ?>" <?= (($address->ADDRESS_Primary == 1) ? 'checked' : ''); ?> />
                                                </td>
                                                <td class="noSearch typeCell">
                                                    <select name="address[<?= $address->ADDRESS_ID; ?>][type]" id="AddressType<?=$address->ADDRESS_ID; ?>" class="chzn-select" style="width:125px;">
                                                        <option value=""></option>
                                                        <option <?= (($address->ADDRESS_Type == 'Work') ? 'selected="selected"' : ''); ?> value="Work">Work</option>
                                                        <option <?= (($address->ADDRESS_Type == 'Home') ? 'selected="selected"' : ''); ?>  value="Home">Home</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input 
                                                        type="text" 
                                                        name="address[<?= $address->ADDRESS_ID; ?>][street]" 
                                                        id="street<?=$address->ADDRESS_ID;?>" 
                                                        class="" 
                                                        value="<?= $address->ADDRESS_Street; ?>" />
                                                        <span class="formNote">Street Address</span>
                                                    <input 
                                                        type="text" 
                                                        name="address[<?= $address->ADDRESS_ID; ?>][city]" 
                                                        id="street<?= $address->ADDRESS_ID; ?>" 
                                                        class="" 
                                                        value="<?= $address->ADDRESS_City; ?>" />
                                                        <span class="formNote">City</span>
                                                    <?= showStatesArray($address->ADDRESS_State,false,$address->ADDRESS_ID); ?>
                                                    <span class="formNote">State</span>
                                                    <input 
                                                        type="text" 
                                                        name="address[<?= $address->ADDRESS_ID; ?>][zip]" 
                                                        id="zip<?=$address->ADDRESS_ID;?>" 
                                                        class="" 
                                                        value="<?= $address->ADDRESS_Zip; ?>" />
                                                        <span class="formNote">Zip Code</span>
                                                 </td>
                                             </tr>
                                        <?php } ?>
                                    <?php }else { ?>
                                            <tr>
                                                <td class="primaryCell">
                                                    <input 
                                                        type="radio" 
                                                        name="address[new][primary]" 
                                                        value="" 
                                                        class="primaryAddress"
                                                        checked />
                                                </td>
                                                <td class="noSearch typeCell">
                                                    <select name="address[new][type]" id="AddressType" class="chzn-select" style="width:125px;">
                                                        <option value=""></option>
                                                        <option value="Work">Work</option>
                                                        <option value="Home">Home</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="address[new][street]" id="street" class="" value="" />
                                                    <span class="formNote">Street Address</span>
                                                    <input type="text" name="address[new][city]" id="street" class="" value="" />
                                                    <span class="formNote">City</span>
                                                    <?= showStatesArray('',false,'new'); ?>
                                                    <span class="formNote">State</span>
                                                    <input type="text" name="address[new][zip]" id="zip" class="" value="" />
                                                    <span class="formNote">Zip Code</span>
                                                    <input type="hidden" name="newAddress" value="1" />
                                                 </td>
                                             </tr>
                                    <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="rowElem noborder">
                                	<label>Notes</label>
                                    <div class="formRight">
                                    	<?php if(isset($view)) { ?>
                                    		<textarea 
                                            	rows="8" 
                                                cols="" 
                                                class="auto" 
                                                name="notes"><?= ((isset($vendor->Notes)) ? $vendor->Notes : '...'); ?></textarea>
                                        <?php }else { ?>
                                            <textarea 
                                                rows="8" 
                                                cols="" 
                                                class="auto" 
                                                name="notes"><?= ((isset($vendor->Notes)) ? $vendor->Notes : ''); ?></textarea>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                     </div>
                     <?php if(isset($websites)) { ?>
                     <div id="websites" class="tab_content" style="display:none;">
                     	<?= WebsiteListingTable($vendor->ID,2,true); ?>
                     </div>
                     <?php } ?>
                     <?php /*
                     <div id="contactInfo" class="tab_content" style="display:none;">
                    	<?= $contactInfo; ?>
                     </div>
					 */ ?>
                  </div>
                <div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>

<div id="addWebsiteForm"></div>

<div id="addContactInfoPhonePop"></div>
<div id="editContactInfoPhonePop"></div>
<div id="addContactInfoEmailPop"></div>
<div id="editContactInfoEmailPop"></div>

<style type="text/css">
	.rowElem > label {padding-top:5px;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery;
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	$(".maskIntPhone").mask("+33 999 999 999");
	$(".maskTin").mask("99-9999999");
	$(".maskSsn").mask("999-99-9999");
	$(".maskProd").mask("a*-999-a999", { placeholder: " " });
	$(".maskEye").mask("~9.99 ~9.99 999");
	$(".maskPo").mask("PO: aaa-999-***");
	$(".maskPct").mask("99%");

	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});

	<?php if(!isset($view)) { ?>
	
	$('input.primaryPhone').click(function() {
		$('input.primaryPhone').each(function() {
			$('input.primaryPhone').attr('checked',false);
		});
		$(this).attr('checked','checked');
	});
	
	$('input.primaryAddress').click(function() {
		$('input.primaryAddress').each(function() {
			$('input.primaryAddress').attr('checked',false);
		});
		$(this).attr('checked','checked');
	});

	$('#VendorForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'<?= ((isset($vendor->ID)) ? '/admin/vendors/edit_vendor?vid=' . $vendor->ID : '/admin/vendors/add_new_vendor'); ?>',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= (isset($vendor)) ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						vendorTable();
					}); 
				}else {
					msg = '<?= (isset($vendor)) ? 'There was a problem with editing the vendor requested. Please try again.':'There was a problem adding the vendor. Please try again.'; ?>';
					jAlert(msg,'Error');
				}
			}
		});
	});
	<?php } ?>
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		
		$('#editVendor div.tab_container div.tab_content').hide();
		$('#editVendor div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		//alert(activeContent);
		
		<?php if(!isset($view)) { ?>
				
		if(activeContent == 'contacts') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addVendor').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addVendor').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveVendor').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveVendor').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addVendor').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addVendor').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveVendor').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveVendor').addClass('hidden');
			}
		}
		
		if(activeContent == 'vendorInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addVendor').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addVendor').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveVendor').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveVendor').removeClass('hidden');
			}
		}
		<?php } ?>

	});
	
	$("#editVendor").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			<?php if(isset($view) && isset($vendor->ID)) { ?>
				{
					class:'greyBtn close',
					text:'Close',
					click:function() {$(this).dialog('close')}	
				}
			<?php } ?>
			<?php if(!isset($view) && isset($vendor->ID)) { ?>
			{
				class:'redBtn saveVendor',
				text:'Save',
				click:function(){$('#VendorForm').submit()}
			},
			<?php } ?>
			<?php if(!isset($view) && !isset($vendor->ID)) { ?>
			{
				class:'greenBtn addVendor',
				text:'Add',
				click:function() {$('#VendorForm').submit()}
			},
			<?php } ?>
			<?php if(GateKeeper('Website_Add',$this->user['AccessLevel'])) { ?>
				<?php if(!isset($view) && isset($vendor->ID)) { ?>
				{
					class:'greenBtn hidden addWebsiteBtn',
					text:"Add New Website",
					click:function() { addWebsiteForm('<?= $vendor->ID;?>',2)}
				}, <?php } ?>
			<?php } ?>
			<?php if(GateKeeper('Contact_Add',$this->user['AccessLevel'])) { ?>
				<?php if(!isset($view)) { ?>
				{
					class:'greenBtn hidden addContactBtn',
					text:"Add New Contact",
					click:function() { addContact()}
				}, <?php } ?>
			<?php } ?>
		] 
	});
</script>
