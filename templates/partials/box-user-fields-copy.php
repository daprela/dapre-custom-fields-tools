<h3 class="c-metaField__title hndle"><?php esc_html_e("Copy User Field","dapre-cft") ?></h3>
<div class ="inside">
	<div class="c-copyContainer">
		<h4 class="c-copyFromTitle"><?php esc_html_e("Copy from user field...","dapre-cft") ?></h4>
		<div class="c-metaFieldIdName">
			<div class="c-metaFieldIdName__header">
				<?php esc_html_e("Current User Field","dapre-cft") ?>
			</div>
			<div class="c-metaFieldIdName__IdName has-errorField">
				<div class="c-metaFieldIdName_errorContainer">
					<p class="c-metaFieldErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
				</div>
				<div class="c-metaFieldIdName_fieldContainer">
					<label class="c-metaFieldFieldID">
						<?php esc_html_e("User ID","dapre-cft") ?>
						<input class="js-userFieldCopyUserID small-text" type="number" name="current_user_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-userFieldCopyFieldName regular-text" type="text" name="current_user_field_name" />
					</label>
				</div>
			</div>
		</div>
		<div class="c-copyContainer__currentNameOptions">
			<div class="c-copyContainer__space"></div>
			<div>
				<label>
					<?php esc_html_e('...to option', 'dapre-cft') ?>
					<input class="js-copyUserFieldTo" type="radio" name="copy_user_field_to" value="user field to option" />
				</label>

				<label>
					<?php esc_html_e('...to user field','dapre-cft') ?>
					<input class="js-copyUserFieldTo" type="radio" name="copy_user_field_to" value="user field to user field" />
				</label>

				<label>
					<?php esc_html_e('...to post field','dapre-cft') ?>
					<input class="js-copyUserFieldTo" type="radio" name="copy_user_field_to" value="user field to post field" />
				</label>
			</div>
		</div>
	</div>
	<div class='js-copyUserFieldToUserField c-copyToOption is-hidden'>
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
						<input class="js-userFieldToUserFieldID c-copyToUserID small-text" type="number" name="userfield_to_user_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-userFieldToUserFieldName c-copyToUserName regular-text" type="text" name="userfield_to_user_field_name" />
					</label>
				</div>
				<div class="c-metaFieldIdName_optionContainer">
					<label>
						<input class="js-userFieldToUserFieldCreate regular-text" type="checkbox" name="userfield_to_user_field_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class='js-copyUserFieldToOption c-copyToUserField is-hidden'>
		<h4 class="c-copyToTitle"><?php esc_html_e('...to option','dapre-cft') ?></h4>
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("New Option Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field has-option">
				<input class="js-userFieldToOptionName regular-text" type="text" name="userfield_to_option_name" />
				<div class="c-metaField__fieldOptionContainer">
					<label>
						<input class="js-userFieldToOptionCreate regular-text" type="checkbox" name="userfield_to_option_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div class='js-copyOptionToPostField c-copyToPostField is-hidden'>
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
						<input class="js-userFieldToPostFieldID c-copyToPostID small-text" type="number" name="userfield_to_post_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-userFieldToPostFieldName c-copyToPostName regular-text" type="text" name="userfield_to_post_field_name" />
					</label>
				</div>
				<div class="c-metaFieldIdName_optionContainer">
					<label>
						<input class="js-userFieldToPostFieldCreate regular-text" type="checkbox" name="userfield_to_post_field_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
					</label>
				</div>
			</div>
		</div>
	</div>
	<div><input class="js-submitCopyUserField button button-primary" type="submit" name="copy_user_field" value="Copy"></div>
</div>