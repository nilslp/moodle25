<?PHP global $CFG,$SESSION,$OUTPUT,$SITE;?>

<?php 

if( !isloggedin() ){
		 
		echo '<div class="region-content">';
		include("$CFG->dirroot/theme/$CFG->theme/layout/index_form.html");
		// echo $SITE->summary;
		echo $PAGE->theme->settings->customanonhomepagecontent;
		echo '</div>';
		 
}else if ($SITE->numsections > 0) {

				if (!$section = $DB->get_record('course_sections', array('course'=>$SITE->id, 'section'=>1))) {
						
						$DB->delete_records('course_sections', array('course'=>$SITE->id, 'section'=>1)); // Just in case
						$section->course = $SITE->id;
						$section->section = 1;
						$section->summary = '';
						$section->summaryformat = FORMAT_HTML;
						$section->sequence = '';
						$section->visible = 1;
						$section->id = $DB->insert_record('course_sections', $section);
				}

				if (!empty($section->sequence) or !empty($section->summary) or $editing) {
						echo $OUTPUT->box_start('generalbox sitetopic');

						/// If currently moving a file then show the current clipboard
						if (ismoving($SITE->id)) {
								$stractivityclipboard = strip_tags(get_string('activityclipboard', '', $USER->activitycopyname));
								echo '<p><font size="2">';
								echo "$stractivityclipboard&nbsp;&nbsp;(<a href=\"course/mod.php?cancelcopy=true&amp;sesskey=".sesskey()."\">". get_string('cancel') .'</a>)';
								echo '</font></p>';
						}

						$context = get_context_instance(CONTEXT_COURSE, SITEID);
						$summarytext = file_rewrite_pluginfile_urls($section->summary, 'pluginfile.php', $context->id, 'course', 'section', $section->id);
						$summaryformatoptions = new stdClass();
						$summaryformatoptions->noclean = true;
						$summaryformatoptions->overflowdiv = true;

						echo format_text($summarytext, $section->summaryformat, $summaryformatoptions);

						if ($editing) {
								$streditsummary = get_string('editsummary');
								echo "<a title=\"$streditsummary\" ".
										 " href=\"course/editsection.php?id=$section->id\"><img src=\"" . $OUTPUT->pix_url('t/edit') . "\" ".
										 " class=\"iconsmall\" alt=\"$streditsummary\" /></a><br /><br />";
						}

						get_all_mods($SITE->id, $mods, $modnames, $modnamesplural, $modnamesused);
						print_section($SITE, $section, $mods, $modnamesused, true);

						if ($editing) {
								print_section_add_menus($SITE, $section->section, $modnames);
						}
						echo $OUTPUT->box_end();
				}
		}

		if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
				$frontpagelayout = $CFG->frontpageloggedin;
		} else {
				$frontpagelayout = $CFG->frontpage;
		}

		foreach (explode(',',$frontpagelayout) as $v) {
				switch ($v) {		 /// Display the main part of the front page.
						case FRONTPAGENEWS:
								if ($SITE->newsitems) { // Print forums only when needed
										require_once($CFG->dirroot .'/mod/forum/lib.php');

										if (! $newsforum = forum_get_course_forum($SITE->id, 'news')) {
												print_error('cannotfindorcreateforum', 'forum');
										}

										// fetch news forum context for proper filtering to happen
										$newsforumcm = get_coursemodule_from_instance('forum', $newsforum->id, $SITE->id, false, MUST_EXIST);
										$newsforumcontext = get_context_instance(CONTEXT_MODULE, $newsforumcm->id, MUST_EXIST);

										$forumname = format_string($newsforum->name, true, array('context' => $newsforumcontext));
										echo html_writer::tag('a', get_string('skipa', 'access', moodle_strtolower(strip_tags($forumname))), array('href'=>'#skipsitenews', 'class'=>'skip-block'));

										if (isloggedin()) {
												$SESSION->fromdiscussion = $CFG->wwwroot;
												$subtext = '';
												if (forum_is_subscribed($USER->id, $newsforum)) {
														if (!forum_is_forcesubscribed($newsforum)) {
																$subtext = get_string('unsubscribe', 'forum');
														}
												} else {
														$subtext = get_string('subscribe', 'forum');
												}
												echo $OUTPUT->heading($forumname, 2, 'headingblock header');
												$suburl = new moodle_url('/mod/forum/subscribe.php', array('id' => $newsforum->id, 'sesskey' => sesskey()));
												echo html_writer::tag('div', html_writer::link($suburl, $subtext), array('class' => 'subscribelink'));
										} else {
												echo $OUTPUT->heading($forumname, 2, 'headingblock header');
										}

										forum_print_latest_discussions($SITE, $newsforum, $SITE->newsitems, 'plain', 'p.modified DESC');
										echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipsitenews'));
								}
						break;

						case FRONTPAGECOURSELIST:
								if (isloggedin() and !$hassiteconfig and !isguestuser() and empty($CFG->disablemycourses)) {
										echo html_writer::tag('a', get_string('skipa', 'access', moodle_strtolower(get_string('mycourses'))), array('href'=>'#skipmycourses', 'class'=>'skip-block'));
										echo $OUTPUT->heading(get_string('mycourses'), 2, 'headingblock header');
										print_my_moodle();
										echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipmycourses'));
								} else if ((!$hassiteconfig and !isguestuser()) or ($DB->count_records('course') <= FRONTPAGECOURSELIMIT)) {
										// admin should not see list of courses when there are too many of them
										echo html_writer::tag('a', get_string('skipa', 'access', moodle_strtolower(get_string('availablecourses'))), array('href'=>'#skipavailablecourses', 'class'=>'skip-block'));
										echo $OUTPUT->heading(get_string('availablecourses'), 2, 'headingblock header');
										print_courses(0);
										echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipavailablecourses'));
								}
						break;

						case FRONTPAGECATEGORYNAMES:
								echo html_writer::tag('a', get_string('skipa', 'access', moodle_strtolower(get_string('categories'))), array('href'=>'#skipcategories', 'class'=>'skip-block'));
								echo $OUTPUT->heading(get_string('categories'), 2, 'headingblock header');
								echo $OUTPUT->box_start('generalbox categorybox');
								print_whole_category_list(NULL, NULL, NULL, -1, false);
								echo $OUTPUT->box_end();
								print_course_search('', false, 'short');
								echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipcategories'));
						break;

						case FRONTPAGECATEGORYCOMBO:
								echo html_writer::tag('a', get_string('skipa', 'access', moodle_strtolower(get_string('courses'))), array('href'=>'#skipcourses', 'class'=>'skip-block'));
								echo $OUTPUT->heading(get_string('courses'), 2, 'headingblock header');
								$renderer = $PAGE->get_renderer('core','course');
								// if there are too many courses, budiling course category tree could be slow,
								// users should go to course index page to see the whole list.
								$coursecount = $DB->count_records('course');
								if (empty($CFG->numcoursesincombo)) {
										// if $CFG->numcoursesincombo hasn't been set, use default value 500
										$CFG->numcoursesincombo = 500;
								}
								if ($coursecount > $CFG->numcoursesincombo) {
										$link = new moodle_url('/course/');
										echo $OUTPUT->notification(get_string('maxnumcoursesincombo', 'moodle', array('link'=>$link->out(), 'maxnumofcourses'=>$CFG->numcoursesincombo, 'numberofcourses'=>$coursecount)));
								} else {
										echo $renderer->course_category_tree(get_course_category_tree());
								}
								print_course_search('', false, 'short');
								echo html_writer::tag('span', '', array('class'=>'skip-block-to', 'id'=>'skipcourses'));
						break;

						case FRONTPAGETOPICONLY:		// Do nothing!!	:-)
						break;

				}
				echo '<br />';
		}
?>

