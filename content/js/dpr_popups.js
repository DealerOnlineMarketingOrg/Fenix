
var $ = jQuery.noConflict();

function addDpr() {
	$('#addMetrics').remove();
	
	$.ajax({
		type:'GET',
		url:'/dpr/add',
		success:function(code) {
			if(code == '0') {
				jAlert('Something went wrong. Please try again','Error');
			}else {
				$('#dprAddPop').html(code);
				loadInit();
			}
		}
	});
}
