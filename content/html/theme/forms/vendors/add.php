<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="addVendor" title="Add Vendor Details">
        <div class="uiForm">
			<style type="text/css">
				#addVendor label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#addVendor .chzn-container,textarea{margin-top:12px;}
				#addVendor div.widget {margin-top:0;padding-top:0;margin-bottom:10px;}
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
                                        <?= form_input(array('id'=>'vendorName','name'=>'name','class'=>'validate[required]'));
                                        ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <div class="head" style="border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;">
                                    	<h5 class="iPhone">Phone Numbers</h5>
                                    </div>
                                    <table width="100%" class="tableStatic" id="VendorPhones">
                                        <thead>
                                            <tr>
                                                <td>Type</td>
                                                <td>Number</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="typeCell">
                                                    <select name="phone[type]" id="PhoneType" class="chzn-select" style="width:125px;">
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
                                                        name="phone[number]" 
                                                        id="PhoneNumber" 
                                                        class="validate[custom[phone]] maskPhoneExt" 
                                                        value="" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
								</div>
                                <div class="rowElem noborder">
                                    <div class="head" style="border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;">
                                    	<h5 class="iBuilding">Address</h5>
                                    </div>
                                    <table width="100%" class="tableStatic" id="VendorPhones">
                                        <thead>
                                            <tr>
                                                <td>Type</td>
                                                <td>Address</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="noSearch typeCell">
                                                    <select name="address[type]" id="AddressType" class="chzn-select" style="width:125px;">
                                                        <option value=""></option>
                                                        <option value="Work">Work</option>
                                                        <option value="Home">Home</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="address[street]" id="street" class="" value="" />
                                                    <span class="formNote">Street Address</span>
                                                    <input type="text" name="address[city]" id="street" class="" value="" />
                                                    <span class="formNote">City</span>
                                                    <?= showStatesArray('',false,false); ?>
                                                    <span class="formNote">State</span>
                                                    <input type="text" name="address[zip]" id="zip" class="" value="" />
                                                    <span class="formNote">Zip Code</span>
                                                 </td>
                                             </tr>
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
                                            name="notes"></textarea>
                                    </div>
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                     </div>
                     <?php if(isset($websites)) { ?>
                     <div id="websites" class="tab_content" style="display:none;">
                     	<?= $websites; ?>
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
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");

	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});

	$('#VendorForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/vendors/add_new_vendor',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = 'The Vendor was added successfully.';
					jAlert(msg,'Success',function() {
						vendorTable();
					}); 
				}else {
					msg = 'There was a problem adding the vendor. Please try again.';
					jAlert(msg,'Error');
				}
			}
		});
	});
	
	$(".chzn-select").chosen();
	
	$("#addVendor").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greenBtn addVendor',
				text:'Add',
				click:function() {$('#VendorForm').submit()}
			},
		] 
	});
</script>
