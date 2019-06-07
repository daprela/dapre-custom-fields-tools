<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Copy User  Field","dapre-cft") ?></h3>
<div class ="inside">
	<h4><?php esc_html_e("Copy from user  field...","dapre-cft") ?></h4>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e("Current User Field","dapre-cft") ?>
				</th>
				<td>
					<p class="<?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
					<label for="current_user_field_id"><?php esc_html_e("User  ID","dapre-cft") ?></label>
					<input id="current_user_field_id" class="small-text" type="number" name="current_user_field_id" />
					<label for="current_user_field_name"><?php esc_html_e("Field Name","dapre-cft") ?></label>
					<input id="current_user_field_name" class="regular-text" type="text" name="current_user_field_name" />
				</td>
			</tr>
			<tr>
				<th scope="row"></th>
				<td>
					<label for="copy_user_field_to_user_field"><?php esc_html_e("...to user field","dapre-cft") ?></label>
					<input id="copy_user_field_to_user_field" type="radio" name="copy_user_field_to" value="user field to user field" />

					<label for="copy_user_field_to_option"><?php esc_html_e("...to option","dapre-cft") ?></label>
					<input id="copy_user_field_to_option" type="radio" name="copy_user_field_to" value="user field to option" />

					<label for="copy_user_field_to_post_field"><?php esc_html_e("...to post field","dapre-cft") ?></label>
					<input id="copy_user_field_to_post_field" type="radio" name="copy_user_field_to" value="user field to post field" />
				</td>
			</tr>
		</tbody>
	</table>
	<div id="copy-user-field-to-user-field" class='hide'>
		<h4><?php esc_html_e("...to user field","dapre-cft") ?></h4>
		<table class="form-table">
			<tbody>
				<tr id="" class="user-data-row <?php echo $row_error_class ?>">
					<th scope="row">
						<?php esc_html_e("User Field","dapre-cft") ?>
					</th>
					<td>
						<fieldset>
							<p class="<?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
							<label for="userfield_to_user_field_id"><?php esc_html_e("User ID","dapre-cft") ?></label>
							<input id="userfield_to_user_field_id" class="small-text" type="number" name="userfield_to_user_field_id" />
							<label for="userfield_to_user_field_name"><?php esc_html_e("Field Name","dapre-cft") ?></label>
							<input id="userfield_to_user_field_name" class="regular-text" type="text" name="userfield_to_user_field_name" /><br>
							<label for="userfield_to_user_field_create">
							<input id="userfield_to_user_field_create" class="regular-text" type="checkbox" name="userfield_to_user_field_create" />
							<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?></label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="copy-user-field-to-option" class='hide'>
		<h4><?php esc_html_e("...to option","dapre-cft") ?></h4>
		<table class="form-table">
			<tbody>
				<tr id="" class="user-data-row <?php echo $row_error_class ?>">
					<th scope="row">
						<?php esc_html_e("New Option Name","dapre-cft") ?>
					</th>
					<td>
						<input id="userfield_to_option_name" class="regular-text" type="text" name="userfield_to_option_name" /><br>
						<label for="userfield_to_option_create">
						<input id="userfield_to_option_create" class="regular-text" type="checkbox" name="userfield_to_option_create" />
						<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?></label>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="copy-user-field-to-post-field" class='hide'>
		<h4><?php esc_html_e("...to post field","dapre-cft") ?></h4>
		<table class="form-table">
			<tbody>
				<tr id="" class="user-data-row <?php echo $row_error_class ?>">
					<th scope="row">
						<?php esc_html_e("Post Field","dapre-cft") ?>
					</th>
					<td>
						<fieldset>
							<p class="<?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
							<label for="userfield_to_post_field_id"><?php esc_html_e("Post ID","dapre-cft") ?></label>
							<input id="userfield_to_post_field_id" class="small-text" type="number" name="userfield_to_post_field_id" />
							<label for="userfield_to_post_field_name"><?php esc_html_e("Field Name","dapre-cft") ?></label>
							<input id="userfield_to_post_field_name" class="regular-text" type="text" name="userfield_to_post_field_name" /><br>
							<label for="userfield_to_post_field_create">
							<input id="userfield_to_post_field_create" class="regular-text" type="checkbox" name="userfield_to_post_field_create" />
							<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?></label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div><input id="copy-user-field-btn" class="button button-primary" type="submit" name="copy_user_field" value="Copy"></div>
</div>
<div class="message-box message-ok user-field-copy-ok hide">
	<div class="fade-out"><?php esc_html_e("User field copied","dapre-cft") ?></div>
</div>
<div class="message-box message-error user-field-copy-error hide">
	<div class="fade-out"><?php esc_html_e("There was an error. User field not copied","dapre-cft") ?></div>
</div>