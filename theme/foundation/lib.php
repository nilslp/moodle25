<?php

require_once($CFG->dirroot.'/lib/adminlib.php');



//////////////////////////////////////////////
// Admin Settings Page

class admin_setting_startgroup extends admin_setting {

    public function __construct($name,$group_name=false) {
        $this->nosave = true;
        $this->group_name = $group_name;
        parent::__construct($name, '', '', '');
    }

    public function output_html($data, $query='') {
        $html = '<div class="admin-setting-group '.$this->group_name.'">';
        return $html;
    }

    // These are no supposed to return anything
    public function get_setting() { return true; }
    public function write_setting($data) { return ''; }

}

class admin_setting_endgroup extends admin_setting {

    public function __construct($name) {
        $this->nosave = true;
        parent::__construct($name, '', '', '');
    }

    public function output_html($data, $query='') {
        $html = '</div>';
        return $html;
    }

    // These are no supposed to return anything
    public function get_setting() { return true; }
    public function write_setting($data) { return ''; }

}



///////////////////////////////////////////
// Header Search Widget

function theme_foundation_search_widget() {

    global $CFG, $PAGE;

    $html = '';

    $show_widget = (!empty($PAGE->theme->settings->searchwidget)) ? $PAGE->theme->settings->searchwidget : false;
    //$show_widget = true;
    if (!$show_widget) {
        return false;
    }

    $html.= '<div class="search-widget">';
    $html.= '<form method="get" action="/course/search.php">';
    $html.= '<fieldset class="coursesearchbox invisiblefieldset">';
    $html.= '<input type="text" value="" name="search" placeholder="'.get_string('label:searchwidget_input', 'theme_foundation').'">';
    $html.= '<button type="submit">'.get_string('label:searchwidget_button','theme_foundation').'</button>';
    $html.= '</fieldset>';
    $html.= '</form>';
    $html.= '</div>';

    return $html;

}



///////////////////////////////////////////
// Ticker

function makeTicker(){

    global $PAGE;

    $ticker = (!empty($PAGE->theme->settings->ticker)) ? $PAGE->theme->settings->ticker : false;

    // Determine whether to show the ticker or not
    if ($ticker) {
        $ticker_raw = str_replace("\n\r", "\n", $ticker);
        $ary_marquee = explode("\n", $ticker_raw);
    }
    else {
        return false;
    }

    // Variable name has switched so if it hasn't been set yet, check the old one.
    if (!empty($PAGE->theme->settings->tickerlocation)) {
        $homeonly = $PAGE->theme->settings->tickerlocation;
    }
    else if (!empty($PAGE->theme->settings->tickerhomeonly)) {
        $homeonly =  $PAGE->theme->settings->tickerhomeonly;
    }
    else {
        $homeonly = 0;
    }

    if ($homeonly && $PAGE->pagetype != 'my-index' && $PAGE->pagetype != 'site-index') { return; }
    if ($homeonly == 2 && !isloggedin() && ($PAGE->pagetype == 'my-index' || $PAGE->pagetype == 'site-index')) { return; }

    $html = '';

    $html .= '<div id="ticker"><div class="wrapper"><h2>'.get_string('ticker_heading','theme_ubertheme').'</h2><ul>';
    $c=1;
    foreach ($ary_marquee as $msg) {
        $msg = explode('|', $msg);
        if (count($msg) > 1) {
            $html .= '<li id="num-'.$c.'" data-id="'.$c++.'"><span><a href="'.$msg[1].'">'.$msg[0].'</a></span></li>';
        }
        else {
            $html .= "<li id=\"num-".$c."\" data-id=\"".$c++."\"><span>".$msg[0]."</span></li>";
        }
    }
    $html .= '</ul>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;

} // end makeTicker()




////////////////////////////////////////////
// Support Widget

function renderSupportWidget() {

    global $CFG;
    global $PAGE;

    $autorun_setting = (isset($PAGE->theme->settings->autorunsupportwidget)) ? $PAGE->theme->settings->autorunsupportwidget : true;
    $autorun = ($autorun_setting) ? 'true' : 'false';

    $html = '';

    // $html .= '<div id="support-widget">';
    // $html .= '<ul>';
    // $html .= '<li class="lp-logo"><a href="http://www.learningpool.com" title="Learning Pool">&nbsp;</a></li>';
    // $html .= '<li class="opts">Support: <a href="'.$CFG->supportpage.'">Web</a> | <a href="mailto:'.$CFG->supportemail.'">Email '.$CFG->supportname.'</a> | <b>0845 543 6033</b>.</li>';
    // $html .= '<li class="retest">&nbsp;</li>';
    // $html .= '<li class="js test unknown failed">&nbsp;</li>';
    // $html .= '<li class="browser test unknown">&nbsp;</li>';
    // $html .= '<li class="popup test unknown">&nbsp;</li>';
    // $html .= '<li class="flash test unknown">&nbsp;</li>';
    // $html .= '</ul>';
    // $html .= '</div>';

    $html .= '<div id="support-widget" data-autorun="'.$autorun.'">';
    $html .= '<div class="wrapper">';
    $html .= '<ul class="tests">';
    $html .= '<li class="unknown"><span class="js">Javascript Enabled</span></li>';
    $html .= '<li class="unknown"><span class="browser">Supported Browser</span></li>';
    $html .= '<li class="unknown"><span class="popup">Pop-ups Enabled</span></li>';
    $html .= '<li class="unknown"><span class="flash">Flash Enabled</span></li>';
    $html .= '</ul>';
    $html .= '<div class="support-details">';
    $html .= '<span class="lp-logo"><a href="http://www.learningpool.com" title="Learning Pool">&nbsp;</a></span>';
    $html .= '<span class="opts">Support: <a href="'.$CFG->supportpage.'">Web</a> | <a href="mailto:'.$CFG->supportemail.'">Email '.$CFG->supportname.'</a> | <b>0845 543 6033</b>.</span>';
    $html .= '<button class="retest">&nbsp;</button>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;

} // end renderSupportWidget()



///////////////////////////////////////
// YUI Initialization

function theme_foundation_init_yui() {

    global $CFG, $PAGE;

    // Foundation Scripts
    if (file_exists($CFG->dirroot . '/theme/foundation/javascript/yui-init.js')) {
		// Removed 2013-04-11 - DPMH: double include of theme config causes duplication issues
        //$foundation_config = theme_config::load('foundation');
        //$yuimods = (isset($foundation_config->javascripts['yuimods'])) ? $foundation_config->javascripts['yuimods'] : array('node');
		$yuimods = array(
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
        );
        $jsconfig = array(
            'name' => 'theme_foundation_init',
            'fullpath' => '/theme/foundation/javascript/yui-init.js',
            'requires' => $yuimods
        );

        $PAGE->requires->js_init_call('M.theme_foundation.init', null, false, $jsconfig);

        $PAGE->requires->string_for_js('course-list-show-az','theme_foundation');
        $PAGE->requires->string_for_js('course-list-show-cat','theme_foundation');
        $PAGE->requires->string_for_js('course-list-goto-cat','theme_foundation');
        $PAGE->requires->string_for_js('course-list-expand-all','theme_foundation');
        $PAGE->requires->string_for_js('course-list-collapse-all','theme_foundation');
    }

} // end theme_foundation_init_yui()



///////////////////////////////////////
// AZ Course List

function theme_foundation_get_course_list() {
    global $DB, $CFG;

    $viewhiddencats = has_capability('moodle/category:viewhiddencategories', get_context_instance(CONTEXT_SYSTEM));
    
    $sql = "SELECT
            c.id,c.sortorder,c.visible,c.fullname,c.shortname,c.summary,c.category,c.format
            FROM {course} c
            WHERE c.format <> 'site'
            ORDER BY c.fullname ASC";
    $records = $DB->get_records_sql($sql);

    $courselist = array();
    $count = 0;

    foreach ($records as $course) {
        if (!$viewhiddencats && !$course->visible) { continue; }
        $temp_sortname = preg_replace('/[^a-zA-Z0-9]/', '', $course->fullname);
        $course->sortname = preg_replace('/([0-9])/','#${1}', $temp_sortname, 1);
        $courselist[$count++] = $course;
    }
    $courselist_sorted = theme_foundation_sortByOneKey($courselist, 'sortname');

    return $courselist_sorted;
}

function theme_foundation_sortByOneKey(array $array, $key, $asc = true) {
    // From > http://www.php.net/manual/en/function.uasort.php#104714
    $result = array();
    $values = array();
    foreach ($array as $value) { $values[] = isset($value->$key) ? $value->$key : ''; }

    if ($asc) {
        asort($values);
    }
    else {
        arsort($values);
    }

    $new_index = 0;
    foreach ($values as $key => $value) { $result[$new_index++] = $array[$key]; }

    return $result;
}



////////////////////////////////
// Colors

class theme_foundation_colors {

    public $basecolor = '#CCC';

    // Percentage of color difference as a decimal of 1
    public $percentage = 1;



    public function lighter ($incr=false, $base=false)
    {
        if (!$base) { $base = $this->basecolor; }
        if (!$incr) { $incr = $this->percentage; }

        $temp_hsl = $this->rgb2hsl($this->hex2rgb($base));
        if (!$temp_hsl) return;
        
        $temp_hsl->l += (1 - $temp_hsl->l) * $incr;
        return $this->rgb2hex($this->hsl2rgb($temp_hsl));
    }

    public function darker ($incr=false, $base=false)
    {
        if (!$base) { $base = $this->basecolor; }
        if (!$incr) { $incr = $this->percentage; }

        $temp_hsl = $this->rgb2hsl($this->hex2rgb($base));
        if (!$temp_hsl) return;

        $temp_hsl->l -= $temp_hsl->l * $incr;
        return $this->rgb2hex($this->hsl2rgb($temp_hsl));
    }

    public function text ($incr=false, $base=false)
    {
        if (!$base) { $base = $this->basecolor; }
        if (!$incr) { $incr = 1; }

        $temp_hsl = $this->rgb2hsl($this->hex2rgb($base));
        if (!$temp_hsl) return;

        if ($temp_hsl->l >= 0.5) { return $this->darker($incr, $base); }
        else                     { return $this->lighter($incr, $base); }
    }

    ///////////////////

    private function hex2rgb ($hex=false)
    {
        if ($hex===false) return false;

        $error = false;
        $rgb = new Object();

        // Assure correct consistant value of XXXXXX
        switch (strlen($hex)) {
            case 7: $mhex = substr($hex,1); // remove assumed leading #
            case 6: break;
            case 4: $hex = substr($hex,1); // remove assumed leading #
            case 3: $mhex=''; for($i=0;$i<3;$i++) { $mhex .= str_repeat(substr($hex,$i,1),2); } break;
            default: $error = true;
        }
        
        if ($error) return false;
        
        $rgb->r = hexdec(substr($mhex,0,2));
        $rgb->g = hexdec(substr($mhex,2,2));
        $rgb->b = hexdec(substr($mhex,4,2));
        
        return $rgb;

    }


    private function rgb2hex ($rgb=false)
    {
        if ($rgb===false) return false;
        
        $temp->r = dechex($rgb->r);
        $temp->g = dechex($rgb->g);
        $temp->b = dechex($rgb->b);
        
        if (strlen($temp->r) == 1) $temp->r = '0'.$temp->r;
        if (strlen($temp->g) == 1) $temp->g = '0'.$temp->g;
        if (strlen($temp->b) == 1) $temp->b = '0'.$temp->b;

        $hex = '#' . $temp->r . $temp->g . $temp->b;
        return $hex;
    }


    private function rgb2hsl ($rgb=false)
    {

        if ($rgb===false) return false;
        if (gettype($rgb) != 'object') {
            $hsl = new Object();
            $hsl->l = 0;
            $hsl->s = 0;
            $hsl->h = 0;
            return $hsl;
        }

        $r = $rgb->r / 255;
        $g = $rgb->g / 255;
        $b = $rgb->b / 255;

        $hsl = new Object();

        $var_min = min($r,$g,$b);
        $var_max = max($r,$g,$b);
        $del_max = $var_max - $var_min;

        $hsl->l = ($var_max + $var_min) / 2;

        if ($del_max == 0)
        {
            $hsl->h = 0;
            $hsl->s = 0;
        }
        else
        {
            if ($hsl->l < 0.5)  { $hsl->s = $del_max / ($var_max + $var_min); }
            else                { $hsl->s = $del_max / (2 - $var_max - $var_min); }

            $del_r = ((($var_max - $r) / 6) + ($del_max / 2)) / $del_max;
            $del_g = ((($var_max - $g) / 6) + ($del_max / 2)) / $del_max;
            $del_b = ((($var_max - $b) / 6) + ($del_max / 2)) / $del_max;

            if     ($r == $var_max) { $hsl->h = $del_b - $del_g; }
            elseif ($g == $var_max) { $hsl->h = (1 / 3) + $del_r - $del_b; }
            elseif ($b == $var_max) { $hsl->h = (2 / 3) + $del_g - $del_r; }

            if ($hsl->h < 0) $hsl->h += 1;
            if ($hsl->h > 1) $hsl->h -= 1;
                
        }
        
        return $hsl;
    }


    private function hsl2rgb ($hsl=false)
    {
        if (!$hsl) return false;

        $rgb = new Object();

        if ($hsl->s == 0)
        {
            $rgb->r = intval($hsl->l * 255);
            $rgb->g = intval($hsl->l * 255);
            $rgb->b = intval($hsl->l * 255);
        }
        else
        {
            if ($hsl->l < 0.5) { $var_2 = $hsl->l * (1 + $hsl->s); }
            else               { $var_2 = ($hsl->l + $hsl->s) - ($hsl->s * $hsl->l); }

            $var_1 = 2 * $hsl->l - $var_2;
            $rgb->r = intval(255 * $this->hue2rgb($var_1,$var_2,$hsl->h + (1 / 3)));
            $rgb->g = intval(255 * $this->hue2rgb($var_1,$var_2,$hsl->h));
            $rgb->b = intval(255 * $this->hue2rgb($var_1,$var_2,$hsl->h - (1 / 3)));
        }
        
        return $rgb;
    }


    private function hue2rgb ($v1=false,$v2=false,$vh=false)
    {
        if ($v1===false || $v2===false || $vh===false) return false;

        if ($vh < 0) $vh += 1;
        if ($vh > 1) $vh -= 1;
        
        if ((6 * $vh) < 1) return ($v1 + ($v2 - $v1) * 6 * $vh);
        if ((2 * $vh) < 1) return ($v2);
        if ((3 * $vh) < 2) return ($v1 + ($v2 - $v1) * ((2 / 3 - $vh) * 6));
        
        return ($v1);
    }

}



////////////////////////////////
// Basic Theme Settings

// Checkbox with description
require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm/checkbox.php');
class LPQuickForm_checkbox extends HTML_QuickForm_checkbox {

    var $_desc = '';

    function LPQuickForm_checkbox($elementName=null, $elementLabel=null, $text=null, $attributes=null, $desc=null) {
        $this->HTML_QuickForm_checkbox($elementName, $elementLabel, $text, $attributes);
        $this->_desc = $desc;
    }

    function toHtml() {
        return HTML_QuickForm_checkbox::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }

}

// WYSIWYG editor with description
require_once($CFG->dirroot.'/lib/form/editor.php');
class LPQuickForm_editor extends MoodleQuickForm_editor {

    var $_desc = '';

    function LPQuickForm_editor($elementName=null, $elementLabel=null, $attributes=null, $options=null, $desc=null) {
        $this->MoodleQuickForm_editor($elementName, $elementLabel, $attributes, $options);
        $this->_desc = $desc;
    }

    function toHtml() {
        return MoodleQuickForm_editor::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}

// Textarea with description
require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm/textarea.php');
class LPQuickForm_textarea extends HTML_QuickForm_textarea {

    var $_desc = '';

    function LPQuickForm_textarea($elementName=null, $elementLabel=null, $attributes=null, $desc=null) {
        $this->HTML_QuickForm_textarea($elementName, $elementLabel, $attributes);
        $this->_desc = $desc;
    }

    function toHtml() {
        return HTML_QuickForm_textarea::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}

// Select with description
require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm/select.php');
class LPQuickForm_select extends HTML_QuickForm_select {

    var $_desc = '';

    function LPQuickForm_select($elementName=null, $elementLabel=null, $options=null, $attributes=null, $desc=null) {
        $this->HTML_QuickForm_select($elementName, $elementLabel, $options, $attributes);
        $this->_desc = $desc;
    }

    function toHtml() {
        return HTML_QuickForm_select::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}

// Color picker with desciption
require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm/text.php');
class LPQuickForm_colorpicker extends HTML_QuickForm_text {

    var $_desc = '';
    var $_attr = '';

    function LPQuickForm_colorpicker($elementName=null, $elementLabel=null, $attributes=null, $desc=null) {
        $this->_attr = array('class'=>'fitem_fcolorpicker');
        $this->HTML_QuickForm_text($elementName, $elementLabel, $this->_attr);
        $this->_desc = $desc;
    }

    function toHtml() {
        return '<div class="admin_colourpicker"></div>' . HTML_QuickForm_text::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}

// Text input with description
require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm/text.php');
class LPQuickForm_text extends HTML_QuickForm_text {

    var $_desc = '';

    function LPQuickForm_text($elementName=null, $elementLabel=null, $attributes=null, $desc=null) {
        $this->HTML_QuickForm_text($elementName, $elementLabel, $attributes);
        $this->_desc = $desc;
    }

    function toHtml() {
        return HTML_QuickForm_text::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}

// Static Label
require_once($CFG->dirroot.'/lib/pear/HTML/QuickForm/static.php');
class LPQuickForm_static extends HTML_QuickForm_static {

    function LPQuickForm_static($elementName=null, $elementLabel=null, $text=null) {
        $this->HTML_QuickForm_static($elementName, $elementLabel, $text);
    }

    function toHtml() {
        $this->updateAttributes(array('id'=>$this->_type));
        return HTML_QuickForm_static::toHtml();
    }

}

require_once($CFG->dirroot.'/lib/form/filepicker.php');
class LPQuickForm_filepicker extends MoodleQuickForm_filepicker {

    var $_desc = '';

    function LPQuickForm_filepicker($elementName=null, $elementLabel=null, $attributes=null, $options=null, $desc=null) {
        $this->MoodleQuickForm_filepicker($elementName, $elementLabel, $attributes, $options);
        $this->_desc = $desc;
    }

    function toHtml() {
        return MoodleQuickForm_filepicker::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}

require_once($CFG->dirroot.'/lib/form/filemanager.php');
class LPQuickForm_filemanager extends MoodleQuickForm_filemanager {

    var $_desc = '';

    function LPQuickForm_filemanager($elementName=null, $elementLabel=null, $attributes=null, $options=null, $desc=null) {
        $this->MoodleQuickForm_filemanager($elementName, $elementLabel, $attributes, $options);
        $this->_desc = $desc;
    }

    function toHtml() {
        return MoodleQuickForm_filemanager::toHtml() . '<div class="desc">'.$this->_desc.'</div>';
    }
}
