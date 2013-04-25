<!-- Content -->
    <div class="content">
    	<div class="title"><h5>Dashboard</h5></div>
        
        <!-- Statistics -->
        <div class="stats">
        	<ul>
            	<li style="width:16%;">
                	<a href="#" class="count grey" title="Number of Reviews on Bing"><?= $bingReviewCount; ?></a>
                    <span style="float:left;margin-top:10px;">Review<?= ($bingReviewCount > 1) ? 's' : ''; ?></span>
                </li>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Average Rating on Bing"><?= $bingAvgRating; ?></a>
                    <span style="float:left;margin-top:10px;">Avg. Rating</span>
                </li>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Number of High Ratings on Bing"><?= $highRatings; ?></a>
                    <span style="float:left;margin-top:10px;">High Rating<?= ($highRatings > 1) ? 's' : ''; ?></span>
                </li>
                <li style="width:16%;">
                	<a href="#" class="count grey" title="Number of Middle Ratings on Bing"><?= $midRatings; ?></a>
                    <span style="float:left;margin-top:10px;">Middle Rating<?= ($midRatings > 1) ? 's' : ''; ?></span>
                </li>
                <li style="width:16%;" class="last">
                	<a href="#" class="count grey" title="Number of Low Ratings on Bing"><?= $lowRatings; ?></a>
                    <span style="float:left;margin-top:10px;">Low Rating<?= ($lowRatings > 1) ? 's' : ''; ?></span>
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