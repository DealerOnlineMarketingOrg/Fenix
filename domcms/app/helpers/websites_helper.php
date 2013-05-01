<?php
//WebsitesListingTable is the table on all module popups that lets you add websites to any group of data.
function WebsiteListingTable($owner_id,$owner_type,$showButtons = true) { ?>
	<script type="text/javascript" src="<?= base_url(); ?>js/websites_popups.js"></script>
    <?php
		$ci =& get_instance();
		$userPermissionLevel = $ci->user['AccessLevel'];
		$addPriv			 = GateKeeper('Website_Add',$userPermissionLevel);
		$editPriv			 = GateKeeper('Website_Edit',$userPermissionLevel);
		$disablePriv		 = GateKeeper('Website_Disable_Enable',$userPermissionLevel);
		$listingPriv		 = GateKeeper('Website_List',$userPermissionLevel);
		
		//load masterlist queries
		$ci->load->model('domwebsites');
		$websites = $ci->domwebsites->getWebsites($owner_id,$owner_type);
		if($websites AND $listingPriv) { ?>
            <table cellpadding="0" cellspacing="0" border="0" class="tableStatic websitesTable">
                <thead>
                    <tr>
                        <td>Vendor</td>
                        <td>URL</td>
                        <td>Notes</td>
                        <?php if($showButtons) { ?>
                            <td class="actionsCol">Actions</td>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($websites as $website) { ?>
                        <tr>
                            <td><?= $website->VendorName; ?></td>
                            <td><a href="<?= $website->URL; ?>" target="_blank"><?= $website->URL; ?></a></td>
                            <td><?= $website->Notes; ?></td>
                            <?php if($showButtons) { ?>
                                <td class="actionsCol noSort"><a href="javascript:editWebsite('<?=$website->ID;?>');" title="Edit Website"><img src="<?= base_url(); ?>imgs/icons/color/pencil.png" alt="" /></a>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    <? } //end listing priv 
		else { ?>
        	<p class="noData">No websites found. Please try again.</p>
    <?  } ?>	
<? } ?>