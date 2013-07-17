<?php

/**
* Settings for the ubertheme theme
*/

defined('MOODLE_INTERNAL') || die;

global $CFG;

$istotara = (isset($CFG->totara_build));

if (!file_exists($CFG->dirroot.'/theme/ubertheme/lib.php')) {
    return;
}
require_once($CFG->dirroot.'/theme/ubertheme/lib.php');

if ($ADMIN->fulltree) {

	/** General Settings **/

	$setting = new admin_setting_heading('theme_ubertheme/basicsettings', null, html_writer::link($CFG->wwwroot.'/theme/ubertheme/basic-settings.php', 'Basic Settings'));
	$settings->add($setting);


	/** Page Title **/

// start
	$name = 'theme_ubertheme/miscellaneousgroupstart';
	$group_name = 'miscellaneous';
	$setting = new admin_setting_startgroup($name,$group_name);
	$settings->add($setting);

// heading
	$name = 'theme_ubertheme/miscellaneousheading';
	$heading = 'Miscellaneous';
	$info = '';
	$setting = new admin_setting_heading($name,$heading,$info);
	$settings->add($setting);

// Individual Settings

// Sample Page Title

// Full Site Name
	$name = 'fullname';
	$title = get_string('fullname', 'theme_ubertheme');
	$description = get_string('fullsitename_desc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_sitesettext($name, $title, $description, $default);
	$settings->add($setting);

// Additional Text
	$name = 'theme_ubertheme/additionalpagetitle';
	$title = get_string('additionalpagetitle','theme_ubertheme');
	$description = get_string('additionalpagetitle_desc', 'theme_ubertheme');
	$default = 'on Learning Pool';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$settings->add($setting);

// end
	$name = 'theme_ubertheme/miscellaneousgroupend';
	$setting = new admin_setting_endgroup($name);
	$settings->add($setting);


	/** Custom CSS **/

// start
	$name = 'theme_ubertheme/customcssgroupstart';
	$group_name = 'customcss';
	$setting = new admin_setting_startgroup($name,$group_name);
	$settings->add($setting);

// heading
	$name = 'theme_ubertheme/customcssheading';
	$heading = 'Custom CSS';
	$info = '';
	$setting = new admin_setting_heading($name,$heading,$info);
	$settings->add($setting);

// Individual Settings

// Custom CSS file
	$name = 'theme_ubertheme/customcss';
	$title = get_string('customcss','theme_ubertheme');
	$description = get_string('customcssdesc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// end
	$name = 'theme_ubertheme/customcssgroupend';
	$setting = new admin_setting_endgroup($name);
	$settings->add($setting);


	/** Custom YUI **/

// start
	$name = 'theme_ubertheme/customyuigroupstart';
	$group_name = 'customyui';
	$setting = new admin_setting_startgroup($name,$group_name);
	$settings->add($setting);

// heading
	$name = 'theme_ubertheme/customyuiheading';
	$heading = 'Custom YUI';
	$info = '';
	$setting = new admin_setting_heading($name,$heading,$info);
	$settings->add($setting);

// Individual Settings

// Custom YUI Modules
	$name = 'theme_ubertheme/customyuimods';
	$title = get_string('customyuimods','theme_ubertheme');
	$description = get_string('customyuimodsdesc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// Custom YUI
	$name = 'theme_ubertheme/customyuicode';
	$title = get_string('customyuicode','theme_ubertheme');
	$description = get_string('customyuicodedesc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// end
	$name = 'theme_ubertheme/customyuigroupend';
	$setting = new admin_setting_endgroup($name);
	$settings->add($setting);


	/** Additional HTML **/

// start
	$name = 'theme_ubertheme/additionalhtmlgroupstart';
	$group_name = 'additionalhtml';
	$setting = new admin_setting_startgroup($name,$group_name);
	$settings->add($setting);

// heading
	$name = 'theme_ubertheme/additionalhtmlheading';
	$heading = 'Additional HTML';
	$info = '';
	$setting = new admin_setting_heading($name,$heading,$info);
	$settings->add($setting);

// Individual Settings

// Within HEAD
	$name = 'additionalhtmlhead';
	$title = get_string('additionalhtmlhead','admin');
	$description = get_string('additionalhtmlhead_desc', 'admin');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// When BODY is opened
	$name = 'additionalhtmltopofbody';
	$title = get_string('additionalhtmltopofbody','admin');
	$description = get_string('additionalhtmltopofbody_desc', 'admin');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// Custom HTML in on Page Bottom
	$name = 'additionalhtmlfooter';
	$title = get_string('additionalhtmlfooter','admin');
	$description = get_string('additionalhtmlfooter_desc', 'admin');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// end
	$name = 'theme_ubertheme/additionalhtmlgroupend';
	$setting = new admin_setting_endgroup($name);
	$settings->add($setting);


	/** Custom HTML **/

// start
	$name = 'theme_ubertheme/customhtmlgroupstart';
	$group_name = 'customhtml';
	$setting = new admin_setting_startgroup($name,$group_name);
	$settings->add($setting);

// heading
	$name = 'theme_ubertheme/customhtmlheading';
	$heading = 'Custom HTML in DIV#PAGE';
	$info = '';
	$setting = new admin_setting_heading($name,$heading,$info);
	$settings->add($setting);

// Individual Settings

// Custom HTML in on Page Top
	$name = 'theme_ubertheme/customhtmltop';
	$title = get_string('customhtmltop','theme_ubertheme');
	$description = get_string('customhtmltopdesc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// Custom HTML in on Page Bottom
	$name = 'theme_ubertheme/customhtmlbottom';
	$title = get_string('customhtmlbottom','theme_ubertheme');
	$description = get_string('customhtmlbottomdesc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// end
	$name = 'theme_ubertheme/customhtmlgroupend';
	$setting = new admin_setting_endgroup($name);
	$settings->add($setting);


	/** Depricated **/

// start
	$name = 'theme_ubertheme/depricatedgroupstart';
	$group_name = 'depricated';
	$setting = new admin_setting_startgroup($name,$group_name);
	$settings->add($setting);

// heading
	$name = 'theme_ubertheme/depricatedheading';
	$heading = 'Depricated';
	$info = '';
	$setting = new admin_setting_heading($name,$heading,$info);
	$settings->add($setting);

// Individual Settings

// Feature Slider
	$name = 'theme_ubertheme/featureslider';
	$title = get_string('featureslider','theme_ubertheme');
	$description = get_string('featuresliderdesc', 'theme_ubertheme');
	$default = '';
	$setting = new admin_setting_configtextarea($name, $title, $description, $default);
	$settings->add($setting);

// end
	$name = 'theme_ubertheme/depricatedgroupend';
	$setting = new admin_setting_endgroup($name);
	$settings->add($setting);


/** XXXX

// start
$name = 'theme_ubertheme/XXXXgroupstart';
$group_name = 'XXXX';
$setting = new admin_setting_startgroup($name,$group_name);
$settings->add($setting);

// heading
$name = 'theme_ubertheme/XXXXheading';
$heading = 'XXXX';
$info = '';
$setting = new admin_setting_heading($name,$heading,$info);
$settings->add($setting);

// Individual Settings

// end
$name = 'theme_ubertheme/XXXXgroupend';
$setting = new admin_setting_endgroup($name);
$settings->add($setting);

**/

}
