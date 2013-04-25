<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="viewClient" title="View">
        <div class="uiForm">
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="clientInfo">Client Information</a></li>
                    <li><a href="javascript:void(0);" rel="websites">Websites</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="clientInfo" class="tab_content">
        				<fieldset>
			                <div class="rowElem noborder">
			                    <label>Client Name</label>
			                    <div class="formRight"><?= $client->Name; ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Client Code</label>
			                    <div class="formRight">
                                	<?= $client->Code; ?>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Address</label>
			                    <div class="formRight"><?= $client->Address['street'] . ' ' . $client->Address['city'] . ', ' . $client->Address['state'] . ' ' . $client->Address['zipcode']; ?></div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Phone</label>
			                    <div class="formRight">
                                	<?php
										// Locate primary.
										foreach ($client->Phone as $type => $phone) {
											if ($phone == $client->PrimaryPhoneType) {
												echo $phone;
												break;
											}
										}
									?>
                                    <span class="formNote">Primary Number</span>
			                    </div>
			                    <div class="fix"></div>
			                </div>
			                <div class="rowElem noborder">
			                    <label>Notes</label>
			                    <div class="formRight">
                                	<p><?= $client->Description; ?></p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
                            <?php if($client->Reviews['Google'] != '' OR $client->Reviews['Yelp'] != '' OR $client->Reviews['Yahoo'] != '') { ?>
			                <div class="rowElem noborder">
			                	<label>Review Sites</label>
			                    <div class="formRight">
                                	<?php if(isset($client->Reviews['Google']) && $client->Reviews['Google'] != '') { ?>
                                	<a href="<?= $client->Reviews['Google']; ?>" target="_blank">Google Review Page</a><br />
                                    <?php } ?>
                                    <?php if(isset($client->Reviews['Yelp']) && $client->Reviews['Yelp'] != '') { ?>
                                    <a href="<?= $client->Reviews['Yelp']; ?>" target="_blank">Yelp Review Page</a><br />
                                    <?php } ?>
                                    <?php if(isset($client->Reviews['Yahoo']) && $client->Reviews['Yahoo'] != '') { ?>
                                    <a href="<?= $client->Reviews['Yahoo'];?>" target="_blank">Yahoo Review Page</a>
                                    <?php } ?>
			                        <p class="formNote">Click links to open a new tab</p>
			                    </div>
			                    <div class="fix"></div>
			                </div>
                            <?php } ?>
			           </fieldset>
    				</div>
                    <div id="websites" class="tab_content" style="display:none;">
                    	<?= $websites; ?>
                    </div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>			       
            </div> <? //end widget ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery('ul.tabs li a').live('click',function() {
		//remove all activetabs
		jQuery('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		jQuery(this).parent().addClass('activeTab');
		var content = 'div#' + jQuery(this).attr('rel');
		//alert(content);
		jQuery('#viewClient div.tab_container div.tab_content').hide();
		jQuery('#viewClient div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	jQuery("#viewClient").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true
	});
</script>
