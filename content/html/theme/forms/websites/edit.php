<div class="uDialog">

	<?php
		$owner_type = $website->OwnerType;
		$owner_id   = $website->OwnerID;
	?>

	<?php 
		if($owner_type == 1) {
			$page_group = 'Client';
		}elseif($owner_type == 2) {
			$page_group = 'Vendor';	
		}elseif($owner_type == 3) {
			$page_group = 'User';	
		}elseif($owner_type == 4) {
			$page_group = 'Directory';
		}elseif($owner_type == 5) {
			$page_group = 'Website';	
		}elseif($owner_type == 6) {
			$page_group = 'Contact';	
		}elseif($owner_type == 7) {
			$page_group = 'Agency';	
		}elseif($owner_type == 8) {
			$page_group = 'Group';	
		}else {
			$page_group = '';	
		}
	?>
    <div class="dialog-message" id="editWebsite" title="Edit <?=$page_group;?> Website">
        <div class="uiForm">
        	 <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                	<?= form_open(base_url() . 'admin/websites/add',array('id'=>'editWeb','class'=>'valid mainForm','style'=>'text-align:left;')); ?>
					<fieldset>
                        <div class="rowElem noborder noSearch">
                            <label><span class="req">*</span> Vendor</label>
                            <div class="formRight">
                                <select id="vendors" name="vendor" class="chzn-select validate[required] vendors" style="float:left;" <?= (($owner_type == 2) ? 'disabled' : ''); ?>>
                                    <option value="">Choose a Vendor</option>
                                    <? foreach($vendors as $vendor) : ?>
                                    	<?php if($owner_type == 2) { ?>
                                        	<option <?= ((($owner_type == 2) AND ($vendor->VendorID == $owner_id)) ? 'selected="selected"' : ''); ?> value="<?= $vendor->VendorID; ?>"><?= $vendor->VendorName; ?></option>
                                        <?php }else { ?>
                                        	<option <?= (($vendor->VendorID == $website->VendorID) ? 'selected="selected"' : ''); ?> value="<?= $vendor->VendorID; ?>"><?= $vendor->VendorName; ?></option>
                                        <?php } ?>
                                    <? endforeach; ?>
                                </select>
                                <?php if($owner_type == 2) { ?>
                                	<input type="hidden" value="<?= $owner_id; ?>" name="vendor" />
                                <?php } ?>
                            </div>
                            <div class="fix"></div>
                        </div>
						<div class="rowElem noborder">
							<label>URL</label>
							<div class="formRight">
								<?= form_input(array('class'=>'validate[required,custom[url]]','name'=>'url','id'=>'url','value'=>$website->URL)); ?>
							</div>
							<div class="fix"></div>
						</div>
                        <?php if ($owner_type == 1) { ?>
                            <div class="rowElem noborder">
                                <label>UA Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'ua_code','id'=>'google_ua_code','value'=>$website->GoogleUACode)); ?>
                                    <span class="formNote">Google Analytics</span>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Meta Code Number</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'meta_code_number','id'=>'meta_code_number','value'=>$website->GoogleWebToolsMetaCode)); ?>
                                    <span class="formNote">Google Webmaster Tools</span>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Google+ Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'gplus_code','id'=>'gplus_code','value'=>$website->GooglePlusCode)); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Bing Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'bing_code','id'=>'bing_code','value'=>$website->BingCode)); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Yahoo Code</label>
                                <div class="formRight">
                                    <?= form_input(array('name'=>'yahoo_code','id'=>'yahoo_code','value'=>$website->YahooCode)); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="rowElem noborder">
                                <label>Global JS Script</label>
                                <div class="formRight">
                                    <?= form_textarea(array('name'=>'global_code','id'=>'global_code','value'=>$website->GlobalScript)); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                        <?php } ?>
						<div class="rowElem noborder">
							<label>Notes</label>
							<div class="formRight">
								<?= form_textarea(array('name'=>'notes','id'=>'web_notes','value'=>$website->Notes)); ?>
							</div>
							<div class="fix"></div>
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
	
	$('#editWeb').validationEngine({promptPosition : "top", scroll: true});
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

	$('#editWeb').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		var isFormValid = jQuery(this).validationEngine('validate');
		if(isFormValid) {
			$.ajax({
				type:'POST',
				data:formData,
				url:'/admin/websites/edit?web_id=<?=$web_id;?>',
				success:function(data) {
					if(data) {
						jAlert('The Website was edited successfully.','Success!',function() {
							websiteListTable('<?=$owner_type;?>','<?=$owner_id;?>');
						});
					}else {
						jAlert('There was an error editing the website you selected. Please try again.','Error',function() {
							$('#editWebsite').dialog('close');
						});
					}
					
				}
			});
		}
	});
	
	$(".chzn-select").chosen();
	
	//load the popup by default;
	$("#editWebsite").dialog({
		minWidth:300,
		width:575,
		height:550,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'redBtn saveWebsite',
				text:"Save",
				click:function() {$('#editWeb').submit();}
			},
		]
	});

</script>
