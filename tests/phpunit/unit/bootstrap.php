<?php
/**
 * Bootstraps the Unit Tests.
 *
 * @package     Dcft\Tests\Unit
 * @since       4.4.0
 * @license     GNU-2.0+
 */

if ( version_compare( phpversion(), '5.6.0', '<' ) ) {
    die( 'Unit Tests require PHP 5.6 or higher.' );
}

// Define testing constants.
define ("DCFT_TEST_DIR" , __DIR__ );
define ("DCFT_PLUGIN_DIR", dirname( dirname( dirname( __DIR__))) . DIRECTORY_SEPARATOR );

// Load Composer's autoloader
require_once DCFT_ROOT_DIR . 'vendor/autoload.php';

// Load Composer's autoloader.
$dcft_autoload_path = DCFT_ROOT_DIR . 'vendor/';

if ( ! file_exists( $dcft_autoload_path . 'autoload.php' ) ) {
    die( 'Whoops, we need Composer before we start running tests.  Please type: `composer install`.  When done, try running `phpunit` again.' );
}
require_once $dcft_autoload_path . 'autoload.php';
unset( $dcft_autoload_path );