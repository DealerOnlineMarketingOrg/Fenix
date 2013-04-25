<div class="uDialog">
    <div class="dialog-message" id="editAvatar" title="Edit Avatar">
        <div class="uiForm">
            <p style="margin-left:15px !important;">Upload a custom Avatar to our system.</p>
            <?= form_open_multipart('/admin/users/upload_avatar?uid=' . $user_id, array('id' => 'uploadAvatar','class'=>'valid')); ?>
            	<input name="avatar" placeholder="Custom Avatar" id="fileInput" class="fileInput" type="file" size="24" style="opacity:0;" />
                <input type="hidden" name="user_id" value="<?= $user_id; ?>" />
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	jQuery("#uploadAvatar").validationEngine({promptPosition : "right", scroll: true});
	jQuery("select, input:checkbox, input:radio, input:file").uniform();
	jQuery("#editAvatar").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Upload: function() {
				jQuery('#uploadAvatar').submit();
				jQuery('#editAvatar').dialog('close');
			}
		}
	});
</script>