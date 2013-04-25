<div class="uDialog">
	<?php switch ($type) {
			case 'CID': $typeName = 'Client'; break;
			case 'VID': $typeName = 'Vendor'; break;
			case 'GID': $typeName = 'Contact'; break;
			case 'UID': $typeName = 'User'; break;
			default: $typeName = 'Vendor';
		} ?>
    <div class="dialog-message" id="addWebsite" title="<?= (($page == 'edit') ? 'Edit ' . $typeName . ' Website' : 'Add New Website To ' . $typeName); ?>">
        <div class="uiForm">
        	 <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                	<?= form_open(base_url() . 'admin/websites/'.$page,array('id'=>'web','class'=>'valid mainForm','style'=>'text-align:left;')); ?>
					<fieldset>
                    	<?php if(!isset($selectedVendor)) { ?>
                            <div class="rowElem noborder noSearch">
                                <label><span class="req">*</span> Vendor</label>
                                <div class="formRight">
                                    <select id="vendors" name="vendor" class="chzn-select validate[required] vendors" style="float:left;">
                                        <option value="">Choose a Vendor</option>
                                        <? foreach($vendors as $vendor) : ?>
                                            <?php if($website) { ?>
                                                <option <?= ($website->Vendor == $vendor->ID) ? 'selected="selected"' : ''; ?> value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
                                            <?php }else { ?>
                                                <option value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
                                            <?php } ?>
                                        <? endforeach; ?>
                                        <option value="custom">Custom</option>
                                    </select>
                                    <div id="CustomVendor" class="CustomVendor" style="float:left;display:none;">
                                        <?= form_input(array('name'=>'custom_vendor','id'=>'custom_vendor')); ?>
                                        <span class="formNote">Type the vendor name here and the vendor will be added to the system.</span>
                                    </div>
                                </div>
                                <div class="fix"></div>
                            </div>
                        <?php } ?>
						<div class="rowElem noborder">
							<label>URL</label>
							<div class="formRight">
								<?= form_input(array('class'=>'validate[required,custom[url]]','name'=>'url','id'=>'url','value'=>((isset($website->URL)) ? $website->URL : ''))); ?>
							</div>
							<div class="fix"></div>
						</div>
                        <?php if ($type != 'GID' && $type != 'UID') { ?>
                            <div class="rowElem noborder">
                                <label>UA Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'ua_code','id'=>'google_ua_code','value'=>((isset($website->GoogleUACode)) ? $website->GoogleUACode : ''))); ?>
                                    <span class="formNote">Google Analytics</span>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Meta Code Number</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'meta_code_number','id'=>'meta_code_number','value'=>((isset($website->GoogleWebToolsMetaCode)) ? $website->GoogleWebToolsMetaCode : ''))); ?>
                                    <span class="formNote">Google Webmaster Tools</span>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Google+ Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'gplus_code','id'=>'gplus_code','value'=>((isset($website->GooglePlusCode)) ? $website->GooglePlusCode : ''))); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Bing Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'bing_code','id'=>'bing_code','value'=>((isset($website->BingCode)) ? $website->BingCode : ''))); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Yahoo Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'yahoo_code','id'=>'yahoo_code','value'=>((isset($website->YahooCode)) ? $website->YahooCode : ''))); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Global JS Script</label>
                                <div class="formRight">
                                    <?= form_textarea(array('name'=>'global_code','id'=>'global_code','value'=>((isset($website->GlobalScript)) ? $website->GlobalScript : ''))); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                        <?php } ?>
						<div class="rowElem noborder">
							<label>Notes</label>
							<div class="formRight">
								<?= form_textarea(array('name'=>'notes','id'=>'web_notes','value'=>((isset($website->Description))?$website->Description:''))); ?>
							</div>
							<div class="fix"></div>
						</div>
		                <div class="submitForm">
		                	<?php if(isset($website->ID)) { ?>
		                		<input type="hidden" name="web_id" value="<?= $website->ID; ?>" />
		                	<?php } ?>
	               			<input type="hidden" name="ID" value="<?= $caller->ID; ?>" />
                           	<input type="hidden" name="VendorID" value="<?= (isset($selectedVendor) ? $selectedVendor : ''); ?>" />
		                </div> 
					</fieldset>
				<?= form_close(); ?>
				<div class="fix"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$ = jQuery;
	
	$('#web').validationEngine({promptPosition : "top", scroll: true});
	$('.vendors').change(function() {
		var selectBox = $(this);
		if(selectBox.val() == '') {
			alert('Vendors are required');	
		}
		if(selectBox.val() == 'custom') {
			$('div.CustomVendor').slideDown('fast');
			$('div.CustomVendor input').addClass('validate[required]');
			$('.vendors').removeClass('validate[required]');
		}else {
			$(".vendors").addClass('validate[required]');
			$("div.CustomVendor input").removeClass('validate[required]');
			$('div.CustomVendor').slideUp('fast');	
		}
	});

	$('#web').submit(function(e) {
		e.preventDefault();
		var id = '<?= $caller->ID; ?>';
		var msg;
		var wid;
		var cUrl;
		var formData = $(this).serialize();
		<?php if($website) { ?>
			wid = '<?= $website->ID; ?>';
			cUrl = '/admin/websites/edit?<?= $type; ?>='+id+'&wid='+wid;
			msg = 'Website edited successfully.';
		<?php }else { ?>
			cUrl = '/admin/websites/add?<?= $type; ?>='+id;
			msg = 'Website added successfully.';
		<?php } ?>
		
		submitWebsiteForm('<?= $typeID; ?>','<?= $type; ?>',formData,cUrl,msg);
	});
	
	$(".chzn-select").chosen();
	
	//load the popup by default;
	$("#addWebsite").dialog({
		minWidth:300,
		width:500,
		height:550,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close');}
			},
			{
				class:'redBtn saveWebsite',
				text:"Save",
				click:function() {$('#web').submit();}
			},
		]
	});

</script>
