<?php
	function LoadUserPhoneNumberTable($view = false,$uid) { 
		$ci =& get_instance();
		$ci->load->model('system_contacts','syscontacts');
		$user = $ci->administration->getMyUser($uid);
		$user->ContactID = $user->DirectoryID;
		$user->Address = mod_parser($user->Address);
		$user->CompanyAddress = mod_parser($user->CompanyAddress);
		$user->Email = mod_parser($user->Emails,false,true);
		$user->Phone = mod_parser($user->Phones,false,true);
		$user->Modules = ParseModulesInReadableArray($user->Modules);
		$avatar = $ci->members->get_user_avatar($user->ID);
		$user->TypeCode = substr($user->UserType,0,3);
		$user->TypeID = substr($user->UserType,4);
	 	$contactInfo = $ci->syscontacts->getUserContactInfo($uid)
		?>
		<?php if(!empty($contactInfo['phones'])) { ?>
            <div style="margin-top:10px;margin-bottom:10px;">
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
                        <?php foreach ($contactInfo['phones'] as $phone) { ?>
                            <tr>
                                <td width="10%" style="text-align:center;">
                                    <input 
                                        type="radio" 
                                        name="phone" 
                                        onclick="javascript:executePrimaryPhone('<?=$phone->PHONE_ID;?>','<?=$phone->DIRECTORY_ID;?>','<?=$phone->PHONE_Primary;?>')"
                                        value="<?= $phone->DIRECTORY_ID; ?>:<?= $phone->PHONE_Primary; ?>" 
                                        <?= ($phone->PHONE_Primary != 0) ? 'checked' : ''; ?> />
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
            <a href="javascript:addUserPhone('<?= $contactInfo['directory']->did; ?>');" class="greenBtn floatRight button" style="position:relative;z-index:1000;">Add New Phone Number</a>
        <? } ?>
	<? }
	
	function LoadUserEmailAddresses($view = false,$uid) { 
		$ci =& get_instance();
		$ci->load->model('system_contacts','syscontacts');
	 	$contactInfo = $ci->syscontacts->getUserContactInfo($uid)
		?>
		<?php if(!empty($contactInfo['emails'])) { ?>
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
                        <?php foreach ($contactInfo['emails'] as $email) { ?>
                            <tr>
                                <td width="10%" style="text-align:center;">
                                    <input 
                                        type="radio" 
                                        name="email" 
                                        onclick="javascript:executePrimaryEmail('<?=$email->EMAIL_ID;?>','<?=$email->DIRECTORY_ID;?>','<?=$email->EMAIL_Primary;?>')"
                                        value="<?= $email->DIRECTORY_ID; ?>:<?= $email->EMAIL_Primary; ?>" 
                                        <?= ($email->EMAIL_Primary != 0) ? 'checked' : ''; ?> />
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
            <a href="javascript:addUserEmail('<?= $contactInfo['directory']->did; ?>');" class="greenBtn floatRight button" style="margin-top:10px;">Add New Email</a>
        <?php } ?>
	<?php }
?>