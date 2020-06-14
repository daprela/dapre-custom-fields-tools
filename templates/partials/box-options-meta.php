<div class="c-optionsMetaHeaders">
	<div class="c-optionsMetaHeaders__title c-optionsMetaHeaders__name">
		<?php esc_html_e("Option Name","dapre-cft") ?>
	</div>
	<div class="c-optionsMetaHeaders__title c-optionsMetaHeaders__actions">
		<?php esc_html_e("Actions","dapre-cft") ?>
	</div>
	<div class="c-optionsMetaHeaders__title c-optionsMetaHeaders__valueToAdd">
		<?php esc_html_e("Value to Add","dapre-cft") ?>
	</div>
	<div class="c-optionsMetaHeaders__title c-optionsMetaHeaders__prevValue">
		<?php esc_html_e("Previous Value","dapre-cft") ?>
	</div>
	<div class="c-optionsMetaHeaders__title c-optionsMetaHeaders__curValueOptions">
		<?php esc_html_e("Current Value Options","dapre-cft") ?>
	</div>
	<div class="c-optionsMetaHeaders__title c-optionsMetaHeaders__curValue">
		<?php esc_html_e("Current Value","dapre-cft") ?>
	</div>
</div>

<?php

foreach ( $previous_options as $non_esc_key=>$option ) {
// escape the key for safe use in html attributes
$key = esc_attr($non_esc_key);
?>
<div class="js-optionFieldDataRow_<?php echo $key ?> c-optionField <?php echo esc_attr($option->get_row_error_class()) ?>">
	<div class="c-optionField__field--fieldName">
		<div class="c-optionField__field--errorContainer">
			<p class="c-optionField__field--errorMessage <?php echo esc_attr($option->get_field_error_class()) ?>"><?php echo esc_html_e($option->get_error(), "dapre-cft") ?></p>
		</div>
		<input class="c-optionField__field--input" type="text" name="field_name[<?php echo $key ?>]" value="<?php echo esc_attr($option->get_name()) ?>" />
	</div>
	<div class="c-optionField__field--actions">
		<div class="c-optionField__field--action">
			<label>
				<input class="option_action_read_<?php echo $key ?>" type="radio" name="field_action[<?php echo $key ?>]" value="read" data-index="<?php echo $key ?>" checked />
				<?php esc_html_e("Read","dapre-cft") ?>
			</label>
		</div>
		<div class="c-optionField__field--action">
			<label >
				<input type="radio" name="field_action[<?php echo $key ?>]" value="write" data-index="<?php echo $key ?>" <?php echo esc_attr($option->get_disable_write()) ?> />
				<?php esc_html_e("Write","dapre-cft") ?>
			</label>
		</div>
		<div class="c-optionField__field--action">
			<label>
				<input type="radio" name="field_action[<?php echo $key ?>]" value="delete" data-index="<?php echo $key ?>" <?php echo esc_attr($option->get_disable_delete()) ?> />
				<?php esc_html_e("Delete","dapre-cft") ?>
			</label>
		</div>
	</div>

	<div class="c-optionField__field--valueToAdd">
		<div class="c-optionField__field--valueOptions">
			<div class="c-optionField__field--valueOption">
				<label class="c-optionField__field--valueOptionLabel">
					<input class="js-optionEmptyArray_<?php echo $key ?>" type="checkbox" name="empty_array[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" />
					<p><?php esc_html_e("Add empty array","dapre-cft") ?></p>
				</label>
			</div>
			<div class="c-optionField__field--valueOption">
				<label class="c-optionField__field--valueOptionLabel">
					<input class="js-optionDateString_<?php echo $key ?>" type="checkbox" name="date_string[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" />
					<p><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></p>
				</label>
			</div>
		</div>
		<textarea class="js-optionFieldInputValue_<?php echo $key ?> c-optionField__field--valueToAddTextarea" name="field_value[<?php echo $key ?>]" rows="2" disabled="" ></textarea>
		<input class="js-optionFieldInputTimestampBackup_<?php echo $key ?>" type="hidden" name="input-timestamp-backup-<?php echo $key ?>" value="" />
		<input class="js-optionFieldInputStringBackup_<?php echo $key ?>" type="hidden" name="input-string-backup-<?php echo $key ?>" value="" />
	</div>
	<div class="c-optionField__field--prevValue">
		<?php
		if ( is_array($option->get_previous_value()) ) {
			print_r($option->get_previous_value());
		} else {
			echo esc_html($option->get_previous_value());
		}
		?>
	</div>
	<div class="c-optionField__field--curValueOptions">
		<label class="c-optionField__field--curValueOption <?php echo esc_attr($option->get_date_toggle()) ?>">
			<p><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></p>
			<input class="js-optionDateStringCurValue_<?php echo $key ?>" type="checkbox" name="date_string_show[<?php echo $key ?>]" value="" data-index="<?php echo $key ?>" />
		</label>
	</div>
	<div class="c-optionField__field--curValue">
		<p class="js-optionFieldValue_<?php echo $key ?>">
			<?php
			if ( is_array($option->get_current_value()) ) {
				print_r($option->get_current_value());
			} else {
				echo esc_html($option->get_current_value());
			}
			?>
		</p>
		<input class="js-optionFieldValueTimestampBackup_<?php echo $key ?>" type="hidden" name="value-timestamp-backup-<?php echo $key ?>" value="" />
		<input class="js-optionFieldValueStringBackup_<?php echo $key ?>" type="hidden" name="value-string-backup-<?php echo $key ?>" value="" />
	</div>
</div>
<?php } ?>
