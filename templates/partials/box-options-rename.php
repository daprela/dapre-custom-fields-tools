<h3 class="hndle" style="padding-left: 5px"><?php esc_html_e("Rename Option","dapre-cft") ?></h3>
<div class ="inside">
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e("Current Option Name","dapre-cft") ?>
				</th>
				<td>
					<p class="<?php echo $field_error_class ?>"><?php esc_html_e("This field does not exists","dapre-cft") ?></p>
					<input id="old-option-name" class="regular-text" type="text" name="old_option_name" />
				</td>
			</tr>
			<tr id="" class="user-data-row <?php echo $row_error_class ?>">
				<th scope="row">
					<?php esc_html_e("New Option Name","dapre-cft") ?>
				</th>
				<td>
					<input id="new-option-name" class="regular-text" type="text" name="new_option_name" />
				</td>
			</tr>
		</tbody>
	</table>
	<div><input id="rename-option-btn" class="button button-primary" type="submit" name="rename_option" value="Rename"></div>
</div>
<div class="message-box message-ok options-message-ok hide">
	<div class="fade-out"><?php esc_html_e("Option renamed","dapre-cft") ?></div>
</div>
<div class="message-box message-error options-message-error hide">
	<div class="fade-out"><?php esc_html_e("There was an error. Option not renamed","dapre-cft") ?></div>
</div>