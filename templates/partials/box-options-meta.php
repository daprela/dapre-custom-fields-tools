<div class="c-optionsMetaHeaders">
	<div>
		<?php esc_html_e("Option Name","dapre-cft") ?>
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

foreach ( $previous_options as $non_esc_key=>$option ) {
// escape the key for safe use in html attributes
$key = esc_attr($non_esc_key);
?>
<div class="js-optionFieldDataRow c-optionField <?php echo esc_attr($option->get_row_error_class()) ?>" data-index="<?php echo $key ?>" >
	<div class="c-optionField__fieldName">
		<div class="c-optionField__fieldErrorContainer">
			<p class="js-fieldErrorMessage c-optionField__fieldErrorMessage <?php echo esc_attr($option->get_field_error_class()) ?>"><?php echo esc_html_e($option->get_error(), "dapre-cft") ?></p>
		</div>
		<input class="c-optionField__fieldInput" type="text" name="field_name[<?php echo $key ?>]" value="<?php echo esc_attr($option->get_name()) ?>" />
	</div>
	<div class="c-optionField__fieldActions">
		<label>
			<input class="js-fieldAction" type="radio" name="field_action[<?php echo $key ?>]" value="read"" checked />
			<?php esc_html_e("Read","dapre-cft") ?>
		</label>
		<label >
			<input class="js-fieldAction" type="radio" name="field_action[<?php echo $key ?>]" value="write"<?php echo esc_attr($option->get_disable_write()) ?> />
			<?php esc_html_e("Write","dapre-cft") ?>
		</label>
		<label>
			<input class="js-fieldAction" type="radio" name="field_action[<?php echo $key ?>]" value="delete" <?php echo esc_attr($option->get_disable_delete()) ?> />
			<?php esc_html_e("Delete","dapre-cft") ?>
		</label>
	</div>
	<div class="c-optionField__fieldValueToAdd">
		<div class="c-optionField__fieldValueOptions">
			<label class="c-optionField__fieldValueOptionLabel">
				<input class="js-fieldValueToAdd js-emptyArray" type="checkbox" name="empty_array[<?php echo $key ?>]" value="empty_array" data-index="<?php echo $key ?>" disabled="" />
				<p><?php esc_html_e("Add empty array","dapre-cft") ?></p>
			</label>
			<label class="c-optionField__fieldValueOptionLabel">
				<input class="js-fieldValueToAdd js-dateString" type="checkbox" name="date_string[<?php echo $key ?>]" value="date_string" data-index="<?php echo $key ?>" disabled="" />
				<p><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></p>
			</label>
		</div>
		<textarea class="js-metaFieldInputValue c-optionField__fieldValueToAddTextarea" name="field_value[<?php echo $key ?>]" rows="2" disabled="" ></textarea>
		<input class="js-metaFieldInputTimestampBackup" type="hidden" name="input-timestamp-backup-<?php echo $key ?>" value="" />
		<input class="js-metaFieldInputStringBackup" type="hidden" name="input-string-backup-<?php echo $key ?>" value="" />
	</div>
	<div class="c-optionField__fieldCurValue">
		<p class="js-fieldCurrentValue">
			<?php
			if ( is_array($option->get_current_value()) ) {
				print_r($option->get_current_value());
			} else {
				echo esc_html($option->get_current_value());
			}
			?>
		</p>
		<input class="js-fieldValueTimestampBackup" type="hidden" name="value-timestamp-backup-<?php echo $key ?>" value="" />
		<input class="js-fieldValueStringBackup" type="hidden" name="value-string-backup-<?php echo $key ?>" value="" />
	</div>
	<div class="c-optionField__fieldCurValueOptions">
		<label class="js-curValueDateToggle c-optionField__fieldCurValueOption <?php echo esc_attr($option->get_date_toggle()) ?>">
			<p><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></p>
			<input class="js-fieldDateStringCurValue" type="checkbox" name="date_string_show[<?php echo $key ?>]" value="" data-index="<?php echo $key ?>" />
		</label>
	</div>
	<div class="js-fieldPreviousValue c-optionField__fieldPrevValue">
		<?php
		if ( is_array($option->get_previous_value()) ) {
			print_r($option->get_previous_value());
		} else {
			echo esc_html($option->get_previous_value());
		}
		?>
	</div>
</div>
<?php } ?>
