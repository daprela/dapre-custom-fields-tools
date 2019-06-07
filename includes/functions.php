<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * Initializes the previous options.
 *
 * @since 1.0.0
 *
 * @return array $previous_options An array containing the previous options.
 */
function initialize_previous_options() {
	
	$previous_options = array(
		'0' => new Options_Fields(''),
		'1' => new Options_Fields(''),
		'2' => new Options_Fields(''),
		'3' => new Options_Fields(''),
		'4' => new Options_Fields(''),
	);

	return $previous_options;
}

/**
 * Initializes the previous user fields.
 *
 * @since 1.0.0
 *
 * @return array $previous_user_fields An array containing the previous users fields.
 */
function initialize_previous_user_fields() {
	
	$previous_user_fields = array(
		'0' => new User_Fields('',''),
		'1' => new User_Fields('',''),
		'2' => new User_Fields('',''),
		'3' => new User_Fields('',''),
		'4' => new User_Fields('',''),
	);
	
	return $previous_user_fields;
}

/**
 * Initializes the previous post fields.
 *
 * @since 1.0.0
 *
 * @return array $previous_post_fields An array containing the previous post fields.
 */
function initialize_previous_post_fields() {

	$previous_post_fields = array(
		'0' => new Post_Fields('',''),
		'1' => new Post_Fields('',''),
		'2' => new Post_Fields('',''),
		'3' => new Post_Fields('',''),
		'4' => new Post_Fields('',''),
	);
	
	return $previous_post_fields;
}