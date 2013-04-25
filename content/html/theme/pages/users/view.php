    <div class="content hideTagFilter">
    	<div class="title">
        	<h5>Contact Information</h5>
        </div>
        <?php include FCPATH . 'html/global/breadcrumb.php'; ?>
    	<div class="widget" style="margin-top:5px;">
        	<div class="head">
    			<h5 class="iUser"><?= $display->FirstName . ' ' . $display->LastName; ?></h5>
            </div>
            <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
            	<tbody>
                    <tr>
                    	<td width="10%">
                    </tr>
                    <tr>
                    	<td width="10%">Parent Client:</td>
                        <td><?= $display->Dealership; ?></td>
                    </td>
                    <tr>
                    	<td width="10%">Name:</td>
                        <td><?= $display->FirstName . ' ' . $display->LastName; ?></td>
                    </tr>
                    <tr>
                      	<td width="10%" style="vertical-align:top;">Emails:</td>
                        <td>
                        	<?php
                  				// Locate primary.
                                foreach ($contact->Email as $type => $email) {
                                    if ($email == $contact->PrimaryEmailType) { ?>
	                                    <span class="red"><strong>Personal:</strong></span><br />
                                        <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                                        <?php break;
                                    }
                                }
                                // Locate others.
                                foreach ($contact->Email as $type => $email) {
                                    if ($email != $contact->PrimaryEmailType) { ?>
	                                    <span class="red"><strong><?= $type; ?>:</strong></span><br />
                                        <a href="mailto:<?= $email; ?>"><?= $email; ?></a>
                                    <?php }
                                }
							?>
                        </td>
                    </td>
                    <?php if($display->Address) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Address:</td>
                        <td><?= $display->Address; ?></td>
                    </tr>
                    <?php } ?>
                    <?php if(isset($display->Phone)) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Phone:</td>
                        <td>
							<?php
								// Locate primary.
								foreach ($contact->Email as $type => $phone) {
									if ($phone == $contact->PrimaryPhoneType) { ?>
										<span class="red"><strong>Direct:</strong></span><br />
										<a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
										<?php break;
									}
								}
								// Locate others.
								foreach ($contact->Email as $type => $phone) {
									if ($phone != $contact->PrimaryPhoneType) { ?>
										<span class="red"><strong><?= $type; ?>:</strong></span><br />
										<a href="tel:<?= $phone; ?>"><?= $phone; ?></a>
									<?php }
								}
                            ?>                            
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($display->Notes) { ?>
                    <tr>
                    	<td width="10%" style="vertical-align:top;">Notes:</td>
                        <td><p><?= $display->Notes; ?></p></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="fix"></div>
    </div>
    <div class="fix"></div>