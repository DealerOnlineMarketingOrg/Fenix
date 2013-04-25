<div class="uDialog">
    <div class="dialog-message popper" id="editContactInfoPhone" title="<?= (($page == 'edit') ? 'Edit' : 'Add'); ?> Phone">
        <div class="uiForm">
            <div class="widget" style="margin-top:-10px;padding-top:0;margin-bottom:10px;">
                <div class="tab_container">
            		<div id="contactPhone" class="tab_content">
						<?php
                            if($page == 'edit') :
                                echo form_open('/admin/contactInfo/editPhone',array('id'=>'editContactInfoPhoneForm','class' => 'validate mainForm formPop','style' => 'text-align:left'));
                            else :
                                echo form_open('/admin/contactInfo/addPhone',array('id'=>'editContactInfoPhoneForm','class'=>'validate mainForm formPop','style' => 'text-align:left'));				
                            endif;
                        ?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Type</label>
                                    <div class="formRight searchDrop">
                                        <select id="contactPhoneType" class="chzn-select validate[required]" style="width:350px" name="type">
                                            <option value="cell" <?= ($contact) ? (($type == 'cell') ? 'selected="selected"' : '') : ''; ?>>Cell</option>
                                            <option value="home" <?= ($contact) ? (($type == 'home') ? 'selected="selected"' : '') : ''; ?>>Home</option>
                                            <option value="work" <?= ($contact) ?
												(($type == 'work' || $type == 'main') ? 'selected="selected"' : '') : 'selected="selected"'; ?>>Work</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="fix"></div>
                                <div class="rowElem noborder">
                                    <label><span class="req">*</span> Phone</label>
                                    <div class="formRight">
                                        <div style="position:relative;float:left"><?= form_input(array('class'=>'maskPhoneExt validate[required]','name'=>'phone','id'=>'phone','value'=>($type) ? $value : '','style'=>'width:25em !important','placeholder'=>'Enter Phone Number')); ?>
                                        <span class="formNote">(999) 999-9999 x99999</span></div>
                                    </div>
                                </div>
                                <div class="fix"></div>
                                <div class="submitForm">
                                    <input type="hidden" name="contact_id" value="<?= (($contact) ? $contact->ContactID : ''); ?>" />
                                    <input type="hidden" name="old" value="<?= (($type) ? $type.':'.$value : ''); ?>" />
                                </div>
                            </fieldset>
                        <?= form_close(); ?>
                        <div class="fix"></div>
                    </div>
				</div> 
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
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	
	$('#contactType').change(function(e) {
		$('#contactParentClient').css('display',(($(this).val()) == 'CID' ? '' : 'none'));
		$('#contactParentVendor').css('display',(($(this).val()) == 'VID' ? '' : 'none'));
		$('#contactParentGeneral').css('display',(($(this).val()) == 'GID' ? '' : 'none'));
	});
	
	$('#editContactInfoPhoneForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		savePhone('<?= $page; ?>','<?= $contact->TypeID; ?>','<?= $contact->TypeCode; ?>',formData);
	});
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editContactInfoPhone div.tab_container div.tab_content').hide();
		$('#editContactInfoPhone div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		<?php if(isset($view)) { ?>
		
		<?php }else { ?>
		
		if(activeContent == 'contactDetails') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactInfoBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactInfoBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactInfoBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactInfoBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
		}
		
		if(activeContent == 'contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactInfoBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactInfoBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
		}
		<?php } ?>
		
		//alert(content);
	});
	
	<?php if($page != 'edit') { ?>
	$("#editContactInfoPhonePop").dialog({
		minWidth:600,
		width:600,
		minHeight:300,
		height:300,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close');}
			},
				{
					class:'greenBtn addPhoneBtn',
					text:"Add",
					click:function() { $("#editContactInfoPhoneForm").submit(); }
				},
		]
	});
	<?php }else { ?>
	$("#editContactInfoPhonePop").dialog({
		minWidth:600,
		width:600,
		minHeight:300,
		height:300,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close');}
			},
				{
					class:'redBtn savePhoneBtn',
					text:"Save",
					click:function() { $('#editContactInfoPhoneForm').submit(); }
				},
		]
	});
	<?php } ?>
</script>
