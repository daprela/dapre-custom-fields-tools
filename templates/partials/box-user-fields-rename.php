<h3 class="c-metaField__title hndle"><?php esc_html_e("Rename User Field","dapre-cft") ?></h3>
<div class ="inside">
	<div class="c-renameContainer">
		<div class="c-metaFieldIdName">
			<div class="c-metaFieldIdName__header">
				<?php esc_html_e("Current User Field","dapre-cft") ?>
			</div>
			<div class="c-metaFieldIdName__IdName">
				<div class="c-metaFieldIdName_fieldContainer">
					<label class="c-metaFieldFieldID">
						<?php esc_html_e("User ID","dapre-cft") ?>
						<input class="js-userFieldRenameUserID small-text" type="number" step="1" min="0" name="old_user_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-oldUserFieldName regular-text" type="text" name="old_user_field_name" />
					</label>
				</div>
			</div>
		</div>
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("New Field Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field">
				<input class="js-newUserFieldName regular-text" type="text" name="new_user_field_name" />
			</div>
		</div>
	</div>
	<div><input class="js-submitRenameUserField button button-primary" type="submit" name="rename_user_field" value="Rename"></div>
</div>