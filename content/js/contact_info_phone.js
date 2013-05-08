	function primaryPhone(pid,did,pri) {
		$.ajax({
			type:'POST',
			data:{phone_id:pid,directory_id:did,primary:pri},
			url:'/admin/contactInfo/update_primary_phone',
			success:function(data) {
				if(data == '1') {
					jAlert('Primary Number has been changed','Success');
					
				}else {
					jAlert('There was an error changing this number to your primary number. Please try again.','Error');	
				}
			}
		});
	}
	
	function editPhone(pid) {
		$('#editPhone').remove();
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/edit_phone_form?pid='+pid,
			success:function(data) {
				if(data) {
					//alert(data);
					$('#UserPhonePop').html(data);
				}
			}
		});
	}
	
	function addPhone(did,type) {
		$('#addPhone').remove();
		$.ajax({
			type:'GET',
			url:'/admin/contactInfo/add_phone_form?did='+did+'&type='+type,
			success:function(data) {
				if(data) {
					$('#UserPhonePop').html(data);	
				}
			}
		});
	}