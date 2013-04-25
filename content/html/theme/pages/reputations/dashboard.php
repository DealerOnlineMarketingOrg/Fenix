<!-- Content -->
    <div class="content">
    	<div class="title"><h5>Reputation Dashboard</h5></div>
        
        <!-- Statistics -->
        <div class="stats">
        	<ul>
            
            	<?php //Count the Google reviews ?>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Number of Reviews on Google">0</a>
                    <span style="float:left;margin-top:10px;">Google Reviews</span>
                </li>
                
                <?php //Count the Yelp Reviews ?>
            	<li style="width:16%;">
                	<a href="#" class="count grey" title="Number of Reviews on Yelp">0</a>
                    <span style="float:left;margin-top:10px;">Yelp Reviews</span>
                </li>
                
                <?php //Count Yahoo Reviews ?>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Number of Reviews on Yahoo">0</a>
                    <span style="float:left;margin-top:10px;">Yahoo Reviews</span>
                </li>
                
                <?php //Count Bing Reviews ?>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Number of Reviews on Bing">0</a>
                    <span style="float:left;margin-top:10px;">Bing Reviews</span>
                </li>
                
                <?php //Count total Reviews across all networks ?>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Total Number of Reviews from Google, Yelp, Yahoo and Bing">0</a>
                    <span style="float:left;margin-top:10px;">Total Reviews</span>
                </li>
            </ul>
            <div class="fix"></div>
        </div>
        
        <div class="widgets">
        	<div class="left">
                <div class="widget first">
                    <div class="head"><h5 class="iStar">Ratings Distribution</h5></div>
                    <div class="body">
                        <div class="bars" id="vBar"></div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="widget first">
                    <div class="head"><h5 class="iTable">Distribution by Quality Score</h5></div>
                    <table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
                        <thead>
                            <tr>
                              <td width="21%">Amount of Reviews</td>
                              <td>Description</td>
                              <td width="21%">Changes</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">980</a></td>
                                <td>Google</td>
                                <td><span class="statPlus">0.32%</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">1545</a></td>
                                <td>Yahoo</td>
                                <td><span class="statMinus">82.3%</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">457</a></td>
                                <td>Yelp</td>
                                <td><span class="statPlus">100%</span></td>
                            </tr>
                            <tr>
                                <td align="center"><a href="#" title="" class="webStatsLink">9543</a></td>
                                <td>Bing</td>
                                <td><span class="statPlus">4.99%</span></td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>
            <div class="fix"></div>
        </div>
        <div class="widgets">
        	<div class="left">
                <div class="widget first">
                    <div class="head"><h5 class="iMagicMouse">Distribution by Source</h5></div>
                    <div class="body">
                        <div class="bars" id="vBar"></div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="widget first">
                    <div class="head"><h5 class="iGraph">Distribution by Quality Score</h5></div>
                    <div class="body">
                        <div class="chart"></div>
                    </div>
                </div>
            </div>
            <div class="fix"></div>
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