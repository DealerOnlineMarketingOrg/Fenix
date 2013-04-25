<div class="uDialog">
    <div class="dialog-message" id="editUserInfo" title="Edit User Details">
        <div class="uiForm">
			<style type="text/css">
				#editUserInfo label{margin-top:0px;float:left;padding-top:12px;}
				div#editUserInfo div.widget {margin-top:-10px;padding-top:15px;margin-bottom:10px;}
				div.formError{z-index:2000 !important;}
				#editUserInfo .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget">
            	<?= form_open('/admin/users/process_user_details',array('id'=>'userDetailsForm','class'=>'valid','style'=>'text-align:left;')); ?>
                	<fieldset>
                    	<div class="rowElem noborder">
                        	<label><span class="req">*</span> Name</label>
                            <div class="formRight">
                            	<table width="100%" cellspacing="0" cellpadding="0">
                                	<tr>
                                    	<td style="width:49%;padding-right:5px;"><?= form_input(array('name'=>'first_name','id'=>'first_name','class'=>'validate[required]','value'=>$user->FirstName)); ?></td>
                                        <td style="width:49%;"><?= form_input(array('name'=>'last_name','id'=>'last_name','class'=>'validate[required]','value'=>$user->LastName)); ?></td>
                                    </tr>
                                    <tr>
                                    	<td style="width:50%;"><p class="formNote">First Name</p></td>
                                        <td style="width:50%;"><p class="formNote">Last Name</p></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                        	<label><span class="req">*</span> Username</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'username','id'=>'username','class'=>'validate[required,custom[email]]','value'=>$user->Username)); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder noSearch">
                        	<label><span class="req">*</span> Company</label>
                            <div class="formRight">
                            	<select name="company" class="chzn-select" id="company">
                                	<option value=""></option>
                                	<?php foreach($dealerships as $dealership) { ?>
                                    	<option <?= (($dealership->ClientID == $user->ClientID) ? 'selected="selected"' : ''); ?> value="<?= $dealership->ClientID; ?>"><?= $dealership->Dealership; ?></option>
                                    <?php } ?>
                                </select>
                            </div> 
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                        	<label>Address</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'street','id'=>'street','class'=>'','value'=>$user->Address['street'])); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                        	<label>City</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'city','id'=>'city','class'=>'','value'=>$user->Address['city'])); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder noSearch">
                        	<label>State</label>
                            <div class="formRight">
                            	<?= showStates($user->Address['state']); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                        	<label>Zip</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'zipcode','id'=>'zipcode','class'=>'','value'=>$user->Address['zipcode'])); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="DirectoryID" value="<?= $user->DirectoryID; ?>" />
                <?= form_close(); ?>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;

	//reinitialize the validation plugin
	$("#valid,.valid").validationEngine({promptPosition : "right", scroll: true});
	
	$('#userDetailsForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/users/submit_user_details_form?uid=<?= $user->ID; ?>',
			success:function(resp) {
				if(resp == '1') {
					jAlert('The users info has been updated.','Success',function() {
						$('#editUserInfo').dialog('close');	
						load_user_table();
					});
				}else if(resp == '2') {
					jAlert('The users info was updated, however, the username was not! Please check to see if you have the correct permissions to change usernames.','Success Error',function() {
						$('#editUserInfo').dialog('close');
						load_user_table();
					});
				}else if(resp == '3') {
					jAlert('The username was updated however, the users details did not update correctly. Please try again!.','Success Error',function() {
						$('#editUserInfo').dialog('close');
						load_user_table();
					});
				}else{
					jAlert('There was a problem updating the users information. Please try again.','Error',function() {
						$('#editUserInfo').dialog('close');
						load_user_table();
					});
				}
			}
		});
	});
	
	$(".chzn-select").chosen();
	
	$("#editUserInfo").dialog({
		minWidth:300,
		width:650,
		height:465,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'redBtn',
				text:'Save',
				click:function() {$('#userDetailsForm').submit();}
			},
		] 
	});
</script>
