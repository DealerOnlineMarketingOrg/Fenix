<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="editUser" title="User Information">
        <div class="uiForm">
			<style type="text/css">
				#editUser label{margin-top:0px;float:left;padding-top:12px;}
				form#editWeb div.rowElem label,form#web div.rowElem label{margin-top:0!important;padding-top:0 !important;}
				div.formError{z-index:2000 !important;}
				#editUser .chzn-container,textarea{margin-top:12px;}
				#websites .chzn-container{margin-top:12px;}
				div.tab_content div.title {
					border:1px solid #d5d5d5;
					padding:5px;
					margin-bottom:5px;
					background:url('<?= base_url(); ?>imgs/leftNavBg.png') repeat-x scroll 0 0 transparent;
				}
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
				#importGoogleAvatar{background:url('<?= base_url(); ?>imgs/icons/color/google_icon.png') no-repeat top left;background-size:12px 12px;}
				#importGoogleAvatar span {display:none;}
				div.tab_content div.head {background:none;border:none;width:14em;margin:0 auto;}
			</style>
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="userInfo">User Details</a></li>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
                     <li><a href="javascript:void(0);" rel="contactInfo">Contact Info</a></li>
                    <li><a href="javascript:void(0);" rel="modules">Modules</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="userInfo" class="tab_content">
                    	<div class="title">
                        	<h5 class="iUsers2" style="min-height:20px;"><?= $user->FirstName . ' ' . $user->LastName; ?></h5>
                        </div>
                        <div class="avatar" style="width:122px;margin-right:2px;">
                        	<img class="profileAvatar" src="<?= $avatar; ?>" alt="<?= $user->FirstName . ' ' . $user->LastName; ?>" style="width:120px;" />
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
                                </tr>
                                <?php } ?>
                                <?php if(!empty($user->Emails)) { ?>
                                <tr class="even">
                                    <td class="icon"><img src="<?= base_url(); ?>imgs/icons/dark/mail.png" alt="" /></td>
                                    <td class="info">
                                    	<?php foreach($user->Emails as $email) { ?>
                                        	<?php if($email->EMAIL_Primary == 1 ) { ?>
                                        		<span>Primary Email:</span><span id="priEmail"><a href="mailto:'<?= $email->EMAIL_Address; ?>"><?= $email->EMAIL_Address; ?></a></span>
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
                                <?php } ?>
                            </table>
                            <div class="fix"></div>
                        </div>
    				</div>
    				<div id="websites" class="tab_content" style="display:none;">
                    	<?= WebsiteListingTable($user->ID,3,false); ?>
    				</div>
                    <div id="contactInfo" class="tab_content" style="display:none;">
						<style type="text/css">
                            #contactInfo div.head {background:none;border:none;width:100%;margin:0 auto;}
                            #contactInfo div.head h5 {width:115px;margin:0 auto;display:block;float:none;}
                        </style>
                            <?php if(!empty($user->Phones)) { ?>
                                <div style="margin-top:10px;margin-bottom:20px;">
                                    <div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
                                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic" style="border:1px solid #d5d5d5;">
                                        <thead>
                                            <tr>
                                                <td width="10%" style="text-align:left;padding-left:10px;">Type</td>
                                                <td width="90%" colspan="2" style="text-align:left;padding-left:10px;">Phone Number</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($user->Phones as $phone) { ?>
                                                <tr>
                                                    <td width="10%"><?= $phone->PHONE_Type; ?></td>
                                                    <td width="80%"><?= $phone->PHONE_Number; ?></td>
                                                    <td width="10%"><?= ($phone->PHONE_Primary != 0) ? 'Primary' : ''; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php }else { ?>
                                <div style="margin-top:10px;margin-bottom:0px;">
                                    <div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
                                    <p class="noData" style="text-align:center;">No phone numbers found for this user.</p>
                                </div>
                            <?php } ?>
                            <?php if(!empty($user->Emails)) { ?>
                                <div style="margin-top:10px;margin-bottom:60px;">
                                    <div class="head"><h5 class="iPhone">Email Addresses</h5></div>
                                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic" style="border:1px solid #d5d5d5;">
                                        <thead>
                                            <tr>
                                                <td width="10%" style="text-align:left;padding-left:10px;">Type</td>
                                                <td width="90%" colspan="2" style="text-align:left;padding-left:10px;">Email Address</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($user->Emails as $email) { ?>
                                                <tr>
                                                    <td width="10%"><?= $email->EMAIL_Type; ?></td>
                                                    <td width="80%"><?= $email->EMAIL_Address; ?></td>
                                                    <td width="10%"><?= ($email->EMAIL_Primary != 0) ? 'Primary' : ''; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php }else { ?>
                                <div style="margin-top:10px;margin-bottom:60px;">
                                    <div class="head"><h5 class="iPhone">Email Addressess</h5></div>
                                    <p class="noData" style="text-align:center">No email addresses found for this user.</p>
                                </div>
                            <?php } ?>
                    <div class="fix"></div>
                    </div>
                    <div id="modules" class="tab_content" style="display:none;">
						<?= ModulesToEvenlyDesignedTable($user->Modules); ?>
                        <script type="text/javascript">
                            jQuery('ul.modulesTable:even').addClass('even');
                            jQuery('ul.modulesTable:odd').addClass('odd');
                            jQuery('ul.modulesTable:first').addClass('first');
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

<div id="UserPhonePop"></div>
<div id="UserEmailPop"></div>

<script type="text/javascript">

	var $ = jQuery;
	$(".maskDate").mask("99/99/9999",{completed:function(){alert("Callback when completed");}});
	$(".maskPhone").mask("(999) 999-9999");
	$(".maskPhoneExt").mask("(999) 999-9999? x99999");
	
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#editUser div.tab_container div.tab_content').hide();
		$('#editUser div.tab_container').find(content).css({'display':'block'});
		
		var activeContent = $(this).attr('rel');
		
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
		] 
	});
	
	$('div.avatar').hover(function() {
		$(this).find('.editButton').slideDown('fast');
	},function() {
		$(this).find('.editButton').slideUp('fast');
	});
</script>
