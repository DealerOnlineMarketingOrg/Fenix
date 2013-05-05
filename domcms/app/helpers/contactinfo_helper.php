<?php
	function LoadUserPhoneNumberTable($view = false,$uid) { 
		$ci =& get_instance();
		$ci->load->model('system_contacts','syscontacts');
		$user = $ci->administration->getMyUser($uid);
		?>
		<?php if(!empty($user->Phones)) { ?>
            <div style="margin-top:10px;margin-bottom:30px;">
                <div class="head"><h5 class="iPhone">Phone Numbers</h5></div>
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
                                        onclick="javascript:executePrimaryPhone('<?=$phone->PHONE_ID;?>','<?=$phone->OWNER_ID;?>','<?=$phone->PHONE_Primary;?>')"
                                        value="<?= $phone->PHONE_Number; ?>" 
                                        <?= ($phone->PHONE_Primary != 0) ? 'checked' : ''; ?> class="change_primary_phone" />
                                </td>
                                <td width="80%"><?= $phone->PHONE_Number; ?></td>
                                <td width="10%" class="actionsCol"><a title="Edit User Phone" href="javascript:editUserPhone('<?= $phone->PHONE_ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a></td>
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
        <?php if(!$view) {?>
            <a href="javascript:addUserPhone('<?= $user->ID; ?>');" class="greenBtn floatRight button" style="margin-top:-20px;">Add New Phone Number</a>
        <? } ?>
	<? }
	
	function LoadUserEmailAddresses($view = false,$uid) { 
		$ci =& get_instance();
		$ci->load->model('system_contacts','syscontacts');
		$user = $ci->administration->getMyUser($uid);
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
                                        onclick="javascript:executePrimaryEmail('<?=$email->EMAIL_ID;?>','<?=$email->OWNER_ID;?>','<?=$email->EMAIL_Primary;?>')"
                                        value="<?= $email->EMAIL_Address; ?>" 
                                        <?= ($email->EMAIL_Primary != 0) ? 'checked' : ''; ?> class="change_primary_email" />
                                </td>
                                <td width="80%"><?= $email->EMAIL_Address; ?></td>
                                <td width="10%" style="text-align:center;"><a title="Edit User Email" href="javascript:editUserEmail('<?= $email->EMAIL_ID; ?>');" class="actions_link"><img src="<?= base_url() . THEMEIMGS; ?>icons/color/pencil.png" alt="" /></a></td>
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
            <a href="javascript:addUserEmail('<?= $user->ID; ?>');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Email</a>
        <?php } ?>
	<?php }
?>