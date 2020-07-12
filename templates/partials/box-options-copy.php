<h3 class="c-metaField__title hndle"><?php esc_html_e('Copy Option','dapre-cft') ?></h3>
<div class ="inside">
	<div class="c-copyContainer">
		<h4 class="c-copyFromTitle"><?php esc_html_e('Copy from option...', 'dapre-cft') ?></h4>
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("Current Option Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field has-errorField">
				<div class="c-metaField__fieldErrorContainer">
					<p class="c-metaField__errorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exists","dapre-cft") ?></p>
				</div>
				<input class="js-currentOptionName c-metaFieldName__inputField regular-text" type="text" name="current_option_name" />
			</div>
		</div>
		<div class="c-copyContainer__currentNameOptions">
			<div class="c-copyContainer__space"></div>
			<div class="js-copyOptionTo">
				<label>
					<?php esc_html_e('...to option', 'dapre-cft') ?>
					<input class="js-inputCopyOptionToOption" type="radio" name="copy_option_to" value="option" />
				</label>

				<label>
					<?php esc_html_e('...to user field','dapre-cft') ?>
					<input class="js-inputCopyOptionToUserField" type="radio" name="copy_option_to" value="user field" />
				</label>

				<label>
					<?php esc_html_e('...to post field','dapre-cft') ?>
					<input class="js-inputCopyOptionToPostField" type="radio" name="copy_option_to" value="post field" />
				</label>
			</div>
		</div>
	</div>
	<div class='js-optionToOption c-copyToOption'>
		<h4 class="c-copyToTitle"><?php esc_html_e('...to option','dapre-cft') ?></h4>
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("New Option Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field has-option">
				<input class="js-copyOptionToOptionName regular-text" type="text" name="opt_to_option_name" />
				<div class="c-metaField__fieldOptionContainer">
					<label>
						<input class="js-copyOptionToOptionCreate regular-text" type="checkbox" name="opt_to_option_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class='js-optionToUserField c-copyToUserField'>
		<h4 class="c-copyToUserTitle"><?php esc_html_e("...to user field","dapre-cft") ?></h4>
		<div class="c-metaFieldIdName" <?php echo $row_error_class ?>>
			<div class="c-metaFieldIdName__header">
				<?php esc_html_e("User Field","dapre-cft") ?>
			</div>
			<div class="c-metaFieldIdName__IdName has-option has-errorField">
				<div class="c-metaFieldIdName_errorContainer">
					<p class="c-metaFieldErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
				</div>
				<div class="c-metaFieldIdName_fieldContainer">
					<label class="c-metaFieldFieldID">
						<?php esc_html_e("User ID","dapre-cft") ?>
						<input class="js-optToUserFieldID c-copyToUserID small-text" type="number" name="opt_to_user_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-optToUserFieldName c-copyToUserName regular-text" type="text" name="opt_to_user_field_name" />
					</label>
				</div>
				<div class="c-metaFieldIdName_optionContainer">
					<label>
						<input class="js-optToUserFieldCreate regular-text" type="checkbox" name="opt_to_user_field_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class='js-optionToPostField c-copyToPostField'>
		<h4 class="c-copyToPostTitle"><?php esc_html_e("...to post field","dapre-cft") ?></h4>
		<div class="c-metaFieldIdName" <?php echo $row_error_class ?>>
			<div class="c-metaFieldIdName__header">
				<?php esc_html_e("Post Field","dapre-cft") ?>
			</div>
			<div class="c-metaFieldIdName__IdName has-option has-errorField">
				<div class="c-metaFieldIdName_errorContainer">
					<p class="c-metaFieldErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
				</div>
				<div class="c-metaFieldIdName_fieldContainer">
					<label class="c-metaFieldFieldID">
						<?php esc_html_e("Post ID","dapre-cft") ?>
						<input class="js-optToPostFieldID c-copyToPostID small-text" type="number" name="opt_to_post_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-optToPostFieldName c-copyToPostName regular-text" type="text" name="opt_to_post_field_name" />
					</label>
				</div>
				<div class="c-metaFieldIdName_optionContainer">
					<label>
						<input class="js-optToPostFieldCreate regular-text" type="checkbox" name="opt_to_post_field_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div><input class="js-submitCopyOption button button-primary" type="submit" name="copy_option" value="Copy"></div>
</div>