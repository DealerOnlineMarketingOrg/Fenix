<?
	$full_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	$full_url = explode('/',$full_url);
?>
<div class="leftNav">
    <!-- <div class="searchWidget searchMe">
        <form action="#" method="post">
            <input id="ac" class="ui-autocomplete-input" type="text" placeholder="Enter search text..." name="search" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true" />
            <input type="submit" value="" name="find" />
        </form>
    </div> -->
	<div class="fix"></div>
    <ul id="menu">
        <li class="dash"><a <?=  (!$active_button) ? 'class="active"' : ''; ?> href="<?=  base_url(); ?>"><span>Dashboard</span></a></li>
        <?php foreach($nav as $item) { 
			if($item['ViewLevel'] == 0 OR $item['ViewLevel'] == $activeLevel) { ?>
			<?php if($item['Class'] == $active_button) { ?>
                <li class="<?= $item['Class']; ?>"><a class="active" href="<?=  $item['Href']; ?>"><span><?=  $item['Label']; ?></span></a>
            <?php }else { ?>
                <li class="<?=  $item['Class']; ?>"><a class="exp" href="<?=  $item['Href']; ?>"><span><?=  $item['Label']; ?></span></a>
            <?php } ?>
			<?php if(count($item['Subnav']) > 0) { ?>
                <ul class="sub">
                    <?php foreach($item['Subnav'] as $sub) { 
							if($sub->ViewLevel == 0 OR $sub->ViewLevel == $activeLevel) { ?>
                        		<li <?=  ('/' . $full_url[3] == $sub->Href) ? 'class="current"' : ''; ?>><a href="<?=  $sub->Href; ?>"><?=  $sub->Label; ?></a></li>
                            <? } ?>
                    <?php } //end subnav foreach?>
                </ul>
            <?php } //end count?>
        </li>
    <?php } } //endforeach ?>
    </ul>
</div>
<!-- Sidebar -->
<script type="text/javascript">
	$(document).ready(function() {
		$('li.<?=  $full_url[3]; ?>').find('ul.sub').css({'display':'block'});
	});
</script>