<?php
namespace dapre_cft\includes;

use Symfony\Component\Validator\Constraints\Json;
use WP_REST_Controller, WP_REST_Server;
use function dapre_cft\get_asset_version;
use const dapre_cft\PLUGIN_DIR_PATH;
use const dapre_cft\PLUGIN_NAME;
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

	public function register_routes() {
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

	public function get_item( $request ) {
//		$field_name   = filter_var( $request->get_param( 'names' ), FILTER_SANITIZE_ENCODED ) ?? '';
		$fields_names   = $this->sanitize_array(json_decode($request->get_param( 'names' ) ?? '', true));

		$fields = [];

		foreach ( $this->previous_options as $key => $option ) {
			if ( empty( $fields_names[$key]['optionName'] ) ) {
				$option                   = new Options_Fields( '' );
				$this->previous_options[ $key ] = $option;
				continue;
			}

			// if the option name changes then we can't keep the previous object
			if ( $fields_names[$key]['optionName'] != $option->get_name() ) {
				$option                         = new Options_Fields( $fields_names[ $key ]['optionName'] );
				$this->previous_options[ $key ] = $option;
			} else {
				$option->refresh( 'refresh' );
			}

			$fields[ $key ]['index']              = $key;
			$fields[ $key ]['currentValue']       = json_encode( print_r( $option->get_current_value(), true ) );
			$fields[ $key ]['previousValue']      = json_encode( print_r( $option->get_previous_value(), true ) );
			$fields[ $key ]['error']              = $option->get_error();
			$fields[ $key ]['fieldErrorClass']    = $option->get_field_error_class();
			$fields[ $key ]['curValueDateToggle'] = $option->get_date_toggle();
		}

		$this->set_previous_options( $this->previous_options );

		$response = rest_ensure_response($fields);
		$response->header( 'X-WP-Total', 1 );

		return $response;
	}

	public function get_items_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
	}

	public function update_item( $request ) {
		$fields_names   = $this->sanitize_array(json_decode($request->get_param( 'names' ) ?? '', true));

		$fields = [];
	}

	public function update_item_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
	}

	public function delete_item( $request ) {

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
	 * @param  array $previous_options the array containing the previous options.
	 *
	 * @return void
	 */
	public function set_previous_options( $previous_options ) {
		update_option( 'dapre_cft_previous_options', $previous_options );
	}
}