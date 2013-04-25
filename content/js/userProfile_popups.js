// JavaScript Document
var $ = jQuery;
function addUserProfile() {
	$('#editUserProfile').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/userProfiles/add_userProfile_form',
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUserProfilesForm').html(data);
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

function editUserProfile(uid) {
	$('#UpdateUserContactInfo').remove();
		$.ajax({
			type:"GET",
			url:'/user/profile/edit?UID='+uid,
			success:function(data) {
				console.log(data);
				if(data) {
					$('#editUserContact').html(data);
				}else {
					jAlert('There was an error finding the userProfile in our system. Please try again.','View Error');
				}
			}
		});
}

function editUserProfileInfo(id) {
	$('#editUserProfileInfo').remove();
	$.ajax({
		type:'GET',
		url:'/admin/userProfiles/edit_details_form?uid='+id,
		success:function(data) {
			if(data) {
				$('#editUserProfileDetailsForm').html(data);	
			}else {
				jAlert('There was an error finding the userProfile in our system. Please try again.','View Error');
			}
		}
	});
}

function viewUserProfile(uid) {
	$('#editUserProfile,#addUserProfile,#editUserProfileInfo').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/userProfiles/view_popup?UID='+uid,
			success:function(data) {
				if(data) {
					$('#loader_block').slideUp('fast',function() {
						$('#editUserProfilesForm').html(data);
					});
				}else {
					jAlert('There was an error finding the userProfile in our system. Please try again.','View Error',function() {
						$('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function load_userProfile_table(uid) {
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
		  url: "/userProfiles?trigger="+uid,
		  context: document.body,
		  success: function(s,x){
			$('#loader_block').slideUp('fast');
			$(this).html(s);
		  }
		});
	});
}
function load_userProfiles_table() {
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
		  url: "/userProfiles",
		  context: document.body,
		  success: function(s,x){
			$('#loader_block').slideUp('fast');
			$(this).html(s);
		  }
		});
	});
}
