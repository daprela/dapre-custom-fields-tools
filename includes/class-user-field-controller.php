<?php

namespace dapre_cft\includes;

use WP_Error;
use WP_HTTP_Response;
use WP_REST_Controller, WP_REST_Server;
use WP_REST_Request;
use WP_REST_Response;
use function dapre_cft\get_asset_version;
use const dapre_cft\PLUGIN_DIR_PATH;
use const dapre_cft\PLUGIN_URL_PATH;

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
class User_Field_Controller extends WP_REST_Controller {

	/**
	 * @var string $namespace The basic namespace of our API
	 */
	protected $namespace;

	/**
	 * @var string $rest_base The root namespace for the options
	 */
	protected $rest_base;

	/**
	 * @var string $rest_rename The namespace for the rename feature
	 */
	protected $rest_rename;

	/**
	 * @var string $rest_copy The namespace for the copy feature
	 */
	protected $rest_copy;

	/**
	 * @var array $previous_user_fields The array containing the previous options
	 */
	protected $previous_user_fields;

	/**
	 * Constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		$this->namespace        = 'dapre-cft/v1';
		$this->rest_base        = 'user_fields';
		$this->rest_rename      = 'rename';
		$this->rest_copy        = 'copy';
		$this->previous_user_fields = $this->get_previous_user_fields();
	}

	/**
	 * Register the new Rest endpoints.
	 *
	 * @return void
	 * @since 5.0.0
	 *
	 */
	public function register_routes(): void {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			[
				[
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_item' ],
					'permission_callback' => [ $this, 'get_items_permissions_check' ],
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'update_item_permissions_check' ],
					'args'                => $this->get_write_params(),
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => $this->get_delete_params(),
				],
			]
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/rename',
			[
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'rename_item' ],
					'permission_callback' => [ $this, 'rename_item_permissions_check' ],
					'args'                => $this->get_rename_params(),
				],
			]
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/copy',
			[
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'copy_item' ],
					'permission_callback' => [ $this, 'copy_item_permissions_check' ],
					'args'                => $this->get_copy_params(),
				],
			]
		);
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
		$fields_names = json_decode( $request->get_param( 'user_fields' ), true );

		$fields = [];

		foreach ( $fields_names as $user_field ) {
			$index = $user_field['index'];

			if ( empty( $user_field['userID'] ) || empty( $user_field['fieldName'] ) ) {
				$user_field                           = new User_Fields( '', '' );
				$this->previous_user_fields[ $index ] = $user_field;
				$fields                           = $this->set_fields_content( $fields, $index, $this->previous_user_fields[ $index ] );
				continue;
			}

			// if the option name changes then we can't keep the previous object
			if ( $user_field['userID'] != $this->previous_user_fields[ $index ]->get_user_id() || $user_field['fieldName'] != $this->previous_user_fields[ $index ]->get_field_name() ) {
				$user_field_obj                           = new User_Fields( $user_field['userID'], $user_field['fieldName'] );
				$this->previous_user_fields[ $index ] = $user_field_obj;
			} else {
				$this->previous_user_fields[ $index ]->refresh( 'refresh' );
			}

			$fields = $this->set_fields_content( $fields, $index, $this->previous_user_fields[ $index ] );
		}

		$this->set_previous_user_fields( $this->previous_user_fields );

//		$response = rest_ensure_response($fields);
//		$response->header( 'X-WP-Total', 1 );

		return rest_ensure_response( $fields );
	}

	/**
	 * Returns whether the user has the permission to execute the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool True if the user can execute the request.
	 * @since 5.0.0
	 *
	 */
	public function get_items_permissions_check( $request ): bool {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return new WP_Error( 'rest_forbidden',
			esc_html__( 'You do not have permissions to perform this action.', 'dapre-cft' ),
			[ 'status' => 401 ] );
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

		foreach ( $fields_names as $user_field ) {
			$index = $user_field['index'];

			// does the user want to write an empty array or a normal text field?
			if ( $user_field['emptyArray'] ) {
				$field_value = [];
			} else {
				// sanitize text area without losing html tags
				$field_value = stripslashes( sanitize_textarea_field( htmlentities( $user_field['valueToWrite'] ) ) );
			}

			// if the option name changes then we can't keep the previous object
			if ( $user_field['userID'] != $this->previous_user_fields[ $index ]->get_user_id() || $user_field['fieldName'] != $this->previous_user_fields[ $index ]->get_field_name() ) {
				$user_field_obj = new User_Fields( $user_field['userID'], $user_field['fieldName'] );
				$this->previous_user_fields[ $index ] = $user_field_obj;
			} else {
				$this->previous_user_fields[ $index ]->write( $field_value );
			}

			$fields = $this->set_fields_content( $fields, $index, $this->previous_user_fields[ $index ] );
		}

		$this->set_previous_user_fields( $this->previous_user_fields );

		return rest_ensure_response( $fields );
	}

	/**
	 * Returns whether the user has the permission to execute the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool True if the user can execute the request.
	 * @since 5.0.0
	 *
	 */
	public function update_item_permissions_check( $request ): bool {
		return $this->get_items_permissions_check( $request );
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

		foreach ( $fields_names as $user_field ) {
			$index = $user_field['index'];

			$this->previous_user_fields[ $index ]->delete();

			$fields = $this->set_fields_content( $fields, $index, $this->previous_user_fields[ $index ] );
		}

		$this->set_previous_user_fields( $this->previous_user_fields );

		return rest_ensure_response( $fields );
	}

	/**
	 * Returns whether the user has the permission to execute the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool True if the user can execute the request.
	 * @since 5.0.0
	 *
	 */
	public function delete_item_permissions_check( $request ): bool {
		return $this->get_items_permissions_check( $request );
	}

	/**
	 * Returns the array containing the previous options.
	 *
	 * @return array Previous options.
	 * @since 3.0.0
	 *
	 */
	protected function get_previous_user_fields(): array {

		/**
		 * This array contains the previous user fields
		 *
		 *    $previous_user_fields = array (
		 *        'field_name'      => int,
		 *        'previous_value'  => string,
		 *        'field_value'     => string,
		 *        'field_error'     => string,
		 *
		 *    );
		 *
		 */
		return get_option( 'dapre_cft_previous_user_fields' );
	}

	/**
	 * Updates the option array containing the previous options.
	 *
	 * @param array $previous_user_fields The array containing the previous options.
	 *
	 * @return void
	 * @since 3.0.0
	 *
	 */
	protected function set_previous_user_fields( $previous_user_fields ): void {
		update_option( 'dapre_cft_previous_user_fields', $previous_user_fields );
	}

	/**
	 * Prepare an array containing the fields to be updated in the admin page.
	 *
	 * @param array          $fields The list of fields and their content.
	 * @param int            $index  The index order in the admin page.
	 * @param User_Fields $user_field The option object to transfer to the fields array.
	 *
	 * @return array The fields array updated
	 */
	private function set_fields_content( array $fields, int $index, User_Fields $user_field ): array {

		$fields[ $index ]['index']              = $index;
		$fields[ $index ]['currentValue']       = json_encode( print_r( $user_field->get_current_value(), true ) );
		$fields[ $index ]['previousValue']      = json_encode( print_r( $user_field->get_previous_value(), true ) );
		$fields[ $index ]['error']              = $user_field->get_error();
		$fields[ $index ]['fieldErrorClass']    = $user_field->get_field_error_class();
		$fields[ $index ]['curValueDateToggle'] = $user_field->get_date_toggle();
		$fields[ $index ]['disableWrite']       = $user_field->get_disable_write();
		$fields[ $index ]['disableDelete']      = $user_field->get_disable_delete();

		return $fields;
	}

	/**
	 * Retrieves the query params for the user fields collection.
	 *
	 * @return array Collection parameters.
	 * @since 5.0.0
	 *
	 */
	public function get_collection_params(): array {
		return [
			'user_fields' => [
				'description' => __( 'Array of user fields.' ),
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
						'userID' => [
							'description' => __( 'User ID that we want to read.' ),
							'type'        => 'integer',
							'default'     => '',
							'required'    => true,
							'sanitize_callback' => 'absint',
						],
						'fieldName' => [
							'description' => __( 'User field that we want to read.' ),
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
	 * Retrieves the query params for the user fields update.
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
			'userID' => [
				'description' => __( 'User ID that we want to write.' ),
				'type'        => 'integer',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => 'absint',
			],
			'fieldName' => [
				'description' => __( 'User field that we want to write.' ),
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
			'userID' => [
				'description' => __( 'User ID that we want to delete.' ),
				'type'        => 'integer',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => 'absint',
			],
			'fieldName' => [
				'description' => __( 'User field that we want to delete.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
		];
	}

	/**
	 * Rename a user field.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return mixed|WP_Error|WP_HTTP_Response|WP_REST_Response
	 * @since 1.0.0
	 *
	 */
	public function rename_item( $request ) {
		$fields_names = $request->get_json_params();

		/** @var object $old_user_field option field to rename */
		$old_user_field = new User_Fields( $fields_names['userID'], $fields_names['oldFieldName'] );

		/** @var object $new_user_field the new name of the option field */
		$new_user_field = new User_Fields( $fields_names['userID'], $fields_names['newFieldName'] );

		if ( $fields_names['oldFieldName'] == $fields_names['newFieldName'] ) {
			$response = [
				'renamed' => false,
				'message' => 'Old field and new field cannot have the same name.',
			];
		} else if ( ! $old_user_field->user_meta_exists() ) {
			$response = [
				'renamed' => false,
				'message' => 'The starting user field does not exist.',
			];
		} else if ( $new_user_field->user_meta_exists() ) {
			$response = [
				'renamed' => false,
				'message' => 'The destination user field already exists.',
			];

		} else {
			$error = false;

			if ( ! empty( $old_user_field->get_error() ) ) {
				$response = [
					'renamed' => false,
					'message' => $old_user_field->get_error(),
				];

			} else {
				$new_user_field->write( $old_user_field->get_current_value() );

				// if the new option exists and the old content was moved correctly then delete the old option
				if ( empty( $new_user_field->get_error() ) && ! $error ) {
					$old_user_field->delete();
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
	 * Returns whether the user has the permission to execute the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool True if the user can execute the request.
	 * @since 5.0.0
	 *
	 */
	public function rename_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
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
			'userID' => [
				'description' => __( 'User ID that we want to rename.' ),
				'type'        => 'integer',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => 'absint',
			],
			'oldFieldName' => [
				'description' => __( 'Old user field name.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
			],
			'newFieldName' => [
				'description' => __( 'New user field name.' ),
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

		$current_user_field = new User_Fields( $fields_names['currentUserID'], $fields_names['currentFieldName'] );

		if ( ! $current_user_field->user_exists() ) {
			// if the starting user doesn't exists then fail
			$response = [
				'copied'  => false,
				'message' => "The starting user doesn't exist.",
			];
		} else if ( empty($current_user_field->get_name()) || empty( $current_user_field->get_user_id() ) ) {
			$response = [
				'copied'  => false,
				'message' => "Please provide user ID and field name for the starting user field.",
			];
		} else if ( ! $current_user_field->user_meta_exists() ) {
			$response = [
				'copied'  => false,
				'message' => "The starting user field doesn't exist.",
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
							'message' => "Please provide a field name for the destination option.",
						];

						break;
					}

					// check if the new option exists or we are asked to create it
					if ( $new_option->option_exists() || $fields_names['checkboxCreate'] ) {

						$new_option->write( $current_user_field->get_current_value() );

						// if the new option exists and the old content was copied correctly then mark it copied
						if ( $new_option->option_exists() && $current_user_field->get_current_value() == $new_option->get_current_value() ) {
							$response = [
								'copied'  => true,
								'message' => 'User field copied',
							];
						} else {
							$response = [
								'copied'  => false,
								'message' => 'There was an error, the user field could not be copied.',
							];
						}
					} else {
						$response = [
							'copied'  => false,
							'message' => "The user field could not be copied because the destination option doesn't exist.",
						];
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

					if ( $fields_names['userID'] == $fields_names['currentUserID'] && $fields_names['userField'] == $fields_names['currentFieldName'] ) {
						$response = [
							'copied'  => false,
							'message' => "Starting user field and destination user field cannot be the same..",
						];
						break;
					}

					// Check if the user ID exists. We cannot copy to a non existent user
					if ( $new_user_field->user_exists() ) {

						if ( $new_user_field->user_meta_exists() || $fields_names['checkboxCreate'] ) {

							$new_user_field->write( $current_user_field->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $new_user_field->user_meta_exists() && $current_user_field->get_current_value() == $new_user_field->get_current_value() ) {
								$response = [
									'copied'  => true,
									'message' => "User field copied.",
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
								'message' => "The user field could not be copied because the destination field doesn't exist.",
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

					// Check if the post ID exists. We cannot copy to a non existent post
					if ( $new_post_field->post_exists() ) {

						if ( $new_post_field->post_meta_exists() || $fields_names['checkboxCreate'] ) {

							$new_post_field->write( $current_user_field->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $new_post_field->post_meta_exists() && $current_user_field->get_current_value() == $new_post_field->get_current_value() ) {
								$response = [
									'copied'  => true,
									'message' => "User field copied.",
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
								'message' => "The user field could not be copied because the destination field doesn't exist.",
							];
						}
					} else {
						$response = [
							'copied'  => false,
							'message' => "The destination post doesn't exist.",
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
	 * Returns whether the user has the permission to execute the request.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool True if the user can execute the request.
	 * @since 5.0.0
	 *
	 */
	public function copy_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
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
			'currentUserID' => [
				'description' => __( 'User ID that we want to rename.' ),
				'type'        => 'integer',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => 'absint',
			],
			'currentFieldName' => [
				'description' => __( 'Old user field name.' ),
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
			'checkboxCreate' => [
				'description' => __( "Whether we must create a new field if it doesn't exist." ),
				'type'        => 'boolean',
				'default'     => 'false',
				'required'    => true,
			],
			'newOption'      => [
				'description' => __( 'The destination option.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => false,
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
}
