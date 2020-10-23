<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * This is the parent class that defines the common methods.
 *
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 */
abstract class Custom_Fields {

	/**
	 * @var string the name of the option meta.
	 */
	protected $field_name;

	/**
	 * @var mixed the previous value of this meta field.
	 */
	protected $previous_value;

	/**
	 * @var mixed the current value of this meta field.
	 */
	protected $current_value;

	/**
	 * @var string whether we must show or hide the date toggle next to the current value.
	 */
	protected $date_toggle;

	/**
	 * @var string If there's a problem with the current meta it contains the description of the problem.
	 */
	protected $error;

	/**
	 * @var bool Triggers when there's an error in writing the field.
	 */
	protected $write_error;

	/**
	 * Calls the start function to do a soft refresh.
	 * The main difference between a hard and a soft refresh is that in the hard refresh the previous value is always set to empty.
	 *
	 * @param string $from From where the initialisation request is coming
	 */
	public function refresh( $from = 'refresh' ) {
		$this->start( $from );
	}

	/**
	 * Initialises the properties of the class. Invoked directly from the constructor to perform a hard refresh when the object is instantiated.
	 *
	 * @param string $from From where the initialisation request is coming.
	 *
	 * @return void
	 * @since 4.0.0
	 *
	 */
	public function start( $from = 'start' ) {
		$this->set_previous_value( $from );
		$this->set_current_value();
		$this->set_date_toggle();
		$this->set_error();
	}

	/**
	 * Returns the content of the error string for this field.
	 *
	 * @return string Error property
	 * @since 4.0.0
	 *
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Returns the name of the field
	 *
	 * @return string The field name
	 * @since 4.0.0
	 *
	 */
	public function get_field_name() {
		return $this->get_name();
	}

	/**
	 * Returns the name of the field
	 *
	 * @return string The field name
	 * @since 4.0.0
	 *
	 */
	public function get_name() {
		return $this->field_name;
	}

	/**
	 * Returns the previous value of this field.
	 *
	 * @return string
	 * @since 4.0.0
	 *
	 */
	public function get_previous_value() {
		return $this->previous_value;
	}

	/**
	 * Get the status of the date toggle for this field value.
	 *
	 * @return string The date toggle.
	 * @since 4.0.0
	 *
	 */
	public function get_date_toggle() {
		return $this->date_toggle;
	}

	/**
	 * Determines if the date toggle in the form can be switched on.
	 *
	 * @return void
	 * @since 4.0.0
	 *
	 */
	protected function set_date_toggle() {

		if ( empty( $this->current_value ) ) {
			$this->date_toggle = 'is-hidden';
		}

		if ( ! is_array( $this->current_value ) && ( is_numeric( $this->current_value ) || false != strtotime( $this->current_value ) ) ) {
			$this->date_toggle = 'is-visible';
		} else {
			$this->date_toggle = 'is-hidden';
		}
	}

	/**
	 * The current valued of this field.
	 *
	 * @return mixed The current value of this field.
	 * @since 4.0.0
	 *
	 */
	public function get_current_value() {
		return $this->current_value;
	}

	/**
	 * Returns whether to show the error message for this field.
	 *
	 * @return string The CSS class of the error field.
	 * @since 4.0.0
	 *
	 */
	public function get_field_error_class() {
		if ( $this->get_error() ) {
			return 'is-visible';
		}

		return 'is-hidden';
	}

	/**
	 * Returns whether to show the row in the HTML template showing the error message for this field.
	 *
	 * @return string The CSS class of the error row.
	 * @since 4.0.0
	 *
	 */
	public function get_row_error_class() {
		if ( $this->get_error() ) {
			return 'is-error';
		}

		return '';
	}

	abstract protected function set_error( $error = '' );

	abstract protected function set_previous_value( $from = 'start' );

	abstract public function delete();

	abstract protected function set_current_value();

	abstract public function write( $field_value );

	abstract public function get_disable_write();

	abstract public function get_disable_delete();
}