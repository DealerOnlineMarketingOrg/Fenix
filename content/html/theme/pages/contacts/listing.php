<div id="loader_block">
	<div id="client_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
</div>
<div class="content">
    <div id="loader_block">
        <div id="contact_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
    </div>
    <div class="title"><h5>Contacts</div>
    <?php notifyError(); ?>
    <?php include FCPATH . 'html/global/breadcrumb.php'; ?>
    <div class="table" style="margin-top:5px;">
        <div class="head"><h5 class="iView">View All Contacts</h5></div>
        	<div id="contactTable"><?= ContactsMainTable(false,false); ?></div>
        </div>
    </div>
    <div class="fix"></div>
</div>
<div class="fix"></div>
<div id="editContactPop"></div>
<style type="text/css">
    div#loader_block{position:fixed;width:100%;height:100%;top:0;left:0;background:#fff;opacity:0.8;z-index:2000;display:none;}
    div#client_loader{position:absolute;width:16px;height:16px;top:50%;margin-top:-8px;left:50%;margin-left:-8px;}
    div#dataClient{margin-top:1px;}
</style>
<div id="addWebsiteForm"></div>
<script type="text/javascript" src="<?= base_url(); ?>js/contact_popups.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/contactInfo_popups.js"></script>