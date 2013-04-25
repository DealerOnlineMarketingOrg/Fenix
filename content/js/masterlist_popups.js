// JavaScript Document
var $ = jQuery;

function editEntry(id) {
	$.ajax({
		type:'GET',
		url:'/admin/masterlist/edit_entry?cid='+id,
		success:function(data) {
			if(data) {
				$('#editEntry').html(data);	
			}
		}
	});
}

function refreshTable() {
	  jQuery('#editMasterList').remove();
	  jQuery('#editEntry').empty();
	  jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
		  type:"GET",
		  url:'/admin/masterlist/load_table',
		  success:function(data) {
			if(data) {
			  jQuery('#masterListTable').slideUp('fast',function() {
				  jQuery('#masterListTable').html(data);
				  jQuery('#loader_block').slideUp('fast',function() {
							jQuery('#example').dataTable({
								"bJQueryUI": true,
								"sPaginationType": "full_numbers",
								"sDom": '<""f>t<"F"lp>',
								'iDisplayLength':1000,
								"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
							});
					jQuery('#masterListTable').slideDown('fast');
				  });
			  });
			}else {
			  jQuery('#masterListTable').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
			}
		  }
		});
	  });
}