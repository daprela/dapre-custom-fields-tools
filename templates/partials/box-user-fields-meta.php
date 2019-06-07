<table  class="form-table" >
	<tbody>
		<tr>
			<th class="post-id">
				<?php esc_html_e("User  ID","dapre-cft") ?>
			</th>
			<th class="post-field-name">
				<?php esc_html_e("Field Name","dapre-cft") ?>
			</th>
			<th class="post-actions">
				<?php esc_html_e("Actions","dapre-cft") ?>
			</th>
			<th class="post-value-add">
				<?php esc_html_e("Value Options","dapre-cft") ?>
			</th>
			<th class="post-value-to-add">
				<?php esc_html_e("Value to Add","dapre-cft") ?>
			</th>
			<th class="post-previous-value">
				<?php esc_html_e("Previous Value","dapre-cft") ?>
			</th>
			<th class="post-value-options">
				<?php esc_html_e("Current Value Options","dapre-cft") ?>
			</th>
			<th class="post-current-value">
				<?php esc_html_e("Current Value","dapre-cft") ?>
			</th>
		</tr>

		<?php 
		foreach ( $previous_user_fields as $non_esc_key=>$user_field ) {
			// escape the key for safe use in html attributes
			$key = esc_attr($non_esc_key);
		?>
			<tr id="user_field_data_row_<?php echo $key ?>" class="user-data-row <?php echo esc_attr($user_field->get_row_error_class()) ?>">

				<td class="td user-id">
					<input type="number" step="1" min="0" name="user_id[<?php echo $key ?>]" value="<?php echo esc_attr($user_field->get_user_id()) ?>" />
				</td>

				<td class="td field-name">
					<p class="<?php echo esc_attr($user_field->get_field_error_class()) ?>"><?php echo esc_html_e($user_field->get_error(), "dapre-cft") ?></p>
					<input id="user_field_name_<?php echo $key ?>" type="text" name="field_name[<?php echo $key ?>]" value="<?php echo esc_attr($user_field->get_name()) ?>" />
				</td>

				<td class="td user-actions">
					<table>
						<tr>
							<td class="td"><label for="user_action_read_<?php echo $key ?>"><?php esc_html_e("Read","dapre-cft") ?></label></td>
							<td class="td"><input id="user_action_read_<?php echo $key ?>" class="user_action_radio" type="radio" name="field_action[<?php echo $key ?>]" value="read" data-index="<?php echo $key ?>" checked /></td>
						</tr>
						<tr>
							<td class="td"><label for="user_action_write_<?php echo $key ?>"><?php esc_html_e("Write","dapre-cft") ?></label></td>
							<td class="td"><input id="user_action_write_<?php echo $key ?>" class="user_action_radio" type="radio" name="field_action[<?php echo $key ?>]" value="write" data-index="<?php echo $key ?>" <?php echo esc_attr($user_field->get_disable_write()) ?> /></td>
						</tr>
						<tr>
							<td class="td"><label for="user_action_delete_<?php echo $key ?>"><?php esc_html_e("Delete","dapre-cft") ?></label></td>
							<td class="td"><input id="user_action_delete_<?php echo $key ?>" class="user_action_radio" type="radio" name="field_action[<?php echo $key ?>]" value="delete" data-index="<?php echo $key ?>" <?php echo esc_attr($user_field->get_disable_delete()) ?> /></td>
						</tr>
					</table>
				</td>

				<td class="td user-value-add">
					<table>
						<tr>
							<td class="td"><label for="user_empty_array_<?php echo $key ?>"><?php esc_html_e("Add empty array","dapre-cft") ?></label></td>
							<td class="td"><input id="user_empty_array_<?php echo $key ?>" type="checkbox" name="empty_array[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" /></td>
						</tr>
						<tr>
							<td class="td"><label for="user_date_string_<?php echo $key ?>"><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></label></td>
							<td class="td"><input id="user_date_string_<?php echo $key ?>" type="checkbox" name="date_string[<?php echo $key ?>]" value="1" data-index="<?php echo $key ?>" disabled="" /></td>
						</tr>
					</table>
				</td>

				<td class="td value-to-add">
					<textarea id="user_field_input_value_<?php echo $key ?>" name="field_value[<?php echo $key ?>]" disabled=""></textarea>
					<input id="user_field_input_timestamp_backup_<?php echo $key ?>" type="hidden" name="input-timestamp-backup-<?php echo $key ?>" value="" />
					<input id="user_field_input_string_backup_<?php echo $key ?>"  type="hidden" name="input-string-backup-<?php echo $key ?>" value="" />
				</td>

				<td class="td previous-value">
					<?php 
						if ( is_array($user_field->get_previous_value()) ) {
							print_r($user_field->get_previous_value());
						} else {
							echo esc_html($user_field->get_previous_value());
						} 
					?>
				</td>

				<td class="td user-value-options">
					<table>
						<tr>
							<td class="td"><label class="<?php echo esc_attr($user_field->get_date_toggle()) ?>" for="user_date_string_show_<?php echo $key ?>"><?php esc_html_e("Toggle date string/timestamp","dapre-cft") ?></label></td>
							<td class="td"><input id="user_date_string_show_<?php echo $key ?>"  class="<?php echo esc_attr($user_field->get_date_toggle()) ?>" type="checkbox" name="date_string_show[<?php echo $key ?>]" value="" data-index="<?php echo $key ?>" /></td>
						</tr>
					</table>
				</td>

				<td class="td gdp-last-column current-value">
					<p id="user_field_value_<?php echo $key ?>">
						<?php 
						if ( is_array($user_field->get_current_value()) ) {
							print_r($user_field->get_current_value());
						} else {
							echo esc_html($user_field->get_current_value());
						} 
						?>
					</p>
					<input id="user_field_value_timestamp_backup_<?php echo $key ?>" type="hidden" name="value-timestamp-backup-<?php echo $key ?>" value="" />
					<input id="user_field_value_string_backup_<?php echo $key ?>" type="hidden" name="value-string-backup-<?php echo $key ?>" value="" />
				</td>
			</tr>
		<?php } ?>
	</tbody>
</table>