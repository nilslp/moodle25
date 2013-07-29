<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle255';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbsocket' => 0,
);

$CFG->wwwroot   = 'http://moodle255.tonyf.hi';
$CFG->dataroot  = '/www2/sites/moodle255.tonyf.hi/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

// Debug settings
//$CFG->debug = 38911;
//$CFG->debugdisplay = true;
//Development Mode Settings
$CFG->themerev = -1;
$CFG->perfdebug = true;
$CFG->debugpageinfo = true;
$CFG->themedesignermode = true;
$CFG->allowthemechangeonurl = true;
$CFG->cachejs  = false;
$CFG->yuicomboloading = false;


//
require_once(dirname(__FILE__) . '/lib/setup.php');
//require_once(dirname(__FILE__) . '/local/lp_courseaccessmanager/lib.php');
//unset($DB);
//local_lp_cam_setup_DB();


// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
