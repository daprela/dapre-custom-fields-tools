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

<div class="wrap o-customFields">
	<h1><?php esc_html_e("Custom Fields Tools","dapre-cft") ?></h1>
	<form method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<?php wp_nonce_field( 'dapre_cft_options', 'dapre_cft_options_verify' ); ?>
		
		<div class="postbox">
			<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Reset page","dapre-cft") ?></h3>
			<div class="inside">
				<p><?php esc_html_e("If the page gets stuck and doesn't load anymore reset it here","dapre-cft") ?></p>
				<input class="button button-primary" type="submit" name="reset_fields" value="Reset">
			</div>
		</div>
	</form>
	
	<form class="js-optionsForm c-optionsForm" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<div class="c-options">
			<div class="postbox">
				<h3 class="c-metaField__title hndle"><?php esc_html_e("Options","dapre-cft") ?></h3>
				<div class="inside">
					<div id="optionsMetaSection" class="js-optionsMetaSection o-meta">
				<?php

				/**
				 * Calls the box options meta
				 */
//				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-meta.php');

				?>
					</div>
					<input class="js-submitOptions c-metaSubmitButton button button-primary" type="submit" name="submit_options" value="Read/Write Values">
				</div>
			</div>

			<div class="postbox">
				<?php

				/**
				 * calls the box to rename options
				 */
				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-rename.php');

				?>
			</div>

			<div class="postbox">
				<?php

				/**
				 * calls the box to copy options
				 */
				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-options-copy.php');
				?>

			</div>
		</div>
	</form>
	
	<form class="js-userFieldsForm c-metaFieldsForm is-hidden" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<div class="c-metaFields">
			<div class="postbox">
				<h3 class="c-metaField__title hndle"><?php esc_html_e("User Fields","dapre-cft") ?></h3>
				<div class="inside">
					<div class="js-userFieldsSection o-meta">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-meta.php');

				?>
					</div>
					<input class="js-submitUserFields c-metaSubmitButton button button-primary" type="submit" name="submit_user_fields" value="Read/Write Values">
				</div>
			</div>
			<div class="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-rename.php');

				?>
			</div>

			<div class="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-user-fields-copy.php');

				?>
			</div>
		</div>
	</form>
	
	<form class="js-postFieldsForm c-metaFieldsForm is-hidden" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8">
		<div class="c-metaFields">
			<div class="postbox">
				<h3 class="c-metaField__title hndle"><?php esc_html_e("Post Fields","dapre-cft") ?></h3>
				<div class="inside">
					<div class="js-postFieldsSection o-meta">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-meta.php');

				?>
					</div>
					<input class="js-submitPostFields c-metaSubmitButton button button-primary" type="submit" name="submit_post_fields" value="Read/Write Values">
				</div>
			</div>
			<div class="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-rename.php');

				?>
			</div>

			<div class="postbox">
				<?php

				require_once ( \dapre_cft\PLUGIN_DIR_PATH . 'templates/partials/box-post-fields-copy.php');

				?>
			</div>
		</div>
	</form>
	<div class="js-halfCircleSpinner o-customFields__halfCircleSpinner half-circle-spinner is-hidden">
		<div class="circle circle-1"></div>
		<div class="circle circle-2"></div>
	</div>
</div>