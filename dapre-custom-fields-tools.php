<?php

/**
 * 
 * @since             1.0.0
 * @package           Dapre Custom Fields Tools
 *
 * @wordpress-plugin
 * Plugin Name:       Dapre Custom Fields Tools
 * Description:       Provides a series of developers tools aimed at facilitating the custom fields management during the development and debugging phase
 * Version:           4.6.0
 * Author:            Giulio Daprela
 * Author URI:        http://giuliodaprela.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dapre-cft
 * Domain Path:       /languages
 */

namespace dapre_cft;

// If this file is called directly, abort.
defined( 'ABSPATH' ) or die;

setup_constants();

/**
 * Setup all constants
 */
function setup_constants() {
	
	// get_file_data should always be available. We leave this check just in case
	if ( ! function_exists( 'get_file_data' ) ) {
		require_once ABSPATH . 'wp-includes/functions.php';
	}
	
	$default_headers = [
		'TextDomain' => 'Text Domain',
		'Version'	 => 'Version'
	];
	
	$plugin_data = get_file_data( __FILE__ , $default_headers);
	
	define( __NAMESPACE__ . '\PLUGIN_VERSION', $plugin_data['Version'] );
	define( __NAMESPACE__ . '\PLUGIN_NAME', $plugin_data['TextDomain'] );
	define( __NAMESPACE__ . '\PLUGIN_URL_PATH', plugins_url( '/' , __FILE__ ) );
	define( __NAMESPACE__ . '\PLUGIN_DIR_PATH', WP_PLUGIN_DIR.'/'. basename(__DIR__) .'/' );
	define( __NAMESPACE__ . '\PLUGIN_SLUG', basename(__DIR__) );
	define( __NAMESPACE__ . '\PLUGIN', basename( __FILE__, '.php' ) );
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\activate_plugin' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dapre-cft-activator.php
 */
function activate_plugin() {
	require_once PLUGIN_DIR_PATH . 'includes/class-activator.php';
	includes\Activator::activate();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require PLUGIN_DIR_PATH . 'includes/class-loader.php';

add_action('admin_enqueue_scripts' , __NAMESPACE__ . '\initialize_ajax', 11);
/**
 * Initialize AJAX
 */
function initialize_ajax() {
	
	/* @var string $protocol detect the protocol used by the website */
	$protocol = is_ssl() ? 'https':'http';
	
	wp_localize_script(
		PLUGIN_NAME,
		'DapreCftAjax',
		array(
			'ajax_url' =>  admin_url( 'admin-ajax.php', $protocol ),
			'nonce'	   =>  wp_create_nonce( 'custom_fields_tools' )
		)
	);
}

/**
 * Provides the asset timestamp as version number if we are in debug mode or the plugin version if we are in production mode
 * 
 * @param  string	$asset_file		Complete path to the asset file (not to confuse with the URL)
 * @return string					The asset version
 */
function get_asset_version( $asset_file ) {

	$asset_version = filemtime($asset_file);

	// detect the case where a Windows server returns the wrong encoding and convert
	if ( $asset_version === false ) {
		$asset_version = filemtime(utf8_decode($asset_file));
	}

	return $asset_version;
}

/**
 * Checks if the site is in development/debug mode
 * 
 * @return boolean True if the site is in debug mode
 */
function plugin_is_in_debug_mode() {
	return ( (bool) WP_DEBUG === true );
}

$loader = new includes\Loader();