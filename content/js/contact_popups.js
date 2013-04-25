
var $ = jQuery.noConflict();

function addContact() {
	$('#addContactInfo').remove();
	$('#editContactInfo').remove();
	$('#viewContactInfo').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contacts/add',
			success:function(code) {
				if(code == '0') {
					jAlert('The Contact can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {					
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactPop').html(code);
					});
				}
			}
		});
	});
}

function editContact(id,type) {
	$('#addContactInfo').remove();
	$('#editContactInfo').remove();
	$('#viewContactInfo').remove();

	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contacts/edit?id='+id+'&type='+type,
			success:function(code) {
				if(code == '0') {
					jAlert('The Contact can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactPop').html(code);
					});
				}
			}
		});
	});
}

function viewContact(id,type) {
	$('#addContactInfo').remove();
	$('#editContactInfo').remove();
	$('#viewContactInfo').remove();

	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/contacts/view?id='+id+'&type='+type,
			success:function(code) {
				if (code == '0') {
					jAlert('The Contact can not be found. Please try again','Error',function() {
						jQuery('#loader_block').slideUp('fast');
					});
				}else {
					jQuery('#loader_block').slideUp('fast',function() {
						$('#editContactPop').html(code);
					});
				}
			}
		});
	});
}

function contactListTable() {
	$('#addContactInfo').remove();
	$('#editContactInfo').remove();
	$('#viewContactInfo').remove();
	
	jQuery('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/contacts/load_table',
			success:function(data) {
				if(data) {
					$('#contactTable').html(data);
					$('#loader_block').slideUp('fast',function() {
						$('#example').dataTable();
						$('#contactTable').slideDown('fast');
					});
				}else {
					$('#contactTable').html('<p>No contacts found at this level. Please use the Dealer Dropdown to change to a different contact.</p>');
				}
			}
		});
	});
}
