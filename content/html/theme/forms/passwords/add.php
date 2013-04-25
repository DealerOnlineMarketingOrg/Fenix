<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="addPasswords" title="Add Password">
        <div class="uiForm">
			<style type="text/css">
				label{margin-top:5px;float:left;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="passwordsInfo">Passwords Information</a></li>
            		<!-- <li><a href="#contacts">Contacts</a></li>
            		<li><a href="#users">Users</a></li>
            		<li><a href="#vendors">Vendors</a></li> -->
            	</ul>
            	<div class="tab_container">
            		<div id="passwordsInfo" class="tab_content">
		            	<?php
					        $form = array(
					            'name' => 'addPasswords',
					            'id' => 'addPasswordsForm',
					            'class' => 'mainForm addPasswords',
					            'style'=>'text-align:left !important;'
					        );
			
			        		echo form_open('/admin/passwords/process_add',$form);
			    		?>
        			<!-- Input text fields -->
        				<fieldset>
                        	<div class="rowElem noborder">
			                    <label>Type</label>
			                    <div class="formRight noSearch">
                                   	<input type="radio" name="radioType" value="old" style="float:left;margin-right:5px;margin-top:18px;" checked="checked">
                                    <div style="float:left;margin-left:5px;margin-top:10px;width:125px;">
                                        <select name="types" class="chzn-select">
                                            <option value="">Select Type</option>
                                            <?php foreach ($types as $type) { ?>
                                                <option value="<?= $type->ID; ?>"><?= $type->Name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                   	<input type="radio" name="radioType" value="new" style="margin-top:5px;margin-left:15px;margin-right:5px;">
                                    <input type="text" name="newType" style="width:15em !important">
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Vendor</label>
			                    <div class="formRight">
                                    <input type="radio" name="radioVendor" style="float:left;margin-right:5px;margin-top:18px;" value="old" checked="checked" />
                                    <div style="float:left;margin-left:5px;margin-top:10px;width:125px;" class="noSearch">
                                        <select name="vendors" class="chzn-select" style="margin-top:15px !important;">
                                            <option value="">Select Vendor</option>
                                            <?php foreach ($vendors as $vendor) { ?>
                                                <option value="<?= $vendor->ID; ?>"><?= $vendor->Name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                   	<input type="radio" name="radioVendor" value="new" style="margin-top:5px;margin-left:15px;margin-right:5px;" />
                                    <input type="text" name="newVendor" style="width:15em !important">
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>URL</label>
			                    <div class="formRight">
			                        <?=  form_input(array('name'=>'login_address','id'=>'login_address','value' => '')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span>Username</label>
			                    <div class="formRight">
			                        <?=  form_input(array('class'=>'required validate[required]','name'=>'username','id'=>'username','value'=>'')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Password</label>
			                    <div class="formRight">
			                        <?=  form_input(array('name'=>'password','id'=>'password','value'=>'')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
			                        <?=  form_input(array('name'=>'notes','id'=>'notes','value'=>'')); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                 
			                <div class="submitForm">
			               		<input type="hidden" name="ClientID" value="<?=  $clientID; ?>" />
			                </div>
			                <div class="fix"></div>
			           </fieldset>
    				<?= form_close(); ?>
    				</div>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;

	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#addPasswords div.tab_container div.tab_content').hide();
		$('#addPasswords div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	
	$('form#addPasswordsForm').submit(function(e){
		e.preventDefault();
		var formData = $(this).serialize();
				
		jQuery.ajax({
			url:'/admin/passwords/process_add',
			type:'POST',
			data:formData,
			success:function(data) {
				if(data == '1') {
					jAlert('Password added successfully!','Add Confirmation',function() {
						passListTable();
						jQuery('#addPasswords').dialog('close');
					});
				}else {
					jAlert('There was an error adding the password. Please try again.','Password Add Failed');
				}
			}
		});
	});
	
	$(".chzn-select").chosen();
	$("#addPasswords").dialog({
		minWidth:800,
		height:700,
		autoOpen: true,
		modal: false,
		buttons: [
			{
				class:'greenBtn',
				text:'Add',
				click:function() {$('#addPasswordsForm').submit()}	
			},
		]
	});
</script>
