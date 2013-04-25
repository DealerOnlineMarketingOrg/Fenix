<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editClient" title="<?= ((isset($view) AND isset($client->Status)) ? 'View ' . $client->Name . ' Details' : ((isset($client->Status)) ? 'Edit ' . $client->Name . ' Details' : 'Add New Client')); ?>">
        <div class="uiForm">
			<style type="text/css">
				#editClient label{margin-top:0px;float:left;padding-top:5px;}
				div.formError{z-index:2000 !important;}
				#editClient .chzn-container{margin-top:5px;}
				#editClient div.tags .chzn-container{margin-top:12px;}
				#editClient .mainForm input[type="text"], #editClient .mainForm textarea, #editGroup .mainForm input[type="password"], #editClient .mainForm input[type="text"], #editClient .mainForm textarea, #editClient .mainForm input[type="password"] {margin:0;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Client Details</a></li>
                    <?php if(isset($websites)) { ?>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <?php } 
					
					if(isset($contacts)) { ?>
            		<li><a href="javascript:void(0);" rel="contactInfo">Contacts</a></li>
                    <?php } ?>
            	</ul>
            	<div class="tab_container">
            		<div id="clientInfo" class="tab_content">
		            	<?php
					        $form = array(
					            'name' => (($client) ? 'editClient' : 'addClient'),
					            'id' => 'clientForm',
					            'class' => 'mainForm ' . (($client) ? 'editClient' : 'addClient'),
					            'style'=>'text-align:left !important;'
					        );
							
			        		echo (!isset($client)) ? form_open('/admin/clients/form_processor/clients/add',$form) : form_open('/admin/clients/form_processor/clients/edit',$form);
			    		?>
        				<!-- Input text fields -->
        				<fieldset>
                        	<div class="rowElem noborder tags">
                            	<label><span class="req">*</span> Tag</label>
                                <div class="formRight noSearch">
                                	<div style="width:25px;border:1px solid #d5d5d5;margin-right:5px;float:left;margin-top:12px;">
                                		<div id="tagThumb" class="<?= ((isset($client->Status)) ? $client->ClassName : 'black_team'); ?>" style="float:left;">&nbsp;</div>
                                    </div>
                                    <?php if(!isset($view) AND !isset($client->Status)) { ?>
                                        <select id="tagChanger" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="1">
                                            <option value=""></option>
                                            <?php foreach($tags as $tag) : ?>
                                                <option rel="<?= $tag->ClassName; ?>" value="<?= $tag->ID; ?>"><?= $tag->Name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php }elseif(!isset($view) AND isset($client->Status)) { ?>
                                    <select id="tagChanger" name="tags" data-placeholder="Link Tags To Client..." class="chzn-select validate[required]" tabindex="1">
			                            <option value=""></option>
			                            <?php foreach($tags as $tag) : ?>
			                            	<option rel="<?= $tag->ClassName; ?>" <?= ($client) ? (($tag->ID == $client->Tag) ? 'selected="selected"' : '') : ''; ?> value="<?= $tag->ID; ?>"><?= $tag->Name; ?></option>
			                            <?php endforeach; ?>
			                        </select>
                                    <?php } ?>
                                </div>
                            </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Client Code</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) { ?>
			                        <?= form_input(array('disabled'=>'disabled','maxlength'=>'4','class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>($client) ? $client->Code : '')); ?>
                                    <?php }else { ?>
									<?= form_input(array('maxlength'=>'4','class'=>'required validate[required]','name'=>'ClientCode','id'=>'code','value'=>($client) ? $client->Code : '')); ?>
                                    <?php } ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Client Name</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) { 
										echo form_input(array('disabled'=>'disabled','class'=>'required validate[required]','name'=>'ClientName','id'=>'name','value'=>($client) ? $client->Name : ''));
									}else {
                                		echo form_input(array('class'=>'required validate[required]','name'=>'ClientName','id'=>'name','value'=>($client) ? $client->Name : '')); 
									}?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) { 
										echo form_input(array('disabled'=>'disabled','name'=>'street','id'=>'address','value'=> ((isset($client->Address['street'])) ? $client->Address['street'] : '')));
									}else {
                                		echo form_input(array('name'=>'street','id'=>'address','value'=> ((isset($client->Address['street'])) ? $client->Address['street'] : '')));
									} ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>City</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) {
										echo form_input(array('disabled'=>'disabled','name'=>'city','id'=>'city','value' => ((isset($client->Address['city'])) ? $client->Address['city'] : '')));
									}else {
                                		echo form_input(array('name'=>'city','id'=>'city','value' => ((isset($client->Address['city'])) ? $client->Address['city'] : ''))); 
									}?>			
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>State</label>
			                    <div class="formRight searchDrop noSearch" style="text-align:left;">
                                	<?php if(isset($view)) {
										echo showStates(((isset($client->Address['state'])) ? $client->Address['state'] : false),true);
									}else {
                                		echo showStates(((isset($client->Address['state'])) ? $client->Address['state'] : false));
									} ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Zip Code</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) {
										echo form_input(array('disabled'=>'disabled','maxlength'=>'6','name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : '')));
									}else {
			                        	echo form_input(array('maxlength'=>'6','name'=>'zip','id'=>'zip','value'=>((isset($client->Address['zipcode'])) ? $client->Address['zipcode'] : '')));
									}?>
			                    </div>
			                    <div class="fix"></div>
			
			                </div>
			                <div class="rowElem noborder">
			                    <label><span class="req">*</span> Phone Number</label>
			                    <div class="formRight">
                                	<?php if (isset($client->Phone)) {
										// Locate primary.
										foreach ($client->Phone as $clientPhone) foreach ($clientPhone as $type => $phone) {
											if ($phone == $client->PrimaryPhoneType) {
												if(isset($view)) { 
													echo form_input(array('disabled'=>'disabled','class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value'=>$phone));
												}else {
													echo form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value'=>$phone)); 
												}
												break;
											}
										}
									} else {
										echo form_input(array('class'=>'maskPhone required validate[required,custom[phone]]','name'=>'phone','id'=>'phone','value'=>''));
									}?>
			                        <span class="formNote">(999) 999-9999</span>
			                    </div>
			                    <div class="fix"></div>
			                </div>
                            <div class="rowElem noborder">
                            	<label>Member Of</label>
                                <div class="formRight noSearch">
                                	<?php if(isset($view)) { ?>
                                		<select class="chzn-select validate[required]" name="Group" disabled style="width:200px;">
                                    <? }else { ?>
                                    	<select class="chzn-select validate[required]" name="Group" <?= ($this->user['AccessLevel'] >= 500000) ? '' : 'disabled'; ?> style="width:200px;">
                                    <? } ?>
                                    	<option value=""></option>
                                        <?php foreach($groups as $group) { ?>
                                        	<?php 
												if(isset($client->GroupID)) {
													if($client->GroupID == $group->GroupID) { ?>
														<option selected="selected" value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
													<? }else { ?>
														<option value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
													<? }
												}else { ?>
                                                	<?php if(isset($this->user['DropdownDefault']->SelectedGroup) && $this->user['DropdownDefault']->SelectedGroup != '') { ?>
                                                    <option <?= ($this->user['DropdownDefault']->SelectedGroup == $group->GroupID) ? 'selected="selected"' : ''; ?> value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
                                                    <?php }else { ?>
													<option value="<?=$group->GroupID; ?>"><?=$group->Name; ?></option>
                                                    <?php } ?>
												<? }?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <?php if(isset($view) and isset($client->Status)) { ?>
                            <div class="rowElem noborder">
                            	<label>Member Since</label>
                                <div class="formRight">
                                	<?= form_input(array('disabled'=>'disabled','value'=>date('m/d/Y', strtotime($client->JoinDate)))); ?>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <?php } ?>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) {
										echo form_textarea(array('disabled'=>'disabled','rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>($client) ? $client->Description : ''));
									}else {
                                		echo form_textarea(array('rows'=>'8','cols'=>'','class'=>'auto','name'=>'Notes','id'=>'notes','value'=>($client) ? $client->Description : '')); 
									}?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Google Review</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) { ?>
                                    	<?php if($client->Reviews['Google'] != '') { ?>
                                    	<a style="margin-top:12px;display:block;float:left;width:100%;" href="<?= $client->Reviews['Google']; ?>" target="_blank"><?= $client->Reviews['Google']; ?></a>
                                        <?php }else { ?>
                                        <p>No Google review page found for this client.</p>
										<?php } ?>
                                    <?php }else { 
			                    		echo form_input(array('class'=>'validate[custum[url]]','name'=>'GoogleReviewURL','id'=>'GoogleReview','value'=>($client) ? ((isset($client->Reviews['Google'])) ? $client->Reviews['Google'] : '') : ''));
			                        	echo form_hidden('GoogleID',(isset($client->Reviews['GoogleID'])) ? $client->Reviews['GoogleID'] : 0); 
									?>
			                        	<p class="formNote">The Web Address for the clients Google Review Page</p>
                                    <?php } ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Yelp Review</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) { ?>
                                    	<?php if($client->Reviews['Yelp'] != '') { ?>
                                    	<a style="margin-top:12px;display:block;float:left;width:100%;" href="<?= $client->Reviews['Yelp']; ?>" target="_blank"><?= $client->Reviews['Yelp']; ?></a>
                                        <?php }else { ?>
                                        	<p>No Yelp review page found for this client.</p>
                                        <?php } ?>
                                    <?php }else {
			                    		echo form_input(array('class'=>'validate[custom[url]]','name'=>'YelpReviewURL','id'=>'YelpReview','value'=>($client) ? ((isset($client->Reviews['Yelp'])) ? $client->Reviews['Yelp'] : '') : ''));
			                        	echo form_hidden('YelpID',(isset($client->Reviews['YelpID'])) ? $client->Reviews['YelpID'] : 0); ?>
			                        	<p class="formNote">The Web Address for the clients Yelp Review Page</p>
                                    <?php } ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                	<label>Yahoo Review</label>
			                    <div class="formRight">
                                	<?php if(isset($view)) { ?>
                                    	<?php if($client->Reviews['Yahoo'] != '') { ?>
                                    		<a style="margin-top:12px;display:block;float:left;width:100%;" href="<?= $client->Reviews['Yahoo']; ?>" target="_blank"><?= $client->Reviews['Yahoo']; ?></a>
                                        <?php }else { ?>
                                        	<p>No Yahoo review page found for this client. </p>
                                        <?php } ?>
                                    <?php }else { 
			                    	 echo form_input(array('class'=>'validate[custom[url]]','name'=>'YahooReviewURL','id'=>'YahooReview','value'=>($client) ? ((isset($client->Reviews['Yahoo'])) ? $client->Reviews['Yahoo'] : '') : ''));
			                         echo form_hidden('YahooID',(isset($client->Reviews['YahooID'])) ? $client->Reviews['YahooID'] : 0); ?>
			                        <p class="formNote">The Web Address for the clients Yahoo Review Page</p>
                                    <?php } ?>
			                    </div>
			                </div>
                            <?php if(!isset($view)) { ?>
                                <div class="rowElem noborder">
                                    <label>Client Status</label>
                                    <div class="formRight" style="text-align:left;padding-top:5px;">
                                        <?php if(isset($view)) { ?>
                                            <?php echo (($client->Status >= 1) ? 'Enabled' : 'Disabled'); ?>
                                        <?php }else { ?>
                                        <?php if(isset($client->Status)) { ?>
                                            <input type="radio" id="radio1" name="status" value="1" <?= (($client->Status >= 1) ? 'checked="checked"' : ''); ?> />
                                            <label style="float:none;display:inline;" for="radio1">Enable</label>
                                            <input type="radio" id="radio2" name="status" value="0" <?= (($client->Status < 1) ? 'checked="checked"' : ''); ?>  />
                                            <label style="float:none;display:inline;" for="radio2">Disable</label>
                                            <?php }else { ?>
                                            <input type="radio" id="radio1" name="status" value="1" checked="checked" />
                                            <label style="float:none;display:inline;" for="radio1">Enable</label>
                                            <input type="radio" id="radio2" name="status" value="0" />
                                            <label style="float:none;display:inline;" for="radio2">Disable</label>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                            <?php } ?>
			                <div class="fix"></div>
                            <?php if(isset($view)) { ?>
                            
                            <?php }else { ?>
			                <div class="submitForm">
                            	<?php if($client) { ?>
			               			<input type="hidden" name="ClientID" value="<?= $client->ClientID; ?>" />
                                <?php } ?>
			                    <!-- <input type="submit" value="<?= ((isset($client->Status)) ? 'Save' : 'Add'); ?>" class="<?= ((isset($client->Status)) ? 'redBtn' : 'greenBtn'); ?>" />-->
			                </div> 
                            <?php } ?>
			                <div class="fix"></div>
			           </fieldset>
    				<?= form_close(); ?>
    				</div>
    				<div id="websites" class="tab_content" style="display:none;">
                    	<?= (isset($websites)) ? $websites : ''; ?>
    				</div>
                    <div id="contactInfo" class="tab_content" style="display:none;">
                    	<?= (isset($contactInfo)) ? $contactInfo : ''; ?>
                    </div>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
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

<script type="text/javascript">

	var $ = jQuery;
	
	<?php if(isset($view)) { ?>
	
	<?php }else { ?>
		$('#tagChanger').change(function() {
			var ele = $(this).find('option:selected');
			var classname = ele.attr('rel');
			$('#tagThumb').attr('class',classname);
		});
	<?php } ?>

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
	jQuery("#clientForm").validationEngine({promptPosition : "right", scroll: true});
	
	$('#clientForm').submit(function(e) {
		e.preventDefault();
		<?php if(isset($view)) { ?>
		<?php }else { ?>
			var formData = $(this).serialize();
			var formType = '<?= ($client) ? 'edit' : 'add'; ?>';
			$.ajax({
				type:'POST',
				data:formData,
				url:'<?php echo (($client) ? '/admin/clients/form?cid=' . $client->ClientID : '/admin/clients/form?gid=' . $this->user['DropdownDefault']->SelectedGroup); ?>',
				success:function(resp) {
					if(resp == '1') {
						if(formType == 'edit') {
							jAlert('The Client was edited successfully','Success',function() {
								clientListTable();
								writeDealerDropdown();
							});
						}else {
							jAlert('The Client was added successfully','Success',function() {
								clientListTable();
								writeDealerDropdown();
							});
						}
					}else {
						if(formType == 'edit') {
							jAlert('Something went wrong while editing the Client. Please try again.','Error');
						}else {
							jAlert('Something went wrong while adding the Client. Please try again.','Error');
						}
					}
				}
			});
		<?php } ?>
	});

	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editClient div.tab_container div.tab_content').hide();
		$('#editClient div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		<?php if(isset($view)) { ?>
		
		<?php }else { ?>
		
		if(activeContent == 'contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addClientBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addClientBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveClientBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveClientBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addClientBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addClientBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveClientBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveClientBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'clientInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveClientBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveClientBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addClientBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addClientBtn').removeClass('hidden');
			}
			
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
		}
		<?php } ?>
		
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	$(".chzn-select").chosen();
	$("#editClient").dialog({
		minWidth:300,
		width:875,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			<?php if(isset($view) AND isset($client->Status)) { ?>
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close')}	
			},
			<?php } ?>
			<?php if(isset($client->Status) AND !isset($view)) { ?>
			{
				class:'redBtn saveClientBtn',
				text:'Save',
				click:function() {$('#clientForm').submit()}	
			},
			<?php }; ?>
			<?php if(!isset($client->Status)) { ?>
			{
				class:'greenBtn addClientBtn',
				text:'Add',
				click:function() {$('#clientForm').submit()}
			},
			<?php }; ?>
			<?php if(GateKeeper('Website_Add',$this->user['AccessLevel'])) { ?>
				<?php if(isset($client->Status)) { ?>
				{
					class:'greenBtn hidden addWebsiteBtn',
					text:"Add New Website",
					click:function() { addWebsiteForm('<?= ($client) ? $client->TypeID : ''; ?>','<?= $client->TypeCode; ?>')}
				}, <?php } ?>
			<?php } ?>
			<?php if(GateKeeper('Contact_Add',$this->user['AccessLevel'])) { ?>
				<?php if(isset($client->Status)) { ?>
				{
					class:'greenBtn hidden addContactBtn',
					text:"Add New Contact",
					click:function() { addContact()}
				}, <?php } ?>
			<?php } ?>

		]
	});
</script>
