<?php
/**
 * Bootstrap the plugin unit testing environment.
 *
 * Edit 'active_plugins' setting below to point to your main plugin file.
 *
 * @package wordpress-plugin-tests
 */
// Activates this plugin in WordPress so it can be tested.
 
$GLOBALS['wp_tests_options'] = array(
    'active_plugins' => array(
        'dapre-custom-fields-tools/dapre-custom-fields-tools.php'
    ),
    'wpsp_test' => true
);
 
require dirname(__FILE__) . '/includes/bootstrap.php';