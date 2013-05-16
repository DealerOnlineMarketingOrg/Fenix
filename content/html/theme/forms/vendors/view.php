<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="viewVendor" title="View Vendor Details">
        <div class="uiForm">
			<style type="text/css">
				#viewVendor label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#viewVendor .chzn-container,textarea{margin-top:12px;}
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
            
            <div class="widget" style="margin-top:0;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="vendorInfo">Vendor Details</a></li>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <li><a href="javascript:void(0);" rel="contacts">Contacts</a></li>
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
											echo form_input(array('disabled'=>'disabled','id'=>'vendor_name','name'=>'name','class'=>'validate[required]','value'=>$vendor->Name));
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
                                        <?php if(!empty($vendor->Phones)) { ?>
											<?php foreach($vendor->Phones as $phone) { ?>
                                                <tr>
                                                    <td class="primaryCell">
                                                        <?php if($phone->PHONE_Primary == 1) {
                                                            echo 'Primary';
                                                        }?>
                                                    </td>
                                                    <td class="typeCell">
                                                        <select name="phone[<?= $phone->PHONE_ID; ?>][type]" id="PhoneType<?=$phone->PHONE_ID; ?>" class="chzn-select" style="width:125px;" disabled>
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
                                                            value="<?= $phone->PHONE_Number; ?>" disabled />
                                                     </td>
                                                 </tr>
                                                <?php } ?>
                                            <? }else { ?>
                                                <tr>
                                                	<td colspan="3"><p class="noData">No phone numbers set for this vendor.</p></td>
                                                </tr>
                                            <? } ?>
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
                                                	<?php if($address->ADDRESS_Primary == 1) {
														echo 'Primary';
													}?>
                                                </td>
                                                <td class="noSearch typeCell">
                                                    <select name="address[<?= $address->ADDRESS_ID; ?>][type]" id="AddressType<?=$address->ADDRESS_ID; ?>" class="chzn-select" style="width:125px;" disabled>
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
                                                        value="<?= $address->ADDRESS_Street; ?>" disabled/>
                                                        <span class="formNote">Street Address</span>
                                                    <input 
                                                        type="text" 
                                                        name="address[<?= $address->ADDRESS_ID; ?>][city]" 
                                                        id="street<?= $address->ADDRESS_ID; ?>" 
                                                        class="" 
                                                        value="<?= $address->ADDRESS_City; ?>" disabled/>
                                                        <span class="formNote">City</span>
                                                    <?= showStatesArray($address->ADDRESS_State,true,$address->ADDRESS_ID); ?>
                                                    <span class="formNote">State</span>
                                                    <input 
                                                        type="text" 
                                                        name="address[<?= $address->ADDRESS_ID; ?>][zip]" 
                                                        id="zip<?=$address->ADDRESS_ID;?>" 
                                                        class="" 
                                                        value="<?= $address->ADDRESS_Zip; ?>" disabled/>
                                                        <span class="formNote">Zip Code</span>
                                                 </td>
                                             </tr>
                                        <?php } ?>
                                    <?php }else { ?>
                                            <tr>
                                            	<td colspan="3"><p class="noData">No Addresses found for this vendor.</p></td>
                                            </tr>
                                    <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="rowElem noborder">
                                	<label>Notes</label>
                                    <div class="formRight">
                                    		<textarea 
                                            	rows="8" 
                                                cols="" 
                                                class="auto" 
                                                name="notes" disabled><?= ((isset($vendor->Notes)) ? $vendor->Notes : '...'); ?></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                     </div>
                     <div id="websites" class="tab_content" style="display:none;">
                     	<?= WebsiteListingTable($vendor->ID,2,false); ?>
                     </div>
                     <div id="contacts" class="tab_content" style="display:none;padding-bottom:10px;">
                     	<?= ContactsMainTable(false,true,$vendor->ID,true); ?>
                     </div>
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
	var $ = jQuery;
	
	$.mask.definitions['~'] = "[+-]";
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");

	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		
		$('#viewVendor div.tab_container div.tab_content').hide();
		$('#viewVendor div.tab_container').find(content).css({'display':'block'});
		
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
	$('#VendorForm').submit(function(e) {
		e.preventDefault();
	});
	
	$(".chzn-select").chosen();
	
	
	$("#viewVendor").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
				{
					class:'greyBtn close',
					text:'Close',
					click:function() {$(this).dialog('close')}	
				}
		] 
	});
</script>
