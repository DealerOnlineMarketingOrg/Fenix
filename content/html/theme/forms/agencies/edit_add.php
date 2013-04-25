<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="editAgency" title="<?= (isset($agency)) ? 'Edit ' . $agency->Name : 'Add Agency'; ?>">
        <div class="uiForm" style="text-align:left;">
			<style type="text/css">
				#editAgency label, #addAgency label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editAgency textarea,#addAgency textarea {margin-top:12px;}
			</style>
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <!-- Form begins -->
                <?php
					$form = array(
						'name' => 'EditAgency',
						'id' => 'EditAgencyForm',
						'class' => 'mainForm formPop',
						'style'=>'text-align:left;'
					);
					
					if(isset($agency)) {
						echo form_open('admin/agency/edit?aid=' . $agency->ID, $form);
					}else {
						echo form_open('admin/agency/add',$form);	
					}
				?>
                    <!-- Input text fields -->
                    <fieldset>
                            <?php if(isset($agency->Name)) { ?>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Name</label>
                                    <div class="formRight">
                                        <input type="text" class="required validate[required]" name="name" id="name" value="<?= $agency->Name; ?>" />
                                    </div>
                                    <div class="fix">
                                </div>
                            <?php }else { ?>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Name</label>
                                    <div class="formRight">
                                        <input type="text" class="required validate[required]" name="name" id="name" value="" />
                                    </div>
                                    <div class="fix">
                                </div>
                            <?php } ?>
                            </div>
                            <?php if(isset($agency->Description)) { ?>
                                <div class="rowElem noborder">
                                    <label>Notes</label>
                                    <div class="formRight">
                                        <textarea rows="8" cols="" class="auto" name="notes"><?= $agency->Description; ?></textarea>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                            <?php }else { ?>
                                <div class="rowElem noborder">
                                    <label>Notes</label>
                                    <div class="formRight">
                                        <textarea rows="8" cols="" class="auto" name="notes"></textarea>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                            <?php } ?>
                            <?php if(isset($agency->Status)) { ?>
                                <div class="rowElem noborder">
                                    <div class="formRight">
                                        <input type="radio" id="radio1" name="status" value="1" <?= (($agency->Status >= 1) ? 'checked="checked"' : ''); ?> />
                                        <label style="float:none;display:inline;" for="radio1">Enabled</label>
                                        <input type="radio" id="radio2" name="status" value="0" <?= (($agency->Status < 1) ? 'checked="checked"' : ''); ?>  />
                                        <label style="float:none;display:inline;" for="radio2">Disabled</label>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                            <?php } ?>
                            <div class="submitForm">
                            	<?php if(isset($agency->ID)) { ?>
                                	<input type="hidden" name="agency_id" value="<?= $agency->ID; ?>" />
                                <?php } ?>
                                <!-- <input type="submit" value="<?= (isset($agency)) ? 'Save' : 'Add'; ?>" class="<?= (isset($agency)) ? 'redBtn' : 'greenBtn'; ?>" />-->
                            </div>
                            <div class="fix"></div>
                        </div>
                    </fieldset>
                <?= form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">

	jQuery('#EditAgencyForm').submit(function(e) {
		e.preventDefault();
		var formData = jQuery(this).serialize();
		var type;
		var url;
		<?php if(isset($agency)) { ?>
			type = 1;
			url = '/admin/agency/form?aid=<?= $agency->ID; ?>';
		<?php }else { ?>
			type = 2;
			url = '/admin/agency/form';
		<?php } ?>
		jQuery.ajax({
			type:'POST',
			url:url,
			data:formData,
			success:function(code) {
				var msg;
				if(code == '1') {
					if(type == 1) {
						msg = 'The Agency was edited successfully.';
					}else {
						msg = 'The Agency was added successfully.';
					}
					jAlert(msg,'Success',function() {
						agencyListTable();
					});
				}else if(code == '3') {
					jAlert('You must first disable all groups and clients related to this agency before disabling. Please try again.','Disable Error');
				}else {
					if(type == 1) {
						msg = 'There was something wrong editing the given agency. Please try again.';
					}else {
						msg = 'Something went wrong while adding the agency you requested. Please try again.';	
					}
					jAlert(msg,'Error',function() {
						agencyListTable();
					});
				}
			}
		});
	});
	
	
	jQuery("#editAgency").dialog({
		minWidth:300,
		width:500,
		height:400,
		autoOpen: true,
		modal: true,
		buttons: [
			<?php if(isset($agency->ID)) { ?>
			{
				class:'redBtn',
				text:'Save',
				click:function() {$('.formPop').submit();}
			},
			<?php } ?>
			<?php if(!isset($agency->ID)) { ?>
			{
				class:'greenBtn',
				text:'Add',
				click:function() {$('.formPop').submit();}
			},
			<? } ?>
		] 

	});
	
</script>