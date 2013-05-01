
function disableWebsite(wid) {
	jConfirm('Are you sure you would like to disable this website?','Disable Confirmation',function(r) {
		if(r) {
			jQuery('#loader_block').slideDown('fast',function() {
				jQuery.ajax({
					type:'GET',
					url:'/admin/websites/disable?wid='+wid,
					success:function(id) {
						if(id) {
							jQuery('#loader_block').slideUp('fast',function() {
								loadWebsiteTable(id);
							});
						}else {
							jAlert('There was an error disabling the website you requested. Please try again.','Disable Error',function() {
								jQuery('#loader_block').slideUp('fast');	
							});	
						}
					}
				});
			});
		}
	});
}

function enableWebsite(wid) {
	jConfirm('Are you sure you would like to enable this website?','Enable Confirmation',function(r) {
		if(r) {
			jQuery('#loader_block').slideDown('fast',function() {
				jQuery.ajax({
					type:'GET',
					url:'/admin/websites/enable?wid='+wid,
					success:function(id) {
						if(id) {
							jQuery('#loader_block').slideUp('fast',function() {
								loadWebsiteTable(id);
							});
						}else {
							jAlert('There was an error enabling the website you requested. Please try again.','Enable Error',function() {
								jQuery('#loader_block').slideUp('fast');	
							});
						}
					}
				});
			});
		}
	});
}


function addWebsiteForm(id,type) {
	jQuery('#addWebsite').remove();
	jQuery('#editWebsite').remove();
	jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/add_website_form?owner_type='+type+'&owner_id='+id,
			//data:{client_id:id},
			success:function(data) {
				if(data){
					jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#addWebsiteForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}
 
function editWebsiteForm(id,type,wid) {
	jQuery('#addWebsite').remove();
	jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/form?'+type+'='+id+'&wid='+wid,
			success:function(data) {
				if(data){
					jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#addWebsiteForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the client you needed. Please try again.','Add Website Error',function() {
						jQuery('#loader_block').slideUp('fast');	
					});
				}
			}
		});
	});
}

function editWebsite(id) {
	jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
			type:'GET',
			url:'/admin/websites/edit_website_form?web_id='+id,
			success:function(data) {
				if(data) {
					jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#addWebsiteForm').html(data);
					});
				}else {
					jAlert('There was a problem finding the website you requested. Please try again.','Error',function() {
						jQuery('#loader_block').slideUp('fast');
					});
				}
			}
		});
	});
}

function addWebsite(id) {
	jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
			type:'POST',
			url:'/admin/websites/edit',
			data:{wid:id},
			success:function(data) {
				if(data) {
					jQuery('#loader_block').slideUp('fast',function() {
						jAlert('The website has been successfully changed.','Success',function() {
							window.location.reload();
						});
					});
				}else {
					jAlert('There was an error editing the website','Error',function() {
						window.location.reload();
						jQuery('#loader_block').slideUp('fast');
					})
				}
			}
		});
	});
}

function submitWebsiteForm(id,type,formData,cUrl,msg) {
	jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
			type:'POST',
			url:cUrl,
			data:formData,
			success:function(data) {
				if(data == '1') {
					jQuery('#loader_block').slideUp('fast',function() {
						jAlert(msg,'Success',function() {
							$("#addWebsite").dialog('close');
							websiteListTable(id,type);
						});
					});
				}else {
					jAlert('There was a problem with processing your change. Please try again.','Error',function() {
						jQuery('#loader_block').slideUp('fast');
					});
				}
			}
		});
	});
}

function loadWebsiteTable(type,id) {
	jQuery('#addWebsite').remove();
	  jQuery('#editWebsite').remove();
	//jQuery('#addWebsiteForm').dialog('close');	
	jQuery('#websites').slideUp('fast',function() {
		jQuery('#websites').empty();
		jQuery('#loader_block').slideDown('fast',function() {
			jQuery.ajax({
				type:'GET',
				url:'/admin/websites/load_table?owner_id='+id+'&owner_type='+type,
				success:function(data) {
					jQuery('#websites').html(data);
					jQuery('#loader_block').slideUp('fast',function() {
						jQuery('#websites').slideDown('fast');
					});
				}
			});
		});
	});					
}

	function websiteListTable(type,id) {
	  jQuery('#addWebsite').remove();
	  jQuery('#editWebsite').remove();
	  jQuery('#loader_block').slideDown('fast',function() {
		jQuery.ajax({
		  type:"GET",
		  url:'/admin/websites/load_table?owner_id='+id+'&owner_type='+type,
		  success:function(data) {
			if(data) {
			  jQuery('#loader_block').delay(1000).slideUp('fast',function() {
				  jQuery('#websites').html(data);
			  });
			}else {
			  jQuery('#websites').html('<p class="noData">No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
			}
		  }
		});
	  });
	}
	