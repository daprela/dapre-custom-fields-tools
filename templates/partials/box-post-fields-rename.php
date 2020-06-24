<h3 class="c-metaField__title hndle"><?php esc_html_e("Rename Post Field","dapre-cft") ?></h3>
<div class ="inside">
	<div class="c-renameContainer">
		<div class="c-metaFieldIdName">
			<div class="c-metaFieldIdName__header">
				<?php esc_html_e("Current Post Field","dapre-cft") ?>
			</div>
			<div class="c-metaFieldIdName__IdName">
				<div class="c-metaFieldIdName_fieldContainer">
					<label class="c-metaFieldFieldID">
						<?php esc_html_e("Post ID","dapre-cft") ?>
						<input class="js-postFieldRenamePostID small-text" type="number" name="old_post_field_id" />
					</label>
					<label class="c-metaFieldFieldName">
						<?php esc_html_e("Field Name","dapre-cft") ?>
						<input class="js-postFieldRenameFieldName regular-text" type="text" name="old_post_field_name" />
					</label>
				</div>
			</div>
		</div>
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("New Field Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field">
				<input class="js-newPostFieldName regular-text" type="text" name="new_post_field_name" />
			</div>
		</div>
	</div>
	<div><input class="js-submitRenamePostField button button-primary" type="submit" name="rename_post_field" value="Rename"></div>
</div>