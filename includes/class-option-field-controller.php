<?php
namespace dapre_cft\includes;

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

	/**
	 * Constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		$this->namespace = 'dapre-cft/v1';
		$this->rest_base = 'options';
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
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_item' ],
					'permission_callback' => [ $this, 'create_item_permissions_check' ],
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
		$field_name   = filter_var( $request->get_param( 'name' ), FILTER_SANITIZE_STRING ) ?? '';

		$fields = [];
		$option = new Options_Fields($field_name);

		$fields['current']  = $current_field = $option->get_current_value();
		$fields['previous'] = $previous_field = $option->get_previous_value();

//		$response = rest_ensure_response($fields);
//		$response->header( 'X-WP-Total', 1 );

		return  rest_ensure_response($fields);
	}

	public function get_items_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
	}

	public function create_item( $request ) {
		return 'you created it';
	}

	public function create_item_permissions_check( $request ) {
		// during the debug phase we always authorize
		return true;
	}

	public function update_item( $request ) {

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
}