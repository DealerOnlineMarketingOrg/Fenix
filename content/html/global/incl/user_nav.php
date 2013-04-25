<!-- Top navigation bar -->
<div id="topNav">
    <div class="fixed">
        <div class="wrapper">
        	<?php if(isset($avatar)): ?>
        	<div class="welcome">
            	<a href="<?= base_url(); ?>profile/<?= strtolower($user['FirstName'] . $user['LastName']); ?>"><img style="width:22px;" src="<?= $avatar; ?>" alt="<?= $user['FirstName'] . ' ' . $user['LastName']; ?>" /><span>Welcome, <?= $user['FirstName']; ?></span></a>
            </div>
            <div class="clientInfo" id="clientInformation">
            	<span class="title"><?= get_client_type(); ?></span><span><?= get_client_name(); ?></span>
            </div>
            <div class="userNav">
                <ul>
                	<!-- <li><a href="<?= base_url(); ?>mysettings"><img src="<?= base_url(); ?>imgs/icons/topnav/settings.png" alt="" /><span>Profile Settings</a></li> -->
                    <li><a href="<?= base_url(); ?>logout"><img src="<?= base_url(); ?>imgs/icons/topnav/logout.png" alt="" /><span>Logout</a></li>
                </ul>
            </div>
            <div class="dealerDropdowns">
                <div id="myDealers" class="rowElem searchDrop" style="float:left;margin-right:5px;">
					<?= dealer_selector(); ?>        
            	</div>
            </div>
            <?php endif; ?>
            <div class="fix"></div>
        </div>
    </div>
</div>

<!-- if the user is idle for an hour, log them out -->
<script type="text/javascript">
	setInterval(function() {
		window.location.href="<?= base_url(); ?>logout";		
	},3600000);
</script>

