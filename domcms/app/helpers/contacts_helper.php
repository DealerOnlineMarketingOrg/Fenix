<?php

function ContactsMainTable($id,$type,$actions = false,$tab) {
	?><script type="text/javascript" src="<?= base_url(); ?>js/contact_popups.js"></script><?php
	$ci =& get_instance();
	$ci->load->model('system_contacts','domcontacts');
	$level = $ci->user['DropdownDefault']->LevelType;
	$userPermissionLevel = $ci->user['AccessLevel'];
	$addPriv     		 = GateKeeper('Contact_Add',$userPermissionLevel);
	$editPriv    		 = GateKeeper('Contact_Edit',$userPermissionLevel);
	$disablePriv 		 = GateKeeper('Contact_Disable_Enable',$userPermissionLevel);
	$listingPriv 		 = GateKeeper('Contact_List',$userPermissionLevel);
	
	$contacts = $ci->domcontacts->buildContactTable($id,$type);
	
	if($addPriv) { ?>
		<a href="javascript:addContact();" class="greenBtn floatRight button addButtonTop">Add New Contact</a>	
	<?php }
	
	if($contacts AND $listingPriv) { ?>
   		<table cellpadding="0" cellspacing="0" border="0" class="display contacts" id="example" width="100%;">
			<thead>
            	<tr>
                	<th style="width:50px;">Team</th>
                    <th>Type</th>
                    <th>Client/Vendor Name</th>
                    <th>Title Name</th>
                    <th>Contact Name</th>
                    <th>Primary Email</th>
                    <th>Primary Phone</th>
                    <?php if($editPriv) { ?>
                    	<th class="actionCol noSort" style="width:50px; text-align:center !important;">Actions</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            	<?php foreach($contacts as $contact) { ?>
                    <tr class="tagElement <?= $contact->ClassName; ?>">
                    	  <td class="tags"><div class="<?= $contact->ClassName; ?>">&nbsp;</div><span style="display:none;"><?= $contact->ClassName; ?></span></td>
                          <td class="alignLeft">
                          	<?php
								switch($contact->OwnerType) :
									case '1' : echo 'Client';break;
									case '2' : echo 'Vendor';break;
									case '3' : echo 'User';break;
									case '4' : echo 'General Contact';break;
									case '5' : echo 'Website';break;
									case '6' : echo 'Contact';break;
									case '7' : echo 'Agency';break;
									case '8' : echo 'Group';break;
								endswitch;
							?>
                          </td>
                          <td><?= (!empty($contact->Owner) ? $contact->Owner : '...'); ?></td>
                          <td><?= (!empty($contact->JobTitle) ? $contact->JobTitle : '...'); ?></td>
                          <td><?= $contact->FirstName . ' ' . $contact->LastName; ?></td>
                          <td class="">
                          	<?php if(!empty($contact->Emails)) { ?>
                            	<?php foreach($contact->Emails as $email) { ?>
                                	<?php if($email->EMAIL_Primary == 1) { ?>
                                    	<a href="mailto:<?= $email->EMAIL_Address; ?>"><?= $email->EMAIL_Address; ?></a>
                                    <?php } ?>
                                <?php } ?>
                            <?php }else {  ?>
                            	<span>...</span>
                            <?php } ?>
                          </td>
                          <td>
                          	<?php if(!empty($contact->Phones)) { ?>
                            	<?php foreach($contact->Phones as $phone) { ?>
                                	<?php if($phone->PHONE_Primary == 1) { ?>
                                    	<?= $phone->PHONE_Number; ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php }else { ?>
								<span>...</span>
							<?php }	 ?>
                          </td>
                          <td class="actionsCol noSort">
                            <a title="Edit Contact" href="javascript:editContact('<?= $contact->ContactID; ?>');" class="actions_link">
                                <img src="<?= base_url();?>imgs/icons/color/pencil.png" alt="" />
                            </a>
                            <a title="View Contact" href="javascript:viewContact('<?= $contact->ContactID; ?>');" class="actions_link"><img src="<?= base_url(); ?>imgs/icons/color/cards-address.png" alt="" /></a>
                          </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php }else { ?>
    	<p class="noData">No Contacts Found</p>
    <?php } 
	if($addPriv) { ?>
		<a href="javascript:addContact();" class="greenBtn floatRight button addButtonBottom">Add New Contact</a>	
	<?php }
}

?>