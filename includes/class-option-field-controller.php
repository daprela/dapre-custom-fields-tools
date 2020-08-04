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

	protected $previous_options;

	/**
	 * Constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		$this->namespace        = 'dapre-cft/v1';
		$this->rest_base        = 'options';
		$this->previous_options = $this->get_previous_options();
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// load the JS only in the right admin screen
		if ( 'toplevel_page_dapre_cft' === get_current_screen()->id ) {
			$version = get_asset_version( PLUGIN_DIR_PATH . 'assets/js/controller.min.js' );
			wp_enqueue_script(
				'dapre-fetch',
				PLUGIN_URL_PATH . 'assets/js/controller.min.js',
				[ 'wp-i18n', 'wp-element', 'wp-api-fetch' ],
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
					'args'                => [],
				],
				[
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'update_item_permissions_check' ],
					'args'                => [],
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'delete_item_permissions_check' ],
					'args'                => [],
				],
				'schema' => array( $this, 'get_public_item_schema' ),
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
		$fields_names   = $this->sanitize_array(json_decode($request->get_param( 'names' ) ?? '', true));

		$fields = [];

		foreach ( $fields_names as $index => $option ) {
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

	public function get_items_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
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
		$fields_names   = $this->sanitize_array(json_decode($request->get_body() ?? '', true));

		$fields = [];

		foreach ( $fields_names as $index => $option ) {
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

	public function update_item_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
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
		$fields_names   = $this->sanitize_array(json_decode($request->get_body() ?? '', true));

		$fields = [];

		foreach ( $fields_names as $index => $option ) {
			$this->previous_options[ $index ]->delete();

			$fields = $this->set_fields_content($fields, $index, $this->previous_options[ $index ]);
		}

		$this->set_previous_options( $this->previous_options );

		return rest_ensure_response($fields);
	}

	public function delete_item_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
	}

	/**
	 * @return array
	 */
	public function get_public_item_schema(): array {
		return [];
	}

	protected function sanitize_array($array) {
		return $array;
	}

	/**
	 * Returns the array containing the previous options.
	 *
	 * @since 3.0.0
	 *
	 * @return array Previous options.
	 *
	 * @return void
	 */
	public function get_previous_options() {

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
	public function set_previous_options( $previous_options ) {
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
}