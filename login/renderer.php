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
 * Collection of renderer objects related to the main Moodle authentication/login process.
 *
 * @package    core
 * @subpackage auth
 * @author     Kyle J. Temkin <ktemkin@binghamton.edu>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Added from: https://github.com/bumoodle/moodle/compare/WIP_MDL_29940

// Constant which indicates that the user can opt-in to "remember username".
define('REMEMBER_USERNAME_OPTIONAL', 2);

/**
 * Virtual "enumeration" which specifies the column style for a login box.
 * 
 * @package core
 * @subpackage auth
 * @license GNU Public License, {@link http://www.gnu.org/copyleft/gpl.html}
 */
abstract class core_login_column_style {

    /**
     *  Single column layout- only the login box is rendered.
     */
    const ONE_COLUMN = 'onecolumn';

    /**
     *  Dual column layout- the login box is rendered with some ancillary text (e.g. sign-up instructions).
     */
    const TWO_COLUMN = 'twocolumns';
}


/**
 * An object which contains the metadata associated with a Moodle login form.
 * Used for rendering the core login area of a Moodle form.
 * 
 * @uses renderable
 * @author Kyle Temkin <ktemkin@binghamton.edu> 
 * @license GNU Public License, {@link http://www.gnu.org/copyleft/gpl.html}
 */
class login_form implements renderable {

    /**
     * @var string Determines the column layout for the login form. Supported values are provided in the core_login_column_style class.
     */
    public $column_style;

    /**
     * @var bool Determines whether Moodle should request that the recieving browser disallow autocomplete. This is more secure, but breaks XHTTP strict standards.
     */
    public $allow_autocomplete;

    /**
     * @var string An error message indicating any errors in the last login attempt. If this variable is empty (e.g. ''), no errors will be displayed.
     */
    public $error_message = '';

    /**
     * @var int The input size, in characters, for each of the inputs in the login form.
     */
    public $input_size = 15;

    /**
     * @var string The user name which will provide the current value of the form's input box.
     */
    public $username = '';

    /**
     * @var string The user's password (!) as recieved by the login form. Used only for storage- this should not be used in the rendering of the form!
     */
    public $password = '';

    /**
     * @var bool Stores whether the user's username should be remembered. If true, the "remember username" box will be checked.
     */
    public $rememberusername = true;

    /**
     * Constructs a new login_form object, populating the internal properties with default values according to $CFG. 
     *
     * @param string $username Specifies the initial value of the "username" input. If not provided, this class will attempt
     *                         to load a default value from the 'MOODLEID' cookie.
     */
    public function __construct($username = '', $password = '', $rememberusername = true) {

        global $CFG;

        // Determine the default number of columns which should be shown in the login form. 
        // If any of the following conditions are met, we'll default to a "two column" layout,
        // so we can show instructions:
        // - "Self registration" is enabled;
        // - "No authentication" is enabled (which assumes all unknown credentials are valid account creation requests)l or
        // - Authentication instructions have been provided in in Moodle configuration.
        // If none of the above are met, we'll default to a "one column" layout.
        if (!empty($CFG->registerauth) || is_enabled_auth('none') || !empty($CFG->auth_instructions)) {
            $this->column_style = core_login_column_style::TWO_COLUMN; 
        } else {
            $this->column_style = core_login_column_style::ONE_COLUMN;
        }

        // If the "disallow autocomplete" option is set, then attempt to disallow autocompletion of Moodle usernames and passwords.
        $this->allow_autocomplete = empty($CFG->loginpasswordautocomplete);

        // If a username was provided, use it.
        if($username !== '') {
            $this->username = $username;
        } else {
            // Otherwise, load the default value from a cookie:
            $this->username = get_moodle_cookie();
        }

        // Fill in the fields from the constructor parameters:
        $this->password = $password;
        $this->rememberusername = $rememberusername;
    }

    /**
     * Creates a new login_form from the currently submitted postdata.
     * 
     * @return login_form The login-form which was created from the submitted postdata.
     */
    public static function from_submitted_data() {

        // Get the values of the data submitted by the login form. Note that these statements are very similar to optional_param,
        // but only accept values submitted via POST. 
        //
        // For compatibility with the current login code, each of these values is set to null if unset- so isset() will return false.
        $username = isset($_POST['username']) ? clean_param($_POST['username'], PARAM_USERNAME) : null;
        $password = isset($_POST['password']) ? clean_param($_POST['password'], PARAM_TEXT) : null;
        $rememberusername = isset($_POST['rememberusername']) ? clean_param($_POST['rememberusername'], PARAM_BOOL) : true;

        //... and use it to create a new login_form.
        return new self($username, $password, $rememberusername);
    }

    /**
     * Creates a new login_form from a stdClass object.
     * 
     * @param stdClass $object The object which should be used to create the login_form. 
     * @return login_form The login-form which was created from the given object.
     */
    public static function from_std_class(stdClass $object) {

        // Get values for each of the three "core" form properties...
        $username = empty($object->username) ? null : $object->username;
        $password = empty($object->password) ? null : $object->password;
        $rememberusername = empty($object->rememberusername) ? null : $object->rememberusername;

        // ... and user them to create a new login form.
        return new self($username, $password, $rememberusername);
    }

    /**
     * Creates a new login_form from an array.
     * 
     * @param array $array The array which should be used to create the login_form. 
     * @return login_form The login-form which was created from the given array.
     */
    public static function from_array(array $array) {
        return self::from_std_class((object)$array);
    }

}


/**
 * Represents a (renderable) external view of an Identity Provier (IDP), a 
 * service which can be used to provide authentication to Moodle.
 * 
 * @uses renderable
 * @author Kyle Temkin <ktemkin@binghamton.edu> 
 * @license GNU Public License, {@link http://www.gnu.org/copyleft/gpl.html}
 */
class identity_provider implements renderable {

    /**
     * @var moodle_url  A URL which can be used to link to the given Identity Provider.
     */
    protected $url;

    /**
     * @var string  The printable name for the given identity provider.
     */
    protected $name;


    /**
     * @var pix_icon An icon which is indicative of an indentity provider. Used for linking.
     */
    protected $icon;

    /**
     * Simple property-based constructor for an identify provider 
     * 
     * @param moodle_url $url  A URL which can be used to link to the given Identity Provider.
     * @param string $name  The printable name for the given identity provider.
     * @param pix_icon $icon An icon which is indicative of an indentity provider. Used for linking.
     * @return void
     */
    public function __construct($url, $name, $icon) {

        // Copy the internal fields from the constructor.
        $this->url = $url;
        $this->name = $name;
        $this->icon = $icon;
    }

    /**
     * Simple "magic" getter, which allows read-only access to all properties.
     * 
     * @param string $var The name of the variable to be accessed.
     * @return void
     */
    public function __get($var) {
        return $this->$var;
    }

    /**
     * Convenience method which creates an identity provider from an array.
     * 
     * @param array $idp  An array which _must_ contain at an IDP's url, name, and icon.
     * @return identity_provier A (renderable) identity provider object.
     */
    public static function from_array(array $idp) {
        return new self($idp['url'], $idp['name'], $idp['icon']);
    }
}


/**
 * Primary login renderer; handles the primary authentication method for Moodle. 
 * 
 * @uses plugin_renderer_base
 * @package core
 * @subpackage auth 
 * @author Kyle J. Temkin <ktemkin@binghamton.edu>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_auth_renderer extends plugin_renderer_base {

    /**
     * Renders a login form using options provided in the given $login_form object.
     * 
     * @param login_form $login_form The login form to be rendered, which contains rendering options.
     * @param array $potential_idps A collection of potential identity providers. If provided, links to the  given identity providers will be added.
     * @return string The newly-rendered HTML snippet which represents the bulk of a login page.
     */
    public function login_page(login_form $login_form, array $potential_idps = array()) {

        global $CFG;

        // Start the main login box and panel.
        $output = html_writer::start_tag('div', array('class' => 'loginbox clearfix '.$login_form->column_style));
        $output .= html_writer::start_tag('div', array('class' => 'loginpanel'));

        // If self-registration is enabled, add a "skip to registration" link.
        if (!empty($CFG->registerauth)) {

            // Create a direct link to the Moodle sign-up page...
            $skip_link = new moodle_url('/login/signup.php');

            // ... and output the link.
            $output .= html_writer::start_tag('div', array('class' => 'skiplinks'));
            $output .= html_writer::link($signup_link, get_string('tocreateaccount'), array('class' => skip));
            $output .= html_writer::end_tag('div');
        }

        // Add the "returning to this site?" heading.
        $output .= $this->output->heading(get_string('returningtosite'));

        // Start the core "subcontent" for the login box, which houses the main controls and login form.
        $output .= html_writer::start_tag('div', array('class' => 'subcontent loginsub'));

        // Add the "description", which contains the "login here" and "cookies required" texts.
        $output .= html_writer::start_tag('div', array('class' => 'desc'));
        $output .= get_string('loginusing');
        $output .= html_writer::empty_tag('br');
        $output .= get_string('cookiesenabled');
        $output .= $this->output->help_icon('cookiesenabled');
        $output .= html_writer::end_tag('div');

        //If login errors have occurred, display the relevant error message.
        if (!empty($login_form->error_message)) {
            $output .= html_writer::tag('div', $this->output->error_text($login_form->error_message), array('class' => 'loginerrors'));
        }

        // Render the core login form.
        $output .= $this->render($login_form);

        // End the login box "subcontent".
        $output .= html_writer::end_tag('div');


        // If the "login is guest" button is enabled in the configuration, and the user is not already logged in as a guest...
        if ($CFG->guestloginbutton && !isguestuser()) {

            //... render the "login as guest" form.
            $output .= $this->guest_login_form();
        }

        // End the "login panel" div.
        $output .= html_writer::end_tag('div');

        // If we're using a two-column layout, render the second column, which is typically
        // used to display sign-up options or a 
        if ($login_form->column_style == core_login_column_style::TWO_COLUMN) {
            $output .= $this->login_second_column();
        }

        // If a list of potential Identity Providers was provided, render it.
        if(!empty($potential_idps)) {
            $output .= $this->potential_identity_providers($potential_idps);
        }

        // End the login-box div.
        $output .= html_writer::end_tag('div');

        // Return the newly-rendered login page.
        return $output;
    }



    /**
     * Renders a login form using options provided in the given $login_form object.
     * 
     * @param login_form $login_form The login form to be rendered, which contains rendering options.
     * @return string The newly-rendered HTML snippet.
     */
    protected function render_login_form(login_form $login_form) {

        global $CFG;

        // Construct the attributes for the login form:
        $form_options = array(
            'action' => $CFG->httpswwwroot.'/login/index.php',
            'method' => 'POST', 
            'id' => 'login'
        );

        // If the form has disallowed autocomlete, add an "autocomplete off" attribute:
        if (!$login_form->allow_autocomplete) {
            $form_options['autocomplete'] = 'off';
        }

        // Start the HTML for which the user uses to log in. 
        // Note that we're not using a Moodleform- as doing so would break all current theme's CSS login stylings.
        $output = html_writer::start_tag('form', $form_options);

        // Start the login form div.
        $output .= html_writer::start_tag('div', array('class' => 'loginform'));

        // Render the username field. 
        $output .= $this->login_input_field('username', 'text', $login_form->username, $login_form->input_size);

        // Render a submit button, which will be appended to the password field...
        $submit_button = html_writer::empty_tag('input', array('type' => 'submit', 'id' => 'loginbtn', 'value' => get_string('login')));

        // ... and render the submit button and password field.
        $output .= $this->login_input_field('password', 'password', '', $login_form->input_size, $submit_button);

        // End the core "loginform" div. Note that this div does not contain the "remember username" checkbox, for 
        // compatibility with legacy themes. 
        $output .= html_writer::end_tag('div');

        // If the site allows the user to select whether their username is remembered, add the "remember username" checkbox.
        // Note the presence of the legacy class name "rememberpass", which is retained for compatibility with older themes-
        // but should be considered deprecated. 
        if(isset($CFG->rememberusername) && $CFG->rememberusername == REMEMBER_USERNAME_OPTIONAL) {

            $output .= html_writer::start_tag('div', array('class' => 'rememberusername rememberpass'));
            $output .= html_writer::checkbox('rememberusername', '1', $login_form->rememberusername,
                    get_string('rememberusername', 'admin'), array('id' => 'rememberusername'));
            $output .= html_writer::end_tag('div');
        }

        // Add a separator div. This is done in a legacy style (rather than using the output function) for compatibility with
        // current theme CSS styles.
        $output .= html_writer::tag('div', '<!-- -->', array('class'=> 'clearer'));

        // Create a direct link to the Moodle sign-up page...
        $skip_link = new moodle_url('/login/forgot_password.php');

        // ... and output the link.
        $output .= html_writer::start_tag('div', array('class' => 'forgetpass'));
        $output .= html_writer::link($skip_link, get_string('forgotten'));
        $output .= html_writer::end_tag('div');

        // End the login form.
        $output .= html_writer::end_tag('form');

        // Return the newly rendered login form.
        return $output; 

    }

    /**
     * Internal method used to render an input field in the style used by the Moodle login form.
     * 
     * @param string $field_name The name of the field, which is used in three ways: as the _name_ of the field element, as the _name_ of the relevant label string, and as the _id_ of the field element.
     * @param string $value The default value for the input field.
     * @param int $size The size of the input field, which will be used to set the size parameter.
     * @return string The HTML snippet which contains the relevant input field.
     */
    protected function login_input_field($field_name, $type = 'text', $value = '', $size = 15, $extra_content = '') {

        // Add the field's label.
        $output = html_writer::start_tag('div', array('class' => 'form-label'));
        $output .= html_writer::label(get_string($field_name), $field_name);
        $output .= html_writer::end_tag('div');
        
        // Add the field's input.
        $output .= html_writer::start_tag('div', array('class' => 'form-input'));
        $output .= html_writer::empty_tag('input', array('type' => $type, 'name' => $field_name, 'id' => $field_name, 'size' => $size, 'value' => s($value)));

        // Add any extra content directly to the output, inside of the input div.
        // This is necessary to render select pieces of the legacy form.
        $output .= $extra_content;

        // Close the input-field div.
        $output .= html_writer::end_tag('div');

        // Add a separator div. This is done in a legacy style (rather than using the output function) for compatibility with
        // current theme CSS styles.
        $output .= html_writer::tag('div', '<!-- -->', array('class'=> 'clearer'));

        // Return the newly created output.
        return $output;
    }

    /**
     * Renders a form designed to log the user in as a guest user.
     * 
     * @return string   The HTML content of the normal guest login form.
     */
    protected function guest_login_form() {
        
        // Start the guest-login subsection.
        $output = html_writer::start_tag('div', array('class' => 'subcontent guestsub'));

        // Render the "some courses may allow guests" message.
        $output .= html_writer::tag('div', get_string('someallowguest'), array('class' => 'desc'));

        // Start the guest-login form.
        $output .= html_writer::start_tag('form', array('action' => $CFG->httpswwwroot.'/login/index.php', 'method' => 'POST', 'id' => 'guestlogin'));

        // Start an inner "form elements" div.
        $output .= html_writer::start_tag('div', array('class' => 'guestform'));

        // Provide the interal guest username and password.
        $output .= html_writer::empty_tag('input', array('type' => 'hidden', name => 'username', 'value' => 'guest'));
        $output .= html_writer::empty_tag('input', array('type' => 'hidden', name => 'password', 'value' => 'guest'));

        // Add the submit buton.
        $output .= html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('loginguest')));

        // End the inner forms div, the form, and the outter "guest subsection" div.
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('form');
        $output .= html_writer::end_tag('div');

        // Return the newly composed guest login form.
        return $output;

    }

    /**
     * Renders the "second column" in a multi-column layout.
     * 
     * @return string The HTML snippet which contains the newly-rendered second column.
     */
    protected function login_second_column() {

        global $CFG;

        // Start the second column div. Note the legacy name "signuppanel", which indicates its original function.
        // Like "rememberpass", reliance on 'signuppanel' should be discouraged.
        $output = html_writer::start_tag('div', array('class' => 'secondcolumn signuppanel'));

        // Add the "is this your first time here?" heading.
        $output .= $this->output->heading(get_string('firsttime'));

        // Start a "subcontent" div, which houses the main second column content.
        $output .= html_writer::start_tag('div', array('class' => 'subcontent'));

        // If the "no authentication" method of automatically creating valid credentials is enabled...
        if(is_enabled_auth('none')) {

            //... explain this method to the user.
            $output .= get_string('loginstepsnone');
        } 
        // If another form of self-registration is enabled, render its "entry point" button.
        else if(!empty($CFG->registerauth)) {
            $output .= $this->self_registration_button();
        }
        // Otherwise, if authentication instructions have been configured, display them.
        else if (!empty($CFG->auth_instructions)) {
            $output .= format_text($CFG->auth_instructions);
        }

        // End the subcontent and second-column divs.
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');

        // Return the newly created second column.
        return $output;
    }

    /**
     * Render a self-registration form, which appears to the user as a button with which they can
     * start registration.
     * 
     * @return string The HTML snippet used to display the self-registration button.
     */
    protected function self_registration_button() {
        
        global $CFG;

        // Start an output 'buffer', which will store the self-registration button as we build it.
        $output = '';

        // If authentication instructions have been provided, display them.
        if(!empty($CFG->auth_instructions)) {
            $output .= format_text($CFG->auth_instructions);
        } 
        // Otherwise, use any known defaults. Currently, we only have a sane default for e-mail based self registration.
        // TODO: Get the default instructions from the authentication plugin (or from its string file).
        else if($CFG->registerauth == 'email') {
            $output .= get_string('loginsteps', '', 'signup.php');
        }

        // Get the URL used for self-registration.
        $signup_url = new moodle_url('/login/signup.php');

        // Start the "signup form" div and form.
        $output .= html_writer::start_tag('div', array('class' => 'signupform'));
        $output .= html_writer::start_tag('form', array('action' => $signup_url->out(), 'method' => 'GET', 'id' => 'signup'));

        // Add the submit button, which starts the registration process...
        $output .= html_writer::tag('div', html_writer::empty_tag('input', array('type' => 'submit', 'value' => get_string('startsignup'))));

        // End the form and the div.
        $output .= html_writer::end_tag('form');
        $output .= html_writer::end_tag('div');

    }

    /**
     * Renders an icon link to a given Identity Provider; suitable for use in a login form.
     * 
     * @param identity_provider $idp The Identity Provider to be rendered. 
     * @return string A HTML snippet which contains an icon link to the given identity provider.
     */
    public function render_identity_provider(identity_provider $idp) {

        // Start the "Potential IDP" link div.
        $output = html_writer::start_tag('div', array('class' => 'potentialidp'));

        // Get a HTML snippet which contains the icon for the given identity provider.
        $idp_icon = $this->output->render($idp->icon);

        // And create a link to the IDP.
        $output .= html_writer::link($idp->url, $idp_icon, array('title' => $idp->name));

        // End the "potential IDP" div.
        $output .= html_writer::end_tag('div');

        // Return the newly-rendered IDP link.
        return $output;
    }

    
    /**
     * Creates the section of a login page designed to list potential Identity Providers.
     * 
     * @param array $potential_idps An array of potential Identity Providers, which will be displayed as links.
     * @return string A HTML snippet which contains links to each identity provider in the given array.
     */
    public function potential_identity_providers(array $potential_idps) {

        // Start the "potential identity providers" section div.
        $output = html_writer::start_tag('div', array('class' => 'subcontent potentialidps'));
        
        // Add a "Potential Identity Providers" heading.
        $output .= $this->output->heading(get_string('potentialidps'), 6);

        // Start the "IDP List" div.
        $output .= html_writer::start_tag('div', array('class' => 'potentialidplist'));

        // Add each of the given IDPs to the list.
        foreach($potential_idps as $idp) {
            $output .= $this->render($idp);
        }

        // End each of the divs (the list and the section)...
        $output .= html_writer::end_tag('div');
        $output .= html_writer::end_tag('div');

        // ... and return the newly-created IDP section.
        return $output;
    }

}