<div class="uDialog">
    <div id="edit_change" class="dialog-message edit" title="Edit Change">
        	<div class="widget">
            	<?= form_open('edit_release_change',array('id'=>'edit','class'=>'')); ?>
                    <div class="rowElem">
                    	<input type="hidden" id="changeIDEdit" value="<?= $change->id; ?>" />
                        <label>Area:</label>
                        <div class="formRight">
                            <select id="areaChosenEdit" data-placeholder="Choose Area" class="styled">
                                <option value=""></option>
                                <option <?= (($change->area == 'System Change') ? 'selected="selected"' : ''); ?> value="System Change">System Change</option>
                                <option <?= (($change->area == 'User Login System') ? 'selected="selected"' : ''); ?> value="User Login System">User Login System</option>
                                <option <?= (($change->area == 'Admin Section') ? 'selected="selected"' : ''); ?> value="Admin Section">Admin Section</option>
                                <option <?= (($change->area == 'Reports Section') ? 'selected="selected"' : ''); ?> value="Reports Section">Reports Section</option>
                                <option <?= (($change->area == 'Advertising Section') ? 'selected="selected"' : ''); ?> value="Advertising Section">Advertising Section</option>
                                <option <?= (($change->area == 'Creative Section') ? 'selected="selected"' : ''); ?> value="Creative Section">Creative Section</option>
                                <option <?= (($change->area == 'Merchandising Section') ? 'selected="selected"' : ''); ?> value="Merchandising Section">Merchandising Section</option>
                                <option <?= (($change->area == 'Reputation Section') ? 'selected="selected"' : ''); ?> value="Reputation Section">Reputation Section</option>
                                <option <?= (($change->area == 'System Navigation') ? 'selected="selected"' : ''); ?> value="System Navigation">System Navigation</option>
                                <option <?= (($change->area == 'Design/Template') ? 'selected="selected"' : ''); ?> value="Design/Template">Design or Template Change</option>
                            </select>
                        </div>
                    </div>
                    <div class="rowElem noborder">
                        <label>Description:</label>
                        <div class="formRight"><textarea id="textareaValidEdit" class="" name="textarea" cols="45" rows="8"><?= $change->desc; ?></textarea></div>
                        <div class="fix"></div>
                    </div>
                <?= form_close(); ?>
            </div>
    </div>
</div>
<script type="text/javascript">
	jQuery("#edit_change").dialog({
		autoOpen: true,
		modal: true,
		buttons: {
			Submit: function() {
				updateChange();
			}
		}
	});
	function updateChange() {
			jQuery.ajax({
				type:'POST',
				url:'<?= base_url(); ?>beta/update',
				data:{id:jQuery('#changeIDEdit').val(),area:jQuery('#areaChosenEdit').val(),desc:jQuery('#textareaValidEdit').val()},
				success:function(data) {
					if(data == '0') {
						jAlert('There was an error adding the change to the database. Please try again.','Error');
					}else {
						jAlert('Success! The change was added successfully!','Success');
						jQuery('.dialog-message').dialog('close');
						refreshChanges();
					}
				}
			});
	}

</script>