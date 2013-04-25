<!-- Content -->
<div class="content hideTagFilter" id="container">
    <div class="title"><h5>Reports</h5></div>
    <?php notifyError(); ?>
    <?php echo  (($html) ? $html : ''); ?>
    <!-- Form begins -->
    <?php
		$dateRange = $this->input->post();
		
        $form = array(
            'name' => 'reportDpr',
            'class' => 'mainForm valid'
        );
		
        echo form_open('/dpr',$form);
    ?>
	        <!-- Input text fields -->
        <fieldset>
        	<div class="widget first" style="padding:5px">
               <?php if ($this->user['DropdownDefault']->LevelType != 'c') { ?>
					<div class="head"><h5 class="iList">Add DPR Lead: <span style="color:red">No dealer is selected. Please select a dealer to input DPR leads.</span></h5></div>
                
				<?php } else { ?>
                    <div class="head"><h5 class="iList">Add DPR Lead</h5></div>
                    <div id="reportEntry">
                    	<div style="position:relative;float:left;margin:5px">
                            <label>Starting Month</label>
							<div class="fix"></div>
                            <label style="margin-top:5px">Starting Year</label>
                            <div class="fix"></div>
                            <label style="margin-top:5px">Ending Month</label>
                            <div class="fix"></div>
                            <label style="margin-top:5px">Ending Year</label>
                        </div>
                    	<div class="noSearch" style="position:relative;float:left;margin:5px">
                            <select id="startMonth" name="startMonth" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a month...">
                                <option value="">Select a Month</option>
                                <option value="1"<?php if ($dateRange['startMonth'] == 1) echo ' selected' ?>>January</option>
                                <option value="2"<?php if ($dateRange['startMonth'] == 2) echo ' selected' ?>>Febuary</option>
                                <option value="3"<?php if ($dateRange['startMonth'] == 3) echo ' selected' ?>>March</option>
                                <option value="4"<?php if ($dateRange['startMonth'] == 4) echo ' selected' ?>>April</option>
                                <option value="5"<?php if ($dateRange['startMonth'] == 5) echo ' selected' ?>>May</option>
                                <option value="6"<?php if ($dateRange['startMonth'] == 6) echo ' selected' ?>>June</option>
                                <option value="7"<?php if ($dateRange['startMonth'] == 7) echo ' selected' ?>>July</option>
                                <option value="8"<?php if ($dateRange['startMonth'] == 8) echo ' selected' ?>>August</option>
                                <option value="9"<?php if ($dateRange['startMonth'] == 9) echo ' selected' ?>>September</option>
                                <option value="10"<?php if ($dateRange['startMonth'] == 10) echo ' selected' ?>>October</option>
                                <option value="11"<?php if ($dateRange['startMonth'] == 11) echo ' selected' ?>>November</option>
                                <option value="12"<?php if ($dateRange['startMonth'] == 12) echo ' selected' ?>>December</option>
                            </select>
                            <div class="fix"></div>
                            <select id="startYear" name="startYear" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a year...">
                                <option value="">Select a Year</option>
                                <option value="2013"<?php if ($dateRange['startYear'] == 2013) echo ' selected' ?>>2013</option>
                                <option value="2012"<?php if ($dateRange['startYear'] == 2012) echo ' selected' ?>>2012</option>
                                <option value="2011"<?php if ($dateRange['startYear'] == 2011) echo ' selected' ?>>2011</option>
                                <option value="2010"<?php if ($dateRange['startYear'] == 2010) echo ' selected' ?>>2010</option>
                                <option value="2009"<?php if ($dateRange['startYear'] == 2009) echo ' selected' ?>>2009</option>
                            </select>
                            <div class="fix"></div>
                            <select id="endMonth" name="endMonth" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a month...">
                                <option value="">Select a Month</option>
                                <option value="1"<?php if ($dateRange['endMonth'] == 1) echo ' selected' ?>>January</option>
                                <option value="2"<?php if ($dateRange['endMonth'] == 2) echo ' selected' ?>>Febuary</option>
                                <option value="3"<?php if ($dateRange['endMonth'] == 3) echo ' selected' ?>>March</option>
                                <option value="4"<?php if ($dateRange['endMonth'] == 4) echo ' selected' ?>>April</option>
                                <option value="5"<?php if ($dateRange['endMonth'] == 5) echo ' selected' ?>>May</option>
                                <option value="6"<?php if ($dateRange['endMonth'] == 6) echo ' selected' ?>>June</option>
                                <option value="7"<?php if ($dateRange['endMonth'] == 7) echo ' selected' ?>>July</option>
                                <option value="8"<?php if ($dateRange['endMonth'] == 8) echo ' selected' ?>>August</option>
                                <option value="9"<?php if ($dateRange['endMonth'] == 9) echo ' selected' ?>>September</option>
                                <option value="10"<?php if ($dateRange['endMonth'] == 10) echo ' selected' ?>>October</option>
                                <option value="11"<?php if ($dateRange['endMonth'] == 11) echo ' selected' ?>>November</option>
                                <option value="12"<?php if ($dateRange['endMonth'] == 12) echo ' selected' ?>>December</option>
                            </select>
                            <div class="fix"></div>
                            <select id="endYear" name="endYear" class="input chzn-select required validate[required]" style="width:120px" placeholder="Select a year...">
                                <option value="">Select a Year</option>
                                <option value="2013"<?php if ($dateRange['endYear'] == 2013) echo ' selected' ?>>2013</option>
                                <option value="2012"<?php if ($dateRange['endYear'] == 2012) echo ' selected' ?>>2012</option>
                                <option value="2011"<?php if ($dateRange['endYear'] == 2011) echo ' selected' ?>>2011</option>
                                <option value="2010"<?php if ($dateRange['endYear'] == 2010) echo ' selected' ?>>2010</option>
                                <option value="2009"<?php if ($dateRange['endYear'] == 2009) echo ' selected' ?>>2009</option>
                            </select>
                    	</div>
						<div class="fix"></div>
                    </div>
                <?php } ?>
            </div>
            <div class="submitForm">
	            <input type="button" id="cancel" value="Cancel" class="basicBtn" />
	            <input type="reset" id="reset" value="Reset" class="basicBtn" />
            	<input type="submit" value="submit" class="redBtn" />
            </div>
        </fieldset>
    <?php echo  form_close(); ?>
    
    <?php
        $form = array(
			'id' => 'reportDprEditCancel',
            'name' => 'reportDprEditCancel',
            'class' => 'mainForm valid'
        );
		echo form_open('%page%',$form); ?>
    	<input ID="startMonthCancel" name="startMonth" type="hidden" value="<?php echo $dateRange['startMonth']; ?>" />
        <input ID="startYearCancel" name="startYear" type="hidden" value="<?php echo $dateRange['startYear']; ?>" />
        <input ID="endMonthCancel" name="endMonth" type="hidden" value="<?php echo $dateRange['endMonth']; ?>" />
        <input ID="endYearCancel" name="endYear" type="hidden" value="<?php echo $dateRange['endYear']; ?>" />
    <?php echo  form_close(); ?>
    
    <script type="text/javascript">
	
		$('input#cancel').click(function() {
			// Go back to report page with cancelled values.
			jQuery('form#reportDprEditCancel').attr('action', '<?= base_url(); ?>dpr');
			$("form#reportDprEditCancel").submit();
		});
		
		$('input#reset').click(function() {
			// Refresh edit report page with cancelled values.
			jQuery('form#reportDprEditCancel').attr('action', '<?= base_url(); ?>dpr/editReport');
			$("form#reportDprEditCancel").submit();
		});
		
	</script>
</div>
<div class="fix"></div>