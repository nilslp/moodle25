<?php

// Get Primary Banner
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

// If the site has defined a custom menu we display that, otherwise we show the totara menu.
// This allows sites to replace the totara menu with their own custom navigation easily
$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

// Totara Menu Begins
if($istotara && $show_totaramenu){
	$totaramenuon = true;
	$menudata = totara_build_menu();
	$totara_core_renderer = $PAGE->get_renderer('totara_core');
	$tmenu = '<div id="totara-menu" class="tt-main-menu">'.$totara_core_renderer->print_totara_menu($menudata).'</div>';
}
else {
	$totaramenuon = false;
}
// Containing elements for menu
$menustart = '<div class="yui3-menu-content"><div>';
$menuend = '</div></div>';

// Totara Menu Ends

$hasframe = !isset($PAGE->theme->settings->noframe) || !$PAGE->theme->settings->noframe;
$displaylogo = !isset($PAGE->theme->settings->displaylogo) || $PAGE->theme->settings->displaylogo;

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
	$courseheader = $OUTPUT->course_header();
	$coursecontentheader = $OUTPUT->course_content_header();
	if (empty($PAGE->layout_options['nocoursefooter'])) {
		$coursecontentfooter = $OUTPUT->course_content_footer();
		$coursefooter = $OUTPUT->course_footer();
	}
}

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
	if (!right_to_left()) {
		$bodyclasses[] = 'side-pre-only';
	}else{
		$bodyclasses[] = 'side-post-only';
	}
} else if ($showsidepost && !$showsidepre) {
	if (!right_to_left()) {
		$bodyclasses[] = 'side-post-only';
	}else{
		$bodyclasses[] = 'side-pre-only';
	}
} else if (!$showsidepost && !$showsidepre) {
	$bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
	$bodyclasses[] = 'has_custom_menu';
}

switch ($PAGE->theme->settings->blockstyle) {
	case 4: $cn_blocktype = "sideblock-sticky"; break;
	case 3: $cn_blocktype = "sideblock-minimal"; break;
	case 2: $cn_blocktype = "sideblock-plain"; break;
	case 1: 
	default: $cn_blocktype = "sideblock-basic"; break;
}

// Construct Page Title
$page_title = ($PAGE->pagelayout != 'frontpage') ? $PAGE->title . ' | ' : '';
$page_title.= (isset($PAGE->theme->settings->additionalpagetitle)) ? $SITE->fullname.' '.$PAGE->theme->settings->additionalpagetitle : $SITE->fullname;

// Favicon
$favicon = (!empty($PAGE->theme->settings->favicon)) ? $PAGE->theme->settings->favicon : $OUTPUT->pix_url('favicon', 'theme');
if($istotara) {
	$favicon = (!empty($PAGE->theme->settings->favicon)) ? $PAGE->theme->settings->favicon : 'http://themes.learningpool.com/m2/example_images/favicons/totara_favicon.ico';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
	<title><?php echo $page_title ?></title>
    <meta name="viewport" content="width=device-width; initial-scale=1; maximum-scale=1; minimum-scale=1; user-scalable=0;" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
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
		<?php if ($hasheading || $hasnavbar || !empty($courseheader)) { ?>

		<!-- START OF HEADER SECTION -->

		<section id="section-header">
			<div id="page-header">

				<?php if ($hasheading) { ?>

				<div class="meta">
					<h1 class="headermain"><?php echo $PAGE->heading ?></h1>
					<div class="headermenu">
						<?php
						if ($haslogininfo) {
							echo $OUTPUT->login_info();
						}
						if (!empty($PAGE->layout_options['langmenu'])) {
							echo $OUTPUT->lang_menu();
						}
						echo $PAGE->headingmenu
						?>
					</div>
					<div id="menu-tray"></div>
				</div>

				<?php echo ubertheme_customBanner(); ?>
				<?php } ?>

				<?php if (!empty($courseheader)) { ?>
				<div id="course-header"><?php echo $courseheader; ?></div>
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
					} else if ( $hascustommenu && !$appendmenu && $totaramenuon ) {
						echo $menustart;
						echo $tmenu;
						echo $menuend;
					} else if( !$hascustommenu && $totaramenuon ) {
						echo $menustart;
						echo $tmenu;
						echo $menuend;
					} else if( $hascustommenu && !$totaramenuon ) {
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
					<div class="navbar-wrapper">

						<?php if ($showbreadcrumb) { ?>

						<div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>

						<?php } ?>

					</div>
				</div>

				<?php } ?>

				<div class="editing-bar clearfix">
					<div class="nav-button"> <?php echo $PAGE->button; ?></div>
				</div>

				<?php } ?>

			</div>
		</section>

		<!-- END OF HEADER SECTION -->
		<!-- START OF CONTENT SECTION -->

		<section id="section-content">
			<div id="page-content">
				<div id="region-main-box">
					<div id="region-post-box">

						<div id="region-main-wrap">
							<div id="region-main">
								<div class="region-content">

									<?php echo $coursecontentheader; ?>
									<?php echo $OUTPUT->main_content() ?>
									<?php echo $coursecontentfooter; ?>

								</div>
							</div>
						
    
                            <?php if ($hassidepre OR (right_to_left() AND $hassidepost)) { ?>
    
                            <div id="region-pre" class="block-region <?= $cn_blocktype ?> column">
                                <div class="region-content">
    
                                    <?php
                                    if (!right_to_left()) {
                                        echo $OUTPUT->blocks_for_region('side-pre');
                                    } elseif ($hassidepost) {
                                        echo $OUTPUT->blocks_for_region('side-post');
                                    } ?>
    
                                </div>
                            </div>
                        
                        </div>

						<?php } ?>

						<?php if ($hassidepost OR (right_to_left() AND $hassidepre)) { ?>

						<div id="region-post" class="block-region <?= $cn_blocktype ?> column">
							<div class="region-content">
								<?php
								if (!right_to_left()) {
									echo $OUTPUT->blocks_for_region('side-post');
								} elseif ($hassidepre) {
									echo $OUTPUT->blocks_for_region('side-pre');
								} ?>
							</div>
						</div>

						<?php } ?>

					</div>
				</div>
			</div>
		</section>

		<div class="push"></div>
	</div>

	<!-- END OF CONTENT SECTION -->
	<!-- START OF FOOTER SECTION -->

	<section id="section-footer">
		<div id="zone-footer">

			<?php if (!empty($coursefooter)) { ?>
			<div id="course-footer"><?php echo $coursefooter; ?></div>
			<?php } ?>

			<?php if (!empty($PAGE->theme->settings->customhtmlbottom)) echo $PAGE->theme->settings->customhtmlbottom; ?>
			<?php if ($hasfooter) { echo renderSupportWidget();} ?>
			
			<?php if ($hasfooter) { ?>
			<div id="page-footer" class="clearfix">
				<p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
				<?php
				echo $OUTPUT->standard_footer_html();
				echo $OUTPUT->login_info();
				?>
			</div>
			<?php } ?>

		
		</div>
	</section>

	<?= theme_ubertheme_custom_yui() ?>
	<?php echo $OUTPUT->standard_end_of_body_html() ?>

</body>
</html>
