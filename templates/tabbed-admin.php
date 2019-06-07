<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://giuliodaprela.com
 * @since      1.0.0
 *
 * @package    Dapre_CFT
 * @subpackage Dapre_CFT/plugin-admin/templates/partials
 */
?>

<div class="wrap dapre-cft">
	<h1><?php esc_html_e("Custom Fields Tools","dapre-cft") ?></h1>
	<form method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<?php wp_nonce_field( 'dapre_cft_options', 'dapre_cft_options_verify' ); ?>
		
		<div class ="postbox">
			<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Reset page","dapre-cft") ?></h3>
			<div class ="inside">
				<p><?php esc_html_e("If the page gets stuck and doesn't load anymore reset it here","dapre-cft") ?></p>
				<input class="button button-primary" type="submit" name="reset_fields" value="Reset">
			</div>
		</div>
	</form>
	
	<form id="form-options" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<div id="options">
			<div class ="postbox">
				<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Options","dapre-cft") ?></h3>
				<div class ="inside">
					<div id="options-section">
				<?php

				/**
				 * Calls the box options meta
				 */
				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-meta.php');

				?>
					</div>
				<input id="submit-options" class="button button-primary" type="submit" name="submit_options" value="Read/Write Values">
				</div>
			</div>

			<div id="rename-option" class ="postbox">
				<?php

				/**
				 * calls the box to rename options
				 */
				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-rename.php');

				?>
			</div>

			<div id="copy-option" class ="postbox">
				<?php

				/**
				 * calls the box to copy options
				 */
				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-copy.php');
				?>

			</div>
		</div>
	</form>
	
	<form id="form-user-fields" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<div id="user-fields" class="hide">
			<div class ="postbox">
				<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("User Fields","dapre-cft") ?></h3>
				<div class ="inside">
					<div id="user-fields-section">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-meta.php');

				?>
					</div>
				<input id="submit-user-fields" class="button button-primary" type="submit" name="submit_user_fields" value="Read/Write Values">
				</div>
			</div>
			<div id="rename-user-field" class ="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-rename.php');

				?>
			</div>

			<div id="copy-user-field" class ="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-copy.php');

				?>
			</div>
		</div>
	</form>
	
	<form id="form-post-fields" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<div id="post-fields" class="hide">
			<div class ="postbox">
				<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Post Fields","dapre-cft") ?></h3>
				<div class ="inside">
					<div id="post-fields-section">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-meta.php');

				?>
					</div>
				<input id="submit-post-fields" class="button button-primary" type="submit" name="submit_post_fields" value="Read/Write Values">
				</div>
			</div>
			<div id="rename-post-field" class ="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-rename.php');

				?>
			</div>

			<div id="copy-post-field" class ="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-copy.php');

				?>
			</div>
		</div>
	</form>
	<div class="half-circle-spinner hide">
		<div class="circle circle-1"></div>
		<div class="circle circle-2"></div>
	</div>
</div>