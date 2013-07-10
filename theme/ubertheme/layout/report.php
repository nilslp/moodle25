<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if (!$showsidepre) {
		$bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
		$bodyclasses[] = 'has_custom_menu';
}

// Construct Page Title
$page_title = ($PAGE->pagelayout != 'frontpage') ? $PAGE->title . ' | ' : '';
$page_title.= (isset($PAGE->theme->settings->additionalpagetitle)) ? $SITE->fullname.' '.$PAGE->theme->settings->additionalpagetitle : $SITE->fullname;

$istotara = (isset($CFG->totara_build));

// Favicon
$favicon = (!empty($PAGE->theme->settings->favicon)) ? $PAGE->theme->settings->favicon : $OUTPUT->pix_url('favicon', 'theme');
if($istotara) {
		$favicon = (!empty($PAGE->theme->settings->favicon)) ? $PAGE->theme->settings->favicon : 'http://themes.learningpool.com/m2/example_images/favicons/totara_favicon.ico';
}

echo $OUTPUT->doctype();
#print_r($CFG);
?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
		<title><?php echo $page_title; ?></title>
		<link rel="shortcut icon" href="<?php echo $favicon ?>" />
		<?php 
				theme_ubertheme_init_yui();
				echo $OUTPUT->standard_head_html();
		?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>
<div id="page">
<?php if ($hasheading || $hasnavbar) { ?>
		<div id="page-header">
				<?php if ($hasheading) { ?>
		<div class="meta">
			<h1 class="headermain"><?php echo $PAGE->heading ?></h1>
			<div class="headermenu"><?php
					if ($haslogininfo) { echo $OUTPUT->login_info(); }
					if (!empty($PAGE->layout_options['langmenu'])) { echo $OUTPUT->lang_menu(); }
					echo $PAGE->headingmenu
			?></div>
		</div>
				
				<?php echo ubertheme_customBanner(); ?>

				<?php echo theme_foundation_search_widget(); ?>

		<?php } ?>
		<div id="custommenu" class="custommenu">
				<?php if ($hascustommenu) { echo $custommenu; } ?>
		</div>
				<?php if ($hasnavbar) { ?>
						<div class="navbar clearfix">
								<div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
								<div class="navbutton"> <?php echo $PAGE->button; ?></div>
						</div>
				<?php } ?>
		</div>
<?php } ?>
<!-- END OF HEADER -->

		<div id="page-content" class="clearfix">
				<div id="report-main-content">
						<div class="region-content">
								<?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
						</div>
				</div>
		</div>

<!-- START OF FOOTER -->
		<?php if ($hasfooter) { ?>
		<div id="page-footer" class="clearfix">View the <a href="<?= $CFG->supportpage ?>">support page</a>, contact <a href="mailto:<?= $CFG->supportemail ?>"><?= $CFG->supportname ?></a> or call <b>0845 543 6033</b>.</p>
		</div>
				<?php
				echo $OUTPUT->standard_footer_html();
				?>
		</div>
	<div class="vc"></div>
		<?php } ?>
</div>

<?= theme_ubertheme_custom_yui() ?>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>