<div class="c-metaFieldsHeaders">
	<div>
		<?php esc_html_e("Post ID","dapre-cft") ?>
	</div>
	<div>
		<?php esc_html_e("Field Name","dapre-cft") ?>
	</div>
	<div>
		<?php esc_html_e("Actions","dapre-cft") ?>
	</div>
	<div>
		<?php esc_html_e("Value to Add","dapre-cft") ?>
	</div>
	<div>
		<?php esc_html_e("Current Value","dapre-cft") ?>
	</div>
	<div>
		<?php esc_html_e("Current Value Options","dapre-cft") ?>
	</div>
	<div>
		<?php esc_html_e("Previous Value","dapre-cft") ?>
	</div>
</div>
<?php
foreach ( $previous_post_fields as $non_esc_key=>$post_field ) {
// escape the key for safe use in html attributes
$key = esc_attr($non_esc_key);
?>
<div class="js-postFieldsFieldDataRow_<?php echo $key ?> c-metaField <?php echo esc_attr($post_field->get_row_error_class()) ?>">
	<div class="c-metaField__fieldID">
		<input class="c-metaField__fieldIDInput" type="number" step="1" min="0" name="post_id[<?php echo $key ?>]" value="<?php echo esc_attr($post_field->get_post_id()) ?>">
	</div>
	<div class="c-metaField__fieldName">
		<div class="c-metaField__fieldErrorContainer">
			<p class="c-metaField__fieldErrorMessage <?php echo esc_attr($post_field->get_field_error_class()) ?>"><?php echo esc_html_e($post_field->get_error(), "dapre-cft") ?></p>
		</div>
		<input class="c-metaField__fieldNameInput" type="text" name="field_name[<?php echo $key ?>]" value="<?php echo esc_attr($post_field->get_name()) ?>" />
	</div>
	<div class="c-metaField__fieldActions">
		<label>
			<input type="radio" name="field_action[<?php echo $key ?>]" value="read" data-index="<?php echo $key ?>" checked />
			<?php esc_html_e("Read","dapre-cft") ?>
		</label>
		<label >
			<input type="radio" name="field_action[<?php echo $key ?>]" value="write" data-index="<?php echo $key ?>" <?php echo esc_attr($post_field->get_disable_write()) ?> />
			<?php esc_html_e("Write","dapre-cft") ?>
		</label>
		<label>
			<input type="radio" name="field_action[<?php echo $key ?>]" value="delete" data-index="<?php echo $key ?>" <?php echo esc_attr($post_field->get_disable_delete()) ?> />
			<?php esc_html_e("Delete","dapre-cft") ?>
		</label>
	</div>
	<div class="c-metaField__fieldValueToAdd">
		<div class="c-metaField__fieldValueOptions">
			<label class="c-metaField__fieldValueOptionLabel">
				<input class="js-postEmptyArray_<?php echo $key ?>" type="checkbox" name="empty_array[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" />
				<p><?php esc_html_e("Add empty array","dapre-cft") ?></p>
			</label>
			<label class="c-metaField__fieldValueOptionLabel">
				<input class="js-postDateString_<?php echo $key ?>" type="checkbox" name="date_string[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" />
				<p><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></p>
			</label>
		</div>
		<textarea class="js-postFieldInputValue_<?php echo $key ?> c-metaField__fieldValueToAddTextarea" name="field_value[<?php echo $key ?>]" rows="2" disabled="" ></textarea>
		<input class="js-postFieldInputTimestampBackup_<?php echo $key ?>" type="hidden" name="input-timestamp-backup-<?php echo $key ?>" value="" />
		<input class="js-postFieldInputStringBackup_<?php echo $key ?>" type="hidden" name="input-string-backup-<?php echo $key ?>" value="" />
	</div>
	<div class="c-metaField__fieldCurValue">
		<p class="js-postFieldValue_<?php echo $key ?>">
			<?php
			if ( is_array($post_field->get_current_value()) ) {
				print_r($post_field->get_current_value());
			} else {
				echo esc_html($post_field->get_current_value());
			}
			?>
		</p>
		<input class="js-postFieldValueTimestampBackup_<?php echo $key ?>" type="hidden" name="value-timestamp-backup-<?php echo $key ?>" value="" />
		<input class="js-postFieldValueStringBackup_<?php echo $key ?>" type="hidden" name="value-string-backup-<?php echo $key ?>" value="" />
	</div>
	<div class="c-metaField__fieldCurValueOptions">
		<label class="c-metaField__fieldCurValueOption <?php echo esc_attr($post_field->get_date_toggle()) ?>">
			<p><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></p>
			<input class="js-postDateStringCurValue_<?php echo $key ?>" type="checkbox" name="date_string_show[<?php echo $key ?>]" value="" data-index="<?php echo $key ?>" />
		</label>
	</div>
	<div class="c-metaField__fieldPrevValue">
		<?php
		if ( is_array($post_field->get_previous_value()) ) {
			print_r($post_field->get_previous_value());
		} else {
			echo esc_html($post_field->get_previous_value());
		}
		?>
	</div>
</div>
<?php } ?>