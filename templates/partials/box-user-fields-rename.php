<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Rename User Field","dapre-cft") ?></h3>
<div class ="inside">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e("Current User Field","dapre-cft") ?>
				</th>
				<td>
					<fieldset>
						<p class="<?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
						<label for="old_user_field_id"><?php esc_html_e("User ID","dapre-cft") ?></label>
						<input id="old-user-field-id" class="small-text" type="number" name="old_user_field_id" />
						<label for="old_user_field_name"><?php esc_html_e("Field Name","dapre-cft") ?></label>
						<input id="old-user-field-name" class="regular-text" type="text" name="old_user_field_name" />
					</fieldset>
				</td>
			</tr>
			<tr id="" class="user-data-row <?php echo $row_error_class ?>">
				<th scope="row">
					<?php esc_html_e("New User Field","dapre-cft") ?>
				</th>
				<td>
					<fieldset>
						<label for="new_user_field_name"><?php esc_html_e("New Field Name","dapre-cft") ?></label>
						<input id="new-user-field-name" class="regular-text" type="text" name="new_user_field_name" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<div><input id="rename-user-field-btn" class="button button-primary" type="submit" name="rename_user_field" value="Rename"></div>
</div>
<div class="message-box message-ok user-fields-message-ok hide">
	<div class="fade-out"><?php esc_html_e("User field renamed","dapre-cft") ?></div>
</div>
<div class="message-box message-error user-fields-message-error hide">
	<div class="fade-out"><?php esc_html_e("There was an error. Post field not renamed","dapre-cft") ?></div>
</div>