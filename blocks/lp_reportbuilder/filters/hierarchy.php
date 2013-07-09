<?php //$Id$

require_once($CFG->dirroot.'/blocks/lp_reportbuilder/filters/lib.php');

/**
 * Generic filter based on a hierarchy.
 */
class filter_hierarchy extends filter_type {

    /**
     * Hierarchy type
     */
    var $_type;

    /**
     * Constructor
     * @param object $filter rb_filter object for this filter
     * @param string $sessionname Unique name for the report for storing sessions
     */
    function filter_hierarchy($filter, $sessionname, $type) {
        // hierarchy type
        //$this->_type = $type;
        $this->_type = 'lp_hierarchy';
        parent::filter_type($filter, $sessionname);
    }

    /**
     * Returns an array of comparison operators
     * @return array of comparison operators
     */
    function get_operators() {
        return array(0 => get_string('isanyvalue','filters'),
                     1 => get_string('isequalto','filters'),
                     2 => get_string('isnotequalto','filters'));
    }

    /**
     * Adds controls specific to this filter in the form.
     * @param object $mform a MoodleForm object to setup
     */
    function setupForm(&$mform) {
        global $SESSION;
        $sessionname = $this->_sessionname;
        $label = $this->_filter->label;
        $advanced = $this->_filter->advanced;
        $type = $this->_type;

        // manually disable buttons - can't use disabledIf because
        // button isn't created using form element
        $attr = "onChange=\"if(this.value == 0) {
            $('input[name=" . $this->_name."_rec]').attr('disabled',true);
            $('#show-" . $this->_name."-dialog').attr('disabled',true);
        } else {
            $('input[name=" . $this->_name."_rec]').removeAttr('disabled');
            $('#show-" . $this->_name."-dialog').removeAttr('disabled');
        }\"";
        $objs = array();
        $objs[] =& $mform->createElement('select', $this->_name.'_op', null, $this->get_operators(), $attr);
        $objs[] =& $mform->createElement('static', 'title'.$this->_name, '', '<span id="' . $this->_name . 'title" class="dialog-result-title"></span>');
        // can't use a button because id must be 'show-*-dialog' and
        // formslib appends 'id_' to ID
        // TODO change dialogs to bind to any id
        $objs[] =& $mform->createElement('static', 'selectorbutton',
            '',
            '<input type="button" value="' .
            get_string('choose'.$this->_type, 'block_lp_reportbuilder') .
            '" id="show-' . $this->_name . '-dialog" />');
        $objs[] =& $mform->createElement('checkbox', $this->_name . '_rec', '', get_string('includesubcategories', 'block_lp_reportbuilder'));

        $grp =& $mform->addElement('group', $this->_name.'_grp', $label, $objs, '', false);
       // $grp->setHelpButton(array('reportbuilderdialogfilter', $label, 'local_reportbuilder'));
        if ($advanced) {
            $mform->setAdvanced($this->_name.'_grp');
        }

        $mform->addElement('hidden', $this->_name);

        $hid = optional_param($this->_name, 0, PARAM_INT);
        $hop = optional_param($this->_name.'_op', 0, PARAM_INT);
        $hrec = optional_param($this->_name.'_rec', 0, PARAM_INT);
        if (!empty($hid)){
            $SESSION->{$sessionname}[$this->_name] = array(array(
                'value'=>$hid,
                'operator'=>$hop,
                'recursive'=>$hrec
            ));
        }
        
        if(array_key_exists($this->_name, $SESSION->{$sessionname})) {
            $defaults = $SESSION->{$sessionname}[$this->_name];
        } 
        
        if(isset($defaults[0]['value'])) {
            $mform->setDefault($this->_name, $defaults[0]['value']);
        }

        // set other default values
        //TODO get rid of need for [0]
        if(isset($defaults[0]['operator'])) {
            $mform->setDefault($this->_name.'_op', $defaults[0]['operator']);
        }
        if(isset($defaults[0]['recursive'])) {
            $mform->setDefault($this->_name.'_rec', $defaults[0]['recursive']);
        }
    }

        function definition_after_data(&$mform) {
    	global $DB;
        if($id = $mform->getElementValue($this->_name)) {
            if($title = $DB->get_field($this->_type, 'fullname', array('id'=>$id))) {
                $mform->setDefault('title'.$this->_name,
                    '<span id="' . $this->_name . 'title" class="dialog-result-title">'.$title.'</span>');
            }
        }
    }


    /**
     * Retrieves data from the form data
     * @param object $formdata data submited with the form
     * @return mixed array filter data or false when filter not set
     */
    function check_data($formdata) {
        $field    = $this->_name;
        $operator = $field.'_op';
        $recursive = $field.'_rec';

        if (array_key_exists($field, $formdata) &&
            $formdata->$field != '') {
            $data = array('operator' => (int)$formdata->$operator,
                          'value'    => (string)$formdata->$field);
            if(isset($formdata->$recursive)) {
                $data['recursive'] = (int)$formdata->$recursive;
            } else {
                $data['recursive'] = 0;
            }

            return $data;
        }

        return false;
    }

    /**
     * Returns the condition to be used with SQL where
     * @param array $data filter settings
     * @return string the filtering condition or null if the filter is disabled
     */
    function get_sql_filter($data) {
        global $DB;
        $operator = $data['operator'];
        $recursive = (isset($data['recursive']) && $data['recursive']);
        $value    = $data['value'];
        $query    = $this->_filter->type.'.'.$this->_filter->value;

        switch($operator) {
            case 1:
                $token = ' IN ';
                break;
            case 2:
                $token = ' NOT IN ';
                break;
            default:
                // return 1=1 instead of TRUE for MSSQL support
                return ' 1=1 ';
        }

        $path = $value;
        
        if ($recursive){
            $sub = $DB->get_records_sql("SELECT id FROM {lp_hierarchy} WHERE path LIKE '%/$path/%'");
            if (count($sub)){
                $sub = array_keys($sub);
                $sub []= $path;
                $path = implode(',',$sub);
            }
        }
        
        $path = " ( $path ) ";
        
        if($operator == 2) {
            // check for null case for is not operator
            return '('.$query.$token.$path." OR ".$query.' IS NULL)';
        } else {
            return $query.$token.$path;
        }
    	return '';
    }


    /**
     *
     * @global moodle_database $DB 
     * @param type $data
     * @return type 
     */
    function get_label($data) {
    	global $DB;

            /**
     * Returns a human friendly description of the filter used as label.
     * @param array $data filter settings
     * @return string active filter label
     */
        $operators = $this->get_operators();
        $operator  = $data['operator'];
        $recursive = $data['recursive'];
        $value     = $data['value'];
        $label = $this->_filter->label;

        if (empty($operator) || $value == '') {
            return '';
        }

        $itemname = $DB->get_field($this->_type, 'fullname', array('id' => $value));

        $a = new object();
        $a->label    = $label;
        $a->value    = '"'.s($itemname).'"';
        if($recursive) {
            $a->value .= ' (and children)';
        }
        $a->operator = $operators[$operator];

        return get_string('selectlabel', 'filters', $a);
        
    	#return 'hierarchy.php string';
    }
}

