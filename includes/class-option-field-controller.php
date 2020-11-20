<?php

namespace dapre_cft\includes;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Request;
use WP_REST_Response;

defined( 'ABSPATH' ) or die;

/**
 * API controller for option fields retrieval.
 *
 * @package dapre_cft\includes
 * @since   5.0.0
 * @author  Giulio Daprela <giulio.daprela@gmail.com>
 * @link    https://giuliodaprela.com
 * @license GPL 2.0+
 */
class Option_Field_Controller extends Field_Controller {


	/**
	 * @var array $previous_user_fields The array containing the previous options
	 */
	protected $previous_options;

	/**
	 * Constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		parent::__construct();
		$this->rest_base        = 'options';
		$this->previous_options = $this->get_previous_options();
	}

	/**
	 * Execute the request to read the meta.
	 *
	 * @param WP_REST_Request $request The list of meta to read.
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 5.0.0
	 *
	 */
	public function get_item( $request ) {
		// Sanitizing is not possible because we don't know in advance what type of values we are getting.
		// This is still acceptable considering that this is a tool for developers.
		if ( null != $request->get_param( 'options' ) ) {
			$fields_names = json_decode( $request->get_param( 'options' ), true );

			$fields = [];

			foreach ( $fields_names as $option ) {
				$index = $option['index'];

				if ( empty( $option['optionName'] ) ) {
					$option                           = new Options_Fields( '' );
					$this->previous_options[ $index ] = $option;
					$fields                           = $this->set_fields_content( $fields, $index, $this->previous_options[ $index ] );
					continue;
				}

				// if the option name changes then we can't keep the previous object
				if ( $option['optionName'] != $this->previous_options[ $index ]->get_name() ) {
					$option                           = new Options_Fields( $option['optionName'] );
					$this->previous_options[ $index ] = $option;
				} else {
					$this->previous_options[ $index ]->refresh( 'refresh' );
				}

				$fields = $this->set_fields_content( $fields, $index, $this->previous_options[ $index ] );
			}
			$this->set_previous_options( $this->previous_options );
		} else if ( null != $request->get_param( 'all_options' ) ) {
			$fields = $this->get_all_fields( $this->previous_options );
		} else {
			$fields = [];
		}

		return rest_ensure_response( $fields );
	}

	/**
	 * Execute the request to write the meta.
	 *
	 * @param WP_REST_Request $request The list of meta to write.
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 5.0.0
	 *
	 */
	public function update_item( $request ) {
		// Sanitizing is not possible because we don't know in advance what type of values we are getting.
		// This is still acceptable considering that this is a tool for developers.
		$fields_names = $request->get_json_params();

		$fields = [];

		foreach ( $fields_names as $option ) {
			$index = $option['index'];

			// does the user want to write an empty array or a normal text field?
			if ( $option['emptyArray'] ) {
				$field_value = [];
			} else {
				// sanitize text area without losing html tags
				$field_value = stripslashes( sanitize_textarea_field( htmlentities( $option['valueToWrite'] ) ) );
			}

			// il the field name has changed delete the previous values as they are not relevant anymore
			if ( $option['optionName'] != $this->previous_options[ $index ]->get_name() ) {
				$this->previous_options[ $index ] = new Options_Fields( $index );
			} else {
				$this->previous_options[ $index ]->write( $field_value );
			}

			$fields = $this->set_fields_content( $fields, $index, $this->previous_options[ $index ] );
		}

		$this->set_previous_options( $this->previous_options );

		return rest_ensure_response( $fields );
	}

	/**
	 * Execute the request to delete the meta.
	 *
	 * @param WP_REST_Request $request The list of meta to delete.
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 5.0.0
	 *
	 */
	public function delete_item( $request ) {
		// Sanitizing is not possible because we don't know in advance what type of values we are getting.
		// This is still acceptable considering that this is a tool for developers.
		$fields_names = $request->get_json_params();

		$fields = [];

		foreach ( $fields_names as $option ) {
			$index = $option['index'];

			$this->previous_options[ $index ]->delete();

			$fields = $this->set_fields_content( $fields, $index, $this->previous_options[ $index ] );
		}

		$this->set_previous_options( $this->previous_options );

		return rest_ensure_response( $fields );
	}

	/**
	 * Returns the array containing the previous options.
	 *
	 * @return array Previous options.
	 * @since 3.0.0
	 *
	 */
	public function get_previous_options(): array {

		/**
		 * This array contains the previous options
		 *
		 *    $previous_options = array (
		 *        'field_name'      => int,
		 *        'previous_value'  => string,
		 *        'field_value'     => string,
		 *        'field_error'     => string,
		 *
		 *    );
		 *
		 */
		return get_option( 'dapre_cft_previous_options' );
	}

	/**
	 * Updates the option array containing the previous options.
	 *
	 * @param array $previous_options The array containing the previous options.
	 *
	 * @return void
	 * @since 3.0.0
	 *
	 */
	protected function set_previous_options( array $previous_options ): void {
		update_option( 'dapre_cft_previous_options', $previous_options );
	}

	/**
	 * Retrieves the query params for the options collection.
	 *
	 * @return array Collection parameters.
	 * @since 5.0.0
	 *
	 */
	public function get_collection_params(): array {
		return [
			'options' => [
				'description' => __( 'Array of option names.' ),
				'required'    => true,
				'type'        => 'json',
				'items'       => [
					'type' => 'array',
					[
						'index'      => [
							'description'       => __( 'Order of this option in the plugin options table in admin.' ),
							'type'              => 'integer',
							'default'           => 0,
							'required'          => true,
							'sanitize_callback' => 'absint',
						],
						'optionName' => [
							'description' => __( 'Name of the option that we want to read.' ),
							'type'        => 'string',
							'default'     => '',
							'required'    => true,
						],
					],
				],
			],
		];
	}

	/**
	 * Retrieves the query params for the options update.
	 *
	 * @return array Write parameters.
	 * @since 5.0.0
	 *
	 */
	public function get_write_params(): array {
		return [
			'index'        => [
				'description'       => __( 'Order of this option in the options table.' ),
				'type'              => 'integer',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => 'absint',
			],
			'optionName'   => [
				'description' => __( 'Option name to read.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
			'emptyArray'   => [
				'description' => __( 'Whether the value to write must be an empty array.' ),
				'type'        => 'boolean',
				'default'     => false,
				'required'    => true,
			],
			'valueToWrite' => [
				'description' => __( 'The value to write.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
		];
	}

	/**
	 * Retrieves the query params for the options delete.
	 *
	 * @return array Collection parameters.
	 * @since 5.0.0
	 *
	 */
	public function get_delete_params(): array {
		return [
			'index'      => [
				'description'       => __( 'Order of this option in the options table.' ),
				'type'              => 'integer',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => 'absint',
			],
			'optionName' => [
				'description' => __( 'Option name to delete.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
		];
	}

	/**
	 * Rename an option.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 1.0.0
	 *
	 */
	public function rename_item( $request ) {
		$fields_names = $request->get_json_params();

		$old_option_name = sanitize_text_field( $fields_names['oldOptionName'] );
		$new_option_name = sanitize_text_field( $fields_names['newOptionName'] );

		/** @var object $old_option option field to rename */
		$old_option = new Options_Fields( $old_option_name );

		/** @var object $new_option the new name of the option field */
		$new_option = new Options_Fields( $new_option_name );

		if ( $old_option_name == $new_option_name ) {
			$response = [
				'renamed' => false,
				'message' => 'Old option and new option cannot have the same name.',
			];
		} else if ( ! $old_option->option_exists() ) {
			$response = [
				'renamed' => false,
				'message' => 'The starting option does not exist.',
			];
		} else if ( $new_option->option_exists() ) {
			$response = [
				'renamed' => false,
				'message' => 'The destination option already exists.',
			];

		} else {
			$error = false;

			if ( ! empty( $old_option->get_error() ) ) {
				$response = [
					'renamed' => false,
					'message' => $old_option->get_error(),
				];

			} else {
				$new_option->write( $old_option->get_current_value() );

				// if the new option exists and the old content was moved correctly then delete the old option
				if ( empty( $new_option->get_error() ) && ! $error ) {
					$old_option->delete();
					$response = [
						'renamed' => true,
						'message' => 'The option has been renamed.',
					];
				} else {
					$response = [
						'renamed' => false,
						'message' => 'Something went wrong. Please check that the old option name has been removed.',
					];
				}
			}
		}

		return rest_ensure_response( $response );
	}

	/**
	 * Retrieves the query params for the options rename.
	 *
	 * @return array Collection parameters.
	 * @since 5.0.0
	 *
	 */
	protected function get_rename_params(): array {
		return [
			'oldOptionName' => [
				'description' => __( 'Old option name.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
			'newOptionName' => [
				'description' => __( 'New option name.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
		];
	}

	/**
	 * Copy an option.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 1.0.0
	 *
	 */
	public function copy_item( $request ) {
		$fields_names = $request->get_json_params();

		$response = [];

		$current_option = new Options_Fields( $fields_names['currentOption'] );

		// Checks if the current option entered is valid
		if ( '' == $current_option->get_name() ) {
			$response = [
				'copied'  => false,
				'message' => "Please provide the field name for the starting option.",
			];
		} else if ( ! $current_option->option_exists() ) {
			$response = [
				'copied'  => false,
				'message' => "The starting option doesn't exist.",
			];
		} else {
			// If the starting option is valid, checks where we must copy it
			switch ( $fields_names['copySelection'] ) {
				case 'option':

					// Instantiates new option object
					$new_option = new Options_Fields( $fields_names['newOption'] );

					if ( empty( $new_option->get_name() ) ) {
						$response = [
							'copied'  => false,
							'message' => "Please provide a field name for the destination field.",
						];

						break;
					}

					if ( $current_option->get_name() == $new_option->get_name() ) {
						$response = [
							'copied'  => false,
							'message' => "Starting option and destination option cannot be the same.",
						];
					} else {

						// check if the new option exists or we are asked to create it
						if ( $new_option->option_exists() || $fields_names['checkboxCreate'] ) {

							$new_option->write( $current_option->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $new_option->option_exists() && $current_option->get_current_value() == $new_option->get_current_value() ) {
								$response = [
									'copied'  => true,
									'message' => 'Option copied',
								];
							}
						} else {
							$response = [
								'copied'  => false,
								'message' => "The option could not be copied because the destination option doesn't exist.",
							];
						}
					}
					break;

				case 'user field':

					// Instantiates new user field object
					$new_user_field = new User_Fields( $fields_names['userID'], $fields_names['userField'] );

					if ( empty( $new_user_field->get_name() ) || empty( $new_user_field->get_user_id() ) ) {
						$response = [
							'copied'  => false,
							'message' => "Please provide both user ID and field name for the destination field.",
						];

						break;
					}

					// Check if the post ID exists. We cannot copy to a non existent post.
					if ( $new_user_field->user_exists() ) {

						// check if the new user field exists or we are asked to create it
						if ( $new_user_field->user_meta_exists() || $fields_names['checkboxCreate'] ) {

							$new_user_field->write( $current_option->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $new_user_field->user_meta_exists() && $current_option->get_current_value() == $new_user_field->get_current_value() ) {
								$response = [
									'copied'  => true,
									'message' => "Option copied.",
								];
							} else {
								$response = [
									'copied'  => false,
									'message' => "There was an error, the user field could not be copied.",
								];
							}
						} else {
							$response = [
								'copied'  => false,
								'message' => "The option could not be copied because the destination field doesn't exist.",
							];
						}
					} else {
						$response = [
							'copied'  => false,
							'message' => "The destination user doesn't exist.",
						];
					}
					break;

				case 'post field':

					// Instantiates new post field object
					$new_post_field = new Post_Fields( $fields_names['postID'], $fields_names['postField'] );

					if ( empty( $new_post_field->get_name() ) || empty( $new_post_field->get_post_id() ) ) {
						$response = [
							'copied'  => false,
							'message' => "Please provide both post ID and field name for the destination field.",
						];

						break;
					}

					// Check if the post ID exists. We cannot copy to a non existent post.
					if ( $new_post_field->post_exists() ) {

						// check if the new option exists or we are asked to create it
						if ( $new_post_field->post_meta_exists() || $fields_names['checkboxCreate'] ) {

							$new_post_field->write( $current_option->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $new_post_field->post_meta_exists() && $current_option->get_current_value() == $new_post_field->get_current_value() ) {
								$response = [
									'copied'  => true,
									'message' => "Option copied.",
								];
							} else {
								$response = [
									'copied'  => false,
									'message' => "There was an error, the user field could not be copied.",
								];
							}
						} else {
							$response = [
								'copied'  => false,
								'message' => "There was an error, the option could not be copied.",
							];
						}
					} else {
						$response = [
							'copied'  => false,
							'message' => "The destination post does not exist.",
						];
					}
					break;
				default:
					break;
			}
		}

		return rest_ensure_response( $response );
	}

	/**
	 * Retrieves the query params for the options rename.
	 *
	 * @return array Collection parameters.
	 * @since 5.0.0
	 *
	 */
	protected function get_copy_params(): array {
		return [
			'currentOption'  => [
				'description' => __( 'Old option name.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
			'copySelection'  => [
				'description' => __( 'Where to copy the option.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
			'newOption'      => [
				'description' => __( 'The destination option.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => false,
			],
			'checkboxCreate' => [
				'description' => __( "Whether we must create a new field if it doesn't exist." ),
				'type'        => 'boolean',
				'default'     => 'false',
				'required'    => true,
			],
			'userID'         => [
				'description' => __( "The user ID." ),
				'type'        => 'integer',
				'default'     => '1',
				'required'    => false,
			],
			'userField'      => [
				'description' => __( 'The destination field.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => false,
			],
			'postID'         => [
				'description' => __( "The post ID." ),
				'type'        => 'integer',
				'default'     => '1',
				'required'    => false,
			],
			'postField'      => [
				'description' => __( 'The destination field.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => false,
			],
		];
	}

	/**
	 * Delete one row from the meta rows that are shown in the admin page.
	 *
	 * @param WP_REST_Request $request The list of meta rows.
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 5.1.0
	 *
	 */
	public function delete_row( $request ) {
		$rows = $request->get_json_params();

		$new_previous_options = [];
		foreach ( $rows as $row ) {
			$new_previous_options[$row['index']] = $this->previous_options[$row['index']];
		}

		$this->previous_options = $new_previous_options;

		$this->set_previous_options($this->previous_options);

		return rest_ensure_response( $rows );
	}

	/**
	 * Append one row to the meta rows that are shown in the admin page.
	 *
	 * @param WP_REST_Request $request The list of meta rows.
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 5.1.0
	 *
	 */
	public function append_row( $request ) {
		$new_row_index = $request->get_json_params();

		$new_option = new Options_Fields('');

		$this->previous_options[$new_row_index] = $new_option;

		$this->set_previous_options($this->previous_options);

		return rest_ensure_response( $new_row_index );
	}
}
