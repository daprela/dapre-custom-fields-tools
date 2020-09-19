<?php

namespace dapre_cft\includes;

defined( 'ABSPATH' ) or die;

/**
 * Initializes the previous options.
 *
 * @return array $previous_options An array containing the previous options.
 * @since 1.0.0
 *
 */
function initialize_previous_options(): array {

	return [
		'0' => new Options_Fields( '' ),
	];
}

/**
 * Initializes the previous user fields.
 *
 * @return array $previous_user_fields An array containing the previous users fields.
 * @since 1.0.0
 *
 */
function initialize_previous_user_fields(): array {

	return [
		'0' => new User_Fields( '', '' ),
		'1' => new User_Fields( '', '' ),
		'2' => new User_Fields( '', '' ),
		'3' => new User_Fields( '', '' ),
		'4' => new User_Fields( '', '' ),
	];
}

/**
 * Initializes the previous post fields.
 *
 * @return array $previous_post_fields An array containing the previous post fields.
 * @since 1.0.0
 *
 */
function initialize_previous_post_fields(): array {

	return [
		'0' => new Post_Fields( '', '' ),
		'1' => new Post_Fields( '', '' ),
		'2' => new Post_Fields( '', '' ),
		'3' => new Post_Fields( '', '' ),
		'4' => new Post_Fields( '', '' ),
	];
}