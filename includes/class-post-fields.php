<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * This is the class the manages all the operations on the post fields
 *
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 *
 * @param      $post_id         Post ID
 * @param      $post_field_name The post field name
 */
class Post_Fields extends Custom_Fields {

	/**
	 * @var int the ID of the post
	 */
	protected $post_id;

	/**
	 * @var array list of the system fields from the wp_posts table
	 */
	protected $base_fields;

	/**
	 * @var array list of meta field that cannot be overwritten
	 */
	protected $read_only;

	/**
	 * @var boolean true if the last write operation generated an error
	 */
	protected $write_error;

	/**
	 * Initializes the class
	 *
	 * @param int    $post_id         Post ID
	 * @param string $post_field_name The post field name
	 */
	function __construct( $post_id, $post_field_name ) {
		$this->base_fields = [
			'ID',
			'id',
			'post_author',
			'post_date',
			'post_date_gmt',
			'post_content',
			'post_content_filtered',
			'post_title',
			'post_excerpt',
			'post_status',
			'post_type',
			'comment_status',
			'ping_status',
			'post_password',
			'post_name',
			'to_ping',
			'pinged',
			'post_modified',
			'post_modified_gtm',
			'post_parent',
			'menu_order',
			'post_mime_type',
			'guid',
			'post_category',
			'tags_input',
			'tax_input',
			'meta_input',
		];

		$this->read_only = [
			'ID',
			'id',
		];

		$this->post_id    = $post_id;
		$this->field_name = $post_field_name;
		$this->start();
	}

	/**
	 * Set the error property that tells if the post or the field doesn't exist.
	 *
	 * @since 4.0.0
	 *
	 * @param string $error The error string
	 *
	 * @return void
	 */
	protected function set_error( $error = '' ) {

		if ( $error ) {
			$this->error = $error;
		} else {

			// Checks if the post exists
			if ( ! empty( $this->post_id ) && ! $this->post_exists() ) {
				$this->error = "This post does not exist.";

				return;
			}

			// If the post exists then checks if the meta exists
			if ( ( empty( $this->field_name ) && empty( $this->post_id ) ) || $this->post_meta_exists() || $this->is_base_field() ) {
				$this->error = '';
			} else {
				$this->error = ( "This field does not exist." );
			}
		}
	}

	/**
	 * Returns the post ID the post meta.
	 *
	 * @since 4.0.0
	 *
	 * @return int The post ID
	 */
	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * Set the previous value of this post meta.
	 *
	 * @param    string $from From where this method was called.
	 *
	 * @return    void
	 */
	protected function set_previous_value( $from = 'start' ) {

		if ( empty( $this->field_name ) || empty( $this->post_id ) || ! $this->post_meta_exists() || 'start' == $from ) {
			$this->previous_value = '';

			return;
		}

		// get the new value that is going to be written
		$updated_post = new Post_Fields( $this->post_id, $this->field_name );

		if ( 'refresh' == $from ) {
			if ( $this->current_value != $updated_post->get_current_value() ) {
				$this->previous_value = $this->current_value;
			}
		} else if ( 'write' == $from ) {
			$this->previous_value = $this->current_value;
		}
	}

	/**
	 * Delete the post meta.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function delete() {

		if ( ! $this->can_delete() ) {
			$this->set_error( "You cannot delete system fields" );

			return;
		}

		delete_post_meta( $this->post_id, $this->field_name );
		$this->start();
	}

	/**
	 * Read the post meta value and set the corresponding property object.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	protected function set_current_value() {
		if ( $this->is_base_field() ) {
			$post_data           = get_post( $this->post_id, $this->field_name );
			$name                = $this->field_name;
			$this->current_value = $post_data->$name;

			return;
		}

		if ( $this->post_meta_exists() ) {

			$this->current_value = get_post_meta( $this->post_id, $this->field_name, true );

		} else {
			$this->current_value = '';
		}
	}

	/**
	 * Update the post meta in the database and updates the object properties.
	 *
	 * @since 4.0.0
	 *
	 * @param mixed $field_value The new value for the post meta.
	 *
	 * @return void
	 */
	public function write( $field_value ) {

		$this->write_error = false;

		if ( ! $this->post_exists() ) {
			$this->set_error( "Updating the meta field is not possible. The post does not exist." );
			$this->write_error = true;

			return;
		}

		// Are we trying to write on a read-only field?
		if ( ! $this->can_write() ) {
			$this->set_error( "This is a read-only field. You cannot overwrite this field." );
			$this->write_error = true;

			return;
		}

		// If we are writing on a system field we must use the post API
		if ( $this->is_base_field() && $this->can_write() ) {
			$postarr = [
				'ID'              => $this->post_id,
				$this->field_name => $field_value,
			];

			$error = wp_update_post( $postarr );

			if ( $error ) {
				$errors = $error->errors;

				foreach ( $errors as $thiserror ) {
					$this->set_error( $thiserror[0] );
				}

				$this->write_error = true;

				return;
			}

			$this->refresh( 'write' );

			return;
		}

		$written = update_post_meta( $this->post_id, $this->field_name, $field_value );

		if ( ! $written ) {
			$this->set_error( "There was an error. The meta field could not be written." );
			$this->write_error = true;

			return;
		}

		$this->refresh( 'write' );
	}

	/**
	 * Returns the status of the write error flag.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if the last write operation generated an error. False otherwise.
	 */
	public function get_write_error() {
		return $this->write_error;
	}

	/**
	 * Set the status of the write error flag.
	 *
	 * @since 4.0.0
	 *
	 * @param bool $param The status of the write error flag
	 *
	 * @return void
	 */
	public function set_write_error( bool $param ) {
		$this->write_error = $param;
	}

	/**
	 * Determines if this post meta can be modified manually.
	 *
	 * @since 4.0.0
	 *
	 * @return bool Whether we can write this post meta.
	 */
	public function can_write() {
		if ( $this->is_read_only() ) {
			return false;
		}

		return true;
	}

	/**
	 * Determines if this post meta is read only.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if this post meta cannot be overwritten.
	 */
	public function can_delete() {
		if ( in_array( $this->field_name, $this->base_fields ) ) {
			return false;
		}

		if ( ! $this->post_meta_exists() || ! $this->post_exists() ) {
			return false;
		}

		return true;
	}

	/**
	 * Determines if this post meta is a system field and cannot be deleted.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if this is a system field.
	 */
	private function is_base_field() {
		if ( in_array( $this->field_name, $this->base_fields ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines if this post meta is a read-only field.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if it is a read-only field.
	 */
	private function is_read_only() {
		if ( in_array( $this->field_name, $this->read_only ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines what must be the state of the write action.
	 *
	 * @since 4.0.0
	 *
	 * @return string The state of the write action
	 */
	public function get_disable_write() {
		if ( ! $this->field_name || ! $this->post_id ) {
			return 'disabled';
		}

		if ( ! $this->can_write() ) {
			return 'disabled';
		}

		return '';
	}

	/**
	 * Determines what must be the state of the delete action.
	 *
	 * @since 4.0.0
	 *
	 * @return string The state of the delete action.
	 */
	public function get_disable_delete() {
		if ( ! $this->field_name || ! $this->post_id || ! $this->post_meta_exists() ) {
			return 'disabled';
		}

		if ( ! $this->can_delete() ) {
			return 'disabled';
		}

		return '';
	}

	/**
	 * Checks if a post meta exists for a post.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if the user meta field exists.
	 */
	public function post_meta_exists() {

		// gets an array of all the meta fields of this post
		$all_post_meta = (array) get_post_meta( $this->post_id );

		// if the post meta key is in the array the field exists
		if ( array_key_exists( $this->field_name, $all_post_meta ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines if a post, identified by the specified ID, exist
	 * within the WordPress database.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if the post exists; otherwise, false.
	 */
	function post_exists() {
		return is_string( get_post_status( $this->post_id ) );
	}
}