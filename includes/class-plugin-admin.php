<?php

namespace dapre_cft\includes;

use function dapre_cft\get_asset_version;
use const dapre_cft\PLUGIN_DIR_PATH;
use const dapre_cft\PLUGIN_NAME;
use const dapre_cft\PLUGIN_URL_PATH;

defined( 'ABSPATH' ) or die;

/**
 * This is the core class of the plugin. It creates the tabbed pages and manages all the fields
 *
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 */
class Plugin_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @return void
	 * @since  1.0.0
	 *
	 */
	public function enqueue_styles() {

		// load the CSS only in the right admin screen
		if ( 'toplevel_page_dapre_cft' === get_current_screen()->id ) {
			wp_enqueue_style( 'lumensbox', PLUGIN_URL_PATH . 'libs/LumensBox/css/main.css', [], '0.1', 'all' );

			$version = get_asset_version( PLUGIN_DIR_PATH . 'assets/css/dapre-cft-admin.min.css' );
			wp_enqueue_style( PLUGIN_NAME, PLUGIN_URL_PATH . 'assets/css/dapre-cft-admin.min.css', [], $version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @return void
	 * @since  1.0.0
	 *
	 */
	public function enqueue_scripts() {

		// load the JS only in the right admin screen
		if ( 'toplevel_page_dapre_cft' === get_current_screen()->id ) {
			wp_enqueue_script( 'lumensbox', PLUGIN_URL_PATH . 'libs/LumensBox/js/app.min.js', [], '0.1', false );

			$version = get_asset_version( PLUGIN_DIR_PATH . 'assets/js/custom-fields-tools.min.js' );
			wp_enqueue_script(
				'dapre-fetch',
				PLUGIN_URL_PATH . 'assets/js/custom-fields-tools.min.js',
				[ 'wp-i18n', 'wp-element', 'wp-api-fetch', 'lumensbox' ],
				$version,
				true );
		}

	}

	/**
	 * Add the admin menu of the plugin
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function add_admin_menu() {
		//add an item to the menu
		add_menu_page(
			__( 'Dapre Custom Fields Tools', "dapre-cft" ),
			__( 'Custom Fields Tools', "dapre-cft" ),
			'manage_options',
			'dapre_cft',
			[ $this, 'manage_tabs' ],
			'dashicons-admin-generic'
		);
	}

	/**
	 * Manages the tabbed option page
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function manage_tabs() {

		$this->admin_tabs();

		$options_fields = new Option_Field_Controller();
		$user_fields    = new User_Field_Controller();
		$post_field     = new Post_Field_Controller();

		$previous_options     = $options_fields->get_previous_options();
		$previous_user_fields = $user_fields->get_previous_user_fields();
		$previous_post_fields = $post_field->get_previous_post_fields();

		if ( array_key_exists( 'reset_fields', $_POST ) ) {

			if ( ! check_admin_referer( 'dapre_cft_options', 'dapre_cft_options_verify' ) ) {
				die;
			}

			$previous_options     = initialize_previous_options();
			$previous_user_fields = initialize_previous_user_fields();
			$previous_post_fields = initialize_previous_post_fields();

			$form_data['user_id']     = [];
			$form_data['field_name']  = [];
			$form_data['field_value'] = [];
			$form_data['post_id']     = [];
		}

		/*
		 * options section
		 */

		// the user has clicked on the admin menu so we read the previous values
		foreach ( $previous_options as $option ) {

			$option->refresh( 'refresh' );

		}

		/*
		 * user fields section
		 */

		// the user has clicked on the admin menu so we read the previous values
		foreach ( $previous_user_fields as $user_field ) {

			$user_field->refresh( 'refresh' );

		}

		/*
		 * post fields section
		 */

		// the user has clicked on the admin menu so we read the previous values
		foreach ( $previous_post_fields as $post_field ) {

			$post_field->refresh( 'refresh' );

		}

		$field_error_class = 'is-hidden';
		$row_error_class   = '';

		require_once PLUGIN_DIR_PATH . 'templates/tabbed-admin.php';
	}

	/**
	 * Prints the HTML for rendering the tabs
	 *
	 * @param string $current
	 *
	 * @return void
	 * @since 1.0.0
	 *
	 */
	public function admin_tabs( $current = 'options' ) {

		$tabs = [
			'options'     => esc_html( "Options" ),
			'user_fields' => esc_html( "User Fields" ),
			'post_fields' => esc_html( "Post Fields" ),
		];
		echo '<h1 class="nav-tab-wrapper">';

		$tab_ind = 0;
		foreach ( $tabs as $tab => $name ) {
			$tab_ind += 1;
			$class   = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='js-tab_$tab_ind c-tab_$tab_ind nav-tab$class' data-tab='$tab_ind' href='?page=dapre_cft&tab=$tab'>$name</a>";

		}
		echo '</h1>';
	}
}