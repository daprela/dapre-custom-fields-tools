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
class Option_Field_Controller extends WP_REST_Controller {

	protected $namespace;

	protected $rest_base;

	protected $rest_rename;

	protected $rest_copy;

	protected $previous_options;

	/**
	 * Constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		$this->namespace        = 'dapre-cft/v1';
		$this->rest_base        = 'options';
		$this->rest_rename      = 'rename';
		$this->rest_copy        = 'copy';
		$this->previous_options = $this->get_previous_options();
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {

		// load the JS only in the right admin screen
		if ( 'toplevel_page_dapre_cft' === get_current_screen()->id ) {
			wp_enqueue_script( 'lumensbox', PLUGIN_URL_PATH . 'libs/LumensBox/js/app.min.js',[], '0.1', false );

			$version = get_asset_version( PLUGIN_DIR_PATH . 'assets/js/controller.min.js' );
			wp_enqueue_script(
				'dapre-fetch',
				PLUGIN_URL_PATH . 'assets/js/controller.min.js',
				[ 'wp-i18n', 'wp-element', 'wp-api-fetch', 'lumensbox' ],
				$version,
				true );
		}
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
					'args'                => [],
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
		$fields_names   = json_decode($request->get_param( 'names' ) ?? '', true);

		$fields = [];

		foreach ( $fields_names as $option ) {
			$index = $option['index'];

			if ( empty( $option['optionName'] ) ) {
				$option                           = new Options_Fields( '' );
				$this->previous_options[ $index ] = $option;
				$fields = $this->set_fields_content( $fields, $index, $this->previous_options[ $index ] );
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

//		$response = rest_ensure_response($fields);
//		$response->header( 'X-WP-Total', 1 );

		return rest_ensure_response($fields);
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
		if ( current_user_can('manage_options') ) {
			return true;
		}
		return false;
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
		$fields_names   = json_decode($request->get_body() ?? '', true);

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

			$fields = $this->set_fields_content($fields, $index, $this->previous_options[ $index ]);
		}

		$this->set_previous_options( $this->previous_options );

		return rest_ensure_response($fields);
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
		return $this->get_items_permissions_check($request);
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
		$fields_names   = json_decode($request->get_body() ?? '', true);

		$fields = [];

		foreach ( $fields_names as $option ) {
			$index = $option['index'];

			$this->previous_options[ $index ]->delete();

			$fields = $this->set_fields_content($fields, $index, $this->previous_options[ $index ]);
		}

		$this->set_previous_options( $this->previous_options );

		return rest_ensure_response($fields);
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
		return $this->get_items_permissions_check($request);
	}

	/**
	 * Returns the array containing the previous options.
	 *
	 * @since 3.0.0
	 *
	 * @return array Previous options.
	 */
	protected function get_previous_options(): array {

		/**
		 * This array contains the previous options
		 *
		 *    $previous_options = array (
		 *        'field_name'         => int,
		 *        'previous_value' => string,
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
	 * @since 3.0.0
	 *
	 * @param  array $previous_options The array containing the previous options.
	 *
	 * @return void
	 */
	protected function set_previous_options( $previous_options ): void {
		update_option( 'dapre_cft_previous_options', $previous_options );
	}

	/**
	 * Prepare an array containing the fields to be updated in the admin page.
	 *
	 * @param array          $fields The list of fields and their content.
	 * @param int            $index The index order in the admin page.
	 * @param Options_Fields $option The option object to transfer to the fields array.
	 *
	 * @return array The fields array updated
	 */
	private function set_fields_content( array $fields, int $index, Options_Fields $option ): array {

		$fields[ $index ]['index']              = $index;
		$fields[ $index ]['currentValue']       = json_encode( print_r( $option->get_current_value(), true ) );
		$fields[ $index ]['previousValue']      = json_encode( print_r( $option->get_previous_value(), true ) );
		$fields[ $index ]['error']              = $option->get_error();
		$fields[ $index ]['fieldErrorClass']    = $option->get_field_error_class();
		$fields[ $index ]['curValueDateToggle'] = $option->get_date_toggle();
		$fields[ $index ]['disableWrite']       = $option->get_disable_write();
		$fields[ $index ]['disableDelete']      = $option->get_disable_delete();

		return $fields;
	}

	/**
	 * Retrieves the query params for the options collection.
	 *
	 * @since 5.0.0
	 *
	 * @return array Collection parameters.
	 */
	public function get_collection_params(): array {
		return [
			'options' => [
				'description' => __( 'Array of option names.' ),
				'required'    => true,
				'type'        => 'array',
				'items'       => [
					'type'        => 'array',
					[
						'index'      => [
							'description'       => __( 'Order of this option in the options table.' ),
							'type'              => 'integer',
							'default'           => 0,
							'required'          => true,
							'sanitize_callback' => 'absint',
							'validate_callback' => [$this, 'check_integer'],
						],
						'optionName' => [
							'description' => __( 'Option name to read.' ),
							'type'        => 'string',
							'default'     => '',
							'required'    => true,
							'validate_callback' => [$this, 'check_string'],
						],
					],
				]
			],
		];
	}

	/**
	 * Retrieves the query params for the options update.
	 *
	 * @since 5.0.0
	 *
	 * @return array Write parameters.
	 */
	public function get_write_params(): array {
		return [
			'index'      => [
				'description'       => __( 'Order of this option in the options table.' ),
				'type'              => 'integer',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => 'absint',
				'validate_callback' => [$this, 'check_integer'],
			],
			'optionName' => [
				'description' => __( 'Option name to read.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
				'validate_callback' => [$this, 'check_string'],
			],
			'emptyArray' => [
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
				'validate_callback' => [$this, 'check_string'],
			],
		];
	}

	/**
	 * Retrieves the query params for the options delete.
	 *
	 * @since 5.0.0
	 *
	 * @return array Collection parameters.
	 */
	public function get_delete_params(): array {
		return [
			'index'      => [
				'description'       => __( 'Order of this option in the options table.' ),
				'type'              => 'integer',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => 'absint',
				'validate_callback' => [$this, 'check_integer'],
			],
			'optionName' => [
				'description' => __( 'Option name to read.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
				'validate_callback' => [$this, 'check_string'],
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
	public function rename_item($request) {
		$fields_names   = json_decode($request->get_body() ?? '', true);

		$old_option_name = sanitize_text_field( $fields_names['oldOptionName'] );
		$new_option_name = sanitize_text_field( $fields_names['newOptionName'] );

		/** @var object $old_option option field to rename */
		$old_option = new Options_Fields( $old_option_name );

		/** @var object $new_option the new name of the option field */
		$new_option = new Options_Fields( $new_option_name );

		if ( $old_option_name == $new_option_name ) {
			$response = [
				'renamed' => false,
				'error'   => 'Old option and new option cannot have the same name',
			];
		} else if ( ! $old_option->option_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The starting option does not exist',
			];
		} else if ( $new_option->option_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The destination option already exists',
			];

		} else {
			$error = false;

			if ( ! empty( $old_option->get_error() ) ) {
				$response = [
					'renamed' => false,
					'error'   => $old_option->get_error(),
				];

			} else {
				$new_option->write( $old_option->get_current_value() );

				// if the new option exists and the old content was moved correctly then delete the old option
				if ( empty( $new_option->get_error() ) && ! $error ) {
					$old_option->delete();
					$response = [
						'renamed' => true,
						'error'   => '',
					];
				} else {
					$response = [
						'renamed' => false,
						'error'   => '',
					];
				}
			}
		}

		return rest_ensure_response($response);
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
	public function rename_item_permissions_check($request) {
		return true;
	}

	protected function get_rename_params(): array {
		return [
			'oldOptionName' => [
				'description' => __( 'Old option name.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
				'validate_callback' => [$this, 'check_string'],
			],
			'newOptionName' => [
				'description' => __( 'New option name.' ),
				'type'        => 'string',
				'default'     => '',
				'required'    => true,
				'validate_callback' => [$this, 'check_string'],
			],
		];
	}

	/**
	 * Checks the validity of the value.
	 *
	 * @param integer         $param Value to check
	 * @param WP_REST_Request $request
	 * @param                 $key
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 */
	private function check_integer( $param, $request, $key ): bool {
		return is_int( $param );
	}

	/**
	 * Checks the validity of the value.
	 *
	 * @param string         $param Value to check
	 * @param WP_REST_Request $request
	 * @param                 $key
	 *
	 * @return bool
	 * @since 1.0.0
	 *
	 */
	private function check_string($param, $request, $key): bool {
		return is_string($param);
	}
}