// JavaScript Document

var $ = jQuery;

function addVendor() {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/vendors/add',
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editVendorForm').html(data);
					});
				}else {
					$('There was an error with your request. Please Try Again.','Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function editVendor(vid) {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/vendors/edit?VID='+vid,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editVendorForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the vendor requested. Please try again.','Edit Vendor Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		})
	});
}

function viewVendor(vid) {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/vendors/view?VID='+vid,
			//data:{client_id:id},
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editVendorForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the vendor requested. Please try again.','Edit Vendor Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		})
	});
}

function vendorTable() {
	$('#editVendor').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/vendors/load_table',
			success:function(data) {
				if(data) {
					$('#vendorTable').html(data);
					$('#loader_block').slideUp('fast',function() {
						$('#example').dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"sDom": '<""f>t<"F"lp>',
							'iDisplayLength':1000,
							"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
						});
						$('#vendorTable').slideDown('fast');
					});
				}else {
					$('#vendorTable').html('<p>No vendors found.</p>');
				}
			}
		});
	});
}
	
