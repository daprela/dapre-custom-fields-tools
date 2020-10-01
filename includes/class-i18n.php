<?php

namespace dapre_cft\includes;

use const dapre_cft\PLUGIN_DIR_PATH;
use const dapre_cft\PLUGIN_NAME;

defined( 'ABSPATH' ) or die;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    dapre_cft
 * @subpackage dapre_cft/includes
 * @author     Giulio Daprela <giulio.daprela@example.com>
 */
class i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain(): void {

		load_plugin_textdomain(
			PLUGIN_NAME,
			false,
			PLUGIN_DIR_PATH . '/languages/'
		);

	}



}
