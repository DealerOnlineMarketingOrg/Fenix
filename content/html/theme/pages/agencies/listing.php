<div id="loader_block">
    <div id="client_loader"><img src="<?= base_url() . THEMEIMGS; ?>loaders/loader2.gif" /></div>
</div>
<div class="content hideTagFilter">
    <div class="title"><h5>Agencies</div>
    <?php notifyError(); ?>
    <?php include 'html/global/breadcrumb.php'; ?>
    <div class="table" style="margin-top:5px;">
        <div class="head"><h5 class="iView">View All Agencies</h5></div>
        	<div id="agencyTable"><?= AgencyListingTable(); ?></div>
        </div>
    </div>
    <div class="fix"></div>
</div>
<div class="fix"></div>
<div id="editAgencyPop"></div>
<style type="text/css">
    div#loader_block{position:fixed;width:100%;height:100%;top:0;left:0;background:#fff;opacity:0.8;z-index:2000;display:none;}
    div#client_loader{position:absolute;width:16px;height:16px;top:50%;margin-top:-8px;left:50%;margin-left:-8px;}
    div#dataClient{margin-top:1px;}
</style>
<script type="text/javascript" src="<?= base_url(); ?>js/agency_popups.js"></script>