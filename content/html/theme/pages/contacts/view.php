<div class="uDialog" style="text-align:left;">
    <div class="dialog-message popper" id="viewContact" title="View Contact Details">
        <div class="uiForm">
            <div class="widget" style="margin-top:0;padding-top:0;margin-bottom:10px;">
            	<ul class="tabs">
            		<li class="activeTab"><a href="javascript:void(0);" rel="ContactDetails">Contact Details</a></li>
            		<li class=""><a href="javascript:void(0);" rel="Website">Website</a></li>
            		<li class=""><a href="javascript:void(0);" rel="ContactInfo">Contact Info</a></li>
            	</ul>
            	<div class="tab_container">
            		<div id="ContactDetails" class="tab_content" style="padding:0;">
                        <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                            <thead>
                                <tr>
                                    <td width="37px"></td>
                                    <td width=""></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="iUser"></td>
                                    <td>Name: <?= $contact->Name; ?></td>
                                </tr>
                                    <td class="iVcard"></td>
                                    <td>Title: <?= $contact->JobTitle; ?></td>
                                </tr>
                                <tr>
                                    <td class="iHome"></td>
                                    <td>Address: <?= $contact->Address['street'].'  '.$contact->Address['city'].', '.$contact->Address['state'].' '.$contact->Address['zipcode']; ?></td>
                                </tr>
                                <tr>
                                	<td class="iMail"></td>
                                    <td>Primary Email: <span style="white-space:nowrap;"><a href="tel:'<?= ($contact->PrimaryPhone) ? $contact->PrimaryPhone : ''; ?>'"><?= ($contact->PrimaryPhone) ? $contact->PrimaryPhone : ''; ?></a></span></td>
                                </tr>
                                <tr>
                                    <td class="iPhone"></td>
                                    <td>Primary Phone: <span style="white-space:nowrap;"><a href="mailto:'<?= ($contact->PrimaryEmail) ? $contact->PrimaryEmail : ''; ?>'"><?= ($contact->PrimaryEmail) ? $contact->PrimaryEmail : ''; ?></a></span></td>
                                </tr>
                                <tr>
                                    <td class="iCompanies"></td>
                                    <td>Member of: <?= $contact->Dealership; ?></td>
                                </tr>
                                <tr>
                                    <td class="iClipboard"></td>
                                    <td>Notes: <?= $contact->Notes; ?></td>
                                </tr>
                            </tbody>
                        </table>
    				</div>
    				<div class="fix"></div>
            		<div id="Website" class="tab_content" style="display:none;padding:0;">
                        <?= $websites; ?>
    				</div>
            		<div id="ContactInfo" class="tab_content" style="display:none;padding:0;">
						<?= $contactInfo; ?>
                    </div>
    				<div class="fix"></div>
    			</div>	
    			<div class="fix"></div>		
            </div> <? //end widget ?>
		</div>
	</div>
</div>

<script type="text/javascript">
	//re initialize jQuery
	var $ = jQuery.noConflict();

	$('ul.tabs li a').live('click',function() {
		//remove all activetabs
		$('ul.tabs').find('li.activeTab').removeClass('activeTab');
		
		$(this).parent().addClass('activeTab');
		var content = 'div#' + $(this).attr('rel');
		//alert(content);
		$('#viewContact div.tab_container div.tab_content').hide();
		$('#viewContact div.tab_container').find(content).css({'display':'block'});
		//alert(content);
	});
	//jQuery("div[class^='widget']").simpleTabs();
	$("#viewContact").dialog({
		minWidth:800,
		height:500,
		autoOpen: true,
		modal: true,
		buttons: [
			{
				class:'greyBtn',
				text:'Close',
				click:function() {$(this).dialog('close')}
			},
		]
	});
</script>