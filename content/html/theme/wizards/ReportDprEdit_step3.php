<div id="wizardPop" class="uDialog">
    <div class="dialog-message popper" id="import" title="Import DPR Lead metric Metrics">
        <div class="uiForm">
            <div class="widget" style="border-top-width:1px !important;margin-top:10px;padding-top:0;margin-bottom:10px;">
                <fieldset id="wizardPopForm" name="wizardPopForm">
                <div>
                    <span style="white-space:nowrap;font-weight:bold">Third step - Enter <?= $source->Name; ?> Metrics</span><br /><br />
                    
                    <label>Cost</label><input type="text" id="cost" name="cost" value="<?= $cost; ?>" />
                    
					<?php
                    $metricBlock = '
                    <div class="fix"></div>
                    <div id="metricBlock_%c%" class="noSearch" style="position:relative;float:left;width=1px;border:0px dotted #d5d5d5;margin:5px;margin-bottom:0;padding:0px">
                        <div style="position:relative;float:left;margin:5px">
                            <label style="position:relative;float:left">Metric: %name%</label><input type="hidden" id="metrics_%c%" name="metrics_%c%" value="%id%">
                            <div class="fix"></div>
                        </div>
                        <div class="fix"></div>
						<div id="total_%c%" style="float:left;margin:5px;margin-top:0">
							<input type="text" id="total_%c%" name="total_%c%" value="%value%">
						</div>
                        <div class="fix"></div>
                    </div>
                    ';
                    
					$str = '';
                    for ($i = 0; $i < count($values); $i++) {
						$str = str_replace('%c%', $i, $metricBlock);
						$str = str_replace('%name%', $names[$i], $str);
						$str = str_replace('%id%', $ids[$i], $str);
						$str = str_replace('%value%', $values[$i], $str);
						echo $str;
					}
                    ?>
                    <input id="metricCount" name="metricCount" type="hidden" value="<?= count($values); ?>" />
                </div>
                
                <div class="fix"></div>
                </fieldset>
            </div>
			<div style="float:right">
                <input id="cancel" type="button" class="greyishBtn" value="Cancel" />
                <input id="next" type="button" class="redBtn" value="Submit" />
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	function loadInit() {}
		
	function li() {
		// Don't turn off template metricBlock before document is loaded, otherwise
		//  drop down fields may not get populated.
		for (i = 0; i < 10; i++)
			jQuery('#metricBlock_' + i).css('display','none');

		// Set first metric to visible by default.
		next = parseInt(jQuery("#metricCount").val())+1;
		jQuery("#metricCount").val(next);
		jQuery("#metricBlock_0").css('display','');
		// Set first remove button to invisible.
		jQuery("#metricRemove_0").css('display','none');
	};
	
	$('[id^="Editmetric"]').click(function(e) {
		// Set next metric to visible.
		next = parseInt(jQuery("#metricCount").val())+1;
		jQuery("#metricCount").val(next);
		// Make last metric buttons invisible.
		jQuery("#metricEdit_" + (next-2)).css('display','none');
		jQuery("#metricRemove_" + (next-2)).css('display','none');
		// Make next metric block visible.
		jQuery("#metricBlock_" + (next-1)).css('display','');
		// Make next total block visible.
		jQuery("#total_" + (next-1)).css('display','');
				
		// If we're at the last metric, turn off Editmetric button.
		if (next == 10)
			jQuery("#metricEdit_9").css('display','none');
	});
	
	$('[id^="removemetric"]').click(function(e) {
		// Set last metric to invisible.
		last = parseInt(jQuery("#metricCount").val());
		jQuery("#metricCount").val(last-1);
		// Make last metric buttons visible.
		jQuery("#metricEdit_" + (last-2)).css('display','');
		jQuery("#metricRemove_" + (last-2)).css('display','');
		// Make next metric block visible.
		jQuery("#metricBlock_" + (last-1)).css('display','none');
		// Make last total block invisible.
		jQuery("#total_" + (last-1)).css('display','none');

		// If we're at the first metric, turn off removemetric button.
		if (last-2 == 0)
			jQuery("#metricRemove_0").css('display','none');
	});
	
	$(".chzn-select").chosen();
	
	function getFormObj(rootElementId, type) {
		var form = {};
		$("#" + rootElementId).find(type).each(function() {
			var name = $(this).attr('name');
			var id = $(this).attr('id');
			if (typeof name !== 'undefined' && name !== false) {
				// Get value of control.
				form[name] = $(this).val();
			}
		});
		return form;
	}
	
	function formJSON(rootElementId) {
		var form = {};
		$.extend(form, getFormObj(rootElementId, "input"));
		$.extend(form, getFormObj(rootElementId, "textarea"));
		$.extend(form, getFormObj(rootElementId, "select"));
		return JSON.stringify(form);
	}
	
	function combineJSON(JSON1, JSON2) {
		newJSON = JSON1.substring(0,JSON1.length-1) + "," + JSON2.substring(1);
		return newJSON;
	}
	
	$("#next").click(function() {
		var formData = formJSON("wizardPopForm");
		var persistent = '<?= (isset($persistent)) ? json_encode($persistent) : ""; ?>';
		newFormData = combineJSON(formData,persistent);
		var returnData = JSON.stringify({state:"success", data:newFormData});
		$("#dprEditPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#cancel").click(function() {
		var persistent = '<?= (isset($persistent)) ? json_encode($persistent) : ""; ?>';
		var returnData = JSON.stringify({state:"error", data:persistent});
		$("#dprEditPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#wizardPop").dialog({
		width:450,
		height:300,
		autoOpen: true,
		modal: true,
		title: "Edit DPR Lead Source Metrics"
	});
</script>