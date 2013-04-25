<div id="wizardPop" class="uDialog">
    <div class="dialog-message popper" id="import" title="Import DPR Lead metric Metrics">
        <div class="uiForm">
            <div class="widget" style="border-top-width:1px !important;margin-top:10px;padding-top:0;margin-bottom:10px;">
            <?php
       			$startDate = '1/1/'.dateToYear(date('m/j/Y'));
                $endDate = '12/1/'.dateToYear(date('m/j/Y'));
                $lowerStart = '1/1/2000';
                $upperStart = $endDate;
                $lowerEnd = $startDate;
                $upperEnd = '12/1/' . date('Y');
            ?>
                <fieldset id="wizardPopForm" name="wizardPopForm">
                <div>
                    <span style="white-space:nowrap;font-weight:bold">Second step - Enter Month/Year</span><br /><br />
                    
                    <div style="float:left">
                        <label style="margin-right:0">Month/Year:</label>
                        <div class="fix"></div>
                        <?php dateButtons($lowerStart,$upperStart,$startDate,'metricsStart'); ?>
                    </div>
                    <div class="fix"></div>
                </div>
                
                <div class="fix"></div>
                </fieldset>
            </div>
            <div style="float:right">
                <input id="cancel" type="button" class="greyishBtn" value="Cancel" />
                <input id="next" type="button" class="redBtn" value="Next" />
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	function loadInit() {};
	
	jQuery('[id^="addmetric"]').click(function(e) {
		// Set next metric to visible.
		next = parseInt(jQuery("#metricCount").val())+1;
		jQuery("#metricCount").val(next);
		// Make last add-metric button invisible.
		jQuery("#metricCopy_" + (next-2)).css('display','none');
		jQuery("#metricBlock_" + (next-1)).css('display','');
		
		// If we're at the last metric, turn off addmetric button.
		if (next == 10)
			jQuery("#metricCopy_9").css('display','none');

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
		$("#dprAddPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#cancel").click(function() {
		var persistent = '<?= (isset($persistent)) ? json_encode($persistent) : ""; ?>';
		var returnData = JSON.stringify({state:"error", data:persistent});
		$("#dprAddPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#wizardPop").dialog({
		width:450,
		height:300,
		autoOpen: true,
		modal: true,
		title: "Add DPR Lead Source Metrics"
	});
</script>