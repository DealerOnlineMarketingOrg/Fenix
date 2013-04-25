
    <div class="errorPage" style="margin-top:0;">
        <h2 class="red errorTitle"><span>Access Denied</span></h2>
        <h1>Nope</h1>
        <span class="bubbles"></span>
        <p>Oops! Sorry, you do not have the right credentials to access this feature! Please contact your admin to suggest that you should be able to do this. Till then...</p>
        <p><img src="<?= base_url(); ?>imgs/You-shall-not-pass.jpeg" /></p>
        <div class="backToDash"><a href="<?= base_url(); ?>" title="" class="seaBtn button">Back to Dashboard</a></div>
    </div>
    
    <script type="text/javascript">
		$(document).ready(function() {
			$('div.leftNav').css({'display':'none'});
		});
	</script>
