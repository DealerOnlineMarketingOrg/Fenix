<?php
	function LoadUserPhoneNumberTable($view = false,$uid,$type = 3, $usr = false) { ?>
		<script type="text/javascript" src="<?= base_url(); ?>js/contact_info_phone.js"></script>
	<?php
		$ci =& get_instance();
		if(!$usr) {
			$ci->load->model('system_contacts','syscontacts');
			if($type != 3) {
				$user = $ci->administration->getMyUser($uid);
			}else {
				$user = $ci->syscontacts->preparePopupInfo($uid);	
			}
		}else {
			$user = $usr;	
		}
		?>
		<?php if(!empty($user->Phones)) { ?>
            <div style="margin-top:10px;margin-bottom:30px;">
                <div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
                <div id="phone_numbers_table">
                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic" style="border:1px solid #d5d5d5;">
                        <thead>
                            <tr>
                                <td width="10%" style="text-align:center;padding-left:10px;">Primary</td>
                                <td style="text-align:left;padding-left:10px;">Phone Number</td>
                                <td class="actionsCol" style="text-align:center;">Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user->Phones as $phone) { ?>
                                <tr>
                                    <td width="10%" style="text-align:center;">
                                        <input 
                                            type="radio" 
                                            name="phone" 
                                            onclick="javascript:primaryPhone('<?=$phone->PHONE_ID;?>','<?=$phone->OWNER_ID;?>','<?=$phone->PHONE_Primary;?>')"
                                            value="<?= $phone->PHONE_Number; ?>" 
                                            <?= ($phone->PHONE_Primary != 0) ? 'checked' : ''; ?> class="change_primary_phone" />
                                    </td>
                                    <td width="80%"><?= $phone->PHONE_Number; ?></td>
                                    <td width="10%" class="actionsCol">
                                            <a title="Edit Contact Phone" href="javascript:editPhone('<?= $phone->PHONE_ID; ?>');" class="actions_link">
                                            <img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a>
                                        </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <div class="fix"></div>
                </div>
            </div>
        <?php }else { ?>
            <div style="margin-top:10px;margin-bottom:0px;">
                <div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
                <p class="noData" style="text-align:center;">No phone numbers found for this user.</p>
            </div>
        <?php } ?>
        <?php if(!$view) {?>
            <a href="javascript:addPhone('<?= $user->DirectoryID; ?>','<?= $type; ?>');" class="greenBtn floatRight button" style="margin-top:-20px;">Add New Phone Number</a>
        <? } ?>
        
	<? }
	
	function LoadUserEmailAddresses($view = false,$uid,$type = 3,$usr = false) { ?>
		<script type="text/javascript" src="<?= base_url(); ?>js/contact_info_email.js"></script>
    <?
		$ci =& get_instance();
		if(!$usr) { 
			$ci->load->model('system_contacts','syscontacts');
			if($type != 3) {
				$user = $ci->syscontacts->preparePopupInfo($uid);	
			}else { 
				$user = $ci->administration->getMyUser($uid);
			}
		}else {
			$user = $usr;	
		}
		?>
		<?php if(!empty($user->Emails)) { ?>
            <div style="margin-top:10px;margin-bottom:0px;">
                <div class="head"><h5 class="iPhone">Email Addresses</h5></div>
                <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic" style="border:1px solid #d5d5d5;">
                    <thead>
                        <tr>
                            <td width="10%" style="text-align:center;">Primary</td>
                            <td width="80%" style="text-align:left;padding-left:10px;">Email Address</td>
                            <td width="10%" style="text-align:center;">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user->Emails as $email) { ?>
                            <tr>
                                <td width="10%" style="text-align:center;">
                                    <input 
                                        type="radio" 
                                        name="email" 
                                        onclick="javascript:primaryEmail('<?=$email->EMAIL_ID;?>','<?=$email->OWNER_ID;?>','<?=$email->EMAIL_Primary;?>')"
                                        value="<?= $email->EMAIL_Address; ?>" 
                                        <?= ($email->EMAIL_Primary != 0) ? 'checked' : ''; ?> class="change_primary_email" />
                                </td>
                                <td width="80%"><?= $email->EMAIL_Address; ?></td>
                                <td width="10%" style="text-align:center;"><a title="Edit User Email" href="javascript:editEmail('<?= $email->EMAIL_ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a></td>
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
        <?php if(!$view) { ?>
            <a href="javascript:addEmail('<?=$uid;?>','<?= $type; ?>');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Email</a>
        <?php } ?>
	<?php }
?>