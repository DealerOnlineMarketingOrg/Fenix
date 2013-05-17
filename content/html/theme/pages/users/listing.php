<div id="loader_block">
	<div id="client_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
</div>
<div class="content">
    <div class="title"><h5>Users</h5></div>
    <?php notifyError(); ?>
	<?php if(isset($_GET['e']) OR isset($_GET['cem'])) { ?>
        <div id="errors" class="nNote nFailure" style="margin:0;border:none;">
            <?php if(isset($_GET['e'])) { ?>
                <p><?= ((isset($_GET['e'])) ? 'There was a problem uploading your avatar. Please Try Again.' : ''); ?><a href="javascript:editUser('<?= $_GET['trigger']; ?>');" id="reloadPop">Try Again!</a></p>
            <?php }elseif(isset($_GET['cem'])) { ?>
                <p><strong>Error: </strong><?= ((isset($_GET['cem'])) ? strip_tags($_GET['cem']) : ''); ?>. <a href="javascript:editUser('<?= $_GET['trigger']; ?>');" id="reloadPop">Try Again!</a></p>
            <?php } ?>
        </div>
    <?php } ?>
    <?php include FCPATH . 'html/global/breadcrumb.php'; ?>
    <div class="table" id="dataClient">
        <div class="head" style="margin-top:5px;"><h5 class="iView">Users</h5></div>
        <div id="usersTableHolder">
            <?= UserListingTable(false,false); ?>
        </div>
    </div>
    <div class="fix"></div>
</div>
<style type="text/css">
    div#loader_block{position:fixed;width:100%;height:100%;top:0;left:0;background:#fff;opacity:0.8;z-index:2000;display:none;}
    div#client_loader{position:absolute;width:16px;height:16px;top:50%;margin-top:-8px;left:50%;margin-left:-8px;}
    div#dataClient{margin-top:1px;}
</style>
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
<div id="editUsersForm"></div> 
<div id="editUserDetailsForm"></div>
<div id="passwordForms"></div>
<div id="editAvatarForm"></div>
<script type="text/javascript" src="<?= base_url(); ?>js/user_popups.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/contactInfo_popups.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/websites_popups.js"></script>
<script type="text/javascript">
	<?php if(isset($_GET['trigger'])) { ?>
		<?php if(!isset($_GET['cem']) AND !isset($_GET['e'])) { ?>
			editUser('<?= $_GET['trigger']; ?>');
		<?php }else { ?>
			jQuery('#reloadPop').click(function() {
				editUser('<?= $_GET['trigger']; ?>');
			});
		<?php } ?>
	<?php } ?>
	
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
	
</script>