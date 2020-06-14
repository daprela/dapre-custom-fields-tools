<?php

namespace dapre_cft\includes;

use function dapre_cft\get_asset_version;
use const dapre_cft\PLUGIN_DIR_PATH;
use const dapre_cft\PLUGIN_NAME;
use const dapre_cft\PLUGIN_URL_PATH;

defined( 'ABSPATH' ) or die;

/**
 * This is the core class of the plugin. It creates the tabbed pages and manages all the fields
 *
 * @package    dapre_cft\includes
 *
 * @author     Giulio Daprela <giulio.daprela@gmail.com>
 */
class Plugin_Admin {

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles() {

		// load the CSS only in the right admin screen
		if ( 'toplevel_page_dapre_cft' === get_current_screen()->id ) {
			wp_enqueue_style( 'jquery-colorbox', PLUGIN_URL_PATH . 'libs/jackmoore-colorbox/example4/colorbox.css', [], '1.5.14', 'all' );

			$version = get_asset_version( PLUGIN_DIR_PATH . 'assets/css/dapre-cft-admin.min.css' );
			wp_enqueue_style( PLUGIN_NAME, PLUGIN_URL_PATH . 'assets/css/dapre-cft-admin.min.css', [], $version, 'all' );
		}


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// load the JS only in the right admin screen
		if ( 'toplevel_page_dapre_cft' === get_current_screen()->id ) {
			wp_enqueue_script( 'jquery-colorbox', PLUGIN_URL_PATH . 'libs/jackmoore-colorbox/jquery.colorbox-min.js', [ 'jquery' ], '1.5.14', false );

			$version = get_asset_version( PLUGIN_DIR_PATH . 'assets/js/dapre-cft-admin.min.js' );
			wp_enqueue_script( PLUGIN_NAME, PLUGIN_URL_PATH . 'assets/js/dapre-cft-admin.min.js', [ 'jquery' ], $version, false );
		}
	}

	/**
	 * Add the admin menu of the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		//add an item to the menu
		add_menu_page(
			__( 'Dapre Custom Fields Tools', "dapre-cft" ),
			__( 'Custom Fields Tools', "dapre-cft" ),
			'manage_options',
			'dapre_cft',
			[ $this, 'manage_tabs' ],
			'dashicons-admin-generic'
		);
	}

	/**
	 * Manages the tabbed option page
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function manage_tabs() {

		$this->admin_tabs();

		$form_data = $_POST;

		$previous_options     = $this->get_previous_options();
		$previous_user_fields = $this->get_previous_user_fields();
		$previous_post_fields = $this->get_previous_post_fields();

		if ( array_key_exists( 'reset_fields', $form_data ) ) {

			if ( ! check_admin_referer( 'dapre_cft_options', 'dapre_cft_options_verify' ) ) {
				die();
			}

			$previous_options     = initialize_previous_options();
			$previous_user_fields = initialize_previous_user_fields();
			$previous_post_fields = initialize_previous_post_fields();

			$this->set_previous_options( $previous_options );
			$this->set_previous_user_fields( $previous_user_fields );
			$this->set_previous_post_fields( $previous_post_fields );

			$form_data['user_id']     = [];
			$form_data['field_name']  = [];
			$form_data['field_value'] = [];
			$form_data['post_id']     = [];
		}

		/*
		 * options section
		 */

		// the user has clicked on the admin menu so we read the previous values
		foreach ( $previous_options as $option ) {

			$option->refresh( 'refresh' );

		}

		/*
		 * user fields section
		 */

		// the user has clicked on the admin menu so we read the previous values
		foreach ( $previous_user_fields as $user_field ) {

			$user_field->refresh( 'refresh' );

		}

		/*
		 * post fields section
		 */

		// the user has clicked on the admin menu so we read the previous values
		foreach ( $previous_post_fields as $post_field ) {

			$post_field->refresh( 'refresh' );

		}

		$field_error_class = 'is-hidden';
		$row_error_class   = '';

		require_once \dapre_cft\PLUGIN_DIR_PATH . 'templates/tabbed-admin.php';
	}

	/**
	 * Prints the HTML for rendering the tabs
	 *
	 * @since 1.0.0
	 *
	 * @param string $current
	 *
	 * @return void
	 */
	public function admin_tabs( $current = 'options' ) {

		$tabs = [
			'options'     => esc_html( "Options", "dapre-cft" ),
			'user_fields' => esc_html( "User Fields", "dapre-cft" ),
			'post_fields' => esc_html( "Post Fields", "dapre-cft" ),
		];
		echo '<h1 class="nav-tab-wrapper">';

		$tab_ind = 0;
		foreach ( $tabs as $tab => $name ) {
			$tab_ind += 1;
			$class   = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='js-tab_$tab_ind c-tab_$tab_ind nav-tab$class' data-tab='$tab_ind' href='?page=dapre_cft&tab=$tab'>$name</a>";

		}
		echo '</h1>';
	}

	/**
	 * Read and write option fields.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function option_fields() {
		$this->can_respond();

		$form_data = $this->get_form_data();

		$previous_options = $this->get_previous_options();

		$options_count = count( $previous_options );

		for ( $key = 0; $key < $options_count; $key ++ ) {
			if ( empty( $form_data['field_name'][ $key ] ) ) {
				continue;
			}

			$form_data['field_name'][ $key ] = \sanitize_text_field( $form_data['field_name'][ $key ] );
		}

		// Now we perform first the writing operations...
		foreach ( $previous_options as $key => $option ) {
			if ( empty( $form_data['field_name'][ $key ] ) ) {
				$option                   = new Options_Fields( '' );
				$previous_options[ $key ] = $option;
				continue;
			}

			// this is not necessary but it makes the following code cleaner and more readable
			$field_name = $form_data['field_name'][ $key ];

			// delete the option...
			if ( 'delete' == $form_data['field_action'][ $key ] ) {
				$option->delete();
				continue;
			}

			// ...or write the option
			if ( 'write' == $form_data['field_action'][ $key ] ) {

				// does the user want to write an empty array or a normal text field?
				if ( $form_data['empty_array'][ $key ] ) {
					$field_value = [];
				} else {
					// sanitize text area without losing html tags
					$field_value = stripslashes( sanitize_textarea_field( htmlentities( $form_data['field_value'][ $key ] ) ) );
				}

				// il the field name has changed delete the previous values as they are not relevant anymore
				if ( $field_name != $option->get_name() ) {
					$option                   = new Options_Fields( $field_name );
					$previous_options[ $key ] = $option;
				}

				$option->write( $field_value );
			}
		}

		// ...then we read all the fields
		foreach ( $previous_options as $key => $option ) {
			if ( empty( $form_data['field_name'][ $key ] ) ) {
				$option                   = new Options_Fields( '' );
				$previous_options[ $key ] = $option;
				continue;
			}

			// this is not necessary but it makes the following code cleaner and more readable
			$field_name = $form_data['field_name'][ $key ];

			// if the option name changes then we can't keep the previous object
			if ( $field_name != $option->get_name() ) {
				$option                   = new Options_Fields( $field_name );
				$previous_options[ $key ] = $option;
			} else {
				$option->refresh( 'refresh' );
			}
		}

		$this->set_previous_options( $previous_options );

		ob_start();

		include_once \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-meta.php';

		$html = ob_get_clean();

		$response = [
			'html' => $html,
		];

		wp_send_json_success( $response );
	}

	/**
	 * Read and write user fields
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function user_fields() {
		$this->can_respond();

		$form_data = $this->get_form_data();

		$previous_user_fields = $this->get_previous_user_fields();

		// let's sanitize all the field names first
		foreach ( $previous_user_fields as $key => $user_field ) {
			if ( empty( $form_data['field_name'][ $key ] ) ) {
				continue;
			}

			$form_data['field_name'][ $key ] = \sanitize_text_field( $form_data['field_name'][ $key ] );
		}

		// Now we perform first the writing operations...
		foreach ( $previous_user_fields as $key => $user_field ) {
			if ( empty( $form_data['user_id'][ $key ] ) || empty( $form_data['field_name'][ $key ] ) ) {
				$user_field                   = new User_Fields( '', '' );
				$previous_user_fields[ $key ] = $user_field;
				continue;
			}

			// this is not necessary but it makes the following code cleaner and more readable
			$field_name = $form_data['field_name'][ $key ];
			$user_id    = $form_data['user_id'][ $key ];

			// delete the user field...
			if ( 'delete' == $form_data['field_action'][ $key ] ) {
				$user_field->delete();
				continue;
			}

			// ...or write the user field
			if ( 'write' == $form_data['field_action'][ $key ] ) {

				// does the user want to write an empty array or a normal text field?
				if ( $form_data['empty_array'][ $key ] ) {
					$field_value = [];
				} else {
					// sanitize text area without losing html tags
					$field_value = stripslashes( sanitize_textarea_field( htmlentities( $form_data['field_value'][ $key ] ) ) );
				}

				// il the field has changed delete the previous values as they are not relevant anymore
				if ( $field_name != $user_field->get_name() || $user_id != $user_field->get_user_id() ) {
					$user_field                   = new User_Fields( $user_id, $field_name );
					$previous_user_fields[ $key ] = $user_field;
				}

				$user_field->write( $field_value );
			}
		}

		// ...then we read all the fields
		foreach ( $previous_user_fields as $key => $user_field ) {
			if ( empty( $form_data['user_id'][ $key ] ) || empty( $form_data['field_name'][ $key ] ) ) {
				$user_field                   = new User_Fields( '', '' );
				$previous_user_fields[ $key ] = $user_field;
				continue;
			}

			// this is not necessary but it makes the following code cleaner and more readable
			$field_name = $form_data['field_name'][ $key ];
			$user_id    = $form_data['user_id'][ $key ];

			// if the user field changes then we can't keep the previous object
			if ( $user_id != $user_field->get_user_id() || $field_name != $user_field->get_name() ) {
				$user_field                   = new User_Fields( $user_id, $field_name );
				$previous_user_fields[ $key ] = $user_field;
			} else {
				if ( $user_field->get_write_error() ) {
					$user_field->set_write_error( false );
				} else {
					$user_field->refresh( 'refresh' );
				}
			}
		}

		$this->set_previous_user_fields( $previous_user_fields );

		ob_start();

		require_once \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-meta.php';

		$html = ob_get_clean();

		$response = [
			'html' => $html,
		];

		wp_send_json_success( $response );
	}

	/**
	 * Read and write post fields.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function post_fields() {
		$this->can_respond();

		$form_data = $this->get_form_data();

		$previous_post_fields = $this->get_previous_post_fields();

		// let's sanitize all the field names first
		foreach ( $previous_post_fields as $key => $post_field ) {
			if ( empty( $form_data['field_name'][ $key ] ) ) {
				continue;
			}

			$form_data['field_name'][ $key ] = \sanitize_text_field( $form_data['field_name'][ $key ] );
		}

		// Now we perform first the writing operations...
		foreach ( $previous_post_fields as $key => $post_field ) {
			if ( empty( $form_data['post_id'][ $key ] ) || empty( $form_data['field_name'][ $key ] ) ) {
				$post_field                   = new Post_Fields( '', '' );
				$previous_post_fields[ $key ] = $post_field;
				continue;
			}

			// this is not necessary but it makes the following code cleaner and more readable
			$field_name = $form_data['field_name'][ $key ];
			$post_id    = $form_data['post_id'][ $key ];

			// delete the user field...
			if ( 'delete' == $form_data['field_action'][ $key ] ) {
				$post_field->delete();
				continue;
			}

			// ...or write the user field
			if ( 'write' == $form_data['field_action'][ $key ] ) {

				// does the user want to write an empty array or a normal text field?
				if ( $form_data['empty_array'][ $key ] ) {
					$field_value = [];
				} else {
					// sanitize text area without losing html tags
					$field_value = stripslashes( sanitize_textarea_field( htmlentities( $form_data['field_value'][ $key ] ) ) );
				}

				// il the field has changed delete the previous values as they are not relevant anymore
				if ( $field_name != $post_field->get_name() || $post_id != $post_field->get_post_id() ) {
					$post_field                   = new Post_Fields( $post_field, $field_name );
					$previous_post_fields[ $key ] = $post_field;
				}

				$post_field->write( $field_value );
			}
		}

		// ...then we read all the fields
		foreach ( $previous_post_fields as $key => $post_field ) {
			if ( empty( $form_data['post_id'][ $key ] ) || empty( $form_data['field_name'][ $key ] ) ) {
				$post_field                   = new Post_Fields( '', '' );
				$previous_post_fields[ $key ] = $post_field;
				continue;
			}

			// this is not necessary but it makes the following code cleaner and more readable
			$field_name = $form_data['field_name'][ $key ];
			$post_id    = $form_data['post_id'][ $key ];

			// if the user field changes then we can't keep the previous object
			if ( $post_id != $post_field->get_post_id() || $field_name != $post_field->get_name() ) {
				$post_field                   = new Post_Fields( $post_id, $field_name );
				$previous_post_fields[ $key ] = $post_field;
			} else {
				if ( $post_field->get_write_error() ) {
					$post_field->set_write_error( false );
				} else {
					$post_field->refresh( 'refresh' );
				}
			}
		}

		$this->set_previous_post_fields( $previous_post_fields );

		ob_start();

		require_once \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-meta.php';

		$html = ob_get_clean();

		$response = [
			'html' => $html,
		];

		wp_send_json_success( $response );
	}

	/**
	 * Rename an option.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function rename_option() {
		$this->can_respond();

		$form_data = $this->get_form_data();

		$old_option_name = sanitize_text_field( $form_data['old_option_name'] );
		$new_option_name = sanitize_text_field( $form_data['new_option_name'] );

		/** @var object $old_option option field to rename */
		$old_option = new Options_Fields( $old_option_name );

		/** @var object $new_option the new name of the option field */
		$new_option = new Options_Fields( $new_option_name );

		if ( $old_option_name == $new_option_name ) {
			$response = [
				'renamed' => false,
				'error'   => 'Old option and new option cannot have the same name',
			];
		} else if ( ! $old_option->option_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The starting option does not exist',
			];
		} else if ( $new_option->option_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The destination option already exists',
			];

		} else {
			$error = false;

			if ( ! empty( $old_option->get_error() ) ) {
				$response = [
					'renamed' => false,
					'error'   => $old_option->get_error(),
				];

			} else {
				$new_option->write( $old_option->get_current_value() );

				// if the new option exists and the old content was moved correctly then delete the old option
				if ( empty( $new_option->get_error() ) && ! $error ) {
					$old_option->delete();
					$response = [
						'renamed' => true,
					];
				} else {
					$response = [
						'renamed' => false,
					];
				}
			}
		}

		wp_send_json_success( $response );

	}

	/**
	 * Rename user field.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function rename_user_field() {
		$this->can_respond();

		$form_data = $this->get_form_data();

		$user_field_id       = sanitize_text_field( $form_data['old_user_field_id'] );
		$old_user_field_name = sanitize_text_field( $form_data['old_user_field_name'] );
		$new_user_field_name = sanitize_text_field( $form_data['new_user_field_name'] );

		/** @var object $old_user_meta user field to rename */
		$old_user_meta = new User_Fields( $user_field_id, $old_user_field_name );

		/** @var object $new_user_meta the new name of the user field */
		$new_user_meta = new User_Fields( $user_field_id, $new_user_field_name );

		if ( $old_user_field_name == $new_user_field_name ) {
			$response = [
				'renamed' => false,
				'error'   => 'Old meta and new meta cannot have the same name',
			];
		} else if ( ! $old_user_meta->user_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The user does not exist',
			];
		} else if ( ! $old_user_meta->user_meta_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The starting user meta does not exist',
			];
		} else if ( $new_user_meta->user_meta_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The destination option already exists',
			];
		} else {

			$error = false;

			if ( ! empty( $old_user_meta->get_error() ) ) {
				$response = [
					'renamed' => false,
					'error'   => $old_user_meta->get_error(),
				];
			} else {
				$new_user_meta->write( $old_user_meta->get_current_value() );

				// if the new option exists and the old content was moved correctly then delete the old option
				if ( empty( $new_user_meta->get_error() ) && ! $error ) {
					$old_user_meta->delete();
					$response = [
						'renamed' => true,
					];
				} else {
					$response = [
						'renamed' => false,
					];
				}
			}
		}

		wp_send_json_success( $response );
	}

	/**
	 * Rename post field.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function rename_post_field() {
		$this->can_respond();

		$form_data = $this->get_form_data();

		$post_field_id       = sanitize_text_field( $form_data['old_post_field_id'] );
		$old_post_field_name = sanitize_text_field( $form_data['old_post_field_name'] );
		$new_post_field_name = sanitize_text_field( $form_data['new_post_field_name'] );

		/** @var object $old_post_meta post field to rename */
		$old_post_meta = new Post_Fields( $post_field_id, $old_post_field_name );

		/** @var object $new_post_meta the new name of the post field */
		$new_post_meta = new Post_Fields( $post_field_id, $new_post_field_name );

		if ( $old_post_field_name == $new_post_field_name ) {
			$response = [
				'renamed' => false,
				'error'   => 'Old meta and new meta cannot have the same name',
			];
		} else if ( ! $old_post_meta->post_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The post does not exist',
			];
		} else if ( ! $old_post_meta->post_meta_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The starting post meta does not exist',
			];
		} else if ( $new_post_meta->post_meta_exists() ) {
			$response = [
				'renamed' => false,
				'error'   => 'The destination option already exists',
			];
		} else {

			$error = false;

			if ( ! empty( $old_post_meta->get_error() ) ) {
				$response = [
					'renamed' => false,
					'error'   => $old_post_meta->get_error(),
				];
			} else {
				$new_post_meta->write( $old_post_meta->get_current_value() );

				// if the new option exists and the old content was moved correctly then delete the old option
				if ( empty( $new_post_meta->get_error() ) && ! $error ) {
					$old_post_meta->delete();
					$response = [
						'renamed' => true,
					];
				} else {
					$response = [
						'renamed' => false,
					];
				}
			}
		}

		wp_send_json_success( $response );
	}

	/**
	 * Copy an option.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function copy_option() {
		$this->can_respond();

		$response = [];

		$form_data = $this->get_form_data();

		$current_option = new Options_Fields( sanitize_text_field( $form_data['current_option_name'] ) );

		if ( ! $current_option->option_exists() ) {
			$response = [
				'copy_user_field' => false,
				'error'           => "The starting option doesn't exist.",
			];
		} else if ( empty( $current_option->get_name() ) ) {
			$response = [
				'copy_user_field' => false,
				'error'           => "Please provide the field name for the starting option.",
			];
		} else {
			switch ( $form_data['copy_option_to'] ) {
				case 'option to option':

					$opt_to_option_name = sanitize_text_field( $form_data['opt_to_option_name'] );
					$copy_option        = new Options_Fields( $opt_to_option_name );

					if ( empty( $copy_option->get_name() ) ) {
						$response = [
							'copy_user_field' => false,
							'error'           => "Please provide field name for the destination field.",
						];

						break;
					}

					if ( $current_option->get_name() == $copy_option->get_name() ) {
						$response = [
							'copy_option' => true,
							'copied'      => false,
							'error'       => "Starting option and destination option cannot be the same.",
						];
					} else {

						// determines if the option "create field if it doesn't exists" is checked
						$opt_to_option_create = array_key_exists( 'opt_to_option_create', $form_data ) ? true : false;

						$response = [
							'copy_option' => true,
							'copied'      => false,
							'error'       => "There was an error, the option could not be copied.",
						];

						// check if the new option exists or we are asked to create it
						if ( $copy_option->option_exists() || $opt_to_option_create ) {

							$copy_option->write( $current_option->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $copy_option->option_exists() && $current_option->get_current_value() == $copy_option->get_current_value() ) {
								$response = [
									'copy_option' => true,
									'copied'      => true,
								];
							}
						}
					}

					break;

				case 'option to user field':

					$opt_to_user_field_id   = sanitize_text_field( $form_data['opt_to_user_field_id'] );
					$opt_to_user_field_name = sanitize_text_field( $form_data['opt_to_user_field_name'] );
					$user_to_copy           = new User_Fields( $opt_to_user_field_id, $opt_to_user_field_name );

					if ( empty( $user_to_copy->get_name() ) || empty( $user_to_copy->get_user_id() ) ) {
						$response = [
							'copy_user_field' => false,
							'error'           => "Please provide both user ID and field name for the destination field.",
						];

						break;
					}

					if ( ! $user_to_copy->user_exists() ) {
						// if the user to copy from doesn't exists then fail
						$response = [
							'copy_user_field' => false,
							'error'           => "The destination user doesn't exist.",
						];
					} else {

						// determines if the option "create field if it doesn't exists" is checked
						$opt_to_user_field_create = array_key_exists( 'opt_to_user_field_create', $form_data ) ? true : false;

						$response = [
							'copy_option' => true,
							'copied'      => false,
							'error'       => "There was an error, the option could not be copied.",
						];

						// check if the new option exists or we are asked to create it
						if ( $user_to_copy->user_meta_exists() || $opt_to_user_field_create ) {

							$user_to_copy->write( $current_option->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $user_to_copy->user_meta_exists() && $current_option->get_current_value() == $user_to_copy->get_current_value() ) {
								$response = [
									'copy_option' => true,
									'copied'      => true,
								];
							}
						}
					}

					break;

				case 'option to post field':

					$opt_to_post_field_id   = sanitize_text_field( $form_data['opt_to_post_field_id'] );
					$opt_to_post_field_name = sanitize_text_field( $form_data['opt_to_post_field_name'] );
					$copy_post_field        = new Post_Fields( $opt_to_post_field_id, $opt_to_post_field_name );

					if ( empty( $copy_post_field->get_name() ) || empty( $copy_post_field->get_post_id() ) ) {
						$response = [
							'copy_user_field' => false,
							'error'           => "Please provide both post ID and field name for the destination field.",
						];

						break;
					}

					// Check if the post ID exists. We cannot copy to a non existent post
					if ( $copy_post_field->post_exists() ) {

						// determines if the option "create field if it doesn't exists" is checked
						$opt_to_post_field_create = array_key_exists( 'opt_to_post_field_create', $form_data ) ? true : false;

						$response = [
							'copy_option' => true,
							'copied'      => false,
							'error'       => "There was an error, the option could not be copied.",
						];

						// check if the new option exists or we are asked to create it
						if ( $copy_post_field->post_meta_exists() || $opt_to_post_field_create ) {

							$copy_post_field->write( $current_option->get_current_value() );

							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $copy_post_field->post_meta_exists() && $current_option->get_current_value() == $copy_post_field->get_current_value() ) {
								$response = [
									'copy_option' => true,
									'copied'      => true,
								];
							}
						}
					} else {
						$response = [
							'copy_option' => false,
							'copied'      => false,
							'error'       => "The destination post does not exist.",
						];
					}


					break;

				default:
					break;
			}
		}

		wp_send_json_success( $response );
	}

	/**
	 * Copy user field.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function copy_user_field() {
		$this->can_respond();

		$response = [];

		$form_data = $this->get_form_data();

		$current_user_field_id   = sanitize_text_field( $form_data['current_user_field_id'] );
		$current_user_field_name = sanitize_text_field( $form_data['current_user_field_name'] );
		$current_user_field      = new User_Fields( $current_user_field_id, $current_user_field_name );

		// if the user to copy from doesn't exist then fail
		if ( ! $current_user_field->user_exists() ) {
			$response = [
				'copy_user_field' => false,
				'copied'          => false,
				'error'           => "The starting user does not exist.",
			];
		} else if ( ! $current_user_field->user_meta_exists() ) {
			$response = [
				'copy_user_field' => false,
				'copied'          => false,
				'error'           => "The starting user meta does not exist.",
			];
		} else if ( empty( $current_user_field->get_name() ) ) {
			$response = [
				'copy_user_field' => false,
				'copied'          => false,
				'error'           => "Please provide the field name for the starting option.",
			];
		} else {
			// Check where do we have to copy the field to
			switch ( $form_data['copy_user_field_to'] ) {
				case 'user field to user field':

					$userfield_to_user_field_id   = $form_data['userfield_to_user_field_id'];
					$userfield_to_user_field_name = sanitize_text_field( $form_data['userfield_to_user_field_name'] );
					$copy_user_field              = new User_Fields( $userfield_to_user_field_id, $userfield_to_user_field_name );

					if ( empty( $copy_user_field->get_name() ) || empty( $copy_user_field->get_user_id() ) ) {
						$response = [
							'copy_user_field' => false,
							'copied'          => false,
							'error'           => "Please provide both user ID and field name for the destination field.",
						];

						break;
					}

					// check if the user to copy to exists. If not then returns
					if ( ! $copy_user_field->user_exists() ) {
						$response = [
							'copy_user_field' => false,
							'copied'          => false,
							'error'           => "The destination user doesn't exist.",
						];

						break;
					}

					// checks if starting field and destination field are the same
					if ( $current_user_field->get_name() == $copy_user_field->get_name() && $current_user_field->get_user_id() == $copy_user_field->get_user_id() ) {
						$response = [
							'copy_user_field' => true,
							'copied'          => false,
							'error'           => "Starting field and destination field cannot be the same",
						];
					} else {

						// determines if the option "create field if it doesn't exists" is checked
						$userfield_to_user_field_create = array_key_exists( 'userfield_to_user_field_create', $form_data ) ? true : false;

						$response = [
							'copy_user_field' => true,
							'copied'          => false,
							'error'           => 'There was an error. The field could not be copied.',
						];

						// check if the new option exists or we are asked to create it
						if ( $copy_user_field->user_meta_exists() || $userfield_to_user_field_create ) {

							$copy_user_field->write( $current_user_field->get_current_value() );

							// if the new user field exists and the old content was copied correctly then mark it copied
							if ( $copy_user_field->user_meta_exists() && $current_user_field->get_current_value() == $copy_user_field->get_current_value() ) {
								$response = [
									'copy_user_field' => true,
									'copied'          => true,
								];
							}
						}
					}

					break;

				case 'user field to option':

					$userfield_to_option_name = sanitize_text_field( $form_data['userfield_to_option_name'] );
					$copy_option              = new Options_Fields( $userfield_to_option_name );

					if ( empty( $copy_option->get_name() ) ) {
						$response = [
							'copy_user_field' => false,
							'copied'          => false,
							'error'           => "Please provide field name for the destination field.",
						];

						break;
					}

					// determines if the option "create field if it doesn't exists" is checked
					$userfield_to_option_create = array_key_exists( 'userfield_to_option_create', $form_data ) ? true : false;

					$response = [
						'copy_user_field' => true,
						'copied'          => false,
						'error'           => "There was an error, the field could not be copied.",
					];

					// check if the new option exists or we are asked to create it
					if ( $copy_option->option_exists() || $userfield_to_option_create ) {

						$copy_option->write( $current_user_field->get_current_value() );

						// if the new option exists and the old content was copied correctly then mark it copied
						if ( $copy_option->option_exists() && $current_user_field->get_current_value() == $copy_option->get_current_value() ) {
							$response = [
								'copy_user_field' => true,
								'copied'          => true,
							];
						}
					}

					break;

				case 'user field to post field':

					$user_to_post_field_id   = sanitize_text_field( $form_data['userfield_to_post_field_id'] );
					$user_to_post_field_name = sanitize_text_field( $form_data['userfield_to_post_field_name'] );
					$copy_post_field         = new Post_Fields( $user_to_post_field_id, $user_to_post_field_name );

					if ( empty( $copy_post_field->get_name() ) || empty( $copy_post_field->get_post_id() ) ) {
						$response = [
							'copy_user_field' => false,
							'copied'          => false,
							'error'           => "Please provide both post ID and field name for the destination field",
						];

						break;
					}

					if ( ! $copy_post_field->post_exists() ) {
						$response = [
							'copy_user_field' => false,
							'copied'          => false,
							'error'           => "The destination post does not exist.",
						];
					}

					// determines if the option "create field if it doesn't exists" is checked
					$user_to_post_field_create = array_key_exists( 'userfield_to_post_field_create', $form_data ) ? true : false;

					$response = [
						'copy_user_field' => true,
						'copied'          => false,
						'error'           => "There was an error, the field could not be copied.",
					];

					// check if the new post meta exists or we are asked to create it
					if ( $copy_post_field->post_meta_exists() || $user_to_post_field_create ) {

						$copy_post_field->write( $current_user_field->get_current_value() );

						// if the new option exists and the old content was copied correctly then mark it copied
						if ( $copy_post_field->post_meta_exists() && $current_user_field->get_current_value() == $copy_post_field->get_current_value() ) {
							$response = [
								'copy_user_field' => true,
								'copied'          => true,
							];
						}
					}

					break;

				default:
					break;
			}
		}

		wp_send_json_success( $response );
	}

	/**
	 * Copy a post field.
	 *
	 * @since 3.0.0
	 *
	 * @return void
	 */
	public function copy_post_field() {
		$this->can_respond();

		$response = [];

		$form_data = $this->get_form_data();

		$current_post_field_id   = sanitize_text_field( $form_data['current_post_field_id'] );
		$current_post_field_name = sanitize_text_field( $form_data['current_post_field_name'] );
		$current_post_field      = new Post_Fields( $current_post_field_id, $current_post_field_name );

		// if the post to copy from doesn't exist then fail
		if ( ! $current_post_field->post_exists() ) {
			$response = [
				'copy_post_field' => false,
				'copied'          => false,
				'error'           => "The starting post does not exist.",
			];
		} else if ( ! $current_post_field->post_meta_exists() ) {
			$response = [
				'copy_post_field' => false,
				'copied'          => false,
				'error'           => "The starting post meta does not exist.",
			];
		} else if ( empty( $current_post_field->get_name() ) ) {
			$response = [
				'copy_post_field' => false,
				'copied'          => false,
				'error'           => "Please provide the field name for the starting post meta.",
			];
		} else {
			// Check where do we have to copy the field to
			switch ( $form_data['copy_post_field_to'] ) {
				case 'post field to post field':

					$postfield_to_post_field_id   = $form_data['postfield_to_post_field_id'];
					$postfield_to_post_field_name = sanitize_text_field( $form_data['postfield_to_post_field_name'] );
					$copy_post_field              = new Post_Fields( $postfield_to_post_field_id, $postfield_to_post_field_name );

					if ( empty( $copy_post_field->get_name() ) || empty( $copy_post_field->get_post_id() ) ) {
						$response = [
							'copy_post_field' => false,
							'copied'          => false,
							'error'           => "Please provide both post ID and field name for the destination field.",
						];

						break;
					}

					// check if the post to copy to exists. If not then returns
					if ( ! $copy_post_field->post_exists() ) {
						$response = [
							'copy_post_field' => false,
							'copied'          => false,
							'error'           => "The destination post doesn't exist.",
						];

						break;
					}

					if ( $current_post_field->get_name() == $copy_post_field->get_name() && $current_post_field->get_post_id() == $copy_post_field->get_post_id() ) {
						$response = [
							'copy_post_field' => true,
							'copied'          => false,
							'error'           => "Origin post and destination post cannot be the same.",
						];
					} else {

						// determines if the option "create field if it doesn't exists" is checked
						$postfield_to_post_field_create = array_key_exists( 'postfield_to_post_field_create', $form_data ) ? true : false;

						$response = [
							'copy_post_field' => true,
							'copied'          => false,
							'error'           => "There was an error. The field could not be copied.",
						];

						// check if the new post field exists or we are asked to create it
						if ( $copy_post_field->post_meta_exists() || $postfield_to_post_field_create ) {

							$copy_post_field->write( $current_post_field->get_current_value() );

							// if the new post field exists and the old content was copied correctly then mark it copied
							if ( $copy_post_field->post_meta_exists() && $current_post_field->get_current_value() == $copy_post_field->get_current_value() ) {
								$response = [
									'copy_post_field' => true,
									'copied'          => true,
								];
							}
						}
					}

					break;

				case 'post field to option':

					$postfield_to_option_name = sanitize_text_field( $form_data['postfield_to_option_name'] );
					$copy_option              = new Options_Fields( $postfield_to_option_name );

					if ( empty( $copy_option->get_name() ) ) {
						$response = [
							'copy_post_field' => false,
							'copied'          => false,
							'error'           => "Please provide the option name for the destination field",
						];

						break;
					}

					// determines if the option "create field if it doesn't exists" is checked
					$postfield_to_option_create = array_key_exists( 'postfield_to_option_create', $form_data ) ? true : false;

					$response = [
						'copy_post_field' => true,
						'copied'          => false,
						'error'           => "There was an error. The field could not be copied.",
					];

					// check if the new option exists or we are asked to create it
					if ( $copy_option->option_exists() || $postfield_to_option_create ) {

						$copy_option->write( $current_post_field->get_current_value() );

						// if the new option exists and the old content was copied correctly then mark it copied
						if ( $copy_option->option_exists() && $current_post_field->get_current_value() == $copy_option->get_current_value() ) {
							$response = [
								'copy_post_field' => true,
								'copied'          => true,
							];
						}
					}

					break;

				case 'post field to user field':

					$post_to_user_field_id   = sanitize_text_field( $form_data['postfield_to_user_field_id'] );
					$post_to_user_field_name = sanitize_text_field( $form_data['postfield_to_user_field_name'] );
					$copy_user_field         = new User_Fields( $post_to_user_field_id, $post_to_user_field_name );

					if ( empty( $copy_user_field->get_name() ) || empty( $copy_user_field->get_user_id() ) ) {
						$response = [
							'copy_post_field' => false,
							'copied'          => false,
							'error'           => "Please provide both user ID and field name for the destination field.",
						];

						break;
					}

					if ( ! $copy_user_field->user_exists() ) {
						$response = [
							'copy_post_field' => false,
							'error'           => "The destination user doesn't exist.",
						];

						break;
					}

					// determines if the option "create field if it doesn't exists" is checked
					$post_to_user_field_create = array_key_exists( 'postfield_to_user_field_create', $form_data ) ? true : false;

					$response = [
						'copy_post_field' => true,
						'copied'          => false,
						'error'           => "There was an error. The field could not be copied.",
					];

					// check if the current post meta exists
					if ( $current_post_field->post_meta_exists( $current_post_field_id, $current_post_field_name ) ) {

						// check if the new post meta exists or we are asked to create it
						if ( $copy_user_field->user_meta_exists() || $post_to_user_field_create ) {

							$copy_user_field->write( $current_post_field->get_current_value() );


							// if the new option exists and the old content was copied correctly then mark it copied
							if ( $copy_user_field->user_meta_exists() && $current_post_field->get_current_value() == $copy_user_field->get_current_value() ) {
								$response = [
									'copy_post_field' => true,
									'copied'          => true,
								];
							}
						}
					}

					break;

				default:
					break;
			}
		}

		wp_send_json_success( $response );
	}

	/**
	 * Returns the array containing the previous options.
	 *
	 * @since 3.0.0
	 *
	 * @return array Previous options.
	 *
	 * @return void
	 */
	public function get_previous_options() {

		/**
		 * This array contains the previous options
		 *
		 *    $previous_options = array (
		 *        'field_name'         => int,
		 *        'previous_value' => string,
		 *        'field_value'     => string,
		 *        'field_error'     => string,
		 *
		 *    );
		 *
		 */
		return get_option( 'dapre_cft_previous_options' );
	}

	/**
	 * Returns the array containing the previous user fields.
	 *
	 * @since 3.0.0
	 *
	 * @return array Previous user fields.
	 */
	public function get_previous_user_fields() {

		/**
		 * This array contains the previous options
		 *
		 *    previous_users = array (
		 *        'user_id'         => int,
		 *        'field_name'         => int,
		 *        'previous_value' => string,
		 *        'field_value'     => string,
		 *        'field_error'     => string,
		 *
		 *    );
		 *
		 */
		return get_option( 'dapre_cft_previous_user_fields' );
	}

	/**
	 * Returns the array containing the previous post fields
	 *
	 * @since 3.0.0
	 *
	 * @return array previous post fields
	 */
	public function get_previous_post_fields() {

		/**
		 * This array contains the previous options.
		 *
		 *    previous_users = array (
		 *        'post_id'          => int,
		 *        'field_name'      => int,
		 *        'previous_value'  => string,
		 *        'field_value'      => string,
		 *        'field_error'      => string,
		 *
		 *    );
		 *
		 */
		return get_option( 'dapre_cft_previous_post_fields' );
	}

	/**
	 * Updates the option array containing the previous options.
	 *
	 * @since 3.0.0
	 *
	 * @param  array $previous_options the array containing the previous options.
	 *
	 * @return void
	 */
	public function set_previous_options( $previous_options ) {
		update_option( 'dapre_cft_previous_options', $previous_options );
	}

	/**
	 * Updates the option array containing the previous user fields.
	 *
	 * @since 3.0.0
	 *
	 * @param  array $previous_user_fields The array containing the previous user fields.
	 *
	 * @return void
	 */
	public function set_previous_user_fields( $previous_user_fields ) {
		update_option( 'dapre_cft_previous_user_fields', $previous_user_fields );
	}

	/**
	 * Updates the option array containing the previous post fields.
	 *
	 * @since 3.0.0
	 *
	 * @param  array $previous_post_fields the array containing the previous post fields.
	 *
	 * @return void
	 */
	public function set_previous_post_fields( $previous_post_fields ) {
		update_option( 'dapre_cft_previous_post_fields', $previous_post_fields );
	}

	/**
	 * Returns true if the user has permission to respond; otherwise, it dies.
	 *
	 * @since 3.0.0
	 *
	 * @return bool True if the user has permission to respond.
	 */
	private function can_respond() {
		check_ajax_referer( 'custom_fields_tools', 'dapre_cft_nonce' );

		return true;
	}

	/**
	 * Returns the content of the form submitted.
	 *
	 * @since 3.0.0
	 *
	 * @return array $form_data The submitted form.
	 */
	private function get_form_data() {
		$form_data = [];

		$data = filter_input( INPUT_POST, "data" );

		parse_str( $data, $form_data );

		return $form_data;
	}
}