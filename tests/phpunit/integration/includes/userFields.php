<?php
namespace dapre_cft\tests\integration\plugin_admin;

use WP_UnitTestCase;

use dapre_cft\includes;

class Tests_UserFields extends WP_UnitTestCase {
	
	public function setUp() {
		parent::setUp();
		
		require_once DCFT_PLUGIN_DIR . 'includes/abstract-class-custom-fields.php';
		require_once DCFT_PLUGIN_DIR . 'includes/class-user-fields.php';
	}
	
	public function test_object_status_when_non_existent_field_is_created() {
		$test = new includes\User_Fields(1, 'test_option');
		
		// current value should return empty
		$this->assertEmpty($test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be enabled
		$this->assertEmpty($test->get_disable_write());
		
		// delete option should be disabled
		$this->assertEquals('disabled', $test->get_disable_delete());
		
		// the error message must be shown
		$this->assertEquals('This field does not exist.', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
	}
	
	public function test_object_status_when_user_does_not_exist() {
		
		$test = new includes\User_Fields(100, 'test_option');
		
		// current value should return empty
		$this->assertEmpty($test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be enabled
		$this->assertEmpty($test->get_disable_write());
		
		// delete option should be disabled
		$this->assertEquals('disabled', $test->get_disable_delete());
		
		// the error message must be shown
		$this->assertEquals('This user does not exist.', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
	}
	
	public function test_error_when_trying_to_write_a_non_existent_user() {
		
		$test = new includes\User_Fields(100, 'test_option');
		
		// the error message must be shown
		$this->assertEquals('This user does not exist.', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
		
		$test->write('test');
		
		// the error message must be shown
		$this->assertEquals('Updating the meta field is not possible. The user does not exist.', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
	}
	
	public function test_error_when_trying_to_write_read_only_field() {
		
		$test = new includes\User_Fields(1, 'ID');
		
		// the error message should not be shown
		$this->assertempty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
		
		$test->write('2');
		
		// the error message must be shown
		$this->assertEquals('This is a read-only field. You cannot overwrite this field.', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
	}
	
	public function test_object_status_when_empty_field_name_is_given() {
		$test = new includes\User_Fields(1, '');
		
		// current value should return empty
		$this->assertEmpty($test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be disabled
		$this->assertEquals('disabled', $test->get_disable_write());
		
		// delete option should be disabled
		$this->assertEquals('disabled', $test->get_disable_delete());
		
		// the error message should not be shown
		$this->assertEquals('This field does not exist.', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
	}
	
	public function test_object_status_when_field_exists() {
		update_user_meta(1, 'test_option', 'test');
		
		$test = new includes\User_Fields(1, 'test_option');
		
		// current value must return 'test'
		$this->assertEquals('test', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be enabled
		$this->assertEmpty($test->get_disable_write());
		
		// delete option should be enabled
		$this->assertEmpty($test->get_disable_delete());
		
		// the error message should not be shown
		$this->assertempty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_objecft_status_when_field_is_written_with_a_string() {

		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('test');
		
		// current value must return 'test'
		$this->assertEquals('test', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be enabled
		$this->assertEmpty($test->get_disable_write());
		
		// delete option should be enabled
		$this->assertEmpty($test->get_disable_delete());
		
		// the error message should not be shown
		$this->assertempty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_object_status_when_int_number_is_written() {

		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('1234');
		
		// current value must return '1234'
		$this->assertEquals('1234', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should show up
		$this->assertEmpty($test->get_date_toggle());
		
		// write option should be enabled
		$this->assertEmpty($test->get_disable_write());
		
		// delete option should be enabled
		$this->assertEmpty($test->get_disable_delete());
		
		// the error message should not be shown
		$this->assertempty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_object_status_when_a_date_string_is_written() {

		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('January 1, 2018');
		
		// current value must return 'January 1, 2018'
		$this->assertEquals('January 1, 2018', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should show up
		$this->assertEmpty($test->get_date_toggle());
		
		// write option should be enabled
		$this->assertEmpty($test->get_disable_write());
		
		// delete option should be enabled
		$this->assertEmpty($test->get_disable_delete());
		
		// the error message should not be shown
		$this->assertempty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_object_status_when_field_is_overwritten() {
		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('old_value');
		
		// current value should return 'old value'
		$this->assertEquals('old_value', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		$test->write('new_value');
		
		// current value should return 'new value'
		$this->assertEquals('new_value', $test->get_current_value());
		
		// previous value should return 'old value'
		$this->assertEquals('old_value', $test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be disabled
		$this->assertEmpty($test->get_disable_write());
		
		// no error should be shown
		$this->assertEmpty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_object_status_when_field_changes_from_number_to_date_string() {
		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('1234');
		
		// current value should return 'old value'
		$this->assertEquals('1234', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should show up
		$this->assertEmpty($test->get_date_toggle());
		
		
		$test->write('January 1, 2018');
		
		// current value should return 'new value'
		$this->assertEquals('January 1, 2018', $test->get_current_value());
		
		// previous value should return 'old value'
		$this->assertEquals('1234', $test->get_previous_value());
		
		// date toggle should show up
		$this->assertEmpty($test->get_date_toggle());
		
		// write option should be disabled
		$this->assertEmpty($test->get_disable_write());
		
		// no error should be shown
		$this->assertEmpty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_object_status_when_field_changes_from_string_to_date_string() {
		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('old value');
		
		// current value should return 'old value'
		$this->assertEquals('old value', $test->get_current_value());
		
		// previous value should return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle should be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		$test->write('January 1, 2018');
		
		// current value should return 'new value'
		$this->assertEquals('January 1, 2018', $test->get_current_value());
		
		// previous value should return 'old value'
		$this->assertEquals('old value', $test->get_previous_value());
		
		// date toggle should show up
		$this->assertEmpty($test->get_date_toggle());
		
		// write option should be disabled
		$this->assertEmpty($test->get_disable_write());
		
		// no error should be shown
		$this->assertEmpty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_object_status_when_field_is_deleted() {
		$test = new includes\User_Fields(1, 'test_option');
		
		$test->write('old_value');
		
		$this->assertEquals('old_value', $test->get_current_value());
		
		$test->delete();
		
		// current value must return empty
		$this->assertEmpty($test->get_current_value());
		
		// previous value must return empty
		$this->assertEmpty($test->get_previous_value());
		
		// date toggle shlud be hidden
		$this->assertEquals('hide', $test->get_date_toggle());
		
		// write option should be disabled
		$this->assertEquals('disabled', $test->get_disable_write());
		
		// delete  option should be disabled
		$this->assertEquals('disabled', $test->get_disable_delete());
		
		// no error should be shown
		$this->assertEmpty($test->get_error());
		$this->assertEquals('hide', $test->get_field_error_class());
	}
	
	public function test_error_when_we_try_to_delete_a_system_field() {
		
		$test = new includes\User_Fields(1, 'user_nicename');
		
		$test->delete();
		
		// the error message should not be shown
		$this->assertEquals('You cannot delete system fields', $test->get_error());
		$this->assertEquals('show', $test->get_field_error_class());
	}
}