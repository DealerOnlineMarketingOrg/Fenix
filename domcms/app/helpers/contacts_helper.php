<?php

function ContactsMainTable($actions_off = false,$tab = false, $id = false) {
	?><script type="text/javascript" src="<?= base_url(); ?>js/contact_popups.js"></script><?php
	$ci =& get_instance();
	$ci->load->model('system_contacts','domcontacts');
	$level = $ci->user['DropdownDefault']->LevelType;
	$userPermissionLevel = $ci->user['AccessLevel'];
	$addPriv     		 = GateKeeper('Contact_Add',$userPermissionLevel);
	$editPriv    		 = GateKeeper('Contact_Edit',$userPermissionLevel);
	$disablePriv 		 = GateKeeper('Contact_Disable_Enable',$userPermissionLevel);
	$listingPriv 		 = GateKeeper('Contact_List',$userPermissionLevel);
	
	$contacts = $ci->domcontacts->buildContactTable($id);
	//print_object($contacts);
	if($addPriv AND !$tab) { ?>
		<a href="javascript:addContact();" class="greenBtn floatRight button addButtonTop">Add New Contact</a>	
	<?php }
	
	if($contacts AND $listingPriv) { ?>
   		<table cellpadding="0" cellspacing="0" border="0" class="<?= ((!$tab) ? 'display' : 'tableStatic'); ?> contacts" id="example" width="100%;" <?= (($tab) ? 'style="border:1px solid #d5d5d5;"' : ''); ?>>
			<thead>
            	<tr>
                	<?php if(!$tab) { ?>
                        <th style="width:50px;">Team</th>
                        <th>Type</th>
                        <th>Client/Vendor Name</th>
                        <th>Title Name</th>
                        <th>Contact Name</th>
                        <th>Primary Email</th>
                        <th>Primary Phone</th>
                        <?php if(!$actions_off) { ?>
                        	<th class="actionCol noSort" style="width:50px; text-align:center !important;">Actions</th>
                        <?php } ?>
                    <?php }else { ?>
                    	<td>Title Name</td>
                        <td>Contact Name</td>
                        <td>Primary Email</td>
                        <td>Primary Phone</td>
                        <?php if(!$actions_off) { ?>
                        	<td class="actionCol noSort" style="width:50px; text-align:center !important;">Actions</td>
                        <?php } ?>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            	<?php foreach($contacts as $contact) { ?>
                    <tr <?= ((!$tab) ? 'class="tagElement' .  $contact->ClassName . '"' : ''); ?>>
                    	  <?php if(!$tab) { ?><td class="tags"><div class="<?= $contact->ClassName; ?>">&nbsp;</div><span style="display:none;"><?= $contact->ClassName; ?></span></td><?php } ?>
                          <?php if(!$tab) { ?><td class="alignLeft">
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
                          <?php } ?>
                          <?php if(!$tab) { ?><td><?= (!empty($contact->Owner) ? $contact->Owner : '...'); ?></td><?php } ?>
                          <td><?= (!empty($contact->JobTitle) ? $contact->JobTitle : '...'); ?></td>
                          <td><a href="javascript:viewContact('<?= $contact->ContactID; ?>');"><?= $contact->FirstName . ' ' . $contact->LastName; ?></a></td>
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
                          <?php if(!$actions_off) { ?>
                              <td class="actionsCol noSort">
                                <?php if($editPriv) { ?><a title="Edit Contact" href="javascript:editContact('<?= $contact->ContactID; ?>');" class="actions_link">
                                    <img src="<?= base_url();?>imgs/icons/color/pencil.png" alt="" />
                                </a><?php } ?>
                                <a title="View Contact" href="javascript:viewContact('<?= $contact->ContactID; ?>');" class="actions_link"><img src="<?= base_url(); ?>imgs/icons/color/cards-address.png" alt="" /></a>
                              </td>
                          <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php }else { ?>
    	<p class="noData">No Contacts Found</p>
    <?php } 
	if($addPriv AND !$actions_off AND !$tab) { ?>
		<a href="javascript:addContact();" class="greenBtn floatRight button addButtonBottom">Add New Contact</a>	
	<?php }
}

?>