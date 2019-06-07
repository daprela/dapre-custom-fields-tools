<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Rename Post Field","dapre-cft") ?></h3>
<div class ="inside">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e("Current Post Field","dapre-cft") ?>
				</th>
				<td>
					<fieldset>
						<p class="<?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
						<label for="old_post_field_id"><?php esc_html_e("Post ID","dapre-cft") ?></label>
						<input id="old-post-field-id" class="small-text" type="number" name="old_post_field_id" />
						<label for="old_post_field_name"><?php esc_html_e("Field Name","dapre-cft") ?></label>
						<input id="old-post-field-name" class="regular-text" type="text" name="old_post_field_name" />
					</fieldset>
				</td>
			</tr>
			<tr id="" class="post-data-row <?php echo $row_error_class ?>">
				<th scope="row">
					<?php esc_html_e("New Post Field","dapre-cft") ?>
				</th>
				<td>
					<fieldset>
						<label for="new_post_field_name"><?php esc_html_e("New Field Name","dapre-cft") ?></label>
						<input id="new-post-field-name" class="regular-text" type="text" name="new_post_field_name" />
					</fieldset>
				</td>
			</tr>
		</tbody>
	</table>
	<div><input id="rename-post-field-btn" class="button button-primary" type="submit" name="rename_post_field" value="Rename"></div>
</div>
<div class="message-box message-ok post-fields-message-ok hide">
	<div class="fade-out"><?php esc_html_e("Post field renamed","dapre-cft") ?></div>
</div>
<div class="message-box message-error post-fields-message-error hide">
	<div class="fade-out"><?php esc_html_e("There was an error. Post field not renamed","dapre-cft") ?></div>
</div>