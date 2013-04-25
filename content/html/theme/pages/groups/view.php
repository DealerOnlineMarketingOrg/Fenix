<style type="text/css">
	 .ui-dialog #viewGroup input[type="text"]  {margin:0}
</style>

<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="viewGroup" title="Group Information">
        <div class="uiForm">
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="groupInfo">Group Details</a></li>
                    <li><a href="javascript:void(0);" rel="groupClients">Clients</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="groupInfo" class="tab_content">
        				<fieldset>
			                <div class="rowElem noborder">
			                    <label>Group Name</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>$group->Name)); ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Member Of</label>
			                    <div class="formRight">
                                	<?= form_input(array('disabled'=>'disabled','value'=>$group->AgencyName)); ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Member Since</label>
			                    <div class="formRight"><?= form_input(array('disabled'=>'disabled','value'=>date('m-d-Y',strtotime($group->JoinDate)))); ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<textarea name="textarea" cols="80" rows="8" disabled><?= $group->Description; ?></textarea>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			           </fieldset>
    				</div>
                    <div id="groupClients" class="tab_content" style="display:none;padding:0;">
                    	<?= GroupsClientTable($group->GroupId); ?>
                    </div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery('ul.tabs li a').live('click',function() {
		//remove all activetabs
		jQuery('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		jQuery(this).parent().addClass('activeTab');
		var content = 'div#' + jQuery(this).attr('rel');
		//alert(content);
		jQuery('#viewGroup div.tab_container div.tab_content').hide();
		jQuery('#viewGroup div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery("#viewGroup").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close')}
			},
		] 
	});
</script>

