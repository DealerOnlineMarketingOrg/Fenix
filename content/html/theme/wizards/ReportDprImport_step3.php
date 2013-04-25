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
                    <span style="white-space:nowrap;font-weight:bold">Third step - Enter Date Range</span><br /><br />
                    
                    <div style="float:left">
                        <label style="margin-right:0">Starting Date:</label>
                        <div class="fix"></div>
                        <?php dateButtons($lowerStart,$upperStart,$startDate,'metricsStart'); ?>
                    </div>
                    <div class="fix">
                        <label style="margin-right:0">Ending Date:</label>
                        <div class="fix"></div>
                        <?php dateButtons($lowerEnd,$upperEnd,$endDate,'metricsEnd'); ?>
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
	
	var today = new Date();
	var rangeSelectorStartMonth = '';
	var rangeSelectorStartYear = '';
	var rangeSelectorEndMonth = '';
	var rangeSelectorEndYear = '';
	var rangeStartMonth = 1;
	var rangeStartYear = 2000;
	var rangeEndMonth = 12;
	var rangeEndYear = 2013;
	var thisStartMonth = 1;
	var thisStartYear = today.getFullYear();
	var thisEndMonth = today.getMonth()+1;
	var thisEndYear = today.getFullYear();
	
	function loadInit() {
		dateRangeInitialize("#metricsStartMonth", "#metricsStartYear",
							"#metricsEndMonth", "#metricsEndYear",
							 rangeStartMonth, rangeStartYear, rangeEndMonth, rangeEndYear);
		dateRangeSet(thisStartMonth, false,
					 thisStartYear, false,
					 thisEndMonth, false,
					 thisEndYear, false);
	};
	
	function dateRangeInitialize(startMonthSelector, startYearSelector,
								 endMonthSelector, endYearSelector,
								 startMonth, startYear, endMonth, endYear) {
		
		rangeSelectorStartMonth = startMonthSelector;
		rangeSelectorStartYear = startYearSelector;
		rangeSelectorEndMonth = endMonthSelector;
		rangeSelectorEndYear = endYearSelector;
		
		rangeStartMonth = startMonth;
		rangeStartYear = startYear;
		rangeEndMonth = endMonth;
		rangeEndYear = endYear;
		
		dateRangeSetMonths(rangeSelectorStartMonth, startMonth, endMonth);
		dateRangeSetYears(rangeSelectorStartYear, startYear, endYear);
		dateRangeSetMonths(rangeSelectorEndMonth, startMonth, endMonth);
		dateRangeSetYears(rangeSelectorEndYear, startYear, endYear);
	}
	
	function dateRangeSetMonths(monthSelector, start, end) {
		months = {
			'1':'January','2':'Febuary','3':'March','4':'April','5':'May','6':'June',
			'7':'July','8':'August','9':'September','10':'October','11':'November','12':'December',
		};

		chosenClear(monthSelector);
		for (var val in months) {
			if (val >= start && val <= end)
				chosenAdd(monthSelector, months[val], val);
		}
	}
	
	function dateRangeSetYears(yearSelector, start, end) {
		chosenClear(yearSelector);
		for (year = start; year <= end; year++) {
			if (year >= start && year <= end)
				chosenAdd(yearSelector, year, year);
		}
	}
	
	// Compares two month/year pairs.
	// Returns -1 if month1/year1 is before, 0 if equal, and 1 if after month2/year2.
	function compareMonthYear(month1, year1, month2, year2) {
		if (year1 < year2)
			return -1;
		if (year1 == year2) {
			if (month1 < month2)
				return -1;
			if (month1 == month2)
				return 0;
			if (month1 > month2)
				return 1;
		}
		if (year1 > year2) {
			return 1;
		}		
	}
	
	function dateRangeSet(selectedStartMonth, changeStartMonth, selectedStartYear, changeStartYear, selectedEndMonth, changeEndMonth, selectedEndYear, changeEndYear) {
		if (compareMonthYear(selectedStartMonth, selectedStartYear, selectedEndMonth, selectedEndYear) == 1) {
			// Selected dates don't fall inside proper date range. Force conform.
			if (changeStartMonth) {
				// Force end month to conform to start month.
				selectedEndMonth = selectedStartMonth;
			} else if (changeStartYear) {
				// Force end month/year to conform to start month/year.
				selectedEndMonth = selectedStartMonth;
				selectedEndYear = selectedStartYear;
			} else if (changeEndMonth) {
				// Force start month to conform to end month.
				selectedStartMonth = selectedEndMonth;
			} else if (changeEndYear) {
				// Force start month/year to conform to end month/year.
				selectedStartMonth = selectedEndMonth;
				selectedStartYear = selectedEndYear;
			} else {
				// Default. End month/year will be forced to conform to start month/year.
				selectedEndMonth = selectedStartMonth;
				selectedEndYear = selectedStartYear;
			}
		}
		
		// Set all date boxes.
		chosenValSet(rangeSelectorStartMonth, selectedStartMonth);
		chosenValSet(rangeSelectorStartYear, selectedStartYear);
		chosenValSet(rangeSelectorEndMonth, selectedEndMonth);
		chosenValSet(rangeSelectorEndYear, selectedEndYear);
	}
	
	$("#metricsStartMonth").change(function(e) {
		dateRangeSet(chosenVal("#metricsStartMonth"), true,
					 chosenVal("#metricsStartYear"), false,
					 chosenVal("#metricsEndMonth"), false,
					 chosenVal("#metricsEndYear"), false);
	});
	
	$("#metricsStartYear").change(function(e) {
		dateRangeSet(chosenVal("#metricsStartMonth"), false,
			 chosenVal("#metricsStartYear"), true,
			 chosenVal("#metricsEndMonth"), false,
			 chosenVal("#metricsEndYear"), false);
	});

	$("#metricsEndMonth").change(function(e) {
		dateRangeSet(chosenVal("#metricsStartMonth"), false,
					 chosenVal("#metricsStartYear"), false,
					 chosenVal("#metricsEndMonth"), true,
					 chosenVal("#metricsEndYear"), false);
	});
	
	$("#metricsEndYear").change(function(e) {
		dateRangeSet(chosenVal("#metricsStartMonth"), false,
					 chosenVal("#metricsStartYear"), false,
					 chosenVal("#metricsEndMonth"), false,
					 chosenVal("#metricsEndYear"), true);
	});
	
	$('[id^="addmetric"]').click(function(e) {
		// Set next metric to visible.
		next = parseInt($("#metricCount").val())+1;
		$("#metricCount").val(next);
		// Make last add-metric button invisible.
		$("#metricCopy_" + (next-2)).css('display','none');
		$("#metricBlock_" + (next-1)).css('display','');
		
		// If we're at the last metric, turn off addmetric button.
		if (next == 10)
			$("#metricCopy_9").css('display','none');

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
		$("#dprImportPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#cancel").click(function() {
		var persistent = '<?= (isset($persistent)) ? json_encode($persistent) : ""; ?>';
		var returnData = JSON.stringify({state:"error", data:persistent});
		$("#dprImportPop").attr("return",returnData);
		$("#wizardPop").dialog("destroy").remove();
	});
	
	$("#wizardPop").dialog({
		width:450,
		height:300,
		autoOpen: true,
		modal: true,
		title: "Import DPR Lead Source Metrics"
	});
</script>