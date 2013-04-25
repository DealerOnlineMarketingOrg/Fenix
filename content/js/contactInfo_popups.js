function getPhoneEmailName(type) {
	switch (type) {
		case 'cid': return 'Client';
		case 'gid': return 'Contact';
		case 'uid': return 'User';
		case 'vid': return 'Vendor';
		default: return 'Contact';
	}
}

function addPhone(id, type) {
	$('#editContactInfoPhone').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/PhoneAdd?id='+id,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactInfoPhonePop').html(code);
					});
				}
			}
		});
	});
}

function editPhone(id, type, value) {
	$('#editContactInfoPhone').remove();
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/PhoneEdit?id='+id+'&type='+type+'&value='+value,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactInfoPhonePop').html(code);
					});
				}
			}
		});
	});
}

function savePhone(page, id, type, formData) {
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/contactInfo/FormPhone?page='+page,
			success:function(code) {
				if(code == '0') {
					jAlert('There was a problem saving phone number. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						jAlert('New phone number saved successfully!','Save',function() {
							$('#editContactInfoPhonePop').dialog('close');
							contactInfoListTable(page,id,type);
						});
					});
				}
			}
		});
	});
}

function saveEmail(page, id, type, formData) {
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'POST',
			data:formData,
			url:'/admin/contactInfo/FormEmail?page='+page,
			success:function(code) {
				if(code == '0') {
					jAlert('There was a problem saving email address. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						jAlert('New email address saved successfully!','Save',function() {
							$('#editContactInfoEmailPop').dialog('close');
							contactInfoListTable(page,id,type);
						});
					});
				}
			}
		});
	});
}

function addEmail(id, type) {
	$('#editContactInfoEmail').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/EmailAdd?id='+id,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactInfoEmailPop').html(code);
					});
				}
			}
		});
	});
}

function editEmail(id, type, value) {
	$('#editContactInfoEmail').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/EmailEdit?id='+id+'&type='+type+'&value='+value,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactInfoEmailPop').html(code);
					});
				}
			}
		});
	});
}

function updatePrimaries(id, type, phonePrimary, emailPrimary) {
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/FormPrimary?id='+id+'&phone='+phonePrimary+'&email='+emailPrimary,
			success:function(code) {
				if(code == '0') {
					jAlert('The '+getPhoneEmailName(type)+' can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						jAlert('The primary phone and email have been saved.','Primary');
					});
				}
			}
		});
	});
}

	function contactInfoListTable(page,id,type) {
	  jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
		  type:"GET",
		  url:'/admin/contactInfo/load_table?type='+type+'&id='+id+'&page='+page,
		  success:function(data) {
			if(data) {
			  jQuery('#loader_block').delay(1000).slideUp('fast',function() {
				  jQuery('#contactInfo').html(data);
			  });
			}else {
			  jQuery('#dataClient').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
			}
		  }
		});
	  });
	}
