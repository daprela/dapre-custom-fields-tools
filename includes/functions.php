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
	$previous_options = [
		'0' => new Options_Fields( '' ),
	];

	update_option( 'dapre_cft_previous_options', $previous_options );

	return $previous_options;
}

/**
 * Initializes the previous user fields.
 *
 * @return array $previous_user_fields An array containing the previous user fields.
 * @since 1.0.0
 *
 */
function initialize_previous_user_fields(): array {
	$previous_user_fields = [
		'0' => new User_Fields( '', '' ),
	];

	update_option( 'dapre_cft_previous_options', $previous_user_fields );

	return $previous_user_fields;
}

/**
 * Initializes the previous post fields.
 *
 * @return array $previous_post_fields An array containing the previous post fields.
 * @since 1.0.0
 *
 */
function initialize_previous_post_fields(): array {
	$previous_post_fields = [
		'0' => new Post_Fields( '', '' ),
	];

	update_option( 'dapre_cft_previous_post_fields', $previous_post_fields );

	return $previous_post_fields;
}