<?php

global $CFG;
require_once($CFG->dirroot.'/lib/formslib.php');
MoodleQuickForm::registerElementType('lp_checkbox', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_checkbox');
MoodleQuickForm::registerElementType('lp_editor', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_editor');
MoodleQuickForm::registerElementType('lp_textarea', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_textarea');
MoodleQuickForm::registerElementType('lp_colorpicker', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_colorpicker');
MoodleQuickForm::registerElementType('lp_select', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_select');
MoodleQuickForm::registerElementType('lp_static', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_static');
MoodleQuickForm::registerElementType('lp_text', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_text');
MoodleQuickForm::registerElementType('lp_filepicker', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_filepicker');
MoodleQuickForm::registerElementType('lp_filemanager', "$CFG->dirroot/theme/ubertheme/lib.php",'LPQuickForm_filemanager');

class theme_ubertheme_admin_form extends moodleform {

		function definition() {

				global $CFG;
				$istotara = (isset($CFG->totara_build));
				// $context = get_system_context();
				// $has_accordion_course_format = (file_exists($CFG->dirroot.'/course/format/accordion/format.php'));

				$mform = & $this->_form;

				// @TODO: add all your fancy form controls here

				// Some generic select options
				$choices_on_off = array(0=>get_string('off','theme_ubertheme'), 1=>get_string('on','theme_ubertheme'));

				$cs_ubertheme = get_config('theme_ubertheme');
				$cs_moodle = get_config('moodle');

				// Set Form Defaults
				$defaults->fontsizereference = (isset($cs_ubertheme->fontsizereference)) ? $cs_ubertheme->fontsizereference : 13;
				$defaults->fontfamilyref = (isset($cs_ubertheme->fontfamilyref)) ? $cs_ubertheme->fontfamilyref : 1;
				$defaults->pagefixedwidth = (isset($cs_ubertheme->pagefixedwidth)) ? $cs_ubertheme->pagefixedwidth : 1;
				$defaults->pagerounded = (isset($cs_ubertheme->pagerounded)) ? $cs_ubertheme->pagerounded : 0;
				$defaults->pageshadow = (isset($cs_ubertheme->pageshadow)) ? $cs_ubertheme->pageshadow : 0;
				$defaults->collapsecourselist = (isset($cs_ubertheme->collapsecourselist)) ? $cs_ubertheme->collapsecourselist : 0;
				$defaults->autorunsupportwidget = (isset($cs_ubertheme->autorunsupportwidget)) ? $cs_ubertheme->autorunsupportwidget : 1;
				$defaults->linkc = (isset($cs_ubertheme->linkc)) ? $cs_ubertheme->linkc : '#116699';
				$defaults->pagebgc = (isset($cs_ubertheme->pagebgc)) ? $cs_ubertheme->pagebgc : '#FFFFFF';
				$defaults->pagegradients = (isset($cs_ubertheme->pagegradients)) ? $cs_ubertheme->pagegradients : 0;
				$defaults->pagebbgc = (isset($cs_ubertheme->pagebbgc)) ? $cs_ubertheme->pagebbgc : '#FFFFFF';
				$defaults->searchwidget = (isset($cs_ubertheme->searchwidget)) ? $cs_ubertheme->searchwidget : 0;
				$defaults->custombanner = (isset($cs_ubertheme->custombanner)) ? $cs_ubertheme->custombanner : '';
				// $defaults->custombannerheight = (isset($cs_ubertheme->custombannerheight)) ? $cs_ubertheme->custombannerheight : 120;
				$defaults->custombannerslideshow = (isset($cs_ubertheme->custombannerslideshow)) ? $cs_ubertheme->custombannerslideshow : 0;
				$defaults->custombannerhomeonly = (isset($cs_ubertheme->custombannerhomeonly)) ? $cs_ubertheme->custombannerhomeonly : 0;
				$defaults->favicon = (isset($cs_ubertheme->favicon)) ? $cs_ubertheme->favicon : '';
				$defaults->logo1 = (isset($cs_ubertheme->logo1)) ? $cs_ubertheme->logo1 : '';
				$defaults->logo2 = (isset($cs_ubertheme->logo2)) ? $cs_ubertheme->logo2 : '';
				$defaults->ticker = (isset($cs_ubertheme->ticker)) ? $cs_ubertheme->ticker : '';
				$defaults->tickerlocation = (isset($cs_ubertheme->tickerlocation)) ? $cs_ubertheme->tickerlocation : 0;
				$defaults->totaramenu = (isset($cs_ubertheme->totaramenu)) ? $cs_ubertheme->totaramenu : 1;
				$defaults->appendcustommenuitems = (isset($cs_ubertheme->appendcustommenuitems)) ? $cs_ubertheme->appendcustommenuitems : 1;
				$defaults->custommenuitems = (isset($cs_moodle->custommenuitems)) ? $cs_moodle->custommenuitems : '';
				$defaults->menutc = (isset($cs_ubertheme->menutc)) ? $cs_ubertheme->menutc : '#000000';
				$defaults->menubgc = (isset($cs_ubertheme->menubgc)) ? $cs_ubertheme->menubgc : '#CCCCCC';
				$defaults->menugradient = (isset($cs_ubertheme->menugradient)) ? $cs_ubertheme->menugradient : 0;
				$defaults->menushadow = (isset($cs_ubertheme->menushadow)) ? $cs_ubertheme->menushadow : 0;
				$defaults->showbreadcrumb = (isset($cs_ubertheme->showbreadcrumb)) ? $cs_ubertheme->showbreadcrumb : 0;
				$defaults->blockstyle = (isset($cs_ubertheme->blockstyle)) ? $cs_ubertheme->blockstyle : 1;
				$defaults->blockwidth = (isset($cs_ubertheme->blockwidth)) ? $cs_ubertheme->blockwidth : 200;
				$defaults->blockrounded = (isset($cs_ubertheme->blockrounded)) ? $cs_ubertheme->blockrounded : 0;
				$defaults->blockshadow = (isset($cs_ubertheme->blockshadow)) ? $cs_ubertheme->blockshadow : 0;
				$defaults->blockgradient = (isset($cs_ubertheme->blockgradient)) ? $cs_ubertheme->blockgradient : 0;
				$defaults->sbbgc = (isset($cs_ubertheme->sbbgc)) ? $cs_ubertheme->sbbgc : '#CCCCCC';
				$defaults->sbtc = (isset($cs_ubertheme->sbtc)) ? $cs_ubertheme->sbtc : '#000000';
				$defaults->customanonhomepage = (isset($cs_ubertheme->customanonhomepage)) ? $cs_ubertheme->customanonhomepage : 0;
				$defaults->customanonhomepagecontent = (isset($cs_ubertheme->customanonhomepagecontent)) ? $cs_ubertheme->customanonhomepagecontent : '';
				$defaults->mcpcompletecolor = (isset($cs_ubertheme->mcpcompletecolor)) ? $cs_ubertheme->mcpcompletecolor : '#55B295';
				$defaults->mcpincompletecolor = (isset($cs_ubertheme->mcpincompletecolor)) ? $cs_ubertheme->mcpincompletecolor : '#5875B5';
				$defaults->mcpnotattemptedcolor = (isset($cs_ubertheme->mcpnotattemptedcolor)) ? $cs_ubertheme->mcpnotattemptedcolor : '#FFFFFF';
				// $defaults->accordioncolor = (isset($cs_ubertheme->accordioncolor)) ? $cs_ubertheme->accordioncolor : '#CCCCCC';
				// $defaults->accordiongradient = (isset($cs_ubertheme->accordiongradient)) ? $cs_ubertheme->accordiongradient : 0;


				// General Settings

				$mform->addElement('header', 'general-settings',get_string('general_settings', 'theme_ubertheme'));

						// Base Font Size
						$choices_font_size = array(11=>'11px', 12=>'12px', 13=>'13px', 14=>'14px', 15=>'15px', 16=>'16px');
						$mform->addElement('lp_select', 'fontsizereference', get_string('fontsizereference', 'theme_ubertheme'), $choices_font_size, null, get_string('fontsizereference_help','theme_ubertheme'));
						$mform->setDefault('fontsizereference', $defaults->fontsizereference);

						// Font Family
						$choices_font_family = array(
								1=>'Arial, sans-serif',
								2=>'Verdana, sans-serif',
								3=>'Trebuchet, sans-serif',
								4=>'Times New Roman, serif',
								5=>'Georgia, serif'
								);
						$mform->addElement('lp_select', 'fontfamilyref', get_string('fontfamilyref', 'theme_ubertheme'), $choices_font_family, null, get_string('fontfamilyref_help','theme_ubertheme'));
						$mform->setDefault('fontfamilyref', $defaults->fontfamilyref);

						// Fixed Page Width
						$choices_pagefixedwidth = array(0=>get_string('fluid_layout','theme_ubertheme'), 1=>get_string('fixed_layout','theme_ubertheme'));
						$mform->addElement('lp_select', 'pagefixedwidth', get_string('pagefixedwidth', 'theme_ubertheme'), $choices_pagefixedwidth, null, get_string('pagefixedwidth_help','theme_ubertheme'));
						$mform->setDefault('pagefixedwidth', $defaults->pagefixedwidth);

						// Page Rounded Corners
						$choices_pagerounded = array(0=>get_string('sharp_corners','theme_ubertheme'), 1=>get_string('rounded_corners','theme_ubertheme'));
						$mform->addElement('lp_select', 'pagerounded', get_string('pagerounded', 'theme_ubertheme'),$choices_pagerounded,null, get_string('pagerounded_help','theme_ubertheme'));
						$mform->setDefault('pagerounded', $defaults->pagerounded);

						// Page Shadow Effect
						$mform->addElement('lp_select', 'pageshadow', get_string('pageshadow', 'theme_ubertheme'),$choices_on_off,null, get_string('pageshadow_help','theme_ubertheme'));
						$mform->setDefault('pageshadow', $defaults->pageshadow);

						// Course Search Widget
						$mform->addElement('lp_select', 'searchwidget', get_string('searchwidget', 'theme_ubertheme'),$choices_on_off,null, get_string('searchwidget_help','theme_ubertheme'));
						$mform->setDefault('searchwidget', $defaults->searchwidget);

			if (!$istotara) {
					// Collapseable Course List
					$choices_collapsecourselist = array(0=>get_string('courselist_expanded','theme_ubertheme'), 1=>get_string('courselist_collapsed','theme_ubertheme'), 2=>get_string('courselist_collapsedopen','theme_ubertheme'));
					$mform->addElement('lp_select', 'collapsecourselist', get_string('collapsecourselist', 'theme_ubertheme'), $choices_collapsecourselist, null, get_string('collapsecourselist_help','theme_ubertheme'));
					$mform->setDefault('collapsecourselist', $defaults->collapsecourselist);
			}
				
						// Favicon URL
						$mform->addElement('lp_text', 'favicon', get_string('favicon', 'theme_ubertheme'), array('size'=>'60'), get_string('favicon_help', 'theme_ubertheme'));
						$mform->setType('favicon', PARAM_RAW_TRIMMED);
						$mform->setDefault('favicon', $defaults->favicon);

						// Autorun the Support Widget
						$mform->addElement('lp_select', 'autorunsupportwidget', get_string('autorunsupportwidget', 'theme_ubertheme'),$choices_on_off,null, get_string('autorunsupportwidget_help','theme_ubertheme'));
						$mform->setDefault('autorunsupportwidget', $defaults->autorunsupportwidget);

						// Link Color
						$mform->addElement('lp_colorpicker', 'linkc', get_string('linkc', 'theme_ubertheme'),null, get_string('linkc_help','theme_ubertheme'));
						$mform->setType('linkc', PARAM_RAW_TRIMMED);
						$mform->setDefault('linkc', $defaults->linkc);

						// Page Background Color
						$mform->addElement('lp_colorpicker', 'pagebgc', get_string('pagebgc', 'theme_ubertheme'),null, get_string('pagebgc_help','theme_ubertheme'));
						$mform->setType('pagebgc', PARAM_RAW_TRIMMED);
						$mform->setDefault('pagebgc', $defaults->pagebgc);

						// Gradients
						$mform->addElement('lp_select', 'pagegradients', get_string('pagegradients', 'theme_ubertheme'),$choices_on_off,null, get_string('pagegradients_help','theme_ubertheme'));
						$mform->setDefault('pagegradients', $defaults->pagegradients);

						// Page Background Color
						$mform->addElement('lp_colorpicker', 'pagebbgc', get_string('pagebbgc', 'theme_ubertheme'), null, get_string('pagebbgc_help','theme_ubertheme'));
						$mform->setType('pagebbgc', PARAM_RAW_TRIMMED);
						$mform->setDefault('pagebbgc', $defaults->pagebbgc);


				// Banners

				$mform->addElement('header', 'banner-settings',get_string('banners', 'theme_ubertheme'));

						// Custom Banner URLS
						$mform->addElement('lp_textarea', 'custombanner', get_string('custombanner', 'theme_ubertheme'), array('cols'=>'60','rows'=>'10'), get_string('custombanner_help', 'theme_ubertheme'));
						$mform->setDefault('custombanner', $defaults->custombanner);

						// Custom Banner Height - in pixels
						// $mform->addElement('lp_text', 'custombannerheight', get_string('custombannerheight', 'theme_ubertheme'), array('size'=>'5'), get_string('custombannerheight_help', 'theme_ubertheme'));
						// $mform->setType('custombannerheight', PARAM_INT);
						// $mform->setDefault('custombannerheight', $defaults->custombannerheight);

						// Custom Banner Slideshow
						$mform->addElement('lp_select', 'custombannerslideshow', get_string('custombannerslideshow', 'theme_ubertheme'), $choices_on_off, null, get_string('custombannerslideshow_help', 'theme_ubertheme'));
						$mform->setDefault('custombannerslideshow', $defaults->custombannerslideshow);

						// Custom Banner to be displayed on the Home Page only
						$mform->addElement('lp_select', 'custombannerhomeonly', get_string('custombannerhomeonly', 'theme_ubertheme'), $choices_on_off, null, get_string('custombannerhomeonly_help', 'theme_ubertheme'));
						$mform->setDefault('custombannerhomeonly', $defaults->custombannerhomeonly);


				// Logos

				$mform->addElement('header', 'logo-settings',get_string('logos', 'theme_ubertheme'));

//						>>
//						>> Attempt at uploading files directly into the DLE. Couldn't get it to work, but leaving for future development.
//						>>
//						$logo_upload_opts = array('accepted_types'=>array('web_images','archive'));
//
//						// Logo 1 (Primary, left side)
//						$mform->addElement('lp_filemanager', 'logo1', get_string('logo1', 'theme_ubertheme'),null,$logo_upload_opts, get_string('logo1_help','theme_ubertheme'));
//						$mform->setDefault('logo1', $cs_ubertheme->logo1);
//
//						$logo1_draftitemid = file_get_submitted_draft_itemid('logo1');
//						file_prepare_draft_area($logo1_descraftitemid, $context->id, 'theme', 'theme_images', null, $logo_upload_opts);
//						file_prepare_draft_area($logo1_draftitemid, $context->id, 'theme', 'theme_ubertheme', null, null);
//						$logo1->images = $logo1_draftitemid;
//						$mform->set_data($logo1);
//
						// Logo 1 (Primary, left side)
						$mform->addElement('lp_text', 'logo1', get_string('logo1', 'theme_ubertheme'),array('size'=>'60'), get_string('logo1_help','theme_ubertheme'));
						$mform->setType('logo1', PARAM_RAW_TRIMMED);
						$mform->setDefault('logo1', $defaults->logo1);

						// Logo 2 (Secondary, right side)
						$mform->addElement('lp_text', 'logo2', get_string('logo2', 'theme_ubertheme'),array('size'=>'60'), get_string('logo2_help','theme_ubertheme'));
						$mform->setType('logo2', PARAM_RAW_TRIMMED);
						$mform->setDefault('logo2', $defaults->logo2);


				// Ticker

				$mform->addElement('header', 'ticker-settings',get_string('ticker', 'theme_ubertheme'));

						// Ticker Messages
						$mform->addElement('lp_editor','ticker', get_string('ticker_text', 'theme_ubertheme'),array('rows'=>'10','cols'=>'60'), null,	get_string('ticker_help','theme_ubertheme'));
						$mform->setType('ticker', PARAM_RAW_TRIMMED);
						$mform->setDefault('ticker', array('format'=>FORMAT_HTML, 'text'=>$defaults->ticker));

						// Ticker to be displayed on the Home Page only
						$choices_tickerlocation = array(0=>get_string('tl_all_pages','theme_ubertheme'), 1=>get_string('tl_home_all','theme_ubertheme'), 2=>get_string('tl_home_logged_in','theme_ubertheme'));
						$mform->addElement('lp_select', 'tickerlocation', get_string('tickerlocation', 'theme_ubertheme'),$choices_tickerlocation ,null, get_string('tickerlocation_help','theme_ubertheme'));
						$mform->setDefault('tickerlocation', $defaults->tickerlocation);


				// Navigation

				$mform->addElement('header', 'custommenu-settings', get_string('navigation', 'theme_ubertheme'));

						if ($istotara) {
								// Include the Totara build in Menu
								$mform->addElement('lp_select', 'totaramenu', get_string('totaramenu', 'theme_ubertheme'),$choices_on_off,null, get_string('totaramenu_help','theme_ubertheme'));
								$mform->setDefault('totaramenu', $defaults->totaramenu);

								// Append Moodle Menu to the end of the Totara Menu
								$mform->addElement('lp_select', 'appendcustommenuitems', get_string('appendcustommenuitems', 'theme_ubertheme'),$choices_on_off,null, get_string('appendcustommenuitems_help','theme_ubertheme'));
								$mform->setDefault('appendcustommenuitems', $defaults->appendcustommenuitems);
						}

						// Main Navigation Items
						$mform->addElement('lp_textarea','custommenuitems', get_string('custommenuitems', 'theme_ubertheme'),null,get_string('custommenuitems_help','theme_ubertheme'));
						$mform->setType('custommenuitems', PARAM_TEXT);
						$mform->setDefault('custommenuitems', $defaults->custommenuitems);

						// Main Navigation Text Color
						$mform->addElement('lp_colorpicker', 'menutc', get_string('menutc', 'theme_ubertheme'), null, get_string('menutc_help','theme_ubertheme'));
						$mform->setType('menutc', PARAM_RAW_TRIMMED);
						$mform->setDefault('menutc', $defaults->menutc);

						// Main Navigation Background Color
						$mform->addElement('lp_colorpicker', 'menubgc', get_string('menubgc', 'theme_ubertheme'), null, get_string('menubgc_help','theme_ubertheme'));
						$mform->setType('menubgc', PARAM_RAW_TRIMMED);
						$mform->setDefault('menubgc', $defaults->menubgc);

						// Gradient
						$mform->addElement('lp_select', 'menugradient', get_string('menugradient', 'theme_ubertheme'),$choices_on_off, null, get_string('menugradient_help','theme_ubertheme'));
						$mform->setDefault('menugradient', $defaults->menugradient);

						// Shadow
						$mform->addElement('lp_select', 'menushadow', get_string('menushadow', 'theme_ubertheme'),$choices_on_off, null, get_string('menushadow_help','theme_ubertheme'));
						$mform->setDefault('menushadow', $defaults->menushadow);

						// Breadcrumb
						$choices_breadcrumb = array(0=>get_string('bc_not_shown','theme_ubertheme'), 1=>get_string('bc_all_pages','theme_ubertheme'));
						$mform->addElement('lp_select', 'showbreadcrumb', get_string('showbreadcrumb', 'theme_ubertheme'),$choices_breadcrumb, null, get_string('showbreadcrumb_help','theme_ubertheme'));
						$mform->setDefault('showbreadcrumb', $defaults->showbreadcrumb);


				// Side Blocks

				$mform->addElement('header', 'sideblocks-settings', get_string('sideblocks', 'theme_ubertheme'));

						// Style
						$choices_blockstyle = array(1=>'Basic', 2=>'Plain', 3=>'Minimal', 4=>'Sticky Note');
						$mform->addElement('lp_select', 'blockstyle', get_string('blockstyle', 'theme_ubertheme'),$choices_blockstyle, null, get_string('blockstyle_help','theme_ubertheme'));
						$mform->setDefault('blockstyle', $defaults->blockstyle);

						// Width
						$choices_blockwidth = array(170=>'170 px', 200=>'200 px', 240=>'240 px', 290=>'290 px', 350=>'350 px');
						$mform->addElement('lp_select', 'blockwidth', get_string('blockwidth', 'theme_ubertheme'),$choices_blockwidth, null, get_string('blockwidth_help','theme_ubertheme'));
						$mform->setDefault('blockwidth', $defaults->blockwidth);

						// Rounded Corners
						$choices_blockrounded = array(0=>get_string('sharp_corners','theme_ubertheme'), 1=>get_string('rounded_corners','theme_ubertheme'));
						$mform->addElement('lp_select', 'blockrounded', get_string('blockrounded', 'theme_ubertheme'),$choices_blockrounded, null, get_string('blockrounded_help','theme_ubertheme'));
						$mform->setDefault('blockrounded', $defaults->blockrounded);

						// Shadow Effect
						$mform->addElement('lp_select', 'blockshadow', get_string('blockshadow', 'theme_ubertheme'),$choices_on_off, null, get_string('blockshadow_help','theme_ubertheme'));
						$mform->setDefault('blockshadow', $defaults->blockshadow);

						// Gradients
						$mform->addElement('lp_select', 'blockgradient', get_string('blockgradient', 'theme_ubertheme'),$choices_on_off, null, get_string('blockgradient_help','theme_ubertheme'));
						$mform->setDefault('blockgradient', $defaults->blockgradient);

						// Background Color
						$mform->addElement('lp_colorpicker', 'sbbgc', get_string('sbbgc', 'theme_ubertheme'), null, get_string('sbbgc_help','theme_ubertheme'));
						$mform->setType('sbbgc', PARAM_RAW_TRIMMED);
						$mform->setDefault('sbbgc', $defaults->sbbgc);

						// Text Color
						$mform->addElement('lp_colorpicker', 'sbtc', get_string('sbtc', 'theme_ubertheme'), null, get_string('sbtc_help','theme_ubertheme'));
						$mform->setType('sbtc', PARAM_RAW_TRIMMED);
						$mform->setDefault('sbtc', $defaults->sbtc);


				// Anonymous Home

				$mform->addElement('header', 'anon-home-settings', get_string('anon_home', 'theme_ubertheme'));

						if ($CFG->forcelogin) {
								// A warning whether 'forced login' is on. If so then the anonymous home page content won't be seen.
								$mform->addElement('lp_static', 'forcedloginnote', get_string('forcedloginnote','theme_ubertheme'));
						}

						// Use Custom Content for the Anonymous Home Page
						$mform->addElement('lp_select', 'customanonhomepage', get_string('customanonhomepage', 'theme_ubertheme'),$choices_on_off, null, get_string('customanonhomepage_help','theme_ubertheme'));
						$mform->setDefault('customanonhomepage', $defaults->customanonhomepage);

						// Custom Home Page content
						$mform->addElement('lp_editor','customanonhomepagecontent', get_string('customanonhomepagecontent', 'theme_ubertheme'), array('rows'=>'10','cols'=>'60'),null, get_string('customanonhomepagecontent_help','theme_ubertheme'));
						$mform->setType('customanonhomepage', PARAM_RAW_TRIMMED);
						$mform->setDefault('customanonhomepagecontent', array('format'=>FORMAT_HTML, 'text'=>$defaults->customanonhomepagecontent));


				if (!$istotara) {
						// Course Progress

						$mform->addElement('header', 'course-progress-settings', get_string('course_progress', 'theme_ubertheme'));

								// Complete Color
								$mform->addElement('lp_colorpicker', 'mcpcompletecolor', get_string('mcpcompletecolor', 'theme_ubertheme'), null, get_string('mcpcompletecolor_help','theme_ubertheme'));
								$mform->setType('mcpcompletecolor', PARAM_RAW_TRIMMED);
								$mform->setDefault('mcpcompletecolor', $defaults->mcpcompletecolor);

								// Incomplete Color
								$mform->addElement('lp_colorpicker', 'mcpincompletecolor', get_string('mcpincompletecolor', 'theme_ubertheme'), null, get_string('mcpincompletecolor_help','theme_ubertheme'));
								$mform->setType('mcpincompletecolor', PARAM_RAW_TRIMMED);
								$mform->setDefault('mcpincompletecolor', $defaults->mcpincompletecolor);

								// Not Attempted Color
								$mform->addElement('lp_colorpicker', 'mcpnotattemptedcolor', get_string('mcpnotattemptedcolor', 'theme_ubertheme'), null, get_string('mcpnotattemptedcolor_help','theme_ubertheme'));
								$mform->setType('mcpnotattemptedcolor', PARAM_RAW_TRIMMED);
								$mform->setDefault('mcpnotattemptedcolor', $defaults->mcpnotattemptedcolor);
				}


				// Accordion Course Format

				// if ($has_accordion_course_format) {
				// 		// Only show the setting if the accordion course format exists
				// 		$mform->addElement('header', 'accordion-course-format-settings', get_string('accordion_course_format', 'theme_ubertheme'));

				// 				// Base Color
				// 				$mform->addElement('lp_colorpicker', 'accordioncolor', get_string('accordioncolor', 'theme_ubertheme'), null, get_string('accordioncolor_help','theme_ubertheme'));
				// 				$mform->setDefault('accordioncolor', $defaults->accordioncolor);

				// 				// Gradients
				// 				$mform->addElement('lp_select', 'accordiongradient', get_string('accordiongradient', 'theme_ubertheme'),$choices_on_off, null, get_string('accordiongradient_help','theme_ubertheme'));
				// 				$mform->setDefault('accordiongradient', $defaults->accordiongradient);
				// }


				// Form Submit Buttons
				$this->add_action_buttons(true);

		}
}
