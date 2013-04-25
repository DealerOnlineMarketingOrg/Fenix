<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editMasterList" title="Client Assets">
        <div class="uiForm">
            <div class="widget masterlistPop" style="margin-top:5px;">
            	<div class="head"><h5 class="iCompanies"><?= $client->Dealership; ?></h5></div>
            	<?php echo form_open('/admin/masterlist/form',array('id'=>'editMasterlistForm','class'=>'validate mainForm formPop','style'=>'text-align:left;'));	?>
                	<? //print_object($client); ?>
                    <fieldset>
                        <div class="rowElem noborder noSearch">
                            <table width="100%">
                                <tr>
                                    <td style="width:50%;">DOC And XSL Links</td>
                                    <td style="width:50%;">CRM Name and Link</td>
                                </tr>
                                <tr>
                                    <td>
                                    	<?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                        	<?php foreach($client->Assets as $asset) { ?>
                                                <input type="text" value="<?= (isset($asset->DOCLink)) ? $asset->DOCLink : ''; ?>" name="doc" class="enableCopy" />
                                            <?php } ?>
                                        <?php }else { ?>
                                    		<input type="text" name="doc" id="Doc" />
                                        <?php } ?>
                                    </td>
                                    <td>
                                    	<?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                        	<?php foreach($client->Assets as $asset) { ?>
                                                <select class="chzn-select crm" name="crm" style="margin:12px 0 0 0;width:43%;" id="crm_<?= $asset->AssetsID; ?>" onChange="javascript:addValidation('crm_<?= $asset->AssetsID; ?>','crm_link_<?= $asset->AssetsID; ?>')">
                                                    <option value="">Choose A CRM</option>
                                                    <?php foreach($vendorOptions as $option) { ?>
                                                        <option <?= ((isset($asset->VendorName)) ? (($asset->VendorName == $option->Name) ? 'selected="selected"':'') : ''); ?> value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        <?php }else { ?>
                                                <select class="chzn-select" name="crm" style="margin:12px 0;width:43%;" id="crm_<?= $asset->AssetsID; ?>" onChange="javascript:addValidation('crm_<?= $asset->AssetsID; ?>','crm_link_<?= $asset->AssetsID; ?>')">
                                                    <option value="">Choose A CRM</option>
                                                    <?php foreach($vendorOptions as $option) { ?>
                                                        <option value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                    <?php } ?>
                                                </select>
                                        <?php } ?>
                                    </td>
                                    
                                </tr>
                                <tr>
                                	<td><p class="formNote">Full URL To the Google Doc file</p></td>
                                    <td><p class="formNote">Select a CRM Vendor</p></td>
                                </tr>
                                <tr>
                                	<td>
									<?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                        <?php foreach($client->Assets as $asset) { ?>
                                            <input type="text" value="<?= (isset($asset->ExcelLink)) ? $asset->ExcelLink : ''; ?>" name="xsl" id="xsl" class="enableCopy" />
                                        <?php } ?>
                                    <?php }else { ?>
                                        <input type="text" name="xsl" id="Doc" />
                                    <?php } ?>
                                    </td>
                                    	<td>
                                        	<?php if(isset($client->Assets) AND !empty($client->Assets)) { ?>
                                            	<?php foreach($client->Assets as $assets) { ?>
                                        			<input style="margin-top:0px;width:330px !important;" id="crm_link_<?= $asset->AssetsID; ?>"  class="enableCopy" type="text" value="<?= (isset($asset->CRMLink)) ? $asset->CRMLink : ''; ?>" name="crm_link" />
                                                <?php } ?>
                                            <?php }else { ?>
                                                <input style="margin-top:0px;width:330px !important;" id="crm_link_<?= $asset->AssetsID; ?>" type="text" value="" placeholder="CRM Url" name="crm_link" />
                                            <?php } ?>
                                        </td>
                                    <td>
                                </tr>
                                <tr>
                                	<td><p class="formNote">Full URL to Google Spreadsheet</p></td>
                                    <td><p class="formNote">Full URL to CRM Vendor</p></td>
                                </tr>
                             </table>
                        </div>
                        <div class="fix"></div>
                        <?php if(!empty($client->Websites)) { ?>
							<?php $i = 0; foreach($client->Websites as $website) { ?>
                                <div class="rowElem noSearch">
                                    <table style="margin:0 auto;width:100%" cellpadding="0" cellspacing="0">
                                    	<tr>
                                        	<td colspan="2"><h5 class="website"><a href="<?= $website->href;?>" target="_blank"><?= str_replace('http://','',$website->href); ?></a></h5></td>
                                        </tr>
                                        <tr>
                                            <td style="width:50%;">CMS Name and Link</td>
                                            <td style="width:50%;">Crazy Egg</td>
                                        </tr>
                                        <tr>
                                            <td>
                                            	<?php if(isset($website->VendorName) AND isset($website->CMSLink)) { ?>
                                                        <select class="chzn-select cms" style="float:left;width:43%" name="cms[<?= $website->ID ?>][id]" id="cms_<?= $website->ID; ?>_cms" onChange="javascript:addValidation('cms_<?= $website->ID; ?>','cms_link_<?= $website->ID; ?>')">
                                                            <option value="">Choose a CMS</option>
                                                            <?php foreach($vendorOptions as $option) { ?>
                                                                <option <?= (($website->VendorName == $option->Name) ? 'selected="selected"' : ''); ?> value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        
                                                        <input id="cms_link_<?= $website->ID; ?>" style="margin-top:5px;width:330px !important;" class="enableCopy" type="text" value="<?= $website->CMSLink; ?>" name="cms[<?= $website->ID; ?>][link]" />

                                                <?php }else { ?>
                                                    <select class="chzn-select" style="float:left;width:43%" name="cms[<?= $website->ID; ?>][id]" id="cms_<?= $website->ID; ?>_cms" onChange="javascript:addValidation('cms_<?= $website->ID; ?>','cms_link_<?= $website->ID; ?>')">
                                                        <option value="">Choose a CMS</option>
                                                        <?php foreach($vendorOptions as $option) { ?>
                                                            <option value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <input id="cms_link_<?= $website->ID; ?>" style="margin-top:5px;width:330px !important;" class="enableCopy" placeholder="CMS Url" type="text" value="" name="cms[<?= $website->ID; ?>][link]" />
                                                <?php } ?>
                                            </td>
                                            <td>
                                            	<?php if(isset($website->CrazyEggLabel)) { ?>
                                                        <select class="chzn-select" style="float:left;width:43%" name="crazyegg[<?= $website->ID; ?>]" id="web_<?= $website->ID; ?>_crazyegg">
                                                            <option value="">Choose Crazy Egg</option>
                                                            <?php foreach($crazyEggOptions as $option) { ?>
                                                                <option <?= (($option->Name == $website->CrazyEggLabel) ? 'selected="selected"' : ''); ?> value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                <?php }else { ?>
                                                        <select class="chzn-select" style="float:left;width:43%" name="crazyegg[<?= $website->ID; ?>]" id="web_<?= $website->ID; ?>_crazyegg">
                                                            <option value="">Choose Crazy Egg</option>
                                                            <?php foreach($crazyEggOptions as $option) { ?>
                                                                <option value="<?= $option->ID; ?>"><?= $option->Name; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                     </table>
                                    <div class="fix"></div>
                                </div>
                            <?php $i++;} ?>
                        <?php }else { ?>
                        	<div class="rowElem">
                            	<p>No websites added for this client.</p>
                            </div>
                        <?php } ?>
                        <div class="submitForm" style="margin-top:10px;border-top:1px solid #d5d5d5;">
                            <input type="hidden" name="client_id" value="<?= $client->ClientID; ?>" />
                            <input type="submit" value="Save" class="redBtn" />
                        </div>
                    </fieldset>
               	<?= form_close(); ?>
                <div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<style type="text/css">
.rowElem > label {padding:0;}
	.ui-datepicker-append{float:left;}
	.rowElem > input {margin-bottom:0;margin-top:0;}
	div.formError{z-index:2000;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery;

	function addValidation(drop,inp) {
		if($('#'+drop).val() != '') {
			$('#'+inp).addClass('required');	
		}else{
			$('#'+inp).removeClass('required');	
		}
	}

	$("#editMasterlistForm").validationEngine({promptPosition : "right", scroll: true});
	
	var crm_name_width = $('#crm_name_chzn').width() + 10;
	$('div#editMasterList .mainForm input#crm_link').css({'width':crm_name_width + 'px'});
	
	$('div.rowElem:odd').addClass('odd');
	$('div.rowElem:last').addClass('last');
	
	$('input.enableCopy').click(function() {
		$(this).select();
	});
	
	$('input.enableCopy').blur(function() {
		$(this).next().slideUp('fast');
	});
	
		
	$('#editMasterlistForm').submit(function(e) {
		
		if($('input.required').val() == '') {
			jAlert('The CRM and CMS Links are required when a vendor is chosen. Please copy and paste the URL to these into the link box','Validation Error');
		}else {
			
			e.preventDefault();
			var formData = $(this).serialize();
			$.ajax({
				type:'POST',
				data:formData,
				url:'/admin/masterlist/form?cid=<?= $client->ClientID; ?>',
				success:function(code) {
					if(code == '1') {
						jAlert('The clients data was successfully updated.','Success',function() {
							refreshTable();
						});	
					}else {
						jAlert('There was an issue updating the clients assets.','Error',function() {
							$('#editMasterList').dialog('close');
						});
					}
				}
			});
		}
	});
	
	$(".chzn-select").chosen();
	
	$("#editMasterList").dialog({
		minWidth:750,
		height:500,
		autoOpen: true,
		modal: true,
	});
</script>
