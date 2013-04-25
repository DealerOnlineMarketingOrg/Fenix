<div class="uDialog" style="text-align:left;">
    <div class="dialog-message" id="input" title="Input">
        <div class="uiForm">
			<style type="text/css">
				label{margin-top:5px;float:left;}
			</style>
            <div class="widget" style="margin:0;padding:0;">
            	<?php /* This block is for tabs if needed:
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="selectInfo">Input</a></li>
            		<!-- <li><a href="#contacts">Contacts</a></li>
            		<li><a href="#users">Users</a></li>
            		<li><a href="#vendors">Vendors</a></li> -->
            	</ul>
				*/ ?>
            	<div class="<?php // Uncomment for tabs: tab_container ?>">
            		<div id="dialogInputInfo" class="tab_content">
                    
                    	<?php switch($options['type']) {
                        	case 'dualList': ?>
                            <div class="rowElem noborder dualBoxes">
                                <div class="floatleft" style="width:45%">
                                    <input type="text" id="box1Filter" class="boxFilter" placeholder="Filter entries..." /><button type="button" id="box1Clear" class="dualBtn fltr">x</button><br />
                                    
                                    <select id="box1View" multiple="multiple" class="multiple" style="height:300px;">
                                        <?php foreach ($items as $item) {
                                        	echo '<option value="' . $item['value'] . '">' . $item['text'] . '</option>';
                                        } ?>
                                    </select>
                                    <br/>
                                    <span id="box1Counter" class="countLabel"></span>
                                    
                                    <div class="displayNone"><select id="box1Storage"></select></div>
                                </div>
                                    
                                <div class="dualControl">
                                    <button id="to2" type="button" class="dualBtn mr5 mb15">&nbsp;&gt;&nbsp;</button><br />
                                    <button id="to1" type="button" class="dualBtn mr5">&nbsp;&lt;&nbsp;</button>
                                </div>
                                    
                                <div class="floatright" style="width:45%">
                                    <input type="text" id="box2Filter" class="boxFilter" placeholder="Filter entries..." /><button type="button" id="box2Clear" class="dualBtn fltr">x</button><br />
                                    <select id="box2View" multiple="multiple" class="multiple" style="height:300px;"></select><br/>
                                    <span id="box2Counter" class="countLabel"></span>
                                    
                                    <div class="displayNone"><select id="box2Storage"></select></div>
                                </div>
                                <div class="fix"></div>
                            </div>
                            <div class="submitForm">
                                <input id="dialogDualListAcceptBtn" type="button" value="Accept" class="greenBtn" />
                            </div>
                            <div class="fix"></div>
                        <?php 	break;
                        } ?>

    				</div>
                    <div id="loader" style="display:none;"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    				<div class="fix"></div>
                    <?php
					//Exists only to trigger change event when final return data has been sent.
					//Picked up in the ajax loader.
					?>
                    <input id="returnTrigger" type="hidden" value="" />
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	// These functions are for tabbed popups.
	jQuery('ul.tabs li a').live('click',function() {
		//remove all activetabs
		jQuery('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		jQuery(this).parent().addClass('activeTab');
		var content = 'div#' + jQuery(this).attr('rel');
		//alert(content);
		jQuery('#input div.tab_container div.tab_content').hide();
		jQuery('#input div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	// For chosen drop-down boxes.
	jQuery(".chzn-select").chosen();
	// For dual-list selection boxes.
	jQuery.configureBoxes();
	
	jQuery("#dialogDualListAcceptBtn").click(function() {
		acceptInput();
		closeInput();
	});
	
	function acceptInput() {
		// Gather info for return value.
		// Get the values of all rows in the 'to' select box.
		var values = new Array();
		$("#box2View").find('option').each(function() {
			values.push($(this).text())
			values.push($(this).val());
		});
		var returnData = JSON.stringify({state:'success', data:values});
		// Set return value as json object.
		$("<? echo $options['anchorDiv']; ?>").attr('return',returnData);
	}
	
	function cancelInput() {
		// We only have an empty return value for cancel.
		var returnData = JSON.stringify({state:'error', data:''});
		$("<? echo $options['anchorDiv']; ?>").attr('return',returnData);
	}
	
	function closeInput() {
		// Trigger change event to tell ajax loader we're ready with the return data.
		$("input#returnTrigger").val('done');
		// Stop dialog.
		$("#input").dialog("close");
		// Clear and destroy dialog.
		$("#input").empty();
		$("#input").remove();
	}
	
	jQuery("#input").dialog({
		<?php switch($options['type']) {
			case 'dualList': ?>
			minWidth:800,
			height:580,
		<?php	break;
		} ?>
		autoOpen: true,
		modal: false,
		/*beforeClose: function(event,ui) {
			// Respond to the close 'x' button
			if (event.originalEvent != undefined && event.originalEvent.srcElement.innerText == "close") {
				cancelInput();
				closeInput();
			}
			return true;
		},*/
		buttons: {
			Cancel:function() {
				cancelInput();
				closeInput();
			},
		}
	});
</script>
