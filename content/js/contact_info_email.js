	
	function primaryEmail(eid,did,pri) {
		$.ajax({
			type:'POST',
			data:{email_id:eid,directory_id:did,primary:pri},
			url:'/admin/contactInfo/update_primary_email',
			success:function(data) {
				if(data == '1') {
					jAlert('Primary Email has been changed','Success');
				}else {
					jAlert('There was an error changing this email to your primary email. Please try again.','Error');	
				}
			}
		});
	}
	
	function editEmail(eid) {
		$('#EditEmail').remove();
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/edit_email_form?eid='+eid,
			success:function(data) {
				if(data) {
					//alert(data);
					$('#UserEmailPop').html(data);
				}
			}
		});
	}
	
	function addEmail(did,type) {
		$('#addEmail').remove();
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/add_email_form?did='+did+'&type='+type,
			success:function(data) {
				if(data) {
					//alert(data);
					$('#UserEmailPop').html(data);
				}
			}
		});
	}
