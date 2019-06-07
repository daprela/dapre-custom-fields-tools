<?php

namespace dapre_cft\includes;

use const dapre_cft\PLUGIN_DIR_PATH;
use const dapre_cft\PLUGIN_NAME;

defined( 'ABSPATH' ) or die;

/**
 * The core plugin class.
 *
 * This is used to define internationalization and admin-specific hooks
 *
 * @since      1.0.0
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 */
class Loader {

	/** @var object $admin */
	public $admin;

	/** @var object $plugin_public */
	public $plugin_public;

	/** @var object $plugin_i18n The internationalization class */
	public $plugin_i18n;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load the dependencies, define the locale, and set the hooks for the admin area of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
	    spl_autoload_register( [$this,'autoload'] );

		$this->admin         = new Plugin_Admin();
		$this->plugin_i18n   = new i18n();

	    $this->set_locale();
	    $this->load_dependencies();

	    if ( $this->is_php_version_ok() ) {
		    $this->define_admin_hooks();
	    } else {
		    add_action( 'admin_notices', [$this, 'required_php_version_print_notice'] );
	    }
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Functions. Defines all the utility functions that don't go in a class. 
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		
		/**
		 * General utility functions
		 */
		require_once PLUGIN_DIR_PATH . 'includes/functions.php';
	}
	
	/**
	 * Class autoloader method
	 * 
	 * @param	 string   $class   Class name which also includes the namespace
	 * @return   void
	 */
	private function autoload($class) {

		/** @var string $class_path The path to the class */
		$class_path = strtolower( str_replace("_","-",$class) );

		/** @var array $paths Array containing folder names in each element */
		$paths = explode('\\', $class_path);
		
		if ( $paths[0] != PLUGIN_NAME ) {
			return;
		}

		/** @var string $class_file The complete path to the class file */
		$class_file = PLUGIN_DIR_PATH . "{$paths[1]}/class-{$paths[2]}.php";
		
		if ( file_exists($class_file) ) {
           	include_once($class_file);
       	} else {
			/** @var string $abstract_class_file the complete path to the abstract class */
			$abstract_class_file = PLUGIN_DIR_PATH . "{$paths[1]}/abstract-class-{$paths[2]}.php";
			if ( file_exists($abstract_class_file) ) {
				include_once($abstract_class_file);
			}
		}
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dapre_CFT_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		add_action( 'plugins_loaded', [$this->plugin_i18n, 'load_plugin_textdomain'] );
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		add_action( 'admin_enqueue_scripts', [$this->admin, 'enqueue_styles'] );
		add_action( 'admin_enqueue_scripts', [$this->admin, 'enqueue_scripts'], 10 );
		
		//creates the tabbed settings page and manages option saving
		add_action( 'admin_menu', [$this->admin,'add_admin_menu'] );
		
		/* Options */
		add_action( 'wp_ajax_dapre_submit_options_fields', [$this->admin,'option_fields'] );
		add_action( 'wp_ajax_dapre_rename_option', [$this->admin,'rename_option'] );
		add_action( 'wp_ajax_dapre_copy_option', [$this->admin,'copy_option'] );
		
		/* User fields */
		add_action( 'wp_ajax_dapre_submit_user_fields', [$this->admin,'user_fields'] );
		add_action( 'wp_ajax_dapre_rename_user_field', [$this->admin,'rename_user_field'] );
		add_action( 'wp_ajax_dapre_copy_user_field', [$this->admin,'copy_user_field'] );
		
		/* Post fields */
		add_action( 'wp_ajax_dapre_submit_post_fields', [$this->admin,'post_fields'] );
		add_action( 'wp_ajax_dapre_rename_post_field', [$this->admin,'rename_post_field'] );
		add_action( 'wp_ajax_dapre_copy_post_field', [$this->admin,'copy_post_field'] );
	}
	
	/**
	 * Checks if the PHP version installed on the server is compatible with the plugin
	 * 
	 * @return   boolean   True if the version is good
	 */
	public function is_php_version_ok() {
		if ( version_compare( PHP_VERSION, '7.0.0', '<' ) ) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Prints the notice in case the PHP version doesn't meet the minimum requirements
	 */
	public function required_php_version_print_notice() {
		?>
		<div class="notice notice-error is-dismissible">
			<p>The plugin Dapre Custom Fields Tools requires PHP Version 7.0.0 or greater.</p>
		</div>
		<?php
	}
}
