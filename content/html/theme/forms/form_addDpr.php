<div id="addMetrics" class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="addDprSource" title="Add Source Metrics">
        <div class="uiForm">
            <?php
                $dateRange = $this->input->post();
            
                $form = array(
                    'id' => 'addDpr',
                    'name' => 'addDpr',
                    'class' => 'mainForm valid'
                );
                
                echo form_open('/dpr/form_processor/dpr/add',$form);
            ?>
                <!-- Input text fields -->
                <fieldset>
                    <div class="widget first" style="padding:5px">
                        <?php if ($this->user['DropdownDefault']->LevelType != 'c') { ?>
                            <div class="head"><h5 class="iList">Add DPR Source Metrics: <span style="color:red">No dealer is selected. Please select a dealer to input DPR sources.</span></h5></div>
                        
                        <?php } else { ?>
                            <div class="noborder noSearch" style="position:relative;float:left;margin:5px">
                                <label style="position:relative;float:left"><span class="req">*</span>Source</label>
                                <select id="providers" name="providers" class="input msSelect chzn-select" style="position:relative;float:left" placeholder="Select A Source...">
                                    <option value="">Select A Source</option>
                                    <?php echo $providers; ?>
                                </select>
                                <div class="fix"></div>
                                <div style="position:relative;float:left">
                                    <label data-binding="providers" style="display:none;position:relative;float:left">New source:</label>
                                    <div class="fix"></div>
                                    <label data-binding="providers" style="display:none;position:relative;float:left">New source URL:</label>
                                </div>
                                <div style="position:relative;float:left">
                                    <input id="customProvider" name="customProvider" class="input" data-binding="providers" placeholder="Enter new provider" type="text" style="width:200px;position:relative;float:left;display:none" />
                                    <div class="fix"></div>
                                    <input id="customProviderURL" name="customProviderURL" class="input" data-binding="providers" placeholder="Enter new provider URL" type="text" style="width:200px;display:none;position:relative;float:left" />
                                </div>
                            </div>
                            <div class="noborder noSearch" style="position:relative;float:left;margin:5px">
                                <div style="position:relative;float:left">
                                    <label>Month</label>
                                    <div class="fix"></div>
                                    <select id="month" name="month" class="input chzn-select" placeholder="Select a month...">
                                        <option value="">Select a Month</option>
                                        <option value="1">January</option>
                                        <option value="2">Febuary</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                <div style="position:relative;float:left;margin-left:5px">                        
                                    <label>Year</label>
                                    <div class="fix"></div>
                                    <select id="year" name="year" class="input chzn-select" placeholder="Select a year...">
                                        <option value="">Select a Year</option>
                                        <option value="2013">2013</option>
                                        <option value="2012">2012</option>
                                        <option value="2011">2011</option>
                                        <option value="2010">2010</option>
                                        <option value="2009">2009</option>
                                    </select>
                                </div>
                                <div class="fix"></div>
                                <label style="position:relative;float:left">Cost</label>
                                <input type="text" class="input " style="width:75px !important;position:relative;float:left" id="cost" name="cost" placeholder="Monthly Cost" max-length="10" />
                            </div>
                            <?php
                            $sourceBlock = '
                            <div class="fix"></div>
                            <div id="sourceBlock_%c%" class="noSearch" style="position:relative;float:left;width=1px;border:1px dotted #d5d5d5;margin:5px;margin-bottom:0;padding:5px">
                                <div style="position:relative;float:left;margin:5px">
                                    <label style="position:relative;float:left">Metric</label>
                                    <select id="sources_%c%" name="sources_%c%" class="msSelect chzn-select" style="position:relative;float:left" placeholder="Select A Source Metric...">
                                        <option value="">Select A Sorce Metric</option>
                                           ' . $services . '
                                    </select>
                                    <div class="fix"></div>
                                    <label data-binding="sources_%c%" style="display:none;position:relative;float:left">New metric:</label>
                                    <input id="customSource_%c%" name="customSource_%c%" class="" data-binding="sources_%c%" placeholder="Enter new metric" type="text" style="width:200px;display:none;position:relative;float:left" />
                                </div>
                                <div style="position:relative;float:left;margin:5px">
                                    <label style="position:relative;float:left">Total</label>
                                    <input type="text" class="" style="width:75px !important;position:relative;float:left" id="total_%c%" name="total_%c%" placeholder="Metric Count" max-length="8" />
                                </div>
                                <div id="sourceCopy_%c%" style="float:left;margin:5px"><input type="button" id="addSource" value="+" class="greenBtn" style="font-weight:bold;font-size:120%;padding-top:2px;padding-bottom:2px;padding-left:10px;padding-right:10px" /></div>
                                <div class="fix"></div>
                            </div>
                            ';
                            
                            for ($i = 0; $i < 10; $i++)
                                echo str_replace('%c%', $i, $sourceBlock);
                            ?>
                            <input id="sourceCount" name="sourceCount" type="hidden" value="0" />
                        <?php } ?>
                        <div class="fix"></div>
                    </div>
                    <div class="submitForm">
                        <input type="button" id="cancel" value="Cancel" class="basicBtn" />
                        <!-- <input type="reset" id="reset" value="Reset" class="basicBtn" /> -->
                        <input type="submit" value="submit" class="redBtn" />
                    </div>
                <fieldset>
			<?php echo  form_close(); ?>
        </div>
    </div>
</div>

<?php
	$form = array(
		'id' => 'reportAddDprCancel',
		'name' => 'reportAddDprCancel',
		'class' => 'mainForm valid'
	);
	echo form_open('%page%',$form); ?>
	<input ID="startMonthCancel" name="startMonth" type="hidden" value="<?php echo $dateRange['startMonth']; ?>" />
	<input ID="startYearCancel" name="startYear" type="hidden" value="<?php echo $dateRange['startYear']; ?>" />
	<input ID="endMonthCancel" name="endMonth" type="hidden" value="<?php echo $dateRange['endMonth']; ?>" />
	<input ID="endYearCancel" name="endYear" type="hidden" value="<?php echo $dateRange['endYear']; ?>" />
<?php echo  form_close(); ?>

<script type="text/javascript">
	//$(document).ready(function() {
		var sourceNum = 1;
		var validateForm = true;
		
		function loadInit() {
			// Don't turn off template sourceBlock before document is loaded, otherwise
			//  drop down fields may not get populated.
			for (i = 0; i < 10; i++)
				jQuery('#sourceBlock_' + i).css('display','none');

			// Set first source to visible by default.
			next = parseInt(jQuery("#sourceCount").val())+1;
			jQuery("#sourceCount").val(next);
			jQuery("#sourceBlock_0").css('display','');
			
			//newSource();
		};
		
		jQuery('input#cancel').click(function() {
			//validateForm = false;
			// Go back to report page with cancelled values.
			//jQuery('form#reportAddDprCancel').attr('action', '<?= base_url(); ?>dpr');
			//jQuery("form#reportAddDprCancel").submit();
			$('#addMetrics').dialog('destroy').remove();
		});
		
		jQuery('input#reset').click(function(e) {
			validateForm = false;
			// Refresh add report lead page with cancelled values.
			jQuery('form#reportAddDprCancel').attr('action', '<?= base_url(); ?>dpr/add');
			jQuery("form#reportAddDprCancel").submit();
		});
		
		jQuery('form#addDpr').submit(function(e) {
			if (validateForm) {
				if (validateBlank()) {
					alert('All fields are required. Please fill in all fields.');
					return false;
				} else if (validateData()) {
					alert('Total field must be numeric.');
					return false;
				} else {
				}
			}
		});
		
		jQuery('[id^="addSource"]').click(function(e) {
			// Set next source to visible.
			next = parseInt(jQuery("#sourceCount").val())+1;
			jQuery("#sourceCount").val(next);
			// Make last add-source button invisible.
			jQuery("#sourceCopy_" + (next-2)).css('display','none');
			jQuery("#sourceBlock_" + (next-1)).css('display','');
			
			// If we're at the last source, turn off addSource button.
			if (next == 10)
				jQuery("#sourceCopy_9").css('display','none');
				
			//newSource();
		});
		
		// Clones with outer element (outer html).
		// To get the html, use .html().
		// Use of the clone will include the temp wrapper <div>.
		function cloneWithOuter(selector, withDataAndEvents = false, deepWithDataAndEvents = false) {
			cloneSeg = $('<div>').append($(selector).clone(withDataAndEvents, deepWithDataAndEvents));			
			// Replace all dom-selects with a simplified html version for select-drop-down reloading.
			jQuery('select', cloneSeg).each(function() {
				selHtml = selectHtml($(this));
				$(this).replaceWith(selHtml);
			});
			
			return cloneSeg;
		}
		
		// Returns false if jquery object doesn't have attr, else the value of the attr.
		function getAttr(jQueryObj, attrName) {
			attrVal = jQueryObj.attr(attrName);
			// Check to make sure attr exists.
			// Attr can be undefined or false, depending on browser.
			if (typeof(attrVal) !== 'undefined' && attrVal !== false)
				return attrVal;
			else
				return false;
		}
		
		// Returns a simplified bare-bones version of a dom-based select input.
		function selectHtml(jQueryObj) {
			jqo = jQueryObj;
			
			// Fill in opening tag.
			bbSel = '<select';
			val = getAttr(jqo, 'id');
			if (val) bbSel += ' id="' + val + '"';
			val = getAttr(jqo, 'name');
			if (val) bbSel += ' name="' + val + '"';
			val = getAttr(jqo, 'class');
			if (val) bbSel += ' class="' + val + '"';
			val = getAttr(jqo, 'style');
			if (val) bbSel += ' style="' + val + '"';
			bbSel += '>';
			
			// Fill in options.
			jQueryObj.find('option').each(function(e) {
				bbOpt = '<option';
				val = getAttr($(this), 'value');
				bbOpt += ' value="' + ((val) ? val : '') + '"';
				bbOpt += '>' + $(this).text() + '</option>';
				bbSel += bbOpt;
			});
			
			// Close select.
			bbSel += '</select>';
			
			return bbSel;
		}
		
		// Salts a full html tree segment by appending a salt value to the attributes
		//   in attrList.
		// Can be used with 'id' and 'name' to create distinct cloned elements.
		// Returns the salted segment.
		function saltSegment(segment, attrList, salt) {
			var attrVal = '';
			var newAttr = '';
			jQuery('*', segment).each(function(e) {
				elementThis = $(this);
				$.each(attrList, function(index, attrName) {
					attrVal = getAttr(elementThis, attrName);
					if (attrVal) {
						newAttr = attrVal + salt;
						elementThis.attr(attrName, newAttr);
					}
				});
			});
			return segment;
		}
		
		function changeSelect(selectID, value) {
			jQuery('#'+selectID + " option.*").each(function() {
				if ($(this).value == value) {
					alert('hit');
					$(this).attr('selected','selected');
				} else
					$(this).attr('selected','');
			});
		}
		
		function newSource() {
			// Increment source counters.
			sourceNum++;
			jQuery('#sourceCount').val(sourceNum);
			
			var newBlock = cloneWithOuter('#sourceBlock',true, true);
			newBlock = saltSegment(newBlock, [ 'id', 'name', 'data-binding' ], '_' + sourceNum);
			alert(newBlock.html());
			// Turn new source visible.
			jQuery("#sourceBlock_"+sourceNum, newBlock).css('display','');
			// Use .before for now. Replace with .replaceWith and .after(buttonCode) due to:
			// Replace out add button/placeholder. If we prepend before it instead,
			//   it could cause layout problems with some layouts or browsers.
			//jQuery("#sourceBlock_"+sourceNum + " .chzn-search").addClass('noSearch');
			//jQuery("#sourceBlock_"+sourceNum + " .selector").addClass('noSearch');
			jQuery('#sourceCopy').before(newBlock.html());
			/*jQuery('#sources_'+sourceNum).change(function() {
					var sel = $(this).text();
					jQuery('#sources_'+sourceNum).find("option").attr('selected','');
					jQuery(this).find('option').each(function() {
						if($(this).text() == sel) {
							$(this).attr('selected','selected');
							//alert($(this).text());
						}else {
							$(this).attr('selected','');
							//alert('Deselect: ' + $(this).text());	
						}
						
					});
					
					
					$(this).find("option")attr('selected','selected');
					
					if ($(this).value == value) {
						alert('hit');
						$(this).attr('selected','selected');
					} else
						$(this).attr('selected','');
					
			});*/
			// We need to re-attach drop-down functionality to the new source.
			// This function is loaded for drop-down boxes on DOM-load in custom.js
			//jQuery("#sources_"+sourceNum).chosen({disable_search_threshold: 20});
			//jQuery("#sources_"+sourceNum).chosen({disable_search: true});
			//jQuery("#sources_"+sourceNum).trigger("liszt:updated");
			// Add a new add button/placeholder.
			//jQuery(sourceBlock).after('<div id="sourceCopy"><input type="button" id="addSource" value="addSource" class="basicBtn" style="clear:all" /></div>');
			// Replacing, then remaking, the button unbinds the click event. Rebind.
			//jQuery('#addSource').bind('click', function(e) {newSource();});
		}
		
		function validateBlank()
		{
			if (validateForm) {
				var isBlank = (jQuery('input#providers').val() == '' ||
					 (jQuery('input#providers').val() == 'AddCustom' &&
					   (jQuery('input#customProvider').val() == '' ||
						jQuery('input#customProviderURL').val() == '')) ||
					jQuery('input#month').val() == '' ||
					jQuery('input#year').val() == '' ||
					jQuery('input#cost').val() == '');
					
				var isSourceBlank = false;
				var sourceCount = parseInt(jQuery("#sourceCount").val());
				for (i = 0; i < sourceCount; i++) {
					if (jQuery('input$sources_' + i).is(':visible'))
						isSourceBlank == isSourceBlank && (
							jQuery('input#sources_' + i).val() == '' ||
							 (jQuery('input#sources_' + i).val() == 'AddCustom' &&
							  jQuery('input#customSource_' + i).val() == '') ||
							jQuery('input#total_' + i).val() == '');
					if (isSourceBlank)
						break;
				}
				
				return (!(isBlank || isSourceBlank));
			}
			return true;
		}
		
		function validateData()
		{
			if (validateForm) {
				if (jQuery('input#total').val().match(/^[0-9]+|([0-9]+)?\.[0-9]+$/))
					return false;
				else
					return true;
			} else
				return true;
		}
		
		jQuery('.input').change(function() {
			if ($(this).attr('name') == 'providers' ||
				$(this).attr('name') == 'month' ||
				$(this).attr('name') == 'year' ||
				($(this).attr('name')).indexOf('sources') == 0) {
				if (jQuery("#providers").val() != '' &&
					jQuery("#sources").val() != '' &&
					jQuery("#month").val() != '' &&
					jQuery("#year").val() != '') {
					// Check values of provider and source and see if there's a cost associated with them.
					$.ajax({type:"POST",
							url:"<?= base_url(); ?>dpr/ajaxGetCost",
							data:{provider:jQuery("#providers").val(),
								  source:jQuery("#sources_1").val(),
								  month:jQuery("#month").val(),
								  year:jQuery("#year").val()
								  },
							success:(function(data) {
								cost = data;
								jQuery("#cost").val(cost);
							})
					});
				}
			}
		});
		
	//});
</script>

<script type="text/javascript">
$( ".datepicker" ).datepicker({ // onClick date picker
	defaultDate: +7,
	autoSize: false,
	appendText: '(yyyy-mm-dd)',
	dateFormat: 'yy-mm-dd',
});
</script>
    
<style type="text/css">
.rowElem > label {padding-top:5px;}
	.ui-datepicker-append{float:left;}
</style>
<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();
	
	$('.formPop').submit(function(e) {
		e.preventDefault();
		var formData = $(this).serialize();
		
		$.ajax({
			type:'POST',
			data:formData,
			url:'/dpr/form_processor/dpr/add',
			success:function(code) {
				var msg;
				if(code == '1') {
					msg = '<?= ($page == 'edit') ? 'Your edit was made succesfully.' : 'Your add was made successfully'; ?>';
					jAlert(msg,'Success',function() {
						groupListTable();
					});
				}else {
					msg = '<?= ($page == 'edit') ? 'There was a problem with editing the group requested. Please try again.':'There was a problem adding the group. Please try again.'; ?>';
					jAlert(msg,'Error');
				}
			}
		});
	});
	
	$(".chzn-select").chosen();
	
	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		$('#viewDpr div.tab_container div.tab_content').hide();
		$('#viewDpr div.tab_container').find(content).css({'display':'block'});
	});
	
	$("#addMetrics").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		title: "Add Source Metrics"
	});
</script>

</div>
<div class="fix"></div>