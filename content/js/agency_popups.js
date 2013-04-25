
var $ = jQuery;

function addAgency() {
	$('#addAgency').remove();
	$('#editAgency').remove();
	$('.ui-dialog').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/agency/add',
			success:function(code) {
				if(code) {
					$('#loader_block').slideUp('fast',function() {
						$('#editAgencyPop').html(code);	
					});
				}else {
					jAlert('The Agency requested could not be found. Please try again!','Error',function() {
						$('#loader_block').slideUp('fast');
					});
				}
			}
		});
	});
}

function editAgency(aid) {
	$('#addAgency').remove();
	$('#editAgency').remove();
	$('.ui-dialog').remove();
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:'GET',
			url:'/admin/agency/edit?aid='+aid,
			success:function(code) {
				if(code) {
					$('#loader_block').slideUp('fast',function() {
						$('#editAgencyPop').html(code);
					});
				}else{
					jAlert('The Agency requested could not be found. Please Try Again!','Error',function() {
						$('#loader_block').slideUp('fast');
					});
				}
			}
		});
	});
}

function agencyListTable() {
	$('#addAgency').dialog('close');
	$('#editAgency').dialog('close');
	$('.ui-dialog').remove();
	
	$('#editAgency').dialog('destroy');
	$('#loader_block').slideDown('fast',function() {
		$.ajax({
			type:"GET",
			url:'/admin/agency/load_table',
			success:function(data) {
				if(data) {
					$('#agencyTable').html(data);
					$('#loader_block').slideUp('fast',function() {
						$('#agencyTable').slideDown('fast');
							$('#example').dataTable({
								"bJQueryUI": true,
								"sPaginationType": "full_numbers",
								"sDom": '<""f>t<"F"lp>',
								'iDisplayLength':1000,
								"aLengthMenu": [[-1,10,25,50],['All',10,25,50]]
							});
						});
					}else {
						$('#agencyTable').html('<p>No clients found at this level. Please use the Dealer Dropdown to change to a different group.</p>');
					}
				}
		});
	});
}
