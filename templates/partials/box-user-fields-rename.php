<h3 class="c-userField__title hndle"><?php esc_html_e("Rename User Field","dapre-cft") ?></h3>
<div class ="inside">
	<div class="c-userFieldRename">
		<div class="c-userFieldRename__header c-userFieldRename__headerCurrentField">
			<?php esc_html_e("Current User Field","dapre-cft") ?>
		</div>
		<div class="c-userFieldRename__field c-userFieldRename__fieldCurrentField">
			<p class="c-userFieldRenameErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
			<label >
				<?php esc_html_e("User ID","dapre-cft") ?>
				<input class="js-userFieldRenameUserID c-userFieldRenameUserID small-text" type="number" name="old_user_field_id" />
			</label>
			<label >
				<?php esc_html_e("Field Name","dapre-cft") ?>
				<input class="js-userFieldRenameFieldName c-userFieldRenameFieldName regular-text" type="text" name="old_user_field_name" />
			</label>
		</div>
		<div class="c-userFieldRename__newFieldName <?php echo $row_error_class ?>">
			<div class="c-userFieldRename__header c-userFieldRename__headerNewFieldName">
				<?php esc_html_e("New Field Name","dapre-cft") ?>
			</div>
			<div class="c-userFieldRename__field c-userFieldRename__fieldNewFieldName">
				<input class="js-newUserFieldName regular-text" type="text" name="new_user_field_name" />
			</div>
		</div>
	</div>
	<div><input class="js-submitRenameUserField button button-primary" type="submit" name="rename_user_field" value="Rename"></div>
</div>
<div class="message-box message-ok user-fields-message-ok hide">
	<div class="fade-out"><?php esc_html_e("User field renamed","dapre-cft") ?></div>
</div>
<div class="message-box message-error user-fields-message-error hide">
	<div class="fade-out"><?php esc_html_e("There was an error. Post field not renamed","dapre-cft") ?></div>
</div>