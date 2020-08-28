<?php

namespace dapre_cft\includes;

use WP_Error;
use WP_REST_Controller, WP_REST_Server;
use WP_REST_Request;

defined( 'ABSPATH' ) or die;

/**
 * REST API abstract class controller for fields retrieval.
 *
 * @package dapre_cft\includes
 * @since   5.0.0
 * @author  Giulio Daprela <giulio.daprela@gmail.com>
 * @link    https://giuliodaprela.com
 * @license GPL 2.0+
 */
abstract class Field_Controller extends WP_REST_Controller {

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
	 * Constructor.
	 *
	 * @since 5.0.0
	 */
	public function __construct() {
		$this->namespace   = 'dapre-cft/v1';
		$this->rest_rename = 'rename';
		$this->rest_copy   = 'copy';
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
					'permission_callback' => [ $this, 'items_permissions_check' ],
					'args'                => $this->get_collection_params(),
				],
				[
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'update_item' ],
					'permission_callback' => [ $this, 'items_permissions_check' ],
					'args'                => $this->get_write_params(),
				],
				[
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'delete_item' ],
					'permission_callback' => [ $this, 'items_permissions_check' ],
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
					'permission_callback' => [ $this, 'items_permissions_check' ],
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
					'permission_callback' => [ $this, 'items_permissions_check' ],
					'args'                => $this->get_copy_params(),
				],
			]
		);
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
	public function items_permissions_check( $request ): bool {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return new WP_Error( 'rest_forbidden',
			esc_html__( 'You do not have permissions to perform this action.', 'dapre-cft' ),
			[ 'status' => 401 ] );
	}

	/**
	 * Prepare an array containing the fields to be updated in the admin page.
	 *
	 * @param array  $fields     The list of fields and their content.
	 * @param int    $index      The index order in the admin page.
	 * @param object $meta_field The object to transfer to the fields array.
	 *
	 * @return array The fields array updated
	 */
	public function set_fields_content( array $fields, int $index, object $meta_field ): array {

		$fields[ $index ]['index']              = $index;
		$fields[ $index ]['currentValue']       = json_encode( print_r( $meta_field->get_current_value(), true ) );
		$fields[ $index ]['previousValue']      = json_encode( print_r( $meta_field->get_previous_value(), true ) );
		$fields[ $index ]['error']              = $meta_field->get_error();
		$fields[ $index ]['fieldErrorClass']    = $meta_field->get_field_error_class();
		$fields[ $index ]['curValueDateToggle'] = $meta_field->get_date_toggle();
		$fields[ $index ]['disableWrite']       = $meta_field->get_disable_write();
		$fields[ $index ]['disableDelete']      = $meta_field->get_disable_delete();

		return $fields;
	}
}
