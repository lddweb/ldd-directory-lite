<?php
/**
 * Submit a listing view controller and other functionality
 *
 * @package   ldd_directory_lite
 * @author    LDD Web Design <info@lddwebdesign.com>
 * @license   GPL-2.0+
 * @link      http://lddwebdesign.com
 * @copyright 2014 LDD Consulting, Inc
 */


class ldd_directory_lite_processor {

	const NONCE_FIELD  = 'nonce_field';
	const NONCE_ACTION = 'submit-listing-nonce';

	const DATA_PREFIX = 'ld_s_';

	protected $processing = false;
	protected $extra_fields;
	protected $data;
	protected $errors;
	protected $global_errors;


	public function __construct() {

		if ( !array_key_exists( self::NONCE_FIELD, $_POST ) )
			return;

		$this->processing = true;

		$this->_verify_nonce();
		$this->_process_data();
		$this->_validate();

	}


	private function _verify_nonce() {
		if ( !wp_verify_nonce( $_POST[ self::NONCE_FIELD ], self::NONCE_ACTION ) )
			die( "No, kitty! That's a bad kitty!" );
	}


	private function _process_data() {

		$pfx_length = strlen( self::DATA_PREFIX );

		foreach ( $_POST as $key => $value ) {

			if ( false === strpos( $key, self::DATA_PREFIX ) ) {
				$this->extra_fields[ $key ] = $value;
				continue;
			}

			$data_key = substr( $key, $pfx_length );

			if ( is_array( $value ) ) {
				$this->data[ $data_key ] = stripslashes_deep( $value );
			} else {
				$this->data[ $data_key ] = stripslashes( trim( $value ) );
			}

		}

	}


	private function _validate() {

		$required = ldl_get_required_fields();

		foreach ( $required as $key ) {
			if ( '' == $this->data[ $key ] ) {
				$this->errors[ $key ] = __( 'This field is required.', 'lddlite' );
			}
		}

		foreach( $this->data as $key => $value ) {
			// Use this if you want to add a single function to the validation process
			$error = apply_filters( 'lddlite_validate_fields', '', $key, $value );
			// Or this if you want to alter the validation of a single field
			$error = apply_filters( 'lddlite_validate_field_' . $key, $error, $value );

			if ( !empty( $error ) ) {
				$this->errors[ $key ] = $error;
			}

		}

	}


	public function is_processing() {
		return $this->processing;
	}


	public function get_data() {
		return $this->data;
	}


	public function get_value( $key ) {
		return isset( $this->data[ $key ] ) ? $this->data[ $key ] : '';
	}


	public function has_error( $key ) {
		return isset( $this->errors[ $key ] );
	}


	public function has_errors() {
		return ( !empty( $this->errors ) || !empty( $this->global_errors ) );
	}


	public function has_global_errors() {
		return ( !empty( $this->global_errors ) );
	}


	public function get_error( $key ) {
		if ( isset( $this->errors[ $key ] ) ) {
			echo '<span class="text-danger">' . $this->errors[ $key ] . '</span>';
		}
	}


	public function set_global_error( $errormsg ) {
		$this->global_errors[] = $errormsg;
	}


	public function get_global_errors() {
		return each( $this->global_errors );
	}
}


function ldl_validate_fields( $error, $field, $value ) {

	if ( empty( $value ) )
		return $error;

	switch ( $field ) {
		case 'contact_email':
			if ( !is_email( $value ) ) {
				$error = __( "That email address doesn't appear to be valid.", 'lddlite' );
			}
			break;
		case 'url_website':
		case 'url_facebook':
		case 'url_linkedin':
			if ( 0 !== strpos( $value, 'http' ) ) {
				$value = esc_url( $value );
			}
			if ( false != filter_var( $value, FILTER_VALIDATE_URL ) ) {
				$error = __( 'We were unable to verify that URL, please check it and try again.', 'lddlite' );
			}
			break;
		case 'geo':
			if ( !is_array( $value )
				|| 3 != count( $value )
				|| in_array( '', $value )
				|| !preg_match( '/^(\-?\d+(\.\d+)?),\s*(\-?\d+(\.\d+)?)$/', $value['lat'] . ',' . $value['lng'] ) ) {
				$error = __( 'Something went wrong validating that location, please try again.', 'lddlite' );
			}
			break;
	}

	return $error;
}
add_filter( 'lddlite_validate_fields', 'ldl_validate_fields', 10, 3 );