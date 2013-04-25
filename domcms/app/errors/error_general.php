<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title><?php echo  SITETITLE; ?></title>

<link type="text/css" rel="stylesheet" href="/css/reset.css" />
<link type="text/css" rel="stylesheet" href="/css/dataTable.css" />
<link type="text/css" rel="stylesheet" href="/css/ui_custom.css" />
<link type="text/css" rel="stylesheet" href="/css/fullcalendar.css" />
<link type="text/css" rel="stylesheet" href="/css/icons.css" />
<link type="text/css" rel="stylesheet" href="/css/elfinder.css" />
<link type="text/css" rel="stylesheet" href="/css/wysiwyg.css" />
<link type="text/css" rel="stylesheet" href="/css/prettyPhoto.css" />
<link type="text/css" rel="stylesheet" href="/css/main.css" />

<link href='http://fonts.googleapis.com/css?family=Cuprum' rel='stylesheet' type='text/css' />

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

<script type="text/javascript" src="/js/plugins/spinner/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/js/plugins/spinner/ui.spinner.js"></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="/js/plugins/wysiwyg/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="/js/plugins/wysiwyg/wysiwyg.image.js"></script>
<script type="text/javascript" src="/js/plugins/wysiwyg/wysiwyg.link.js"></script>
<script type="text/javascript" src="/js/plugins/wysiwyg/wysiwyg.table.js"></script>

<script type="text/javascript" src="/js/plugins/flot/jquery.flot.js"></script>
<script type="text/javascript" src="/js/plugins/flot/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="/js/plugins/flot/jquery.flot.pie.js"></script>
<script type="text/javascript" src="/js/plugins/flot/excanvas.min.js"></script>
<script type="text/javascript" src="/js/plugins/flot/jquery.flot.resize.js"></script>

<script type="text/javascript" src="/js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="/js/plugins/tables/colResizable.min.js"></script>

<script type="text/javascript" src="/js/plugins/forms/forms.js"></script>
<script type="text/javascript" src="/js/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="/js/plugins/forms/autotab.js"></script>
<script type="text/javascript" src="/js/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="/js/plugins/forms/jquery.validationEngine.js"></script>
<script type="text/javascript" src="/js/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="/js/plugins/forms/chosen.jquery.min.js"></script>
<script type="text/javascript" src="/js/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="/js/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="/js/plugins/forms/jquery.tagsinput.min.js"></script>

<script type="text/javascript" src="/js/plugins/other/calendar.min.js"></script>
<script type="text/javascript" src="/js/plugins/other/elfinder.min.js"></script>

<script type="text/javascript" src="/js/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="/js/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="/js/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="/js/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="/js/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.alerts.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.colorpicker.js"></script>

<script type="text/javascript" src="/js/plugins/wizards/jquery.form.wizard.js"></script>
<script type="text/javascript" src="/js/plugins/wizards/jquery.validate.js"></script>

<script type="text/javascript" src="/js/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.ToTop.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.listnav.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.sourcerer.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="/js/plugins/ui/jquery.prettyPhoto.js"></script>

<script type="text/javascript" src="/js/custom.js"></script>

</head>

<body>
<!-- Error info area -->
<div class="wrapper">
    <div class="errorPage">
        <h2 class="red errorTitle"><span><?php echo $heading; ?></span></h2>
        <h1>Error</h1>
        <span class="bubbles"></span>
	<p><?php echo $message; ?></p>
        <div class="backToDash"><a href="/" title="" class="seaBtn button">Back to Dashboard</a></div>
    </div>
</div>

<!-- Footer -->
<div id="footer">
	<div class="wrapper">
    	<span>&copy; Copyright <?php echo  date('Y'); ?>. All rights reserved.</span>
    </div>
</div>

</body>
</html>
