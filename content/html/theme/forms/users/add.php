<div class="uDialog">
    <div class="dialog-message" id="addUser" title="User Information">
        <div class="uiForm">
			<style type="text/css">
				#addUser label{margin-top:0px;float:left;padding-top:12px;}
				#addUser div.widget {margin-top:-10px;padding-top:0;margin-bottom:10px;}
				div.formError{z-index:2000 !important;}
				#addUser .chzn-container,textarea{margin-top:12px;}
			</style>
            <div class="widget">
            	<?= form_open('/admin/users/submit_add_user',array('name'=>'addUserForm','id'=>'addUserForm','class'=>'valid','style'=>'text-align:left')); ?>
                	<fieldset>
                    	<div class="rowElem noborder">
                        	<label><span class="req">*</span> Username</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'username','id'=>'username','class'=>'validate[required,custom[email]]')); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem noborder">
                            <label><span class="req">*</span> Tag</label>
                            <div class="formRight noSearch">
                                <div class="tagThumb" style="width:25px;border:1px solid #d5d5d5;margin-right:5px;float:left;margin-top:12px;">
                                    <div class="black_team" style="float:left;">&nbsp;</div>
                                </div>
                                <select name="team" data-placeholder="Select Team Color" class="tagChanger chzn-select validate[required]" tabindex="1">
                                    <option value=""></option>
                                    <?php foreach($tags as $tag) : ?>
                                        <option rel="<?= $tag->ClassName; ?>" value="<?= $tag->ID; ?>"><?= $tag->Name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    	<div class="rowElem noborder noSearch">
                        	<label><span class="req">*</span> Client</label>
                            <div class="formRight">
                            	<select name="dealership" data-placeholder="Select Parent Client" class="chzn-select validate[required]" id="dealerships" style="min-width:200px;">
                                	<option value=""></option>
                                    <?php foreach($dealerships as $dealership) { ?>
                                    	<option value="<?= $dealership->ClientID; ?>"><?= $dealership->Dealership; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                    	<div class="rowElem noborder noSearch">
                        	<label><span class="req">*</span> Security Level</label>
                            <div class="formRight">
                            	<select name="security_level" class="chzn-select validate[required]" id="security_level" style="min-width:200px;">
                                	<option value=""></option>
                                    <?php foreach($SecurityLevels as $levels) { ?>
                                    	<option value="<?= $levels->ID; ?>"><?= $levels->Name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="fix"></div>
                        </div>
                    	<div class="rowElem noborder">
                        	<label>Address</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'street','id'=>'street','class'=>'')); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                    	<div class="rowElem noborder">
                        	<label>City</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'city','id'=>'city','class'=>'')); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                    	<div class="rowElem noborder noSearch">
                        	<label>State</label>
                            <div class="formRight">
                            	<?= showStates(); ?>
                            	
                            </div>
                            <div class="fix"></div>
                        </div>
                    	<div class="rowElem noborder">
                        	<label>Zip</label>
                            <div class="formRight">
                            	<?= form_input(array('name'=>'zipcode','id'=>'zip','class'=>'validate[maxSize[6],custom[number]]','max-length'=>'5','style'=>'width:75px !important;')); ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="fix"></div>
                    </fieldset>
                <?= form_close(); ?>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">

	var $ = jQuery;
	
	$('.tagChanger').change(function() {
		var ele = $(this).find('option:selected');
		var classname = ele.attr('rel');
		$('.tagThumb div').attr('class',classname);
	});

	//reinitialize the validation plugin
	jQuery("#addUserForm").validationEngine({promptPosition : "right", scroll: true});
	
	$('#addUserForm').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
			$.ajax({
				type:'POST',
				data:formData,
				url:'/admin/users/submit_add_user',
				success:function(resp) {
					load_users_table();
				}
			});
	});
	
	$(".chzn-select").chosen();
	
	$("#addUser").dialog({
		minWidth:300,
		width:875,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greenBtn',
				text:'Add',
				click:function() {$('#addUserForm').submit();}
			},
		] 
	});
</script>
