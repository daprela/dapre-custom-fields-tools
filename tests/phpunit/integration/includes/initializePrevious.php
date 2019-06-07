<?php
namespace dapre_cft\tests\integration\plugin_admin;

use WP_UnitTestCase;

use dapre_cft\includes;

class Tests_InitializePrevious extends WP_UnitTestCase {
	
	public function setUp() {
		parent::setUp();
		
		require_once DCFT_PLUGIN_DIR . 'includes/functions.php';
		require_once DCFT_PLUGIN_DIR . 'includes/abstract-class-custom-fields.php';
		require_once DCFT_PLUGIN_DIR . 'includes/class-options-fields.php';
		require_once DCFT_PLUGIN_DIR . 'includes/class-user-fields.php';
		require_once DCFT_PLUGIN_DIR . 'includes/class-post-fields.php';
	}
	
	public function test_initialization_of_previous_options() {
		$previous_options = includes\initialize_previous_options();
		
		foreach ($previous_options as $option) {
			// current value should return empty
			$this->assertEmpty($option->get_name());
		}	
	}
	
	public function test_initialization_of_previous_users() {
		$previous_user_fields = includes\initialize_previous_user_fields();
		
		foreach ($previous_user_fields as $user_field) {
			// current value should return empty
			$this->assertEmpty($user_field->get_name());
		}	
	}
	
	public function test_initialization_of_previous_posts() {
		$previous_post_fields = includes\initialize_previous_post_fields();
		
		foreach ($previous_post_fields as $post_field) {
			// current value should return empty
			$this->assertEmpty($post_field->get_name());
		}	
	}
}