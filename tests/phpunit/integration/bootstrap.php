<?php
/**
 * Bootstraps the Integration Tests.
 *
 * @package     Dcft\Tests\Integration
 * @since       4.4.0
 * @license     GNU-2.0+
 */

if ( ! file_exists( '../../../wp-content' ) ) {
	trigger_error( 'Unable to run the integration tests, as the wp-content folder does not exist.', E_USER_ERROR );
}
// Define testing constants.
define( 'DCFT_TESTS_DIR', __DIR__ );
define( 'DCFT_PLUGIN_DIR', dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR );
define( 'WP_CONTENT_DIR', dirname( dirname( dirname( getcwd() ) ) ) . '/wp-content/' );

if ( ! defined( 'WP_PLUGIN_DIR' ) ) {
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . 'plugins/' );
}

/**
 * Get the WordPress' tests suite directory.
 *
 * @since 1.0.0
 *
 * @return void
 */
function dcft_get_wp_test_dir() {
	$tests_dir = getenv('WP_TESTS_DIR');
	
	// Travis CI & Vagrant SSH tests directory.
	if ( empty( $tests_dir ) ) {
		$tests_dir = '/tmp/wordpress-tests';
	}
	
	// If the tests' includes directory does not exist, try a relative path to Core tests directory.
	if ( ! file_exists( $tests_dir . '/includes/' ) ) {
		$tests_dir = '../../../../tests/phpunit';
		var_dump($tests_dir);
	}
	
	// Check it again. If it doesn't exist, stop here and post a message as to why we stopped.
	if ( ! file_exists( $tests_dir . '/includes/' ) ) {
		trigger_error( 'Unable to run the integration tests, as the WordPress test suite could not be located.', E_USER_ERROR );  // @codingStandardsIgnoreLine.
	}
	
	// Strip off the trailing directory separator, if it exists.
	return rtrim( $tests_dir, DIRECTORY_SEPARATOR );
}

$dcft_tests_dir = dcft_get_wp_test_dir();

// Give access to tests_add_filter() function.
require_once $dcft_tests_dir . '/includes/functions.php';

// Activates this plugin in WordPress so it can be tested.
$GLOBALS['wp_tests_options'] = array(
    'active_plugins' => array(
        'dapre-custom-fields-tools/dapre-custom-fields-tools.php'
    ),
    'wpsp_test' => true
);

// Start up WP's testing suite's bootstrap file.
require_once $dcft_tests_dir . '/includes/bootstrap.php';