<table class="form-table" >
	<tbody>
		<tr>
			<th class="option-name">
				<?php esc_html_e("Option Name","dapre-cft") ?>
			</th>
			<th class="option-actions">
				<?php esc_html_e("Actions","dapre-cft") ?>
			</th>
			<th class="option-value-add">
				<?php esc_html_e("Value Options","dapre-cft") ?>
			</th>
			<th class="option-value-to-add">
				<?php esc_html_e("Value to Add","dapre-cft") ?>
			</th>
			<th class="option-previous-value">
				<?php esc_html_e("Previous Value","dapre-cft") ?>
			</th>
			<th class="option-value-options">
				<?php esc_html_e("Current Value Options","dapre-cft") ?>
			</th>
			<th class="option-current-value">
				<?php esc_html_e("Current Value","dapre-cft") ?>
			</th>
		</tr>

		<?php
		
		foreach ( $previous_options as $non_esc_key=>$option ) {
			// escape the key for safe use in html attributes
			$key = esc_attr($non_esc_key);
		?>
			<tr id="option_field_data_row_<?php echo $key ?>" class="user-data-row <?php echo esc_attr($option->get_row_error_class()) ?>">
				<td class="td option-name">
					<p class="<?php echo esc_attr($option->get_field_error_class()) ?>"><?php echo esc_html_e($option->get_error(), "dapre-cft") ?></p>
					<input id="option_field_name_<?php echo $key ?>" type="text" name="field_name[<?php echo $key ?>]" value="<?php echo esc_attr($option->get_name()) ?>" />
				</td>

				<td class="td option-actions">
					<table>
						<tr>
							<td class="td"><label for="option_action_read_<?php echo $key ?>"><?php esc_html_e("Read","dapre-cft") ?></label></td>
							<td class="td"><input id="option_action_read_<?php echo $key ?>" class="option_action_radio" type="radio" name="field_action[<?php echo $key ?>]" value="read" data-index="<?php echo $key ?>" checked /></td>
						</tr>
						<tr>
							<td class="td"><label for="option_action_write_<?php echo $key ?>"><?php esc_html_e("Write","dapre-cft") ?></label></td>
							<td class="td"><input id="option_action_write_<?php echo $key ?>" class="option_action_radio" type="radio" name="field_action[<?php echo $key ?>]" value="write" data-index="<?php echo $key ?>" <?php echo esc_attr($option->get_disable_write()) ?> /></td>
						</tr>
						<tr>
							<td class="td"><label for="option_action_delete_<?php echo $key ?>"><?php esc_html_e("Delete","dapre-cft") ?></label></td>
							<td class="td"><input id="option_action_delete_<?php echo $key ?>" class="option_action_radio" type="radio" name="field_action[<?php echo $key ?>]" value="delete" data-index="<?php echo $key ?>" <?php echo esc_attr($option->get_disable_delete()) ?> /></td>
						</tr>
					</table>
				</td>

				<td class="td option-value-option">
					<table>
						<tr>
							<td class="td"><label for="option_empty_array_<?php echo $key ?>"><?php esc_html_e("Add empty array","dapre-cft") ?></label></td>
							<td class="td"><input id="option_empty_array_<?php echo $key ?>" type="checkbox" name="empty_array[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" /></td>
						</tr>
						<tr>
							<td class="td"><label for="option_date_string_<?php echo $key ?>"><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></label></td>
							<td class="td"><input id="option_date_string_<?php echo $key ?>" type="checkbox" name="date_string[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" /></td>
						</tr>
					</table>
				</td>

				<td class="td option-value-to-add">
						<textarea id="option_field_input_value_<?php echo $key ?>" name="field_value[<?php echo $key ?>]" disabled=""></textarea>
						<input id="option_field_input_timestamp_backup_<?php echo $key ?>" type="hidden" name="input-timestamp-backup-<?php echo $key ?>" value="" />
						<input id="option_field_input_string_backup_<?php echo $key ?>"  type="hidden" name="input-string-backup-<?php echo $key ?>" value="" />
				</td>

				<td class="td option-previous-value">
					<?php 
						if ( is_array($option->get_previous_value()) ) {
							print_r($option->get_previous_value());
						} else {
							echo esc_html($option->get_previous_value());
						} 
					?>
				</td>

				<td class="td option-value-options">
					<table>
						<tr>
							<td class="td"><label class="<?php echo esc_attr($option->get_date_toggle()) ?>" for="option_date_string_show_<?php echo $key ?>"><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></label></td>
							<td class="td"><input id="option_date_string_show_<?php echo $key ?>" class="<?php echo esc_attr($option->get_date_toggle()) ?>" type="checkbox" name="date_string_show[<?php echo $key ?>]" value="" data-index="<?php echo $key ?>" /></td>
						</tr>
					</table>
				</td>

				<td class="td gdp-last-column option-current-value">
					<p id="option_field_value_<?php echo $key ?>">
						<?php 
						if ( is_array($option->get_current_value()) ) {
							print_r($option->get_current_value());
						} else {
							echo esc_html($option->get_current_value());
						} 
						?>
					</p>
					<input id="option_field_value_timestamp_backup_<?php echo $key ?>" type="hidden" name="value-timestamp-backup-<?php echo $key ?>" value="" />
					<input id="option_field_value_string_backup_<?php echo $key ?>" type="hidden" name="value-string-backup-<?php echo $key ?>" value="" />
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>