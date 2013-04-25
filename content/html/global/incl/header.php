<?= $DocType . "\n" . $HTML . "\n"; ?>
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title><?= $Title; ?></title>
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>reset.css" />
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>dataTable.css" />
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>ui_custom.css" />
	<link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>fullcalendar.css" />    
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>icons.css" />
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>elfinder.css" />
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>wysiwyg.css" />
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>prettyPhoto.css" />
    <link type="text/css" rel="stylesheet" href="<?= base_url() . $CSSDIR; ?>main.css" />
    <link href="//fonts.googleapis.com/css?family=Cuprum" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/spinner/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/spinner/ui.spinner.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/wysiwyg/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/wysiwyg/wysiwyg.image.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/wysiwyg/wysiwyg.link.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/wysiwyg/wysiwyg.table.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/flot/jquery.flot.orderBars.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/flot/jquery.flot.pie.js"></script>
    <!-- <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/flot/jquery.flot.canvas.js"></script> -->
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/flot/excanvas.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/flot/jquery.flot.resize.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/tables/colResizable.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/forms.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/autogrowtextarea.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/autotab.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/jquery.validationEngine-en.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/jquery.dualListBox.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/chosen.jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/jquery.maskedinput.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/jquery.inputlimiter.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/forms/jquery.tagsinput.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/other/calendar.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/other/elfinder.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/uploader/plupload.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/uploader/plupload.html5.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/uploader/plupload.html4.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/uploader/jquery.plupload.queue.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.progress.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.tipsy.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.alerts.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.colorpicker.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/wizards/jquery.form.wizard.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/wizards/jquery.validate.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.breadcrumbs.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.collapsible.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.ToTop.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.listnav.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.sourcerer.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ui/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>custom.js"></script>
    <!-- <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>charts/chart.js"></script> -->
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>charts/auto.js"></script>
	<script type="text/javascript" src="<?= base_url() . $JSDIR; ?>charts/bar.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>charts/hBar.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>charts/pie.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/html2canvas/html2canvas.min.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/html2canvas/jquery.plugin.html2canvas.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/jquery.fileDownload.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ZeroClipboard/ZeroClipboard.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/ZClip/jquery.zclip.js"></script>
    <script type="text/javascript" src="<?= base_url() . $JSDIR; ?>plugins/chosenExtender.js"></script>
    <?php if(isset($TagCss)) { echo $TagCss; }; ?>  

</head>
<body>