<?php

/**
 * Evaluation topics format for course display - NO layout tables, for accessibility, etc.
 *
 * A duplicate course format to enable the Moodle development team to evaluate
 * CSS for the multi-column layout in place of layout tables.
 * Less risk for the Moodle 1.6 beta release.
 *   1. Straight copy of topics/format.php
 *   2. Replace <table> and <td> with DIVs; inline styles.
 *   3. Reorder columns so that in linear view content is first then blocks;
 * styles to maintain original graphical (side by side) view.
 *
 * Target: 3-column graphical view using relative widths for pixel screen sizes
 * 800x600, 1024x768... on IE6, Firefox. Below 800 columns will shift downwards.
 *
 * http://www.maxdesign.com.au/presentation/em/ Ideal length for content.
 * http://www.svendtofte.com/code/max_width_in_ie/ Max width in IE.
 *
 * @copyright &copy; 2006 The Open University
 * @author N.D.Freear@open.ac.uk, and others.
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package
 */

defined('MOODLE_INTERNAL') || die();

global $PAGE;

require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/completionlib.php');

$topic = optional_param('topic', -1, PARAM_INT);

if ($topic != -1) {
    $displaysection = course_set_display($course->id, $topic);
} else {
    $displaysection = course_get_display($course->id);
}

// Load Browser Util
$jsconfig = array(
    'name' => 'course_accordion_format',
    'fullpath' => '/course/format/accordion/behaviors.js',
    'requires' => array('node', 'event')
);
$PAGE->requires->js_init_call('M.course_accordion_format', null, false, $jsconfig);

$context = get_context_instance(CONTEXT_COURSE, $course->id);

if (($marker >=0) && has_capability('moodle/course:setcurrentsection', $context) && confirm_sesskey()) {
    $course->marker = $marker;
    $DB->set_field("course", "marker", $marker, array("id"=>$course->id));
}

$streditsummary  = get_string('editsummary');
$stradd          = get_string('add');
$stractivities   = get_string('activities');
$strshowalltopics = get_string('showalltopics');
$strtopic         = get_string('topic');
$strgroups       = get_string('groups');
$strgroupmy      = get_string('groupmy');
$editing         = $PAGE->user_is_editing();

if ($editing) {
    $strtopichide = get_string('hidetopicfromothers');
    $strtopicshow = get_string('showtopicfromothers');
    $strmarkthistopic = get_string('markthistopic');
    $strmarkedthistopic = get_string('markedthistopic');
    $strmoveup   = get_string('moveup');
    $strmovedown = get_string('movedown');
}

// Print the Your progress icon if the track completion is enabled
$completioninfo = new completion_info($course);
echo $completioninfo->display_help_icon();

echo "<div class='topics accordion-topics'>\n";

/// If currently moving a file then show the current clipboard
if (ismoving($course->id)) {
    $stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
    $strcancel= get_string('cancel');
    echo '<dl class="clipboard"><dd>';
    echo $stractivityclipboard.'&nbsp;&nbsp;(<a href="mod.php?cancelcopy=true&amp;sesskey='.sesskey().'">'.$strcancel.'</a>)';
    echo "</dd></dl>\n";
}

/// Print Section 0 with general activities

$section = 0;
$thissection = $sections[$section];
unset($sections[0]);

// if (!$thissection->summary) {
//     // $thissection->summary = '<p><strong><span style="font-size: x-large;">&lt;&lt;COURSE TITLE&gt;&gt;</span></strong></p><p>&lt;&lt;Introduce The Course Here&gt;&gt;</p>';
//     $thissection->summary = '<div class="no-overflow">&nbsp;</div>';
// }

if ($thissection->summary or $thissection->sequence or $PAGE->user_is_editing()) {

    // Note, no need for a 'left side' cell or DIV.
    // Note, 'right side' is BEFORE content.
    echo '<dl id="section-0" class="section course-summary main clearfix" >';
    echo '<dt>';
    if (!is_null($thissection->name)) {
        echo $OUTPUT->heading(format_string($thissection->name, true, array('context' => $context)), 2, 'coursetitle sectionname');
    }
    else {
        echo $OUTPUT->heading($course->fullname, 2, 'coursetitle sectionname');
    }
    echo '</dt>';
    echo '<dd class="content">';
    echo '<div class="summary">';

    $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
    $summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course', 'section', $thissection->id);
    $summaryformatoptions = new stdClass();
    $summaryformatoptions->noclean = true;
    $summaryformatoptions->overflowdiv = true;
    echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);

    if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $coursecontext)) {
        echo '<a title="'.$streditsummary.'" '.
             ' href="editsection.php?id='.$thissection->id.'"><img src="'.$OUTPUT->pix_url('t/edit') . '" '.
             ' class="iconsmall edit" alt="'.$streditsummary.'" /></a>';
    }
    echo '</div>';

    print_section($course, $thissection, $mods, $modnames);

    if ($PAGE->user_is_editing()) {
        print_section_add_menus($course, $section, $modnames);
    }

    echo '</dd>';
    echo "</dl>\n";
}


/// Now all the normal modules by topic
/// Everything below uses "section" terminology - each "section" is a topic.

$section = 1;
$sectionmenu = array();

while ($section <= $course->numsections) {

    if (!empty($sections[$section])) {
        $thissection = $sections[$section];

    } else {
        $thissection = new stdClass;
        $thissection->course  = $course->id;   // Create a new section structure
        $thissection->section = $section;
        $thissection->name    = null;
        $thissection->summary  = '';
        $thissection->summaryformat = FORMAT_HTML;
        $thissection->visible  = 1;
        $thissection->id = $DB->insert_record('course_sections', $thissection);
    }

    $showsection = (has_capability('moodle/course:viewhiddensections', $context) or $thissection->visible or !$course->hiddensections);

    if (!empty($displaysection) and $displaysection != $section) {  // Check this topic is visible
        if ($showsection) {
            $sectionmenu[$section] = get_section_name($course, $thissection);
        }
        $section++;
        continue;
    }

    if ($showsection) {

        $currenttopic = ($course->marker == $section);

        $currenttext = '';
        if (!$thissection->visible) {
            $sectionstyle = ' hidden collapsed';
        } else if ($currenttopic) {
            $sectionstyle = ' current';
            $currenttext = get_accesshide(get_string('currenttopic','access'));
        } else {
            $sectionstyle = ' collapsed';
        }

        echo '<dl id="section-'.$section.'" class="section main clearfix'.$sectionstyle.'" >'; //'<div class="left side">&nbsp;</div>';

        echo '<dt>';    
        echo '<div class="left side">'.$currenttext.$section.'</div>';
        // Note, 'right side' is BEFORE content.
        echo '<ul class="right side actions">';

        if ($PAGE->user_is_editing() && has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id))) {
            
            echo '<li><a title="'.$streditsummary.'" href="editsection.php?id='.$thissection->id.'">'.
                 '<img src="'.$OUTPUT->pix_url('t/edit') . '" class="iconsmall edit" alt="'.$streditsummary.'" /></a></li>';

            if ($course->marker == $section) {  // Show the "light globe" on/off
                echo '<li><a href="view.php?id='.$course->id.'&amp;marker=0&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strmarkedthistopic.'">'.'<img src="'.$OUTPUT->pix_url('i/marked') . '" alt="'.$strmarkedthistopic.'" class="icon"/></a></li>';
            } else {
                echo '<li><a href="view.php?id='.$course->id.'&amp;marker='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strmarkthistopic.'">'.'<img src="'.$OUTPUT->pix_url('i/marker') . '" alt="'.$strmarkthistopic.'" class="icon"/></a></li>';
            }

            if ($thissection->visible) {        // Show the hide/show eye
                echo '<li><a href="view.php?id='.$course->id.'&amp;hide='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strtopichide.'">'.
                     '<img src="'.$OUTPUT->pix_url('i/hide') . '" class="icon hide" alt="'.$strtopichide.'" /></a></li>';
            } else {
                echo '<li><a href="view.php?id='.$course->id.'&amp;show='.$section.'&amp;sesskey='.sesskey().'#section-'.$section.'" title="'.$strtopicshow.'">'.
                     '<img src="'.$OUTPUT->pix_url('i/show') . '" class="icon hide" alt="'.$strtopicshow.'" /></a></li>';
            }
            if ($section > 1) {                       // Add a arrow to move section up
                echo '<li><a href="view.php?id='.$course->id.'&amp;random='.rand(1,10000).'&amp;section='.$section.'&amp;move=-1&amp;sesskey='.sesskey().'#section-'.($section-1).'" title="'.$strmoveup.'">'.
                     '<img src="'.$OUTPUT->pix_url('t/up') . '" class="icon up" alt="'.$strmoveup.'" /></a></li>';
            }

            if ($section < $course->numsections) {    // Add a arrow to move section down
                echo '<li><a href="view.php?id='.$course->id.'&amp;random='.rand(1,10000).'&amp;section='.$section.'&amp;move=1&amp;sesskey='.sesskey().'#section-'.($section+1).'" title="'.$strmovedown.'">'.
                     '<img src="'.$OUTPUT->pix_url('t/down') . '" class="icon down" alt="'.$strmovedown.'" /></a></li>';
            }
        }

        if ($displaysection == $section) {    // Show the zoom boxes
            echo '<li><a href="view.php?id='.$course->id.'&amp;topic=0#section-'.$section.'" title="'.$strshowalltopics.'">'.
                 '<img src="'.$OUTPUT->pix_url('i/all') . '" class="icon" alt="'.$strshowalltopics.'" /></a></li>';
        }
        // else {
        //     $strshowonlytopic = get_string("showonlytopic", "", $section);
        //     echo '<li><a href="view.php?id='.$course->id.'&amp;topic='.$section.'" title="'.$strshowonlytopic.'">'.
        //          '<img src="'.$OUTPUT->pix_url('i/one') . '" class="icon" alt="'.$strshowonlytopic.'" /></a></li>';
        // }
        
        echo '</ul>';
        
        echo '<div class="title">';
        $sect_title = (!is_null($thissection->name)) ? format_string($thissection->name, true, array('context' => $context)) : get_string('sectionname','format_accordion');
        echo $OUTPUT->heading($sect_title, 3, 'sectionname');
        echo '</div>';

        echo '<div class="vc"></div>';
        
        echo '</dt>';
        
        echo '<dd class="content">';
        if (!has_capability('moodle/course:viewhiddensections', $context) and !$thissection->visible) {   // Hidden for students
            echo get_string('notavailable');
        } else {
            echo '<div class="summary">';								
            if ($thissection->summary) {
                $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
                $summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course', 'section', $thissection->id);
                $summaryformatoptions = new stdClass();
                $summaryformatoptions->noclean = true;
                $summaryformatoptions->overflowdiv = true;
                echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);
            } else {
               echo '&nbsp;';
            }

            echo '</div>';

            print_section($course, $thissection, $mods, $modnamesused);

            if ($PAGE->user_is_editing()) {
                print_section_add_menus($course, $section, $modnames);
            }
        }

        echo '</dd>';
        echo "</dl>\n";
    }

    unset($sections[$section]);
    $section++;
}

if (!$displaysection and $PAGE->user_is_editing() and has_capability('moodle/course:update', get_context_instance(CONTEXT_COURSE, $course->id))) {
    // print stealth sections if present
    $modinfo = get_fast_modinfo($course);
    foreach ($sections as $section=>$thissection) {
        if (empty($modinfo->sections[$section])) {
            continue;
        }

        echo '<dl id="section-'.$section.'" class="section main clearfix orphaned hidden">'; //'<div class="left side">&nbsp;</div>';
        echo '<dt>';
        echo '<div class="left side">';
        echo '</div>';
        // Note, 'right side' is BEFORE content.
        echo '<div class="right side">';
        echo '</div>';
        echo '</dt>';
        
        echo '<dd class="content">';
        echo $OUTPUT->heading(get_string('orphanedactivities'), 3, 'sectionname');
        print_section($course, $thissection, $mods, $modnamesused);
        echo '</dd>';
        echo "</dl>\n";
    }
}


echo "</div>\n";

if (!empty($sectionmenu)) {
    $select = new single_select(new moodle_url('/course/view.php', array('id'=>$course->id)), 'topic', $sectionmenu);
    $select->label = get_string('jumpto');
    $select->class = 'jumpmenu';
    $select->formid = 'sectionmenu';
    echo $OUTPUT->render($select);
}
