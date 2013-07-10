<?php

# -> find primary banner
$custom_banner_path = $CFG->httpswwwroot . '/file.php/1/theme-images/banner/top-banner.jpg';
$custom_banner = $OUTPUT->pix_url('banner_default', 'theme');
if (is_file($custom_banner_path)) {
	$custom_banner = $CFG->httpswwwroot . '/file.php/1/theme-images/banner/top-banner.jpg';
}

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));

$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

/************************************************************************************************/
if (!empty($PAGE->theme->settings->logo)) {
		$logo = $PAGE->theme->settings->logo;
} else {
		$logo = false;
}

$hasframe = !isset($PAGE->theme->settings->noframe) || !$PAGE->theme->settings->noframe;

$displaylogo = !isset($PAGE->theme->settings->displaylogo) || $PAGE->theme->settings->displaylogo;
/************************************************************************************************/

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
		$bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
		$bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
		$bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
		$bodyclasses[] = 'has_custom_menu';
}

switch ($PAGE->theme->settings->blockstyle) {
		case 4:	$cn_blocktype = "sideblock-sticky"; break;
		case 3:	$cn_blocktype = "sideblock-minimal"; break;
		case 2:	$cn_blocktype = "sideblock-plain"; break;
		case 1: 
		default: $cn_blocktype = "sideblock-basic"; break;
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
?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
	<meta charset="utf-8">
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
		
		<div class="banner">
			<?php if($logo) { ?>
			<div class="logo-1"><a href="/"><img src="<?= $logo ?>" alt="Logo"/></a></div>
			<?php } ?>
			<?php echo customBanner(); ?>
		</div>

		<?php } ?>

				<?php echo theme_foundation_search_widget(); ?>
				
		<div id="custommenu" class="custommenu">
				<?php if ($hascustommenu) { echo $custommenu; } ?>
		</div>
				
				<?php echo makeTicker(); ?>
				
				<?php if ($hasnavbar) { ?>
						<div class="navbar clearfix">
								<div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
								<div class="navbutton"> <?php echo $PAGE->button; ?></div>
						</div>
				<?php } ?>
		</div>
<?php } ?>
<!-- END OF HEADER -->

		<div id="page-content">
	
				<div id="region-main-box">
				
						<div id="region-main-wrap">
								<div id="region-main">
										<div class="region-content">
												<?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
										</div>
								</div>

								<?php if ($hassidepre) { ?>
								<div id="region-pre" class="block-region <?= $cn_blocktype ?>">
										<div class="region-content">
												<?php echo $OUTPUT->blocks_for_region('side-pre') ?>
										</div>
								</div>
								<?php } ?>
						</div>

						<?php if ($hassidepost) { ?>
						<div id="region-post" class="block-region <?= $cn_blocktype ?>">
								<div class="region-content">
										<?php echo $OUTPUT->blocks_for_region('side-post') ?>
								</div>
						</div>
						<?php } ?>
								
				</div>
		
		</div>
		
	<div class="vc"></div>
	
<!-- START OF FOOTER -->
		<?php if ($hasfooter) { ?>
		<div id="page-footer" class="clearfix">
				<?php
				echo $OUTPUT->standard_footer_html();
				?>
		</div>
	<div class="vc"></div>
		<?php } ?>
</div>

<div id="support-widget">
		<ul>
				<li class="lp-logo"><a href="http://www.learningpool.com" title="Learning Pool">&nbsp;</a></li><li class="opts">Support: <a href="<?= $CFG->supportpage ?>">Web</a> | <a href="mailto:<?= $CFG->supportemail ?>">Email <?= $CFG->supportname ?></a> | <b>0845 543 6033</b>.</li><li class="retest">&nbsp;</li><li class="js test unknown failed">&nbsp;</li><li class="browser test unknown">&nbsp;</li><li class="popup test unknown">&nbsp;</li><li class="flash test unknown">&nbsp;</li>
		</ul>
</div>

<?= theme_ubertheme_custom_yui() ?>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>