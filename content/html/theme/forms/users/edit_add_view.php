<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editUser" title="User Information">
        <div class="uiForm">
			<style type="text/css">
				#editUser label{margin-top:0px;float:left;padding-top:12px;}
				form#editWeb div.rowElem label,form#web div.rowElem label{margin-top:0!important;padding-top:0 !important;}
				div.formError{z-index:2000 !important;}
				#editUser .chzn-container,textarea{margin-top:12px;}
				#websites .chzn-container{margin-top:12px;}
				div.tab_content div.title {border:1px solid #d5d5d5;padding:5px;margin-bottom:5px;background:url('<?= base_url(); ?>imgs/leftNavBg.png') repeat-x scroll 0 0 transparent;}
				div.tab_content div.title h5{padding-left:30px;margin-top:3px;}
				div.tab_content div.profileRight{margin-left:110px;}
				div.tab_content img.profileAvatar{float:left;border:1px solid #d5d5d5;}
				div.tab_content table.profile{margin-right:10px;border:1px solid #d5d5d5;margin-bottom:5px;}
				div.tab_content table.profile td.icon{text-align:center;width:20px;vertical-align:middle;border-right:none;float:none !important;}
				div.tab_content table.profile td.info{vertical-align:middle;float:none !important;}
				div.tab_content table.profile td.icon img {margin:7px;}
				div.tab_content table.profile td.info span {margin-right:5px;font-weight:bold;}
				div.tab_content table.profile td a {color:#2B6893;}
				div.tab_content table.profile td a:hover{color:#666;}
				div#users_userInfo a.actions_link{float:right;margin-top:-19px;margin-right:3px;}
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
				ul.odd{background-color:#f5f5f5;}
				div.submitForm{margin-top:10px;}
				#importGoogleAvatar{background:url('<?= base_url(); ?>imgs/icons/color/google_icon.png') no-repeat top left;background-size:12px 12px;}
				#importGoogleAvatar span {display:none;}
				div.tab_content div.head {background:none;border:none;width:14em;margin:0 auto;}
				</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="users_userInfo">User Details</a></li>
                    <li><a href="javascript:void(0);" rel="users_websites">Websites</a></li>
                     <li><a href="javascript:void(0);" rel="users_contactInfo">Contact Info</a></li>
                    <?php if(isset($show_mods) AND $show_mods) { ?><li><a href="javascript:void(0);" rel="users_modules">Modules</a></li><?php } ?>
            	</ul>
            	<div class="tab_container">
            		<div id="users_userInfo" class="tab_content">
                    	<div class="title">
                        	<h5 class="iUser" style="min-height:20px;"><?= $user->LastName . ', ' . $user->FirstName; ?></h5>
                            <?php if(($this->user['AccessLevel'] >= 600000 || $this->user['UserID'] == $user->ID) AND !isset($view)) { ?>
                            	<a title="Edit Contact" href="javascript:editUserInfo('<?= $user->ID; ?>','<?=$page;?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                
                            <?php } ?>
                        </div>
                        <div class="avatar" style="width:102px;margin-right:2px;">
                        	<img class="profileAvatar" src="<?= $avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" style="width:100px;" />
                            <?php if($this->user['UserID'] == $user->ID AND $this->user['AccessLevel'] >= 600000) { ?>
                            	<div class="editButton inAvatar">
                        				<a href="javascript:void(0);" rel="<?=$user->ID;?>" id="custom_Avatar"><span>Edit</span></a>
                                    <?php if(isset($_SESSION['token']) AND $this->user['UserID'] == $user->ID) { ?>
                                    	<a title="Import Google Avatar" rel="<?= $user->ID; ?>" id="importGoogleAvatar" href="javascript:void(0);"><span>Import Google Avatar</span></a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="profileRight">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" class="profile">
                            	<?php if(!empty($user->Username)) { ?>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/user.png" alt="" /></td>
                                    <td class="info"><span>Username:</span> <a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></td>
                                </tr>
                                <?php } ?>
                                <?php if(!empty($user->Dealership)) { ?>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/building.png" alt="" /></td>
                                    <td class="info"><span>Company:</span> <?= $user->Dealership; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if(!empty($user->Addresses)) { ?>
                                	<tr class="odd">
                                        <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/home.png" alt="" /></td>
                                        <td class="info">
                                        <?php foreach($user->Addresses as $address) { ?>
                                        	<?php if($address->ADDRESS_Primary == 1) { ?>
                                        		<span>Address:</span> <?= $address->ADDRESS_Street . ' ' . $address->ADDRESS_City . ', ' . $address->ADDRESS_State . ' ' . $address->ADDRESS_Zip; ?>
                                        	<?php } ?>
                                        <?php } ?>
                                        </td>
                                	</tr>
                                <?php } ?>
                                <?php if(!empty($user->AccessName)) { ?>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/locked2.png" alt="" /></td>
                                    <td class="info"><span>Security:</span> <?= $user->AccessName; ?></td>
                                </tr>
                                <?php } ?>
                                <?php if(!empty($user->JoinDate)) { ?>
                                <tr class="odd">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/dayCalendar.png" alt="" /></td>
                                    <td class="info"><span>Member Since:</span> <?= date('m/d/Y',strtotime($user->JoinDate)); ?></td>
                                </td>
                                <?php } ?>
                                <?php if(!empty($user->Emails)) { ?>
                                    <tr class="even">
                                        <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/mail.png" alt="" /></td>
                                        <td class="info">
                                            <?php foreach($user->Emails as $email) { ?>
                                                <?php if($email->EMAIL_Primary == 1 ) { ?>
                                            <span>Primary Email:</span><span id="priEmail"><a href="mailto:<?= $email->EMAIL_Address; ?>"><?= $email->EMAIL_Address; ?></a></span>
                                                <?php } ?>
                                            <?php } ?>
                                         </td>
                                    </tr>
                                <?php } ?>
                                <?php if(!empty($user->Phones)) { ?>
                                    <tr class="odd">
                                        <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/phone.png" alt="" /></td>
                                        <td class="info">
                                            <?php foreach($user->Phones as $phone) { ?>
                                                <?php if($phone->PHONE_Primary == 1) { ?>
                                                    <span>Primary Phone:</span><span id="priPhone"><?= $phone->PHONE_Number; ?></span>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php }?>
                            </table>
                            <div class="fix"></div>
                        </div>
                        <?php if($this->user['AccessLevel'] >= 600000 || $user->ID == $this->user['UserID']) { ?>
                            <div class="password_buttons">
                            	<a href="javascript:changeMyPass('<?= $user->ID; ?>')" class="greenBtn button">Change Password</a>
                                <?php if(($this->user['AccessLevel'] >= 600000) AND ($user->ID != $this->user['UserID'])) { ?>
                                	<a href="javascript:resetMyPass('<?= $user->ID; ?>')" class="blueBtn button">Reset Password</a>
                                <?php } ?>
                            </div>
                        <?php } ?>
    				</div>
    				<div id="users_websites" class="tab_content" style="display:none;">
                    	<?= WebsiteListingTable($user->ID,3,true); ?>
    				</div>
                    <div id="users_contactInfo" class="tab_content" style="display:none;">
						<style type="text/css">
                            #contactInfo div.head {background:none;border:none;width:100%;margin:0 auto;}
                            #contactInfo div.head h5 {width:115px;margin:0 auto;display:block;float:none;}
                        </style>
                        <div id="phone_table">
                        	<?php // print_object($user); ?>
                            <?= LoadUserPhoneNumberTable(false,$user->ID,3,$user); ?> 
                        </div>
                        <div id="email_table">
                            <?= LoadUserEmailAddresses(false,$user->ID,3,$user); ?>
                        </div>
                    <div class="fix"></div>
                    </div>
                    <div id="users_modules" class="tab_content" style="display:none;">
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
                                    url:'<?= ((!isset($view)) ? '/admin/users/submit_user_edit_modules?uid=' . $user->ID : '/admin/users/submit_user_modules'); ?>',
                                    data:formData,
                                    success:function(data) {
                                        if(data == '1') {
                                            jAlert('Module edit made successfully.','Success');
                                        }else {
                                            jAlert('Something went wrong!. Try Again.','Error');	
                                        }
                                    }
                                });
                            });
                        </script>
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
<div class="uDialog">
    <div class="dialog-message" id="editMyAvatar" title="Edit Avatar">
        <div class="uiForm">
            <p style="margin-left:15px !important;">Upload a custom Avatar to our system.</p>
            <?= form_open_multipart(base_url().'profile/avatar/upload', array('id' => 'uploadAvatar','class'=>'valid')); ?>
            	<input name="avatar" placeholder="Custom Avatar" id="fileInput" class="fileInput" type="file" size="24" style="opacity:0;" />
            <?= form_close(); ?>
        </div>
    </div>
</div>


<script type="text/javascript">
	jQuery("select, input:checkbox, input:radio, input:file").uniform();
	function editMyAvatar(id) {
		jQuery("#editMyAvatar").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Upload: function() {
					jQuery('#uploadAvatar').submit();
        		}
			}
		});
	}
	var $ = jQuery;
	
		$('#tagChanger').change(function() {
			var ele = $(this).find('option:selected');
			var classname = ele.attr('rel');
			$('#tagThumb').attr('class',classname);
		});


	
	
	$('.change_primary_email').click(function() {
		var email = $(this).val();
		$('span#priEmail').find('a').attr('href',email);
		$('span#priEmail').find('a').text(email);
	});
	
	$('.change_primary_phone').click(function() {
		var phone = $(this).val();
		$('span#priPhone').text(phone);
	});
	
	function executePrimaryPhone(pid,did,pri) {
		$.ajax({
			type:'POST',
			data:{phone_id:pid,directory_id:did,primary:pri},
			url:'/admin/users/update_primary_phone',
			success:function(data) {
				if(data == '1') {
					jAlert('Primary Number has been changed','Success');
					
				}else {
					jAlert('There was an error changing this number to your primary number. Please try again.','Error');	
				}
			}
		});
	}
	
	function executePrimaryEmail(eid,did,pri) {
		$.ajax({
			type:'POST',
			data:{email_id:eid,directory_id:did,primary:pri},
			url:'/admin/users/update_primary_email',
			success:function(data) {
				if(data == '1') {
					jAlert('Primary Email has been changed','Success');
				}else {
					jAlert('There was an error changing this email to your primary email. Please try again.','Error');	
				}
			}
		});
	}
	
	function editUserPhone(pid) {
		$('#editPhone').remove();
		$.ajax({
			type:'GET',
			url:'/admin/users/edit_phone_form?pid='+pid,
			success:function(data) {
				if(data) {
					//alert(data);
					$('#UserPhonePop').html(data);
				}
			}
		});
	}
	
	function editUserEmail(eid) {
		$('#EditEmail').remove();
		$.ajax({
			type:'GET',
			url:'/admin/users/edit_email_form?eid='+eid,
			success:function(data) {
				if(data) {
					//alert(data);
					$('#UserEmailPop').html(data);
				}
			}
		});
	}
	
	function addUserPhone(did) {
		$('#addPhone').remove();
		$.ajax({
			type:'GET',
			url:'/admin/users/add_phone_form?did='+did,
			success:function(data) {
				if(data) {
					$('#UserPhonePop').html(data);	
				}
			}
		});
	}
	
	function addUserEmail(did) {
		$('#addEmail').remove();
		$.ajax({
			type:'GET',
			url:'/admin/users/add_email_form?did='+did,
			success:function(data) {
				if(data) {
					//alert(data);
					$('#UserEmailPop').html(data);
				}
			}
		});
	}
	
	function load_phone_table() {
		$('#addPhone').remove();
		$('#editPhone').remove();
		$('#loader_block').slideDown('fast',function() {
			$('#editPhone').remove();
			$('#phone_table').html('');
			$.ajax({
				type:'GET',
				url:'/admin/users/load_phone_table?u id=<?= $user->ID; ?>',
				success:function(data) {
					if(data) {
						$('#loader_block').slideUp('fast',function() {
							$('#phone_table').html(data);	
						});
					}
				}
			});
		})
	}
	
	function load_email_table() {
		$('#addEmail').remove();
		$('#EditEmail').remove();
		$('#loader_block').slideDown('fast',function() {
			$('#editEmail').remove();
			$('#email_table').html('');
			$.ajax({
				type:'GET',
				url:'/admin/users/load_email_table?uid=<?= $user->ID; ?>',
				success:function(data) {
					if(data) {
						$('#loader_block').slideUp('fast',function() {
							$('#email_table').html(data);	
						});
					}
				}
			});
		})
	}
	$('#custom_Avatar').click(function() {
		var id = $(this).attr('rel');
		editMyAvatar(id);
	});

	$('#importGoogleAvatar').click(function() {
		var uid = id;
		jConfirm('Are you sure you want to import your Google avatar into the system? This will overwrite any existing avatars you have set.','Import Google Avatar',function(r) {
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
	
	$('#editUser ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('#editUser ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editUser div.tab_container div.tab_content').hide();
		$('#editUser div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
		if(activeContent == 'users_userInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'users_websites') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').hasClass('hidden')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').removeClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'users_contactInfo') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
		if(activeContent == 'users_modules') {
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.addWebsiteBtn').addClass('hidden');
			}
			if($('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').is(':visible')) {
				$('.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset button.savePrimariesBtn').addClass('hidden');
			}
		}
		
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
					click:function() { addWebsiteForm('<?= $user->ID;?>',3)}
				},
			<?php } ?>
			<?php if(!isset($view)) { ?>
				{
					class:'redBtn hidden savePrimariesBtn',
					text:"Save",
					click:function() { updatePrimaries('<?= ($user) ? $user->ID : ''; ?>',$(".phonePrimary:checked").val(),$(".emailPrimary:checked").val())}
				},
			<?php } ?>
		] 
	});
	
	$('div.avatar').hover(function() {
		$(this).find('.editButton').slideDown('fast');
	},function() {
		$(this).find('.editButton').slideUp('fast');
	});
</script>
