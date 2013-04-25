
	function inputPopup(args) {
		argsData = {};
		argsData['type'] = args['type'];
		argsData['anchorDiv'] = '#inputInfo';
		
		jQuery.ajax({
			type:'POST',
			url:args['dataFunc'],
			datatype:'json',
			data:argsData,
			//data:{client_id:id},
			success:function(popupData) {
				if(popupData) {
					// Open popup.
					jQuery(argsData['anchorDiv']).html(popupData);
					
					function endPopup() {
						// If there's a return..
						if (typeof $(argsData['anchorDiv']).attr('return') != 'undefined') {
							// Convert object back from json object.
							var returnData = JSON.parse($(argsData['anchorDiv']).attr('return'));
							// Call any set callbacks.
							if (returnData.state == 'success')
								if (typeof args['success'] != 'undefined')
									args['success'](returnData);
							if (returnData.state == 'error')
								if (typeof args['error'] != 'undefined')
									args['error'](returnData);
							if (typeof args['complete'] != 'undefined')
								args['complete'](returnData);
							
							// Clear the return attribute on the anchor.
							$(argsData['anchorDiv']).removeAttr('return');
							// Clear the binding.
							$(this).unbind();		
						}
					}
					
					// We need to watch for change event on hidden input box,
					//  but it never triggers. Do it this way instead (for now).
					// TODO: find better way.
					function watchChange(selector, attribute, callback) {
					   var input = $(selector);
					   var oldvalue = input.attr(attribute);
					   setInterval(function(){
						  if (input.attr(attribute)!=oldvalue){
							  oldvalue = input.attr(attribute);
							  callback();
						  }
					   }, 100);
					}
					watchChange('input#returnTrigger', 'value', endPopup);
				}else {
					jAlert('There was a problem. Please try again.','Input Error');
				}
			}
		})
	}
	