<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010, 2011 Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Eugene Venter <eugene@catalyst.net.nz>
 * @package totara
 * @subpackage reportbuilder
 */

require_once('../../../config.php');

global $DB;

/// Check access
require_sesskey();
require_login();
// TODO
//require_capability('local/reportbuilder:managereports', get_context_instance(CONTEXT_SYSTEM));

/// Get params
$action = required_param('action', PARAM_TEXT);
$reportid = required_param('id', PARAM_INT);

switch ($action) {
    case 'add' :
        $column = required_param('col', PARAM_TEXT);
        $column = explode('-', $column);
        $coltype = $column[0];
        $colvalue = $column[1];
        $heading = optional_param('heading', '', PARAM_TEXT);

        /// Prevent duplicates
        $sql = "SELECT id FROM {$CFG->prefix}report_builder_columns c
            WHERE reportid = {$reportid}
            AND type = '{$coltype}'
            AND value = '{$colvalue}'";

        if ($DB->get_record_sql($sql)) {
            echo false;
            exit;
        }

        /// Save column
        $todb = new object();
        $todb->reportid = $reportid;
        $todb->type = $coltype;
        $todb->value = $colvalue;
        $todb->heading = $heading;
        $sortorder = $DB->get_field('report_builder_columns', 'MAX(sortorder) + 1', array('reportid' => $reportid));
        if(!$sortorder) {
            $sortorder = 1;
        }
        $todb->sortorder = $sortorder;

        $id = $DB->insert_record('report_builder_columns', $todb);

        echo $id;
        break;
    case 'delete':
        $colid = required_param('cid', PARAM_INT);
        if ($column = $DB->get_record('report_builder_columns', array('id' => $colid))) {
            
            $report = $DB->get_record('report_builder', array('id' => $column->reportid));
            
            if (!empty($report->defaultsortcolumn)) {
                if ($report->defaultsortcolumn == $column->type . '_' . $column->value) {
                    // Clear the existing sort column
                    $report->defaultsortcolumn = '';
                    
                    $DB->update_record('report_builder', $report);
                }
            }
            
            if ($DB->delete_records('report_builder_columns', array('id' => $colid))) {
                //require_once($CFG->dirroot . '/lib/pear/HTML/AJAX/JSON.php'); // required for PHP5.2 JSON support
                echo json_encode((array)$column);
            } else {
                echo false;
            }
        } else {
            echo false;
        }
        break;
    case 'hide':
        $colid = required_param('cid', PARAM_INT);
        $todb = new stdClass;
        $todb->id = $colid;
        $todb->hidden = 1;

        if ($DB->update_record('report_builder_columns', $todb)) {
            echo $colid;
        } else {
            echo false;
        }
        break;
    case 'show':
        $colid = required_param('cid', PARAM_INT);
        $todb = new stdClass;
        $todb->id = $colid;
        $todb->hidden = 0;

        if ($DB->update_record('report_builder_columns', $todb)) {
            echo $colid;
        } else {
            echo false;
        }
        break;
    case 'movedown':
        $colid = required_param('cid', PARAM_INT);

        $col = $DB->get_record('report_builder_columns', array('id' => $colid));
        $sql = "SELECT * FROM {$CFG->prefix}report_builder_columns
            WHERE reportid = {$reportid} AND sortorder > {$col->sortorder}
            ORDER BY sortorder";
        if (!$lowersibling = $DB->get_record_sql($sql)) {
            echo false;
            exit;
        }

        $todb = new stdClass;
        $todb->id = $col->id;
        $todb->sortorder = $lowersibling->sortorder;

        if (!$DB->update_record('report_builder_columns', $todb)) {
            echo false;
            exit;
        }

        $todb = new stdClass;
        $todb->id = $lowersibling->id;
        $todb->sortorder = $col->sortorder;

        if (!$DB->update_record('report_builder_columns', $todb)) {
            echo false;
            exit;
        }

        echo "1";
        break;
    case 'moveup':
        $colid = required_param('cid', PARAM_INT);

        $col = $DB->get_record('report_builder_columns', array('id' => $colid));
        $sql = "SELECT * FROM {$CFG->prefix}report_builder_columns
            WHERE reportid = {$reportid} AND sortorder < {$col->sortorder}
            ORDER BY sortorder DESC";
        if (!$uppersibling = $DB->get_record_sql($sql)) {
            echo false;
            exit;
        }

        $todb = new stdClass;
        $todb->id = $col->id;
        $todb->sortorder = $uppersibling->sortorder;

        if (!$DB->update_record('report_builder_columns', $todb)) {
            echo false;
            exit;
        }

        $todb = new stdClass;
        $todb->id = $uppersibling->id;
        $todb->sortorder = $col->sortorder;

        if (!$DB->update_record('report_builder_columns', $todb)) {
            echo false;
            exit;
        }

        echo "1";
        break;

    default:
        echo '';
        break;
}

exit;

?>
