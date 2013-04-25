
var $ = jQuery.noConflict();

// We need to watch for change event on hidden input box,
//  but it never triggers. Do it this way instead (for now).
// TODO: watch for button events on popup instead of polling
//  for selector change.
function watchChange(selector, attribute, callback) {
   var input = $(selector);
   var oldvalue = input.attr(attribute);
   // Lock is to control timing.
   // Race condition is still possible if interval is short enough.
   // Lock has two stages:
   //  1 - after initialization.
   //  2 - after value has been changed.
   
   var lock = 1;
   setInterval(function(){
	  if (lock != 2) {
		  if (input.attr(attribute)!=oldvalue){
			  // Immediately lock watch from triggering
			  //  any other events.
			  lock = 2;
			  oldvalue = input.attr(attribute);
			  callback();
		  }
	  }
   }, 100);
}

// Wrapper is the selector for the on-page element that contains the dialog segment.
// Wizard uses the attribute 'return' on the wrapper to pass data through to other steps.
// Steps is a 0-number-indexed array of the urls of each wizard step.
// The urls should be functions in a controller.
// Each function defines a single wizard step and
//  page loader (with load->view).
// Each function step recieves posted form data for that step,
//  and needs to process all data for the step. The first step
//  recieves an empty posted form data.
// A final step for data saving is needed if wizard is used as a data-form.
// All wizard pages need to have a top-level element id'd 'wizardPop' for
//  the html segment. Wizard pages may include javascript. A required function
//  wizardLoadInit is needed (even if empty), to replace window.load and document.ready
//  functions (which will never fire on the popup wizard pages).

function wizardDialog(wrapper, steps) {
	// Initilize to empty for the first step.
	var postData = '';
	$(wrapper).attr("return","");
	runWizardSteps(wrapper, steps, postData);
}

// Return values from wizard pages expect a JSON object with the following:
//  state - 'success' or 'error' for the state of the page.
//  data - serialized data from the form.
function runWizardSteps(wrapper, steps, postData) {
	// Get current step in steps.
	if (steps.length == 0)
		// We've ran out of steps.
		return;
	// Current step.
	step = steps[0];
	
	$('#wizardPop').remove();
	
	// This step may be a redirect (redirect:page)
	//  (used to refresh after a wizard).
	if (steps[0].substring(0,9) == 'redirect:') {
		window.location.replace(steps[0].substring(9));
		// Strip current step from steps.
		nextSteps = new Array();
		for (i = 1; i < steps.length; i++)
			nextSteps.push(steps[i]);
		// Go onto next step.
		runWizardSteps(wrapper, nextSteps, '');
	} else {
		// Not a redirect. Open another wizard popup window.
		$.ajax({
			type:'POST',
			url:step,
			data:postData,
			success:function(code) {
				if(code == '0') {
					jAlert('Something went wrong. Please try again','Error');
				}else {
					/* code = code.replace('</script>', wizardScripts())
					alert(code); */
					$(wrapper).html(code);
					loadInit();
					
					function nextStep() {
						// Convert object back from json object.
						var returnData = JSON.parse($(wrapper).attr('return'));
						//alert($(wrapper).attr('return'));
						if (returnData.state == 'success') {
							// Strip current step from steps.
							nextSteps = new Array();
							for (i = 1; i < steps.length; i++)
								nextSteps.push(steps[i]);
							// returnData.data is also in a JSON state.
							var data = JSON.parse(returnData.data);
							// Go onto next step.
							runWizardSteps(wrapper, nextSteps, data);
						}
						else if (returnData.state == 'error')
							// Error and drop out.
							jAlert('You chose to cancel the import.','Error');
					}
					
					// If code == 1, simple flow-through to next step, no window.
					// Used with redirects.
					if (code == '1') {
						// Strip current step from steps.
						nextSteps = new Array();
						for (i = 1; i < steps.length; i++)
							nextSteps.push(steps[i]);
						// Go onto next step.
						runWizardSteps(wrapper, nextSteps, '');
					} else
						watchChange(wrapper, "return", nextStep);
				}
			}
		});
	}
}

function wizardScripts() {
	scripts = ' function queryStringToJSON(str) {}$("#next").click(function() {var persistent = {};<?php if (isset($persistent)) { ?><?php foreach ($persistent as $key => $val) ?>persistent["<?= $key ?>"] = "<?= $val ?>";<?php } ?>var returnData = JSON.stringify({state:"success", data:$("fieldset#wizardPopForm").serialize() + "&" + $.param(persistent)});$("#dprImportPop").attr("return",returnData);$("#wizardPop").dialog("destroy").remove();});$("#cancel").click(function() {var persistent = {};<?php if (isset($persistent)) { ?><?php foreach ($persistent as $key => $val) ?>persistent["<?= $key ?>"] = "<?= $val ?>";<?php } ?>var returnData = JSON.stringify({state:"error", data:$.param(persistent)});$("#dprImportPop").attr("return",returnData);$("#wizardPop").dialog("destroy").remove();});$("#wizardPop").dialog({width:450,height:300,autoOpen: true,modal: true,title: "Import DPR Lead Source Metrics"}); </script>';
	
	return scripts;
}