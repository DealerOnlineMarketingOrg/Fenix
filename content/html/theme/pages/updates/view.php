<div class="content hideTagFilter">
    <div class="title"><h5>Application Changes</h5></div>
    <div class="widget first">
        <div class="head"><h5 class="iCloud">New Features</h5></div>
        <div class="actionsBar">
        	<input type="button" class="redBtn" id="addNewChange" value="Add New Changes" />
        </div>
        <style type="text/css">
			table.tableStatic thead th.edit,table.tableStatic tbody td.edit {width:50px;text-align:center}
			table.tableStatic tbody tr td.edit {width:50px;text-align:center;}
			table.tableStatic tbody tr td.delete{width:50px;text-align:center;}
			div.ui-dialog{width:auto !important;min-width:500px;}
			div.widget{background-color:#fff;}
			.ui-dialog form {text-align:left !important;}
			#load {width:120px;height:16px; text-align:center;margin:50px auto 50px auto;}
			#load span {display:block;color:#ccc;}
			textarea {border:1px solid #d5d5d5;}
			div.rowElem{text-align:left;}
			div.widget{margin-top:0;}
			label {float:left;}
			form{margin:0;padding:0;}
			#changes{display:none;}
		</style>
        <div id="load"><img src="<?= base_url(); ?>imgs/loaders/loader2.gif" alt="" /><span>Loading Updates</span></div>
        <div id="changes"></div>
    </div>
    <div class="fix"></div>
</div>
<div class="fix"></div>
<div class="uDialog">
    <div class="dialog-message add" title="Add New Changes">
        	<div class="widget">
            	<?= form_open('add_release_change',array('id'=>'add','class'=>'')); ?>
                    <div class="rowElem">
                        <label>Area:</label>
                        <div class="formRight">
                            <select id="areaChosen" data-placeholder="Choose Area" class="styled">
                                <option value=""></option>
                                <option value="System Change">System Change</option>
                                <option value="User Login System">User Login System</option>
                                <option value="Admin Section">Admin Section</option>
                                <option value="Reports Section">Reports Section</option>
                                <option value="Advertising Section">Advertising Section</option>
                                <option value="Creative Section">Creative Section</option>
                                <option value="Merchandising Section">Merchandising Section</option>
                                <option value="Reputation Section">Reputation Section</option>
                                <option value="System Navigation">System Navigation</option>
                                <option value="Design/Template">Design or Template Change</option>
                            </select>
                        </div>
                    </div>
                    <div class="rowElem noborder">
                        <label>Description:</label>
                        <div class="formRight"><textarea id="textareaValid" class="" name="textarea" cols="45" rows="8"></textarea></div>
                        <div class="fix"></div>
                    </div>
                <?= form_close(); ?>
            </div>
    </div>
</div>
<div id="edit_changes_form"></div>

<script type="text/javascript">
	
	function editChange(id) {
		jQuery.ajax({
			type:'POST',
			url:'<?= base_url(); ?>beta/edit',
			data:{rowID:id},
			success:function(data) {
				if(data) {
					$('#edit_changes_form').html(data);	
				}else {
					jAlert('There was an error finding the change in the database. Please try again.','Error');	
				}
			}
		});
	}
	
	function deleteChange(myId) {
		jConfirm('Are you sure you want to delete this change?','Warning',function(confirmResult) {
			if(confirmResult) {
				jQuery.ajax({
					type:'POST',
					url:'<?= base_url(); ?>beta/remove',
					data:{id:myId},
					success:function(data) {
						if(data == '1') {
							refreshChanges();	
						}else {
							jAlert('There was an error removing this change. Please try again.','Error');	
						}
					}
				});
			}
		});
	}

	jQuery('#load').fadeIn('slow',function() {
		loadChanges();
	});
	
	setInterval(function() {
		check_updates();		
	},3000);

	function refreshChanges() {
		jQuery('#changes').fadeOut('slow',function() {
			jQuery('#load').fadeIn('slow',function() {
				loadChanges();
			});
		});
	}
	
	function check_updates() {
		jQuery.ajax({
			type:'GET',
			url:'<?= base_url(); ?>beta/check',
			success:function(data) {
				if(data == 1) {
					//refreshChanges();	
				}
			}
		});
	}
	
	function loadChanges() {
		jQuery('#changes').load('<?= base_url(); ?>beta/changes',function() {
			jQuery('#load').fadeOut('fast',function() {
				jQuery('#changes').fadeIn('slow');	
			});	
		});
	}
	
	jQuery('#addNewChange').click(function() {
		jQuery(".dialog-message").dialog({
			autoOpen: true,
			modal: false,
			buttons: {
				Add: function() {
					addChange();
				}
			}
		});
	});
	jQuery('#add').submit(function(e) {
		e.preventDefault();
	});	
	
	function addChange() {
		if(jQuery('#areaChosen').val() == '' || jQuery('#textareaValid').val() == '') {
			alert('Both fields are required');	
		}else {
			jQuery.ajax({
				type:'POST',
				url:'<?= base_url(); ?>add_release_change',
				data:{area:jQuery('#areaChosen').val(),desc:jQuery('#textareaValid').val()},
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
	}
	
	
</script>