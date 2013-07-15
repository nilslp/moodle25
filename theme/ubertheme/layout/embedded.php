<?php

// Construct Page Title
$page_title = ($PAGE->pagelayout != 'frontpage') ? $PAGE->title . ' | ' : '';
$page_title.= (isset($PAGE->theme->settings->additionalpagetitle)) ? $SITE->fullname.' '.$PAGE->theme->settings->additionalpagetitle : $SITE->fullname;

// Favicon
$favicon = (!empty($PAGE->theme->settings->favicon)) ? $PAGE->theme->settings->favicon : $OUTPUT->pix_url('favicon', 'theme');
if($istotara) {
	$favicon = (!empty($PAGE->theme->settings->favicon)) ? $PAGE->theme->settings->favicon : 'http://themes.learningpool.com/m2/example_images/favicons/totara_favicon.ico';
}

echo $OUTPUT->doctype();

?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
		<title><?php echo $PAGE->title ?></title>
		<link rel="shortcut icon" href="<?php echo $favicon ?>" />
		<?php 
				theme_ubertheme_init_yui();
				echo $OUTPUT->standard_head_html();
		?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">

<!-- END OF HEADER -->

		<div id="content" class="clearfix">
				<?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
		</div>

<!-- START OF FOOTER -->
</div>

<?= theme_ubertheme_custom_yui() ?>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>