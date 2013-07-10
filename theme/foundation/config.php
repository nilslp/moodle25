<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Configuration for Moodle's base theme.
 *
 * This theme is special, and implements a minimalist theme with only
 * basic layout. It is intended as a base for other themes to build upon.
 * It is not recommend to actually choose this theme for production sites!
 *
 * DO NOT COPY THIS TO START NEW THEMES!
 * Start with another theme, like "standard".
 *
 * For full information about creating Moodle themes, see:
 *  http://docs.moodle.org/dev/Themes_2.0
 *
 * @package   moodlecore
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$THEME->name = 'foundation';

$THEME->parents = array();

$THEME->sheets = array(
    'base-overrides',
    // 'core',
    'columns',
    // 'admin',
    'layout',
    'blocks',
    'tables',
    'login',
    'forms',
    'tabs',
    'paging',
    'notifications',
    'calendar',
    'course-list',
    //'course',
    'topics',
    'accordian-topic-outline',
    'content',
    'forums_blogs',
    // 'dock',
    'mods',
    // 'grade',
    // 'message',
    // 'question',
    // 'user',
    'popup',
    'browser-specific',
    'yui-overrides',
    'yui3-menu',
    'yui3-overlay',
    'yui3-panel',
    'server-errors',
    'theme-settings',
    'carousel',
    'ticker',
    'support-widget',
    'dialog',
    'treeview',
    'typography',
    'mediaqueries',
    'printstyles'
);

$THEME->editor_sheets = array('editor');

$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default
    'base' => array(
        'file' => 'general.php',
        'regions' => array(),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information
    'standard' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
    ),
    // Main course page
    'course' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
        'options' => array('langmenu'=>true),
    ),
    'coursecategory' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
    ),
    // part of course, typical for modules - default page layout if $cm specified in require_login()
    'incourse' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // My dashboard page
    'mydashboard' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
        'options' => array('langmenu'=>true),
    ),
    // My public page
    'mypublic' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-post',
    ),
    'login' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('langmenu'=>true),
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'popup.php',
        'regions' => array(),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('nofooter'=>true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true),
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, and it is good idea if it does not have links to
    // other places - for example there should not be a home link in the footer...
    'maintenance' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>false, 'nocustommenu'=>true),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true),
    ),
    // The pagelayout used for reports
    'report' => array(
        'file' => 'report.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
);

// We don't want the base theme to be shown on the theme selection screen, by setting
// this to true it will only be shown if theme designer mode is switched on.
$THEME->hidefromselector = true;

$THEME->javascripts = array(
    'init',
    'yuimods' => array(
        'node', 
        'console', 
        'event', 
        'selector-css3', 
        'event-hover', 
        'cookie', 
        'json-parse', 
        'json-stringify', 
        'transition', 
        'anim', 
        'dd-delegate', 
        'dd-constrain'
        )
    );

$THEME->javascripts_footer = array(
    'course-list',
    'toggle-course-list',
    'sideblock-stuff',
    'ticker',
    'paging',
    'tabs',
    'html-corrections',
    'paging',
    'yui-carousel',
    'util.browser-fitness',
    'support-widget',
    'theme-settings',
    'screen-reader'
    );
    
$THEME->rendererfactory = 'theme_overridden_renderer_factory';

// various SCORM configurations require some light javascript hackery :dpmh 2012-02-22
$legacylibpath = $CFG->dirroot.'/local/dlelegacytools/lib.php';
if (file_exists($legacylibpath)) {
    require_once($legacylibpath);
    apply_legacy_settings();
}

