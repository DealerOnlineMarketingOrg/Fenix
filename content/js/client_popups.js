
	function addClient() {
		jQuery('#editClient').remove();
		jQuery('#loader_block').slideDown('fast',function() {
			jQuery.ajax({
				type:'GET',
				url:'/admin/clients/add_form',
				success:function(data) {
					if(data) {
						jQuery('#loader_block').slideUp('fast',function() {
							jQuery('#editClientsForm').html(data);
						});
					}else {
						jAlert('There was an error with your request. Please Try Again.','Error',function() {
							jQuery('#loader_block').slideUp('fast');	
						});
					}
				}
			});
		});
	}
	
	function editClient(cid) {
		jQuery('#editClient').remove();
		jQuery('#loader_block').slideDown('fast',function() {
			jQuery.ajax({
				type:'GET',
				url:'/admin/clients/edit?cid='+cid,
				//data:{client_id:id},
				success:function(data) {
					if(data) {
						jQuery('#loader_block').slideUp('fast',function() {
							jQuery('#editClientsForm').html(data);
						});
					}else {
						jAlert('There was a problem finding the client you needed.Please try again.','Edit Client Error',function() {
							jQuery('#loader_block').slideUp('fast');	
						});
					}
				}
			})
		});
	}
	
	function viewClient(cid) {
		jQuery('#editClient').remove();
		jQuery('#loader_block').slideDown('fast',function() {
			jQuery.ajax({
				type:"GET",
				url:'/admin/clients/view?cid='+cid,
				success:function(data) {
					if(data) {
						jQuery('#loader_block').slideUp('fast',function() {
							jQuery('#editClientsForm').html(data);
						});
					}else {
						jAlert('There was an error finding the client in our system. Please try again.','View Error',function() {
							jQuery('#loader_block').slideUp('fast');	
						});
					}
				}
			});
		});
	}
	
	function clientListTable() {
	  jQuery('#editClient').remove();
	  jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
		  type:"GET",
		  url:'/admin/clients/load_table',
		  success:function(data) {
			if(data) {
			  jQuery('#clientTableHolder').html(data);
			  jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#example').dataTable({
							"bJQueryUI": true,
							"sPaginationType": "full_numbers",
							"sDom": '<""f>t<"F"lp>',
							'iDisplayLength':1000,
							"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
						});
				jQuery('#dataClient').slideDown('fast');
			  });
			}else {
			  jQuery('#dataClient').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
			}
		  }
		});
	  });
	}
	
