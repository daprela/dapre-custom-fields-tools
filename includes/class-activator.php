<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * Fired during plugin activation
 *
 * @link       http://giuliodaprela.com
 * @since      1.0.0
 *
 * @package    dapre_cft\includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    dapre_cft
 * @subpackage dapre_cft/includes
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 */
class Activator {

	/**
	 * Initializes previous values arrays
	 *
	 * Initializes the arrays containing the previous values.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		
		$previous_options = initialize_previous_options();
		add_option('dapre_cft_previous_options', $previous_options);
		
		$previous_user_fields = initialize_previous_user_fields();
		add_option('dapre_cft_previous_user_fields', $previous_user_fields);
		
		$previous_post_fields = initialize_previous_post_fields();
		add_option('dapre_cft_previous_post_fields', $previous_post_fields);
		
	}
}
