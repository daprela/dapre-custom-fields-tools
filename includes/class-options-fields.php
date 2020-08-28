<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * This is the class the manages all the operations on the option fields
 *
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 */
class Options_Fields extends Custom_Fields {

	/**
	 * Initializes the class.
	 *
	 * @param string $option_name The option field name.
	 */
	function __construct( $option_name ) {
		$this->field_name = $option_name;
		$this->start();
	}

	/**
	 * Set the error property that tells if the field doesn't exist.
	 *
	 * @param string $error The error string.
	 *
	 * @return void
	 * @since 4.0.0
	 *
	 */
	protected function set_error( $error = '' ) {

		if ( $error ) {
			$this->error = $error;
		} else {
			if ( empty( $this->field_name ) || $this->option_exists() ) {
				$this->error = '';
			} else {
				$this->error = 'This field does not exist.';
			}
		}
	}

	/**
	 * Set the previous value of this option
	 *
	 * @param string $from From where this method was called
	 *
	 * @return void
	 * @since 4.0.0
	 *
	 */
	protected function set_previous_value( $from = 'start' ) {

		if ( empty( $this->field_name ) || ! $this->option_exists() || 'start' == $from ) {
			$this->previous_value = '';

			return;
		}

		// get the new value that is going to be written
		$new_value = get_option( $this->field_name );

		if ( 'refresh' == $from ) {
			if ( $this->current_value != $new_value ) {
				$this->previous_value = $this->current_value;
			}
		} else if ( 'write' == $from ) {
			$this->previous_value = $this->current_value;
		}
	}

	/**
	 * Delete the option.
	 *
	 * @return void
	 * @since 4.0.0
	 *
	 */
	public function delete() {
		delete_option( $this->field_name );
		$this->start();
	}

	/**
	 * Read the option value and set the corresponding property object.
	 *
	 * @return void
	 * @since 4.0.0
	 *
	 */
	protected function set_current_value() {
		if ( $this->option_exists() ) {
			$this->current_value = get_option( $this->field_name );
		} else {
			$this->current_value = '';
		}
	}

	/**
	 * Update the option in the database and updates the object properties.
	 *
	 * @param mixed $field_value The new value for the option
	 *
	 * @since 4.0.0
	 *
	 */
	public function write( $field_value ) {

		$this->write_error = false;

		$written = update_option( $this->field_name, $field_value );

		if ( ! $written ) {
			$this->set_error( 'There was an error. The meta field could not be written.' );
			$this->write_error = true;

			return;
		}

		$this->refresh( 'write' );
	}

	/**
	 * Determines what must be the state of the write action
	 *
	 * @return string The state of the write action
	 * @since 4.0.0
	 *
	 */
	public function get_disable_write() {
		if ( ! $this->field_name ) {
			return 'disabled';
		}

		return '';
	}

	/**
	 * Determines what must be the state of the delete action.
	 *
	 * @return string The state of the delete action
	 * @since 4.0.0
	 *
	 */
	public function get_disable_delete() {
		if ( ! $this->field_name || ! $this->option_exists() ) {
			return 'disabled';
		}

		return '';
	}

	/**
	 * Return true if an option exists, false if it doesn't.
	 * This function solves the problem arising from using the standard WP functions to check if an option exists.
	 * Source: https://wordpress.stackexchange.com/questions/8936/how-to-find-out-if-option-exists-but-is-empty/185416#185416
	 *
	 * @return bool         True if the option exists
	 * @global object $wpdb The database object
	 *
	 * @since 4.0.0
	 *
	 */
	public function option_exists() {
		global $wpdb;
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $this->field_name ) );
		if ( is_object( $row ) ) {
			return true;
		}

		return false;
	}
}