<!-- Content -->
    <div class="content hideTagFilter">
    	<div class="title"><h5>Dashboard</h5></div>
        <?php notifyError(); ?>
        
        <!-- Statistics -->
        <div class="stats">
        	<ul>
            	<li><a href="#" class="count grey" title="">5293</a><span>unique visitors</span></li>
                
                <li><a href="#" class="count grey" title="">520</a><span>phone calls</span></li>
                <li><a href="#" class="count grey" title="">212</a><span>forum submissions</span></li>
                <li class="last"><a href="#" class="count grey" title="">13.8</a><span>conversion ratio</span></li>
            </ul>
            <div class="fix"></div>
        </div>
                
        <!-- Charts -->
        <!-- <div class="widget first">
            <div class="head"><h5 class="iGraph">Unique Visitors</h5></div>
            <div class="body">
                <div class="chart"></div>
            </div>
        </div> -->
        
        <div class="widget first" style="border:none;">
        	<img src="<?= base_url(); ?>imgs/unique_visitors.png" style="width:100%;" />
        </div>
        <div class="widgets">
        	<div class="left">
                <div class="widget" style="border:none;">
                    <img src="<?= base_url(); ?>imgs/lead_sources.png" style="width:100%;" />
                </div>
            </div>
        <!-- Widgets -->
        <!-- <div class="widgets">
            <div class="left">
                <div class="widget">
                    <div class="head"><h5 class="iChart8">Lead Sources</h5></div>
                    <div class="body">
                        <div id="pie" class="pieWidget"></div>
                    </div>
                </div>
            </div> -->
            <div class="right">
                
                <div class="widget">       
                    <ul class="tabs">
                        <li><a href="#tab1">SEO</a></li>
                        <li><a href="#tab2">SEM</a></li>
                    </ul>
                    
                    <div class="tab_container">
                        <div id="tab1" class="tab_content">
                    <div class="head"><h5 class="iChart8">SEO</h5></div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                        <thead>
                            <tr>
                              <td width="21%">Rank</td>
                              <td>Top Keywords</td>
                              <td width="21%">SERP</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">1</a></td>
                                <td>dom motors</td>
                                <td><span class="statPlus">1</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">3</a></td>
                                <td>dom greenville sc</td>
                                <td><span class="statMinus">0</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">5</a></td>
                                <td>dom upstate</td>
                                <td><span class="statPlus">1</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">3</a></td>
                                <td>toyota camry greenville sc</td>
                                <td><span class="statPlus">1</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">9</a></td>
                                <td>toyota corolla greenville sc</td>
                                <td><span class="statMinus">0</span></td>
                            </tr>
                        </tbody>
                    </table>                    
                        </div>
                        <div id="tab2" class="tab_content">
                    <div class="head"><h5 class="iChart8">SEM</h5></div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                        <thead>
                            <tr>
                              <td width="21%">Rank</td>
                              <td>Top Keywords</td>
                              <td width="21%">Clicks</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">1</a></td>
                                <td>bill currie ford</td>
                                <td><span class="statPlus">1,153</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">2</a></td>
                                <td>bill currie</td>
                                <td><span class="statMinus">133</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">3</a></td>
                                <td>ford tampa</td>
                                <td><span class="statPlus">120</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">4</a></td>
                                <td>ford escape</td>
                                <td><span class="statPlus">119</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">5</a></td>
                                <td>ford dealers tampa</td>
                                <td><span class="statMinus">103</span></td>
                            </tr>
                        </tbody>
                    </table>                    
                        </div>
                    </div>	
                    <div class="fix"></div>		 
                </div>
            </div>
            <div class="fix"></div>
        </div>
        
        <!-- Calendar -->
        <div class="widget">
        	<div class="head"><h5 class="iDayCalendar">Deadlines</h5></div>
            <div id="calendar"></div>
        </div>
        
    </div>
    <div class="fix"></div>
	<script type="text/javascript"> 
		var data = [];
		var series = 10;
		for( var i = 0; i<series; i++) {
			data[i] = { label: "Series"+(i+1), data: Math.floor(Math.random()*100)+1 }
		}
		jQuery.plot($("#pie"), data,{
			series: {
				pie: {
					show: true
				}
			}
		});
    </script>