<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editUser" title="User Information">
        <div class="uiForm">
			<style type="text/css">
				#editUser label{margin-top:0px;float:left;padding-top:12px;}
				div.formError{z-index:2000 !important;}
				#editUser .chzn-container,textarea{margin-top:12px;}
				div.tab_content div.title {border:1px solid #d5d5d5;padding:5px;margin-bottom:5px;background:url('<?= base_url(); ?>imgs/leftNavBg.png') repeat-x scroll 0 0 transparent;}
				div.tab_content div.title h5{padding-left:30px;margin-top:3px;}
				div.tab_content div.profileRight{margin-left:130px;}
				div.tab_content img.profileAvatar{float:left;border:1px solid #d5d5d5;}
				div.tab_content table.profile{margin-right:10px;border:1px solid #d5d5d5;margin-bottom:5px;}
				div.tab_content table.profile td.icon{text-align:center;width:20px;vertical-align:middle;border-right:none;float:none !important;}
				div.tab_content table.profile td.info{vertical-align:middle;float:none !important;}
				div.tab_content table.profile td.icon img {margin:7px;}
				div.tab_content table.profile td.info span {margin-right:5px;font-weight:bold;}
				div.tab_content table.profile td a {color:#2B6893;}
				div.tab_content table.profile td a:hover{color:#666;}
				div#userInfo a.actions_link{float:right;margin-top:-19px;margin-right:3px;}
				div.password_buttons{text-align:right;margin-top:10px;}
				div.password_buttons a {color:#fff;}
				div.tab_content table.mods {}
				div.tab_content table.mods td,div.tab_content table.mods tr {border:none;}
				div.tab_content table.mods {overflow:visible !important;}
				div.tab_content#modules{overflow:auto}
				ul.modulesTable{min-width:709px !important;width:100%;display:block;border-bottom:1px solid #d5d5d5;height:30px;border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;}
				ul.modulesTable li {display:inline;float:left;width:23%;padding:5px;border-right:1px solid #d5d5d5;}
				ul.modulesTable li span.check{float:left;margin-right:5px;}
				ul.modulesTable li:last-child{border-right:none;}
				ul.modulesTable.first{border-top:1px solid #d5d5d5 !important;margin-top:0 !important;}
				ul.odd{background-color:#E2E4FF;}
				div.submitForm{margin-top:10px;}
				#importGoogleAvatar{background:url('<?= base_url() . THEMEIMGS; ?>icons/color/google_icon.png') no-repeat top left;background-size:12px 12px;}
				#importGoogleAvatar span {display:none;}
				div.tab_content div.head {background:none;border:none;width:14em;margin:0 auto;}
				</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="userInfo">User Details</a></li>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                    <?php /* <li><a href="javascript:void(0);" rel="contactInfo">Contact Info</a></li> */ ?>
                    <li><a href="javascript:void(0);" rel="modules">Modules</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="userInfo" class="tab_content">
                    	<div class="title">
                        	<h5 class="iUsers2" style="min-height:20px;"><?= $user->FirstName . ' ' . $user->LastName; ?></h5>
                            <?php if(($this->user['AccessLevel'] >= 600000 || $this->user['UserID'] == $user->ID) AND !isset($view)) { ?>
                            	<a title="Edit Contact" href="javascript:editUserInfo('<?= $user->ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                
                            <?php } ?>
                        </div>
                        <div class="avatar" style="width:122px;margin-right:2px;">
                        	<img class="profileAvatar" src="<?= $avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" style="width:120px;" />
                            <?php if($this->user['UserID'] == $user->ID || $this->user['AccessLevel'] >= 600000) { ?>
                            	<div class="editButton inAvatar">
                                	<a title="Upload Custom Avatar" id="editUsersAvatar" rel="<?= $user->ID; ?>" href="javascript:void(0);"><span>Edit</span></a>
                                    <?php if(isset($_SESSION['token'])) { ?>
                                    	<a title="Import Google Avatar" id="importGoogleAvatar" rel="<?= $user->ID; ?>" href="javascript:void(0);"><span>Import Google Avatar</span></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="profileRight">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="profile">
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/user.png" alt="" /></td>
                                    <td class="info"><span>Username:</span> <a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></td>
                                </tr>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/building.png" alt="" /></td>
                                    <td class="info"><span>Company:</span> <?= $user->Dealership; ?></td>
                                </tr>
                                <?php if(!empty($user->Address)) { ?>
                                    <tr class="odd">
                                        <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/home.png" alt="" /></td>
                                        <td class="info"><span>Address:</span> <?= $user->Address['street'] . ' ' . $user->Address['city'] . ', ' . $user->Address['state'] . ' ' . $user->Address['zipcode']; ?></td> 
                                    </tr>
                                <?php } ?>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/locked2.png" alt="" /></td>
                                    <td class="info"><span>Security:</span> <?= $user->AccessName; ?></td>
                                </tr>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/dayCalendar.png" alt="" /></td>
                                    <td class="info"><span>Member Since:</span> <?= date('m/d/Y',strtotime($user->JoinDate)); ?></td>
                                </td>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/mail.png" alt="" /></td>
                                    <td class="info">
										<?php
                                            // Locate primary.
                                            foreach ($user->Email as $userEmail) foreach ($userEmail as $type => $email) {
                                                if ($email == $user->PrimaryEmailType) {
                                                    echo '<span>Primary Email:</span><a href="mailto:'.$email.'">'.$email.'</a>';
                                                    break;
                                                }
                                            }
                                        ?>
                                     </td>
                                </td>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/phone.png" alt="" /></td>
                                    <td class="info">
                                    	<?php
                                            // Locate primary.
                                            foreach ($user->Phone as $userPhone) foreach ($userPhone as $type => $phone) {
                                                if ($phone == $user->PrimaryPhoneType) {
                                                    echo '<span>Primary Phone:</span><a href="mailto:'.$phone.'">'.$phone.'</a>';
                                                    break;
                                                }
                                            }
                                        ?>
                                	</td>
                                </td>
                            </table>
                            <div class="fix"></div>
                        </div>
                        <?php if((!isset($view)) AND ($this->user['AccessLevel'] >= 600000 || $user->ID == $this->user['UserID'])) { ?>
                            <div class="password_buttons">
                            	<a href="javascript:changeMyPass('<?= $user->ID; ?>')" class="greenBtn button">Change Password</a>
                                <?php if(($this->user['AccessLevel'] >= 600000) AND ($user->ID != $this->user['UserID'])) { ?>
                                	<a href="javascript:resetMyPass('<?= $user->ID; ?>')" class="blueBtn button">Reset Password</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
    				</div>
    				<div id="websites" class="tab_content" style="display:none;">
                    	<?= $websites; ?>
    				</div>
                    <?php /*
                    <div id="contactInfo" class="tab_content" style="display:none;">
						<?= $contactInfo; ?>
                    <div class="fix"></div>
                    </div>
					*/ ?>
                    <div id="modules" class="tab_content" style="display:none;">
                    	<?php if(isset($view)) { ?>
                        	<?= ModulesToEvenlyDesignedTable($user->Modules); ?>
                            <script type="text/javascript">
								jQuery('ul.modulesTable:even').addClass('even');
								jQuery('ul.modulesTable:odd').addClass('odd');
								jQuery('ul.modulesTable:first').addClass('first');
							</script>
                        <?php }else { ?>
                        	<?= form_open('/admin/users/edit_user_modules?uid=' . $user->ID,array('name'=>'userMods','id'=>'userMods','style'=>'text-align:left;')); ?>
                    			<?= ModulesToEvenlyDesignedTableWithForm($user->Modules,$user->ID,$allMods); ?>
                                <div class="fix"></div>
                                <div class="submitForm">
                                    <input type="submit" value="submit" class="redBtn" />
                                </div>
                            <?= form_close(); ?>
                            <script type="text/javascript">
								jQuery('ul.modulesTable:even').addClass('even');
								jQuery('ul.modulesTable:odd').addClass('odd');
								jQuery('ul.modulesTable:first').addClass('first');
								jQuery('input.mod').change(function() {
									if(jQuery(this).is(':checked')) {
										jQuery(this).prev().val('1');
										jQuery(this).val('1');	
									}else {
										jQuery(this).prev().val('0');
										jQuery(this).val('0');	
									}
								});
								jQuery('#userMods').submit(function(e) {
									e.preventDefault();
									var formData = jQuery(this).serialize();
									jQuery.ajax({
										type:'POST',
										url:'/admin/users/submit_user_edit_modules?uid=<?= $user->ID; ?>',
										data:formData,
										success:function(data) {
											if(data == '1') {
												jAlert('Module edits made successfully.','Success');
											}else {
												jAlert('Something went wrong!. Try Again.','Error');	
											}
										}
									});
								});
							</script>
                        <?php } ?>
                    </div>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<div id="editAvatarPop"></div>

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


	$('#editUsersAvatar').click(function() {
		var uid = $(this).attr('rel');
		$.ajax({
			type:'GET',
			url:'/admin/users/edit_avatar_form?uid='+uid,
			success:function(data) {
				$('#editAvatarPop').html(data);	
			}
		});

	});
	
	$('#importGoogleAvatar').click(function() {
		var uid = $(this).attr('rel');
		jConfirm('Are you sure you want to import your google avatar into the system? This will overwrite any existing avatars you have set.','Import Google Avatar',function(r) {
			if(r) {
				$.ajax({
					type:'GET',
					url:'/admin/users/import_google_avatar?uid='+uid,
					success:function(data) {
						if(data) {
							load_user_table(uid);
						}
					}
				});
			}
		});
	});

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
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editUser div.tab_container div.tab_content').hide();
		$('#editUser div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		<?php if(isset($view)) { ?>
		
		<?php }else{ ?>
		
		if(activeContent == 'userInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').removeClass('hidden');
			}
		}
		
		if(activeContent == 'modules') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		<?php } ?>
	});
	
	//jQuery("div[class^='widget']").simpleTabs();
	$(".chzn-select").chosen();
	$("#editUser").dialog({
		minWidth:300,
		width:875,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close')}
			},
			<?php if(GateKeeper('Website_Add',$this->user['AccessLevel'])) { ?>
				{
					class:'greenBtn hidden addWebsiteBtn',
					text:"Add New Website",
					click:function() { addWebsiteForm('<?= ($user) ? $user->TypeID : ''; ?>','<?= $user->TypeCode; ?>')}
				},
			<?php } ?>
				{
					class:'redBtn hidden savePrimariesBtn',
					text:"Save",
					click:function() { updatePrimaries('<?= ($user) ? $user->ID : ''; ?>',$(".phonePrimary:checked").val(),$(".emailPrimary:checked").val())}
				},
		] 
	});
	
	$('div.avatar').hover(function() {
		$(this).find('.editButton').slideDown('fast');
	},function() {
		$(this).find('.editButton').slideUp('fast');
	});
</script>
