<!-- Content -->
<div class="content hideTagFilter" id="container">
    <div class="title"><h5>Reports</h5></div>
	<script type="text/javascript" src="<?= base_url(); ?>js/input_popups.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>js/dpr_popups.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>js/wizardDialog.js"></script>
    <?php notifyError(); ?>
    <?php include 'html/global/breadcrumb.php'; ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
        $form = array(
			'id' => 'reportDpr',
            'name' => 'reportDpr',
            'class' => 'mainForm valid'
        );
	?>
		<?php echo form_open('%page%',$form); ?>
        
        <?php
			$startDate = $dateRange['startMonth'].'/1/'.$dateRange['startYear'];
			$endDate = $dateRange['endMonth'].'/1/'.$dateRange['endYear'];
			$lowerStart = '1/1/2000';
			$upperStart = $endDate;
			$lowerEnd = $startDate;
			$upperEnd = '12/1/' . date('Y');
		?>
            <!-- Input text fields -->
            <fieldset>
			<?php if ($this->user['DropdownDefault']->LevelType != 'c') { ?>
	            <div>
                    <div style="width:1;float:left;vertical-align:middle">
                        <input ID="add" class="greenBtn" type="button" value="Sources" />
                    </div>
                </div>
   	            <div class="widget" style="margin-top:0px">
                	<div class="head"><h5 class="iList">DPR Leads Report: <span style="color:red">No dealer is selected. Please select a dealer to view the DPR reports.</span></h5></div>
				</div>
            <?php } else { ?>
            	<div>
                    <div style="width:1;float:left;vertical-align:middle">
                        <input ID="add" class="greenBtn" type="button" value="Add Source" />
                        <input ID="edit" class="redBtn" type="button" value="Edit Sources" />
                        <input ID="import" class="yellowBtn" style="color:black" type="button" value="Bulk Import" />
                    </div>
                    <div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                        <input ID="email" class="greyishBtn" type="button" value="Email" />
                    </div>
                </div>
                <div class="fix"></div>
                <div class='noSearch'>
	                <div style="position:relative;float:left;border:solid 1px #D5D5D5;padding-top:5px;padding-right:5px">
                    	<div style="float:left">
                            <label style="margin-right:0">Date Range:</label>
                            <?php dateButtons($lowerStart,$upperStart,$startDate,'start'); ?>
                        </div>
                        <div style="float:left">
                            <label style="margin-right:0">To</label>
                            <?php dateButtons($lowerEnd,$upperEnd,$endDate,'end'); ?>
                        </div>
                        <div class="fix"></div>
                    </div>
                </div>
                <div id="reportLive">
                    <div id="fullReportID" class="widget first" style="margin-top:0px !important">
                        <div class="head"><h5 class="iList">DPR Leads Report</h5>
                        </div>
                        <div class="rowElem noborder">
                            <!-- Line graph of year -->
                            <div class="widgets">
                                <div class="left">
                                    <div id="lineID" class="widget" style="margin-top:0px !important">
                                        <div class="head"><h5 id="update" class="iGraph">Leads Per Month</h5></div>
                                        <div class="body">
                                            <? echo $report_lineChart; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Pie total overall -->
                                <div class="right">
                                    <div id="pieID" class="widget" style="margin-top:0px !important">
                                        <div class="head"><h5 class="iChart8">Total Leads</h5></div>
                                        <div class="body">
                                            <? echo $report_pieChart; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="fix"></div>
                            </div>
                        </div>
                        <div class="rowElem noborder">
                            <!-- Provider data lists -->
                            <div id="tableID">
                                <?php echo $report_leads; ?>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="fix"></div>
                    </div>
                    <div style="width:1;float:left;vertical-align:middle">
                        <input ID="add" class="greenBtn" type="button" value="Add Leads" />
                        <input ID="edit" class="redBtn" type="button" value="Edit Sources" />
                        <input ID="import" class="yellowBtn" style="color:black" type="button" value="Bulk Import" />
                    </div>
                    <div style="width:1;float:right;vertical-align:middle">
                        <input ID="excel" class="greyishBtn" type="button" value="Excel" />
                        <input ID="pdf" class="greyishBtn" type="button" value="PDF" />
                        <input ID="email" class="greyishBtn" type="button" value="Email" />
                    </div>
				</div>
                <div id="inputInfo"></div>
                <div id="dynDialog"></div>
                <div id="dprAddPop"></div>
                <div id="dprEditPop"></div>
                <div id="dprImportPop"></div>
			<?php } ?>
    	</fieldset>
    <?php echo  form_close(); ?>
	
    <script type="text/javascript">
		var excelCreated = false;
		var pdfCreated = false;
		
		$('input#add').click(function() {
			// Go to add report lead page with date range values.
			//addDpr();
			wizardDialog("#dprAddPop", [ '/dpr/add_step1', '/dpr/add_step2', '/dpr/add_step3', '/dpr/add_stepSubmit', 'redirect:/dpr' ]);
			//jQuery('form#reportDpr').attr('action', '<?= base_url(); ?>dpr/add');
			//$("form#reportDpr").submit();
		});

		$('input#edit').click(function() {
			// Go to add report lead page with date range values.
			//addDpr();
			wizardDialog("#dprEditPop", [ '/dpr/edit_step1', '/dpr/edit_step2', '/dpr/edit_step3', '/dpr/edit_stepSubmit', 'redirect:/dpr' ]);
			//jQuery('form#reportDpr').attr('action', '<?= base_url(); ?>dpr/add');
			//$("form#reportDpr").submit();
		});
		
		$('input#import').click(function() {
			wizardDialog("#dprImportPop", [ '/dpr/import_step1', '/dpr/import_step2', '/dpr/import_step3', '/dpr/import_step4', '/dpr/import_stepSubmit', 'redirect:/dpr' ]);
		});

		
		function setToBounds(lowerDate, upperDate, date) {
			// If date is out of bounds, set it to the closest bounded date.
			if (date < lowerDate)
				boundDate = lowerDate;
			else if (date > upperDate)
				boundDate = upperDate;
			else
				boundDate = date;
			return boundDate;
		}
		
		var startLock = false;
		$("#startMonth,#startYear").change(function() {
			if (!startLock) {
				endLock = true;
				// Go to report edit page with date range values.
				jQuery('form#reportDpr').attr('action', '<?= base_url(); ?>dpr/editReport');
				startDate = $("#startMonth").val() + "/1/" + $("#startYear").val();
				// We're changing the start dates. Make sure the end dates don't conflict.
				lowerDateObj = new Date(startDate);
				upperDateObj = new Date('<?php echo $upperEnd; ?>');
				endDate = $("#endMonth").val() + "/1/" + $("#endYear").val();
				endDateObj = new Date(endDate);
				endDateObj = setToBounds(lowerDateObj, upperDateObj, endDateObj);
				$("#endMonth").val(endDateObj.getMonth()+1);
				$("#endYear").val(endDateObj.getFullYear());
				$("form#reportDpr").submit();
			}
		});
		var endLock = false;
		$("#endMonth,#endYear").change(function() {
			if (!endLock) {
				startLock = true;
				// Go to report edit page with date range values.
				jQuery('form#reportDpr').attr('action', '<?= base_url(); ?>dpr/editReport');
				endDate = $("#endMonth").val() + "/1/" + $("#endYear").val();
				// We're changing the end dates. Make sure the start dates don't conflict.
				lowerDateObj = new Date('<?php echo $lowerStart; ?>');
				upperDateObj = new Date(endDate);				
				startDate = $("#startMonth").val() + "/1/" + $("#startYear").val();
				startDateObj = new Date(startDate);
				startDateObj = setToBounds(lowerDateObj, upperDateObj, startDateObj);
				$("#startMonth").val(startDateObj.getMonth());
				$("#startYear").val(startDateObj.getFullYear());
				$("form#reportDpr").submit();
			}
		});
		
		
		$('input#email').click(function() {
			getEmailInfo();
		});

		function getEmailInfo() {
			if (!pdfCreated)
				createPDF();
			
			inputPopup({
				type:'dualList',
				dataFunc:'<?php base_url(); ?>dpr/eMail',
				success:function(data) {
					var namesHtml = '<div id="namesDialog" title="Email Names"><p>Send the DPR report to these people?</p>';
					for (var i = 0; i < data.data.length; i=i+2) {
						if (data.data[i] != '') {
							namesHtml += '<p>' + data.data[i] + '</p>';
						}
					}
					namesHtml += '</div>';
					$("#dynDialog").html(namesHtml);
					$("#namesDialog").dialog({
						buttons: {
							Back:function() {
								$(this).dialog("close");
								$(this).empty();
								$(this).remove;
								getEmailInfo();
							},
							Cancel:function() {
								$(this).dialog("close");
								$(this).empty();
								$(this).remove;
							},
							Send:function() {
								$(this).dialog("close");
								$(this).empty();
								$(this).remove;
								var addresses = '';
								for (var i = 1; i < data.data.length; i=i+2) {
									if (data.data[i] != '') {
										addresses += data.data[i] + ',';
									}
								}
								doEmail('',addresses);
							}
						}
					});
				},
				error:function() {
					var dialogHtml = $('<div id="emailDialog" title="Email"><p>DPR email has been cancelled. The DPR report email has not been sent out.</p></div>');
					$("#dynDialog").html(dialogHtml);
					$("#emailDialog").dialog({
						buttons: {
							Ok:function() {
								$(this).dialog("close");
								$(this).empty();
								$(this).remove;
							}
						}
					});
				}
			});
		}
		
		function doEmail(ccRecipients,bccRecipients) {
			var msg = '<head></head><body><p>Attached is your Digital Performance Report, which we will be discussing on our next call.</p>' +
					  '<p>Thank you.</p>';
			var sig = '<?php echo str_replace("'", "\'", $signatureFragment); ?>';
			if (sig != '')
				msg += '<p>--</p><p>' + sig + '</p>';
			msg += '</body>';
			var email = {
				sender_email:'<?php echo $user['Username']; ?>',
				sender_name:'<?php echo ($user['FirstName'] . ' ' . $user['LastName']); ?>',
				reply_to_email:'<?php echo $user['Username']; ?>',
				reply_to_name:'<?php echo ($user['FirstName'] . ' ' . $user['LastName']); ?>',
				to:'<?php echo $user['Username']; ?>',
				cc:ccRecipients,
				bcc:bccRecipients,
				subject:'DPR report',
				message:msg,
				attachments:'uploads/dprReport.pdf'
			};
			$.ajax({type:"POST",
				url:"<?= base_url(); ?>io/sendEmail",
				data:email,
				success:function(msg) {
					var dialogHtml = $('<div id="emailDialog" title="Email"><p>The DPR Report has been sent to the dealer via email.</p></div>');
					$("#dynDialog").html(dialogHtml);
					$("#emailDialog").dialog({
						buttons: {
							Ok:function() {
								$(this).dialog("close");
								$(this).empty();
								$(this).remove;
							}
						}
					});
				}
			});
		};
		
		$(window).load (function() {
			plotLineChart();
			plotPieChart();
		});
		
		function plotLineChart() {<?php echo $report_lineChart_script; ?>}
		function plotPieChart() {<?php echo $report_pieChart_script; ?>}
	
		// Use base64 encoding if XSS filtering is active, since
		//  XSS will strip certain tags, like style.


		// All function calls defined here (for deferreds).
		
		// Gets a dataURL from the element's html (includes children),
		//  and saves that to the server as an image.
		function HTMLToImage(imgFile,element)
			{$(element).html2canvas({onrendered:function(canvas){
					 dataURL = canvas.toDataURL("image/png");
					 return $.ajax({type:"POST",
									url:"<?= base_url(); ?>io/saveDataURL",
									data:{data:dataURL, destPath:imgFile}});
			}});}
		
		// Converts a html piece into an excel file, saving it to
		//  destFile (on the server).
		function convertToExcel(destFile,reportHtml)
			{return $.ajax({type:"POST",
							url:"<?= base_url(); ?>converter",
							data:{type:"excel", file:destFile, html:reportHtml}});
			}
							
		// Converts a html piece into an PDF file, saving it to
		//  destFile (on the server).
		function convertToPDF(destFile,image,imageScale)
			{return $.ajax({type:"POST",
							url:"<?= base_url(); ?>converter",
							data:{type:"PDF", file:destFile, img:image, scale:imageScale}});
			}
							
		// Zips up the files in fileList and saves as zipFile (on the server).
		function saveZip(zipFile,fileList)
			{return $.ajax({type:"POST",
							url:"<?= base_url(); ?>io/zipFiles",
							data:{file_list:fileList, zip_file:zipFile}});
			}
		
		// Retrieves the zip file (as a download-file option).
		function getZip(zipFile)
			{$.fileDownload("<?= base_url(); ?>" + zipFile);
			}
		
		$('input#excel').click(function() {
			if (!excelCreated)
				createExcel();
			downloadExcel();
		});
		
		function downloadExcel() {
			// getZip
			$.fileDownload("<?= base_url(); ?>" + "uploads/dprReportExcel.zip");
		}
		
		function createExcel() {
			// Compile the report.
			var lineChartFile = "uploads/lineChart.png";
			var pieChartFile = "uploads/pieChart.png";
			var report_leads = "<?php echo str_replace('"','\\"',$report_leads); ?>";
			var report = report_leads.replace(/(<table[^>]*?>)/i, "$1" +
				"<tr>" +
					"<td><img src=\"" + lineChartFile + "\" /></td>" +
					"<td></td><td></td><td></td><td></td><td></td>" +
					"<td><img src=\"" + pieChartFile + "\" /></td>" +
				"</tr>");			
			
			// HTMLToImage (lineChartFile)
			$("#lineID").html2canvas({onrendered:function(canvas){
					 dataURL = canvas.toDataURL("image/png");
					 $.ajax({type:"POST",
							url:"<?= base_url(); ?>io/saveDataURL",
							data:{data:dataURL, destPath:lineChartFile},
							success:(function() {
								// HTMLToImage (pieChartFile)
								$("#pieID").html2canvas({onrendered:function(canvas){
										 dataURL = canvas.toDataURL("image/png");
										 $.ajax({type:"POST",
												url:"<?= base_url(); ?>io/saveDataURL",
												data:{data:dataURL, destPath:pieChartFile},
												success:(function() {
													// convertToExcel
													$.ajax({type:"POST",
															url:"<?= base_url(); ?>converter",
															data:{type:"excel", file:"uploads/dprReport.xlsx", html:report},
															success:(function() {
																// saveZip
																$.ajax({type:"POST",
																		url:"<?= base_url(); ?>io/zipFiles",
																		data:{file_list:[ "uploads/dprReport.xlsx" ], zip_file:"uploads/dprReportExcel.zip"},
																		success:(function() {
																			excelCreated = true;
																		})
																});
															})
													});
												})
										 });
								}});
							})
					});
			}});
		}
		
		$('input#pdf').click(function() {
			if (!pdfCreated)
				createPDF();
			downloadPDF();
		});
		
		function downloadPDF() {
			// getZip
			$.fileDownload("<?= base_url(); ?>" + "uploads/dprReportPDF.zip");
		}
		
		function createPDF() {
			fullReportFile = "uploads/fullReport.png";
			
			// HTMLToImage
			$("#fullReportID").html2canvas({onrendered:function(canvas){
					 dataURL = canvas.toDataURL("image/png");
					 $.ajax({type:"POST",
							url:"<?= base_url(); ?>io/saveDataURL",
							data:{data:dataURL, destPath:fullReportFile},
							success:(function() {
								// convertToPDF
								$.ajax({type:"POST",
										url:"<?= base_url(); ?>converter",
										data:{type:"pdf", file:"uploads/dprReport.pdf", img:fullReportFile, scale:1},
										success:(function(data, textStatus) {
											// saveZip
											$.ajax({type:"POST",
													url:"<?= base_url(); ?>io/zipFiles",
													data:{file_list:[ "uploads/dprReport.pdf" ], zip_file:"uploads/dprReportPDF.zip"},
													success:(function() {
														pdfCreated = true;
													})
											});
										})
								});
							})
					});
			}});
		}
		
	</script>
</div>
<div class="fix"></div>