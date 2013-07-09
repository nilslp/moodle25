<?php
global $CFG, $DB;

require_once('../../../config.php');
require_once("base_report.php");

$courseid = optional_param('courseid', 0, PARAM_INT);
$course_name = '';

if ($courseid != 0) {    
    $course = $DB->get_record('course', array('id' => $courseid));
    
    if ($course) {
        $course_name = $course->fullname;
    }
}

$report = new base_report_embedded('users_enrolled', '/blocks/lp_reportbuilder/reports/users_enrolled.php', $course_name);

$report->run();
?>
