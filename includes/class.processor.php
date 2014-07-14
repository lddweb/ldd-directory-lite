<?php

/**
 * Handles all data submitted via the directories Submit Listing interface. Provides an encapsulated object
 * which will contain a safe copy of the form data, validation errors and helper functions to interact with
 * that data at the presentation layer.
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */
class ldd_directory_lite_processor {

    // Constantly
    const NONCE_FIELD = 'nonce_field';
    const NONCE_ACTION = 'submit-listing-nonce';

    const DATA_PREFIX = 'n_';

    /**
     * @var bool True during form submission
     */
    protected $processing = false;

    // Storage facilities
    protected $extra_fields;
    protected $data;
    protected $errors;
    protected $global_errors;

    /**
     * @var array List of required fields, can be altered via the `lddlite_submit_required_fields` filter
     */
    protected $required_fields = array(
        'title',
        'category',
        'description',
        'summary'
    );


    /**
     * Construct the $lddlite_submit_processor object. If a form has been submitted, process and validate the data
     * provided for use in generating a new listing.
     */
    public function __construct() {

        // Check if the form has been submitted
        if (!array_key_exists(self::NONCE_FIELD, $_POST))
            return;

        $this->_verify_nonce();

        // If everything checks out, process and validate
        $this->processing = true;
        $this->_process_data();
        $this->_validate();

    }


    /**
     * Uses wp_verify_nonce() to authorize the form submission before continuing.
     */
    private function _verify_nonce() {
        if (!wp_verify_nonce($_POST[self::NONCE_FIELD], self::NONCE_ACTION))
            die("No, kitty! That's a bad kitty!");
    }


    /**
     * Loop through the supplied data and perform any necessary operations prior to validation. Filters the
     * entire array through `lddlite_submit_process_data` to allow for possible additional processing by add-ons.
     */
    private function _process_data() {

        $pfx_length = strlen(self::DATA_PREFIX);

        foreach ($_POST as $key => $value) {

            if (false === strpos($key, self::DATA_PREFIX)) {
                $this->extra_fields[$key] = $value;
                continue;
            }

            $field = substr($key, $pfx_length);

            if (is_array($value)) {
                $this->data[$field] = stripslashes_deep($value);
            } else {
                $this->data[$field] = stripslashes(trim($value));
            }

        }

        $this->data = apply_filters('lddlite_submit_process_data', $this->data);
    }


    /**
     * Validates the data submitted, and sets errors for any fields that are missing required values. Additionally
     * passes each element through the `lddlite_validate_fields` filters allowing add-ons or core to require further
     * validation of any or all fields.
     */
    private function _validate() {

        // Acquire the list of required fields
        $required = apply_filters('lddlite_submit_required_fields', $this->required_fields);
        $required_errmsg = __('This field is required.', 'lddlite');

        // Loop through and check for required fields first
        foreach ($required as $field) {
            if ('' == $this->data[$field]) {
                $this->errors[$field] = apply_filters('lddlite_presentation_required_errmsg', $required_errmsg, $field);
            }
        }

        // Any additional validation gets run now
        foreach ($this->data as $field => $value) {
            // Attach validation to this filter, be sure to return it empty|false if it passes validation
            $error = apply_filters('lddlite_validate_fields', '', $field, $value);

            if ($error) {
                $this->errors[$field] = $error;
            }

        }
md($this->errors);
    }


    /**
     * @return bool True if a form has been submitted, false otherwise
     */
    public function is_processing() {
        return $this->processing;
    }


    /**
     * @return array Returns the processed data for use in generating a listing|wp_post
     */
    public function get_data() {
        return $this->data;
    }


    /**
     * @param string $key Identify what value is being requested
     *
     * @return string Empty if no value exists for the key provided, otherwise the value
     */
    public function get_value($field) {
        return isset($this->data[$field]) ? $this->data[$field] : '';
    }


    /**
     * @param string $field Identify the field
     *
     * @return bool True if an error exists, false otherwise
     */
    public function has_error($field) {
        return isset($this->errors[$field]);
    }


    /**
     * @return bool True if there are any errors set for any fields or globally, false otherwise
     */
    public function has_errors() {
        return (!empty($this->errors) || !empty($this->global_errors));
    }


    /**
     * @return bool True if there are global errors set, false otherwise
     */
    public function has_global_errors() {
        return !empty($this->global_errors);
    }


    /**
     * @param string $field Identify the field
     *
     * @return string The error message if found, empty string if none is set
     */
    public function get_error($field) {
        $default_wrapper = '<span class="bg-danger text-danger">%s</span>';
        $error_wrapper = apply_filters('lddlite_presentation_error_wrapper', $default_wrapper, $field );

        return isset($this->errors[$field]) ? sprintf($error_wrapper, $this->errors[$field]) : '';
    }


    /**
     * @param $errormsg The error message to be added to the global errors array
     */
    public function set_global_error($errormsg) {
        $this->global_errors[] = $errormsg;
    }


    /**
     * @return array Uses each() to return one element of the global errors array at a time
     */
    public function get_global_errors() {
        return each($this->global_errors);
    }

}


/**
 * This is all the internal validation we require, and is passed to the $lddlite_submit_processor object via
 * the `lddlite_validate_fields` filter. Uses a switch() to determine the field being validated.
 *
 * @param string $error Should always be empty to begin
 * @param string $field Identifies the field
 * @param string $value The value for a particular field
 *
 * @return string Empty or false if passed validation, an error message otherwise
 */
function ldl_validate_fields($error, $field, $value) {

    // Nothing to validate?
    if (empty($value) || (is_array($value) && in_array('', $value))) {
        return $error;
    }

    switch ($field) {
        case 'contact_email':
            if (!is_email($value)) {
                $error = __("The email address provided doesn't appear to be valid.", 'lddlite');
            }
            break;
        case 'url_website':
        case 'url_facebook':
        case 'url_linkedin':
            $value = esc_url($value);
            if ($value != filter_var($value, FILTER_VALIDATE_URL)) {
                $error = __('We were unable to verify that URL, please check it and try again.', 'lddlite');
            }
            break;
        case 'geo':
            if (2 != count($value) || !preg_match('/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $value['lat'] . ',' . $value['lng'])) {
                $error = __('Something went wrong validating that location, please try again.', 'lddlite');
            }
            break;
    }

    return $error;
}
add_filter('lddlite_validate_fields', 'ldl_validate_fields', 10, 3);


function ldl_require_tos($required) {

    if (ldl_get_setting('submit_use_tos'))
        $required[] = 'tos';

    return $required;
}
add_filter('lddlite_submit_required_fields', 'ldl_require_tos');


function ldl_require_tos_errmsg($errmsg, $field) {

    if ('tos' == $field)
        $errmsg = __('Please verify that you have read and agree to our terms of service before continuing.', 'lddlite');

    return $errmsg;
}
add_filter('lddlite_presentation_required_errmsg', 'ldl_require_tos_errmsg', 10, 2);