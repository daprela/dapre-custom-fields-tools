<h3 class="c-metaField__title hndle"><?php esc_html_e("Rename Option","dapre-cft") ?></h3>
<div class ="inside">
	<div class="c-renameContainer">
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("Current Option Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field has-errorField">
				<div class="c-metaField__fieldErrorContainer">
					<p class="c-metaField__errorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exists","dapre-cft") ?></p>
				</div>
				<input class="js-oldOptionName c-metaFieldName__inputField regular-text" type="text" name="old_option_name" />
			</div>
		</div>
		<div class="c-metaFieldName <?php echo $row_error_class ?>">
			<div class="c-metaFieldName__header">
				<?php esc_html_e("New Option Name","dapre-cft") ?>
			</div>
			<div class="c-metaFieldName__field">
				<input class="js-newOptionName regular-text" type="text" name="new_option_name" />
			</div>
		</div>
	</div>
	<div><input class="js-submitRenameOption button button-primary" type="submit" name="rename_option" value="Rename"></div>
</div>
<div class="js-optionRenameOK o-messageBox c-messageOK is-hidden">
	<div class="c-messageOK__fadeOut"><?php esc_html_e("Option renamed","dapre-cft") ?></div>
</div>
<div class="js-optionRenameError o-messageBox c-messageError is-hidden">
	<div class="c-messageError__fadeOut"><?php esc_html_e("There was an error. Option not renamed","dapre-cft") ?></div>
</div>