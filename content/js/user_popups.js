// JavaScript Document
var $ = jQuery;
function addUser() {
	$('#editUser').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/users/add_user_form',
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUsersForm').html(data);
					});
				}else {
					jAlert('There was an error. Please try again.','View Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function editUser(uid) {
	$('#editUser').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/users/edit?UID='+uid,
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUsersForm').html(data);
					});
				}else {
					jAlert('There was an error finding the user in our system. Please try again.','View Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function editUserInfo(id) {
	$('#editUserInfo').remove();
	$.ajax({
		type:'GET',
		url:'/admin/users/edit_details_form?uid='+id,
		success:function(data) {
			if(data) {
				$('#editUserDetailsForm').html(data);	
			}else {
				jAlert('There was an error finding the user in our system. Please try again.','View Error');
			}
		}
	});
}

function viewUser(uid) {
	$('#editUser,#addUser,#editUserInfo').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/users/view_popup?UID='+uid,
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUsersForm').html(data);
					});
				}else {
					jAlert('There was an error finding the user in our system. Please try again.','View Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function load_user_table(uid) {
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
		  url: "/users?trigger="+uid,
		  context: document.body,
		  success: function(s,x){
			$('#loader_block').slideUp('fast');
			$(this).html(s);
		  }
		});
	});
}
function load_users_table() {
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
		  url: "/users",
		  context: document.body,
		  success: function(s,x){
			$('#loader_block').slideUp('fast');
			$(this).html(s);
		  }
		});
	});
}

function changeMyPass(id) {
	$('#changePassword').remove();
	$.ajax({
		type:"GET",
		url:'/admin/users/change_pass_form?uid='+id,
		success:function(data) {
			if(data) {
				$('#passwordForms').html(data);
			}else {
				jAlert('There was an error finding the user in our system. Please try again.','View Error');
			}
		}
	});
}

function resetMyPass(id) {
	jConfirm('Are you sure you want to reset this users password?','Confirmation Password Reset',function(r) {
		if(r) {
			$.ajax({
				type:"POST",
				url:'/admin/users/reset_user_password?uid='+id,
				success:function(data) {
					if(data != '0') {
						jAlert('You have reset the users password to ' + data,'Alert');
					}
				}
			});
		}
	});
}