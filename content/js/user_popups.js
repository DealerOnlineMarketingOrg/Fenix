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

function editUser(uid,mods,page) {
	$('#editUser').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/users/edit?uid='+uid+'&modules='+mods+'&page='+page,
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

function editUserInfo(id,page) {
	$('#editUserInfo').remove();
	$.ajax({
		type:'GET',
		url:'/admin/users/edit_details_form?uid='+id+'&page='+page,
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
			url:'/admin/users/view?uid='+uid,
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

function editTheFreakinAvatar(id,page) {
	jConfirm('This will overwrite your current avatar, Are you sure you want to proceed?','Upload Custom Avatar',function(r) {
		if(r) {
			$.ajax({
				type:'GET',
				url:'/users/profile/load_custom_avatar_form?uid=<?= $user->ID; ?>',
				success:function(data) {
					if(data) {
						$('#editAvatarPop').html(data);	
					}else {
						jAlert('Houston, we have a problem...','Error Finding Popup');	
					}
				}
			});
		}
	});
}

function importGoogleAvatar(id) {
	var uid = id;
	jConfirm('Are you sure you want to import your Google avatar into the system? This will overwrite any existing avatars you have set.','Import Google Avatar',function(r) {
		if(r) {
			$.ajax({
				type:'GET',
				url:'/admin/users/import_google_avatar?uid='+uid,
				success:function(data) {
					if(data) {
						load_user_table(uid);
					}
				}
			});
		}
	});
};

