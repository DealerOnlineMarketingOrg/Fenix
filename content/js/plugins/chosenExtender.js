	// Appends an option to the chosen list.
	function chosenAdd(selector, optionText, optionValue) {
		$(selector).append('<option value='+optionValue+'>'+optionText+'</option>');
		$(selector).trigger('liszt:updated');
	}
	
	// Appends a list of options from an associative array to the chosen.
	//  [optionValue] = 'optionText'
	function chosenAddList(selector, list) {
		for (var val in list)
			chosenAdd(selector, list[val], val);
		$(selector).trigger('liszt:updated');
	}
	
	// Removes the option with optionValue from the chosen list.
	function chosenRemove(selector, optionValue) {
		$(selector+" option[value='"+optionValue+"']").remove();
		$(selector).trigger('liszt:updated');
	}
	
	// Removes all options from the chosen list.
	function chosenClear(selector) {
		$(selector).find('option').remove();
		$(selector).trigger('liszt:updated');
	}
	
	// Gets the selected value of the chosen.
	function chosenVal(selector) {
		return $(selector).val();
	}
	
	// Sets the selected value of the chosen.
	function chosenValSet(selector, value) {
		$(selector).val(value);
		$(selector).trigger('liszt:updated');
	}