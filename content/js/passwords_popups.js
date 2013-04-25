
	function addPasswords(link) {
		jQuery('#editPasswords').remove();
		jQuery('#addPasswords').remove();
		jQuery('#loader_block').slideDown('fast',function() {
			jQuery.ajax({
				type:'GET',
				url:link+'passwords/add',
				//data:{client_id:id},
				success:function(data) {
					if(data) {
						jQuery('#loader_block').slideUp('fast',function() {
							jQuery('#addPasswordsInfo').html(data);
						});
					}else {
						jAlert('There was a problem finding the contact you needed. Please try again.','Add Passwords Error',function() {
							jQuery('#loader_block').slideUp('fast');	
						});
					}
				}
			})
		});
	}
	
	function editPasswords(pwdid) {
		jQuery('#editPasswords').remove();
		jQuery('#addPasswords').remove();
		jQuery('#loader_block').slideDown('fast',function() {
			jQuery.ajax({
				type:'GET',
				url:'/admin/passwords/edit?pwdid=' + pwdid,
				//data:{client_id:id},
				success:function(data) {
					if(data) {
						jQuery('#loader_block').slideUp('fast',function() {
							jQuery('#editPasswordsInfo').html(data);
						});
					}else {
						jAlert('There was a problem finding the contact you needed. Please try again.','Edit Passwords Error',function() {
							jQuery('#loader_block').slideUp('fast');	
						});
					}
				}
			})
		});
	}
	
	function passListTable() {	
		$('#editPasswords').remove();
		$('#addPasswords').remove();
		$('#loader_block').slideDown('fast',function() {
			$.ajax({
				type:"GET",
				url:'/admin/passwords/load_table',
				success:function(data) {
					if(data) {
						$('#passwordListTable').html(data);
						$('#loader_block').slideUp('fast',function() {
							$('#example').dataTable({
								"bJQueryUI": true,
								"sPaginationType": "full_numbers",
								"sDom": '<""f>t<"F"lp>',
								'iDisplayLength':1000,
								"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
							});
		
							$('#passwordListTable').slideDown('fast');
						});
					}else {
						$('#passwordListTable').html('<p style="padding:10px;">No passwords found for this client.</p>');
					}
				}
			});
		});
	}
