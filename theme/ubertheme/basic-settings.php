<?php // $Id$

/**
 * @global core_renderer $OUTPUT
 */
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
global $CFG,$PAGE;
require_once($CFG->dirroot.'/theme/ubertheme/basic-settings-form.php');
require_once($CFG->dirroot.'/theme/ubertheme/lib.php');

$PAGE->set_url('/theme/ubertheme/basic-settings.php');
require_login();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('theme_settings','theme_ubertheme'));
$PAGE->set_heading(get_string('theme_settings','theme_ubertheme'));
require_capability('theme/ubertheme:config',$context);

$jsconfig = array(
		 'name' => 'theme_ubertheme_theme_settings',
		 'fullpath' => '/theme/ubertheme/javascript/theme-settings.js',
		 'requires' => array('node', 'event', 'selector-css3', 'event-hover', 'transition', 'anim')
);

$PAGE->requires->js_init_call('M.theme_ubertheme_theme_settings.init', null, false, $jsconfig);

// form definition
$mform = new theme_ubertheme_admin_form(null, null, 'post', null, array('class'=>'theme-settings'));

echo $OUTPUT->header();

if ($mform->is_cancelled()) {
		// redirect($CFG->wwwroot);
		$msg_cancel = get_string('theme_settings_update_cancel','theme_ubertheme');
		echo $OUTPUT->notification($msg_cancel, 'notifycancel');
}
else if ($mform->is_submitted()) {
		$fromform = $mform->get_data();

		if(theme_ubertheme_update_settings($fromform)) {
				$msg_success = get_string('theme_settings_update_success', 'theme_ubertheme');
				echo $OUTPUT->notification($msg_success, 'notifysuccess');
		}
		else {
				$msg_failure = get_string('theme_settings_update_failure','theme_ubertheme');
				echo $OUTPUT->notification($msg_failure, 'notifyfailure');
		}
}

$adv_span = '';
// print_r($context);
if (has_capability('moodle/site:config', $context, $USER->id)) {
		$adv_link = html_writer::link($CFG->wwwroot.'/admin/settings.php?section=themesettingubertheme', 'Advanced Settings');
		$adv_span = html_writer::tag('span', ' | ' . $adv_link, array('class'=>'adv-link'));
}

echo $OUTPUT->heading(get_string('theme_settings','theme_ubertheme') . ' ' . $adv_span,2);

// display the form
$mform->display();

echo $OUTPUT->footer();

