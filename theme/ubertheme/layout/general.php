<?php

# -> find primary banner
$custom_banner_path = $CFG->httpswwwroot . '/file.php/1/theme-images/banner/top-banner.jpg';
$custom_banner = $OUTPUT->pix_url('banner_default', 'theme');
if (is_file($custom_banner_path)) {
	$custom_banner = $CFG->httpswwwroot . '/file.php/1/theme-images/banner/top-banner.jpg';
}

$istotara = (isset($CFG->totara_build));
$hasheading = ($PAGE->heading || $istotara);

//$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-pre', $OUTPUT));
$hassidepost = (empty($PAGE->layout_options['noblocks']) && $PAGE->blocks->region_has_content('side-post', $OUTPUT));
$haslogininfo = (empty($PAGE->layout_options['nologininfo']));
$showbreadcrumb = (isset($PAGE->theme->settings->showbreadcrumb)) ? $PAGE->theme->settings->showbreadcrumb : true;
$showsidepre = ($hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT));
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));
$collapsecourselist = (isset($PAGE->theme->settings->collapsecourselist)) ? $PAGE->theme->settings->collapsecourselist : 'false';

////////////////////////////////////

$showmenu = empty($PAGE->layout_options['nocustommenu']);

if(isset($PAGE->theme->settings->totaramenu)){
	$show_totaramenu = ($PAGE->theme->settings->totaramenu > 0);
}else{
	$show_totaramenu = true;
}

if(isset($PAGE->theme->settings->appendcustommenuitems)){
	$appendmenu = $PAGE->theme->settings->appendcustommenuitems;
}else{
	$appendmenu = false;
}

// if the site has defined a custom menu we display that,
// otherwise we show the totara menu. This allows sites to
// replace the totara menu with their own custom navigation
// easily
$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && $custommenu);

/*global $CFG,$DB,$USER;
if ($DB->get_field('user','screenreader',array('id'=>$USER->id))) {
	$screenreader = true;
} else {
	$screenreader = false;
}*/

if($istotara && $show_totaramenu){
// totara menu
	$totaramenuon = true;
	$menudata = totara_build_menu();
	$totara_core_renderer = $PAGE->get_renderer('totara_core');
	$tmenu = '<div id="totara-menu" class="tt-main-menu">'.$totara_core_renderer->print_totara_menu($menudata).'</div>';
}
else {
	$totaramenuon = false;
}
// containing elements for menu
$menustart = '<div class="yui3-menu-content"><div>';
$menuend = '</div></div>';

////////////////////////////////////


/************************************************************************************************/
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
/*if ($screenreader) {
	$bodyclasses[] = 'screen-reader-enabled';
}*/

/*switch ($PAGE->theme->settings->blockstyle) {
	case 4:	$cn_blocktype = "sideblock-sticky"; break;
	case 3:	$cn_blocktype = "sideblock-minimal"; break;
	case 2:	$cn_blocktype = "sideblock-plain"; break;
	case 1: 
	default: $cn_blocktype = "sideblock-basic"; break;
}*/

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
	<meta charset="utf-8">
	<meta name='viewport' content='width=device-width'>
	<title><?php echo $page_title ?></title>
	<link rel="shortcut icon" href="<?php echo $favicon ?>" />
	<?php 
	theme_ubertheme_init_yui();
	echo $OUTPUT->standard_head_html();
	?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
	<?php echo $OUTPUT->standard_top_of_body_html() ?>
	<div id="page">
		<?php if (!empty($PAGE->theme->settings->customhtmltop)) echo $PAGE->theme->settings->customhtmltop; ?>
		<?php if ($hasheading || $hasnavbar) { ?>

		<section id="section-header">
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

				<?php } ?>

				<?php
				if ($showmenu) { 

					echo '<div id="custommenu" class="custommenu clearfix">';

					// if totara, append items to totara menu, custom menu exists
					if ( $hascustommenu && $appendmenu && $totaramenuon ) {
						echo $menustart;
						echo $tmenu;
						echo $custommenu;
						echo $menuend;
					}
					else if ( $hascustommenu && !$appendmenu && $totaramenuon ) {
						echo $menustart;
						echo $tmenu;
						echo $menuend;
					}
					else if( !$hascustommenu && $totaramenuon ) {
						echo $menustart;
						echo $tmenu;
						echo $menuend;
					}
					else if( $hascustommenu && !$totaramenuon ) {
						echo $menustart;
						echo $custommenu;
						echo $menuend;
					}

					echo "</div>";
				} 
				?>


				<?php echo makeTicker(); ?>

				<?php if ($hasnavbar) { ?>
				<div class="navbar clearfix">
					<?php if ($showbreadcrumb) { ?>
					<div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
					<?php } ?>
					<div class="navbutton"> <?php echo $PAGE->button; ?></div>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

		</section>

		<!-- END OF HEADER -->

		<section id="page-content">

			<div id="region-main-box">

				<?php if ($hassidepre) { ?>
				<aside id="region-pre" class="block-region <?= $cn_blocktype ?> column">
					<div class="region-content">
						<?php echo $OUTPUT->blocks_for_region('side-pre') ?>
					</div>
				</aside><?php } ?><article id="region-main-wrap" class="column">
				<div id="region-main">
					<div class="region-content">
						<?php echo $OUTPUT->main_content() ?>
					</div>
				</div>
			</article><?php if ($hassidepost) { ?><aside id="region-post" class="block-region <?= $cn_blocktype ?> column">
			<div class="region-content">
				<?php echo $OUTPUT->blocks_for_region('side-post') ?>
			</div>
		</aside>
		<?php } ?>

	</div>

</section>

<div class="push"></div> 

</div>

<!-- START OF FOOTER -->

<section id="section-footer">

	<div id="zone-footer">

		<?php if ($hasfooter) { ?>
		<div id="page-footer" class="clearfix">
			<?php
			echo $OUTPUT->standard_footer_html();
			?>
		</div>
		<?php } ?>
		<?php if (!empty($PAGE->theme->settings->customhtmlbottom)) echo $PAGE->theme->settings->customhtmlbottom; ?>

		<?php if ($hasfooter) { echo renderSupportWidget();} ?>

	</div>

</section>
<?= ubertheme_featureSlider(); ?>

<?= theme_ubertheme_custom_yui() ?>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
