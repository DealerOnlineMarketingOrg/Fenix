<div class="uDialog">
    <div class="dialog-message" id="editUserInfo" title="User Information">
        <div class="uiForm">
			<style type="text/css">
                #contactDetails input{margin-top:0 !important;}
                #editUserInfo div.widget {margin-top:0px;padding-top:0;margin-bottom:10px;}
                #contactDetails div.resetPassword{text-align:right;}
                #contactDetails div.resetPassword a.resetPassBtn{display:block; margin-top:0px; margin-right:37px; width:150px; float:right;text-align:center;color:#fff;}
				#contactDetails label{margin-top:0px;float:left;padding-top:3px;}
				div#contactDetails div.chzn-container div.chzn-search input{width:88% !important;}
            </style>
        	<div class="widget">
                <ul class="tabs">
                    <li class="activeTab"><a href="javascript:void(0);" rel="contactDetails">User Details</a></li>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <li><a href="javascript:void(0);" rel="contactInfo">Contact Info</a></li>
                </ul>
                <div class="tab_container">
            		<div id="contactDetails" class="tab_content">
						<?= form_open(base_url().'profile/update/userInfo', array('id' => 'UpdateUserInfo','class'=>'valid mainForm','style'=>'text-align:left;')); ?>
                            <fieldset>
                                <div class="rowElem noborder">
                                    <label>First Name</label>
                                    <div class="formRight">
                                        <?= form_input(array('id'=>'firstname','name'=>'firstname','value'=>$user->FirstName,'class'=>'validate[required]')); ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Last Name</label>
                                    <div class="formRight">
                                        <?= form_input(array('id'=>'lastname','name'=>'lastname','value'=>$user->LastName,'class'=>'validate[required]')); ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <div class="rowElem noborder">
                                    <label>Username</label>
                                    <div class="formRight">
                                        <?= form_input(array('id' => 'username','name'=>'username','value'=>$user->Username,'class'=>'validate[required]')); ?>
                                    </div>
                                    <div class="fix"></div>
                                </div>
                                <?php if($this->user['AccessLevel'] >= 600000) { ?>
                                    <div class="rowElem noborder">
                                        <label>Security Level</label>
                                        <?php
                                            $options = array(
                                                '1'=>'Super-Admin',
                                                '2'=>'Admin',
                                                '3'=>'Group Admin',
                                                '4'=>'Client Admin',
                                                '5'=>'Manager',
                                                '6'=>'User'
                                            );
                                        ?>
                                        <div class="formRight">
                                            <select id="contactType" class="chzn-select" style="width:26%;" name="owner_type">
                                                <option value=""></option>
                                                <?php foreach($options as $option) { $i=1;?>
                                                	<option value="<?=$i;?>"><?=$option;?></option>
                                                <?php $i++;} ?>
                                            </select>
                                        </div>
                                        <div class="fix"></div>
                                    </div>
                                <?php } ?>
                                <?php if(!empty($user->Addresses)) {
										foreach($user->Addresses as $address) {
											if($address->ADDRESS_Primary == 1) { ?>
                                            	<input type="hidden" name="address_id" value="<?=$address->ADDRESS_ID;?>" />
                                                <div class="rowElem noborder">
                                                    <label>Address</label>
                                                    <div class="formRight">
                                                        <?= form_input(array('id'=>'street','name'=>'street','value'=>$address->ADDRESS_Street)); ?>
                                                    </div>
                                                    <div class="fix"></div>
                                                </div>
                                                <div class="rowElem noborder">
                                                    <label>City</label>
                                                    <div class="formRight">
                                                        <?= form_input(array('id'=>'city','name'=>'city','value'=>$address->ADDRESS_City)); ?>
                                                    </div>
                                                    <div class="fix"></div>
                                                </div>
                                                <div class="rowElem noborder">
                                                    <label>State</label>
                                                    <div class="formRight">
                                                        <?= showStates($address->ADDRESS_State,false,'30%'); ?>
                                                    </div>
                                                    <div class="fix"></div>
                                                </div>
                                                <div class="rowElem noborder">
                                                    <label>Zip</label>
                                                    <div class="formRight">
                                                        <?= form_input(array('id'=>'zip','name'=>'zipcode','value'=>$address->ADDRESS_Zip)); ?>
                                                    </div>
                                                    <div class="fix"></div>
                                                </div>
                                           <?php }
										}
									}else { ?>
                                        <div class="rowElem noborder">
                                            <label>Address</label>
                                            <div class="formRight">
                                                <?= form_input(array('id'=>'street','name'=>'street','value'=>'')); ?>
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                        <div class="rowElem noborder">
                                            <label>City</label>
                                            <div class="formRight">
                                                <?= form_input(array('id'=>'city','name'=>'city','value'=>'')); ?>
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                        <div class="rowElem noborder">
                                            <label>State</label>
                                            <div class="formRight">
                                                <?= popUpStates(''); ?>
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                        <div class="rowElem noborder">
                                            <label>Zip</label>
                                            <div class="formRight">
                                                <?= form_input(array('id'=>'zip','name'=>'zipcode','value'=>'')); ?>
                                            </div>
                                            <div class="fix"></div>
                                        </div>
                                    <?php } ?>
                            	</fieldset>
							<?= form_close(); ?>
                            <div class="resetPassword">
                                <a href="javascript:resetPassword('<?= $user->Username;?>');" class="button blueBtn resetPassBtn">Change Password</a>
                            </div>
                        <div class="fix"></div>
                     </div>
                     <div id="websites" class="tab_content">
                     	websites
                     	<div class="fix"></div>
                     </div>
                     <div id="contactInfo" class="tab_content">
                     	contactInfo
                     	<div class="fix"></div>
                     </div>
                     <div class="fix"></div>
                 </div>
                 <div class="fix"></div>
            </div>
            <div class="fix"></div>
        </div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>
</div>

<script type="text/javascript">
	
	$('.chzn-select').focus(function() {
		$(this).open();
	});

	var $ = jQuery;
	$(".chzn-select").chosen();
	$("#editUserInfo").dialog({
		minWidth:400,
		width:800,
		height:480,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'redBtn saveContactBtn',
				text:'Save',
				click:function() {$('#UpdateUserInfo').submit();}
			},
			{
				class:'greenBtn addWebsiteBtn hidden',
				text:'Add Website',
				click:function() { }	
			}
		] 
	});
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editUserInfo div.tab_container div.tab_content').hide();
		$('#editUserInfo div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		if(activeContent == 'contactDetails') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.saveContactBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').removeClass('hidden');
			}
		}
		
		//alert(content);
	});

</script>