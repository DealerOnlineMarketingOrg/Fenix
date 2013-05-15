
<script type="text/javascript" src="<?= base_url(); ?>js/user_popups.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/websites_popups.js"></script>
<div id="loader_block">
    <div id="client_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
</div>
<style type="text/css">
    ul.modulesTable{min-width:709px !important;width:100%;display:block;border-bottom:1px solid #d5d5d5;height:30px;border-left:1px solid #d5d5d5;border-right:1px solid #d5d5d5;}
    ul.modulesTable li {display:inline;float:left;width:23%;padding:5px;border-right:1px solid #d5d5d5;}
    ul.modulesTable li span.check{float:left;margin-right:5px;}
    ul.modulesTable li:last-child{border-right:none;}
    ul.modulesTable.first{border-top:1px solid #d5d5d5 !important;margin-top:0 !important;}
    ul.odd{background-color:#f5f5f5;}
	div#loader_block{position:fixed;width:100%;height:100%;top:0;left:0;background:#fff;opacity:0.8;z-index:2000;display:none;}
    div#client_loader{position:absolute;width:16px;height:16px;top:50%;margin-top:-8px;left:50%;margin-left:-8px;}

</style>
<div class="content hideTagFilter">
    <div class="title"><h5>User Preferences</h5></div>
    <? notifyError(); ?>
    <?php include FCPATH . 'html/global/breadcrumb.php'; ?>
    <div id="profilePage">
        <div class="widget first" style="margin-top:5px !important;">
            <div class="head info">
                <h5 class="iUser"><?= $user->LastName . ', ' . $user->FirstName; ?></h5>
                <?php if($edit) { ?>
                    <div class="editButton bar"><a href="javascript:editUser('<?= $user->ID; ?>',0,'profile');"><span>Edit</span></a></div>
                <?php } ?>
            </div>
            <div class="body alignleft">
                <div class="avatar" style="border:2px solid <?= $user->Color; ?>;">
                    <img src="<?= $avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" />
                    <?php if($edit) { ?>
                        <div class="editButton inAvatar"><a href="javascript:editAvatar('<?= $user->ID; ?>');"><span>Edit</span></a></div>
                    <?php } ?>
					<?php if(isset($_SESSION['token']) AND ($user->ID == $this->user['UserID'])) { ?>
                        <a title="Import Google Avatar" id="importGoogleAvatar" rel="<?= $user->ID; ?>" href="javascript:void(0);"><span>Import Google Avatar</span></a>
                    <?php } ?>
                </div>
                <div class="profileInfo alignleft">
                    <ul>
                        <li><span>Name:</span> <?= $user->FirstName . ' ' . $user->LastName; ?></li>
                        <li><span>Username:</span> <a href="mailto:<?= $user->Username; ?>"><?= $user->Username; ?></a></li>
                        <li><span>Company:</span> <?= $user->Dealership; ?></li>
                        <?php foreach($user->Addresses as $address) { ?>
                        	<?php if($address->ADDRESS_Primary == 1) { ?>
                        		<li><span>Address:</span> <?= $address->ADDRESS_Street . ' ' . $address->ADDRESS_City . ', ' . $address->ADDRESS_State . ' ' . $address->ADDRESS_Zip; ?></li>
                            <?php } ?>
                        <?php } ?>
                        <li><span>Security:</span> <?= $user->AccessName; ?></li>
                        <li><span>Member Since:</span> <?= date('m/d/Y',strtotime($user->JoinDate)); ?></li>
                    </ul>
                </div>
                <div class="fix"></div>
            </div>
            <div class="head contactInfo">
                <h5 class="iPhone">Contact Information</h5>
            </div>
            <div class="body alignleft contactInfo">
            	<?php //print_object($user); ?>
                <ul>
                	<?php if(!empty($user->Emails)) { ?>
                    <li class="parentLabel" style="width:125px !important;"><span>Email:</span></li>
                    <li class="userContent" style="margin-left:126px !important;">
                    	<div style="overflow:auto;">
                            <table cellpadding="0" cellspacing="0" class="tableStatic" width="100%">
                                <thead>
                                    <tr>
                                        <?php foreach($user->Emails as $email) { ?>
                                            <td class="profileAssets"><?= $email->EMAIL_Type; ?></td>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php foreach($user->Emails as $email) { ?>
                                            <td class="profileAssets"><?= $email->EMAIL_Address; ?></td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(!empty($user->Phones)) { ?>
                        <li class="parentLabel" style="width:125px !important;"><span>Phone:</span></li>
                        <li class="userContent" style="margin-left:126px !important;">
                            <div style="overflow:auto;">
                                <table cellpadding="0" cellspacing="0" class="tableStatic" width="100%">
                                    <thead>
                                        <tr>
                                            <?php foreach($user->Phones as $phone) { ?>
                                                <td class="profileAssets"><?= $phone->PHONE_Type; ?></td>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php foreach($user->Phones as $phone) { ?>
                                                <td class="profileAssets"><?= $phone->PHONE_Number; ?></td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                           </div>
                       </li>
                   <?php } ?>
                   <?php if(!empty($websites)) { ?>
                    <li class="parentLabel" style="width:125px !important;"><span>Websites:</span></li>
                    <li class="userContent" style="margin-left:126px !important;">
                    	<div style="overflow:auto;">
                                <table cellpadding="0" cellspacing="0" class="tableStatic" width="100%">
                                    <thead>
                                        <tr>
                        					<?php foreach($websites as $web){ ?>
                                            	<td class="profileAssets">Website URL</td>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        	<?php foreach($websites as $web) { ?>
                                            	<td class="profileAssets"><a href="<?= $web->URL; ?>" target="_blank"><?= $web->URL; ?></a></td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            <?php } ?>
                       </div>
                   </li>
                </ul>
                <div class="fix"></div>
            </div>
            <div class="head">
                <h5 class="iRobot">Modules</h5>
                <?php if($this->user['AccessLevel'] >= 600000 && $this->user['UserID'] != $user->ID) { ?>
                    <div class="editButton contactInfo"><a href="javascript:editUserModules('<?= $user->UserID; ?>');"><span>Edit</span></a></div>
                <?php } ?>
            </div>
            <div class="body alignleft modList">
                <h6 style="color:#b55d5c !important;padding:10px 10px 10px 15px;border-bottom:1px solid #d5d5d5;">Modules Permissions List</h6>
                <div style="padding:15px;">
                    <?php ModulesToEvenlyDesignedTable($user->Modules); ?>
                    <div class="fix"></div>
                </div>
                <script type="text/javascript">
                    jQuery('ul.modulesTable:even').addClass('even');
                    jQuery('ul.modulesTable:odd').addClass('odd');
                    jQuery('ul.modulesTable:first-child').addClass('first');
                </script>
                <div class="fix"></div>
            </div>
        </div>
    </div>
    <div class="fix"></div>
</div>
<div class="fix"></div>
<div class="uDialog">
    <div class="dialog-message" id="editAvatar" title="Edit Avatar">
        <div class="uiForm">
            <p style="margin-left:15px !important;">Upload a custom Avatar to our system.</p>
            <?= form_open_multipart(base_url().'profile/avatar/upload', array('id' => 'uploadAvatar','class'=>'valid')); ?>
            	<input name="avatar" placeholder="Custom Avatar" id="fileInput" class="fileInput" type="file" size="24" style="opacity:0;" />
            <?= form_close(); ?>
        </div>
    </div>
</div>
<div id="editContactInfo">
    <div class="dialog-message" id="editUserContact" title="Edit User Contact Info">
        <div class="uiForm">
            <?= form_open(base_url().'profile/update/userContactInfo', array('id' => 'UpdateUserContactInfo','class'=>'valid'));

            foreach ($user->Emails as $email) {
                echo '<p style="margin-left:15px !important;">'.$email->EMAIL_Type.' Email</p>';
                echo form_input(array('id' => $email->EMAIL_Type.'_email','name'=>$email->EMAIL_Type.'_email','placeHolder'=>'Your '.$email->EMAIL_Type.' Email','value'=>$email->EMAIL_ADDRESS,'class'=>'validate[required]','style'=>'margin-top:5px;'));
            }

            // Locate primary.
            foreach ($user->Phones as $phone) {
                if ($phone->PHONE_Primary == 1) {
                    echo '<p style="margin-left:15px !important;">Main Phone</p>';
                    echo form_input(array('id' => $phone->PHONE_Type.'_phone','name'=>$phone->PHONE_Type.'_phone','placeHolder'=>'Your '.$phone->PHONE_Type.' Phone Number','value'=>$phone->PHONE_Number,'class'=>'validate[required]','style'=>'margin-top:5px;'));
                    break;
                }
            }
            echo '</form>'; ?>
        </div>
    </div>
</div>
<div id="editUsersForm"></div>
<div id="editUserDetailsForm"></div>
<div id="editUserModules"></div>
<div id="addWebsiteForm"></div>
<div id="addContactInfoPhonePop"></div>
<div id="editContactInfoPhonePop"></div>
<div id="addContactInfoEmailPop"></div>
<div id="editContactInfoEmailPop"></div>

<div id="UserPhonePop"></div>
<div id="UserEmailPop"></div>

<script type="text/javascript">
	function resetPassword(email) {
		jConfirm('Are you sure you want to reset this users password?', 'Confirmation Password Reset', function(r) {
			if(r) {
				jQuery.ajax({
					type:'POST',
					url:'<?= base_url(); ?>user/profile/reset_password',
					data:{userEmail:email},
					success:function(data) {
						alert(data);
						if(data != '0') {
							jAlert('You have reset the users password to '+ data);
						}else {
							jAlert('There was a problem with the password reset. Please try again.');
						}
					}
				})
			}
		});
	}

	function editAvatar(id) {
		jQuery("#editAvatar").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Upload: function() {
					jQuery('#uploadAvatar').submit();
        		}
			}
		});
	}
	
	function editInfo(uid) {
		$('#editUser').remove();
		$('#loader_block').slideDown('fast',function() {
			$.ajax({
				type:"GET",
				url:'/admin/users/edit?uid='+uid+'&modules=false',
				success:function(data) {
					if(data) {
						$('#loader_block').slideUp('fast',function() {
							$('#editUsersForm').html(data);
						});
					}else {
						jAlert('There was an error finding the user in our system. Please try again.','View Error',function() {
							$('#loader_block').slideUp('fast');	
						});
					}
				}
			});
		});
	}
	
	function editContactInfo(id) {
		jQuery("#editUserContact").dialog({
			autoOpen: true,
			modal: true,
			buttons: {
				Save: function() {
					jQuery('#UpdateUserContactInfo').submit();
        		}
			}
		});
	}
	
	function editUserModules(id) {
		alert('edit users module access');
	}
	
	jQuery('div.avatar').hover(function() {
		jQuery(this).find('.editButton').slideDown('fast');
	},function() {
		jQuery(this).find('.editButton').slideUp('fast');
	});
</script>