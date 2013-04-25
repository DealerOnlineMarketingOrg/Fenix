<div id="wizardPop" class="uDialog">
    <div class="dialog-message popper" id="import" title="Import DPR Lead Source Metrics">
        <div class="uiForm">
            <div class="widget" style="border-top-width:1px !important;margin-top:10px;padding-top:0;margin-bottom:10px;">
                <fieldset id="wizardPopForm" name="wizardPopForm">
                <div>
                    <span style="white-space:nowrap;font-weight:bold">First step - Enter Lead Source</span><br /><br />
                    <label style="position:relative;float:left;white-space:nowrap"><span class="req">*</span> Source</label>
                    <select class="chzn-select" style="position:relative;float:left;margin-left:10px" name="source" id="source">
                        <?php echo $sources; ?>
                    </select>
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