<?php

global $CFG;
$istotara = (isset($CFG->totara_build));

$THEME->name = 'ubertheme';

$THEME->parents = array();

$THEME->sheets = array(
	'reset',
	'body-html',
	'layout',
	'typography',
	'accessibility',
	'custom-menu',
	'block-menus',
	'blocks',
	'breadcrumbs',
	'buttons',
	'calendars',
	'carousel',
	'admin',
	'accordions',
	'dock',
	'forms',
	'filemanager',
	'images-icons',
	'labels',
	'lists',
	'miscellaneous',
	'modals',
	'notifications-messages',
	'pagination',
	'slideshows',
	'support-widget',
	'tables',
	'tabs',
	'tags',
	'ticker',
	'page-activity-reports-all-logs',
	'page-activity-reports-complete-report',
	'page-activity-reports-grade',
	'page-activity-reports-outline-report',
	'page-activity-reports-todays-logs',
	'page-anonymous-index',
	'page-blogs-view-all-my-entries',
	'page-calendar',
	'page-course-categories',
	'page-course-sections-certificates',
	'page-course-sections-evaluation',
	'page-course-sections-scorm',
	'page-faqs',
	'page-forum-discussions',
	'page-forum-posts',
	'page-index',
	'page-login-index',
	'page-my-private-files',
	'page-messages',
	'page-notes',
	'page-participants',
	'page-reports-activity-report',
	'page-reports-course-participation',
	'page-reports-live-logs',
	'page-reports-logs',
	'page-site-blogs',
	'page-topic-outline-accordion',
	'page-topic-outline-default',
	'page-topic-outline-scorm',
	'page-topic-outline-social',
	'page-topic-outline-topics',
	'page-topic-outline-weeks',
	'page-view-profile'
	);
		
if (!empty($THEME->settings->blockwidth)) { $THEME->sheets[] = 'columns-'.$THEME->settings->blockwidth; }

$THEME->layouts = array(
		// Most backwards compatible layout without the blocks - this is the layout used by default
		'base' => array(
				'file' => 'general.php',
				'regions' => array(),
				'options' => array('langmenu'=>true)
		),
		// Standard layout with blocks, this is recommended for most pages with general information
		'standard' => array(
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		// Main course page
		'course' => array(
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		'coursecategory' => array(
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		'coursesearch' => array(
				'file' => 'course-list.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		// part of course, typical for modules - default page layout if $cm specified in require_login()
		'incourse' => array(
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		// Error page.
		'server-error' => array(
				//'file' => 'error.php',
				'file' => 'server-error.php',
				'regions' => array(),
				'options' => array('nonavbar'=>true, 'langmenu'=>true)
		),
		// The site home page.
		'frontpage' => array(
				//'file' => 'frontpage.php',
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		// Server administration scripts.
		'admin' => array(
				'file' => 'general.php',
				'regions' => array('side-pre'),
				'defaultregion' => 'side-pre',
				'options' => array('langmenu'=>true)
		),
		// My dashboard page
		'mydashboard' => array(
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		// My public page
		'mypublic' => array(
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
		'login' => array(
				'file' => 'general.php',
				'regions' => array(),
				'options' => array('langmenu'=>true)
		),

		// Pages that appear in pop-up windows - no navigation, no blocks, no header.
		'popup' => array(
				'file' => 'popup.php',
				'regions' => array(),
				'options' => array('langmenu'=>true)
		),
		// No blocks and minimal footer - used for legacy frame layouts only!
		'frametop' => array(
				'file' => 'general.php',
				'regions' => array(),
				'options' => array('nofooter'=>true, 'langmenu'=>true)
		),
		// Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible
		'embedded' => array(
				'file' => 'embedded.php',
				'regions' => array(),
				'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'langmenu'=>true)
		),
		// Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
		// This must not have any blocks, and it is good idea if it does not have links to
		// other places - for example there should not be a home link in the footer...
		'maintenance' => array(
				'file' => 'general.php',
				'regions' => array(),
				'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'langmenu'=>true)
		),
		// Should display the content and basic headers only.
		'print' => array(
				'file' => 'general.php',
				'regions' => array(),
				'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>false, 'nocustommenu'=>true, 'langmenu'=>true)
		),
		// The pagelayout used when a redirection is occuring.
		'redirect' => array(
				'file' => 'embedded.php',
				'regions' => array(),
				'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'langmenu'=>true)
		),
		// The pagelayout used for reports
		'report' => array(
				// 'file' => 'report.php',
				// 'file' => 'general.php',
				// 'regions' => array(),
				// 'options' => array('noblocks'=>true, 'nofooter'=>true, 'nocustommenu'=>true, 'nologininfo'=>true),
				'file' => 'general.php',
				'regions' => array('side-pre', 'side-post'),
				'defaultregion' => 'side-post',
				'options' => array('langmenu'=>true)
		),
);


if (!isloggedin() && $THEME->settings->customanonhomepage) {

		$THEME->layouts = array(
				// The site home page.
				'frontpage' => array(
						'file' => 'general.php',
						'regions' => array(),
						'options' => array('langmenu'=>true)
				)
		);
		
		$CFG->customfrontpageinclude=$CFG->dirroot."/theme/ubertheme/layout/frontpage.php";
		
}

$THEME->javascripts = array(
		'init',
		'yuimods' => array(
		        'anim',
		        'console',
		        'cookie',
		        'dd-constrain',
		        'dd-delegate',
		        'dd-drag',
		        'event',
		        'event-hover',
		        'json-parse',
		        'json-stringify',
		        'node',
		        'selector-css3',
		        'transition'
				)
		);

$THEME->javascripts_footer = array(
		'cycling-banners',
		'feature-slider',
		'misc',
		'sideblock-stuff',
		'support-widget',
		'ticker',
		'util.browser-fitness',
		'yui-carousel',
		);

$THEME->csspostprocess = 'ubertheme_process_css';

$THEME->enable_dock = true;
		
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// Totara
if ($istotara) { 
		$THEME->sheets[] = 'totara';
		array_unshift($THEME->parents, 'standardtotara'); 
		$THEME->parents_exclude_sheets = array_merge($THEME->parents_exclude_sheets,array('standardtotara'=>array('pagelayout','blocks'))); 
}


// Add the Custom CSS file last - always
$THEME->sheets[] = 'custom-css';