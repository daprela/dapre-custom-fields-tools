<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * This is the class the manages all the operations on the user fields
 *
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 *
 * @param	   $user_id User ID
 * @param	   $user_field_name The user field name
 */
class User_Fields extends Custom_Fields {
	
	/**
	 * @var int the ID of the user
	 */
	protected $user_id;

	/**
	 * @var array list of the system fields from the wp_posts table
	 */
	protected $base_fields;
	
	/**
	 * @var array list of meta fields that cannot be overwritten
	 */
	protected $read_only;
	
	/**
	 * @var boolean true if the last write operation generated an error
	 */
	protected $write_error;
	
	/**
	 * Initialises the class
	 * 
	 * @param	int		 $user_id			User ID
	 * @param	string	 $user_field_name	The user field name
	 */
	function __construct($user_id, $user_field_name) {
		$this->base_fields = ['ID', 'id', 'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_status', 'display_name'];
		$this->read_only   = ['ID', 'id', 'user_login', 'user_pass'];
		
		$this->user_id = $user_id;
		$this->field_name = $user_field_name;
		$this->start();
	}
	
	/**
	 * Set the error property that tells if the user or the field doesn't exist.
	 *
	 * @since 4.0.0
	 * 
	 * @param string $error The error string
	 *
	 * @return void
	 */
	protected function set_error( $error = '' ) {
		
		// Checks if the error message is forced from the outside
		if ( $error ) {
			$this->error = $error;
		} else {
			
			// Checks if the user exists
			if ( !empty($this->user_id) && ! $this->user_exists() ) {
				$this->error = "This user does not exist.";
				return;
			}
			
			// If the user exists then checks if the meta exists
			if ( (empty($this->field_name) && empty($this->user_id)) || $this->user_meta_exists()  || $this->is_base_field() ) {
				$this->error = '';
			} else {
				$this->error = "This field does not exist.";
			}
		}
	}
	
	/**
	 * Returns the user ID the user meta.
	 *
	 * @since 4.0.0
	 * 
	 * @return int The user ID
	 */
	public function get_user_id() {
		return $this->user_id;
	}

	/**
	 * Returns the field name the user meta.
	 *
	 * @since 4.0.0
	 *
	 * @return string The field name
	 */
	public function get_field_name() {
		return $this->field_name;
	}
	
	/**
	 * Set the previous value of this user meta.
	 *
	 * @since 4.0.0
	 * 
	 * @param  string $from From where this method was called.
	 *
	 * @return void
	 */
	protected function set_previous_value($from = 'start') {
		
		if ( empty($this->field_name) || empty($this->user_id) || !$this->user_meta_exists() || 'start' == $from ) {
			$this->previous_value = '';
			return;
		}
		
		// get the new value that is going to be written
		$updated_user = new User_Fields($this->user_id, $this->field_name);
		
		if ( 'refresh' == $from ) {
			if ( $this->current_value != $updated_user->get_current_value() ) {
				$this->previous_value = $this->current_value;
			}
		} else if ( 'write' == $from ) {
			$this->previous_value = $this->current_value;
		}
	}

	/**
	 * Delete the user meta.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function delete() {
		
		if ( !$this->can_delete() ) {
			$this->set_error( "You cannot delete system fields" );
			$this->write_error = true;
			return;
		}
		
		delete_user_meta($this->user_id, $this->field_name);
		$this->start();
	}
	
	/**
	 * Read the user meta value and set the corresponding property object
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	protected function set_current_value() {
		if ( $this->user_meta_exists()) {
			if ( $this->is_base_field() ) {
				$user_info = get_userdata($this->user_id);
				$name = $this->field_name;
				$this->current_value = $user_info->data->$name;
			} else {
				$this->current_value = get_user_meta($this->user_id, $this->field_name, true);
			}
		} else {
			$this->current_value = '';
		}
	}
	
	/**
	 * Update the user meta in the database and updates the object properties
	 *
	 * @since 4.0.0
	 * 
	 * @param   mixed   $field_value   The new value for the user meta
	 *
	 * @return void
	 */
	public function write($field_value) {
		
		$this->write_error = false;
		
		if ( ! $this->user_exists() ) {
			$this->set_error( "Updating the meta field is not possible. The user does not exist." );
			$this->write_error = true;
			return;
		}
		
		// Are we trying to write on a read-only field?
		if ( ! $this->can_write() ) {
			$this->set_error( "This is a read-only field. You cannot overwrite this field." );
			$this->write_error = true;
			return;
		}
		
		// If we are writing on a system field we must use the user API
		if ( $this->is_base_field() ) {
			$userarr = [
				'ID' => $this->user_id,
				$this->field_name => $field_value
			];
			
			$error = wp_update_user($userarr);
			
			if ( $error ) {
				$errors = $error->errors;
				
				foreach ( $errors as $thiserror ) {
					$this->set_error($thiserror[0]);
				}
				
				$this->write_error = true;
				
				return;
			}
			
			$this->refresh('write');
			
			return;
		}
		
		$written = update_user_meta($this->user_id, $this->field_name, $field_value);
		
		if ( ! $written ) {
			$this->set_error( "There was an error. The meta field could not be written." );
			$this->write_error = true;
			return;
		}
		
		$this->refresh('write');
	}
	
	/**
	 * Returns the status of the write error flag
	 *
	 * @since 4.0.0
	 * 
	 * @return   bool   True if the last write operation generated an error. False otherwise
	 */
	public function get_write_error() {
		return $this->write_error;
	}
	
	/**
	 * Set the status of the write error flag
	 *
	 * @since 4.0.0
	 * 
	 * @param   bool   $param   The status of the write error flag
	 *
	 * @return void
	 */
	public function set_write_error( bool $param) {
		$this->write_error = $param;
	}
	
	/**
	 * Determines if this user meta can be deleted.
	 *
	 * @since 4.0.0
	 * 
	 * @return   boolean   Whether we can delete this user meta.
	 */
	public function can_delete() {
		if ( in_array($this->field_name, $this->base_fields) ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Determines if this user meta is a system field and cannot be deleted.
	 *
	 * @since 4.0.0
	 * 
	 * @return   boolean   True if it is a system field
	 */
	public function can_write() {
		if ( $this->is_read_only() ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Determines if this user meta is a system base field.
	 *
	 * @since 4.0.0
	 * 
	 * @return   boolean   True if it is a system field.
	 */
	private function is_base_field() {
		if ( in_array($this->field_name, $this->base_fields) ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Determines if this user meta is a read-only field.
	 *
	 * @since 4.0.0
	 * 
	 * @return   boolean   True if it is a read-only field.
	 */
	private function is_read_only() {
		if ( in_array($this->field_name, $this->read_only) ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Determines what must be the state of the write action.
	 *
	 * @since 4.0.0
	 * 
	 * @return   string   The state of the write action.
	 */
	public function get_disable_write() {
		if ( ! $this->field_name ||  ! $this->user_id ) {
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
	 * @return   string   The state of the delete action.
	 */
	public function get_disable_delete() {
		if ( ! $this->field_name ||  ! $this->user_id || ! $this->user_meta_exists() ) {
			return 'disabled';
		}
		
		if ( ! $this->can_delete() ) {
			return 'disabled';
		}
		
		return '';
	}
	
	/**
	 * Checks if a user meta exists for a user.
	 *
	 * @since 4.0.0
	 *
	 * @return bool True if the user meta field exists
	 */
	public function user_meta_exists() {
		
		// gets an array of all the meta fields of this user
		$all_user_meta = (array) get_user_meta($this->user_id);
		
		// if the user meta key is in the array the field exists
		if ( array_key_exists($this->field_name, $all_user_meta) || in_array($this->field_name, $this->base_fields) ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check if the user exists.
	 *
	 * @since 4.0.0
	 * 
	 * @return   mixed   False if the user doesn't exists, the user object if the user exists.
	 */
	public function user_exists() {
		$user = get_user_by('id', $this->user_id);
		
		return $user;
	}
}