<h3 class="c-options__title hndle"><?php esc_html_e('Copy Option','dapre-cft') ?></h3>
<div class ="inside">
	<div class="c-copyContainer">
		<h4 class="c-copyFromTitle"><?php esc_html_e('Copy from option...', 'dapre-cft') ?></h4>
		<div class="c-copyContainer__currentNameHeader">
			<?php esc_html_e('Current Option Name','dapre-cft') ?>
		</div>
		<div class="c-copyContainer__currentNameField">
			<p class="c-CopyOptionErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e('This field does not exist','dapre-cft') ?></p>
			<input id="current_option_name" class="js-currentOptionName c-copyInput__currentNameField regular-text" type="text" name="current_option_name" />
		</div>
		<div class="c-copyContainer__currentNameSpace"></div>
		<div class="c-copyContainer__currentNameOptions">
			<label>
				<?php esc_html_e('...to option', 'dapre-cft') ?>
				<input class="js-copyOptionTo js-inputCopyOptionToOption" type="radio" name="copy_option_to" value="option to option" />
			</label>

			<label>
				<?php esc_html_e('...to user field','dapre-cft') ?>
				<input class="js-copyOptionTo js-inputCopyOptionToUserField" type="radio" name="copy_option_to" value="option to user field" />
			</label>

			<label>
				<?php esc_html_e('...to post field','dapre-cft') ?>
				<input class="js-copyOptionTo js-inputCopyOptionToPostField" type="radio" name="copy_option_to" value="option to post field" />
			</label>
		</div>
	</div>
	<div class='js-copyOptionToOption c-copyOptionToOption is-hidden'>
		<h4 class="c-copyToTitle"><?php esc_html_e('...to option','dapre-cft') ?></h4>
		<div class="c-copyToDestinationOption <?php echo $row_error_class ?>">
			<div class="c-copyOptionToOption__newOptionNameHeader">
				<?php esc_html_e('New Option Name','dapre-cft') ?>
			</div>
			<div class="c-copyOptionToOption__newOptionName">
				<input class="js-copyOptionToOptionName c-copyOptionToOption_newOptionName regular-text" type="text" name="opt_to_option_name" /><br>
				<label>
					<input class="js-copyOptionToOptionCreate c-copyOptionToOption_createOption regular-text" type="checkbox" name="opt_to_option_create" />
					<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
				</label>
			</div>
		</div>
	</div>

	<div class='js-copyOptionToUserField c-copyOptionToUserField is-hidden'>
		<h4 class="c-copyToUserTitle"><?php esc_html_e("...to user field","dapre-cft") ?></h4>
		<div class="c-copyToDestinationUser <?php echo $row_error_class ?>">
			<div class="c-copyOptionToUser__userFieldHeader">
				<?php esc_html_e("User Field","dapre-cft") ?>
			</div>
			<div class="c-copyOptionToUser__newUserField">
				<p class="c-CopyOptionToUserErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
				<label >
					<?php esc_html_e("User ID","dapre-cft") ?>
					<input class="js-optToUserFieldID c-copyOptionToUserID small-text" type="number" name="opt_to_user_field_id" />
				</label>
				<label >
					<?php esc_html_e("Field Name","dapre-cft") ?>
					<input class="js-optToUserFieldName c-copyOptionToUserName regular-text" type="text" name="opt_to_user_field_name" />
				</label>
				<label>
					<input class="js-optToUserFieldCreate c-copyOptionToUserOption regular-text" type="checkbox" name="opt_to_user_field_create" />
					<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
				</label>
			</div>
		</div>
	</div>
	<div class='js-copyOptionToPostField c-copyOptionToPostField is-hidden'>
		<h4 class="c-copyToPostTitle"><?php esc_html_e("...to post field","dapre-cft") ?></h4>
		<div class="c-copyToDestinationPost <?php echo $row_error_class ?>">
			<div class="c-copyOptionToPost__postFieldHeader">
				<?php esc_html_e("Post Field","dapre-cft") ?>
			</div>
			<div class="c-copyOptionToPost__newPostField">
				<p class="c-CopyOptionToPostErrorMessage <?php echo $field_error_class ?>"><?php esc_html_e("This field does not exist","dapre-cft") ?></p>
				<label>
					<?php esc_html_e("Post ID","dapre-cft") ?>
					<input class="js-optToPostFieldID c-copyOptionToPostID small-text" type="number" name="opt_to_post_field_id" />
				</label>
				<label >
					<?php esc_html_e("Field Name","dapre-cft") ?>
					<input class="js-optToPostFieldName c-copyOptionToPostName regular-text" type="text" name="opt_to_post_field_name" />
				</label>
				<label>
					<input class="js-optToPostFieldCreate c-copyOptionToPostOption regular-text" type="checkbox" name="opt_to_post_field_create" />
					<?php esc_html_e("Create field if it doesn't exist","dapre-cft") ?>
				</label>
			</div>
		</div>
	</div>
	<div><input class="js-submitCopyOption button button-primary" type="submit" name="copy_option" value="Copy"></div>
</div>
<div class="js-optionCopyOK o-messageBox c-messageOK is-hidden">
	<div class="c-messageOK__fadeOut"><?php esc_html_e("Option copied","dapre-cft") ?></div>
</div>
<div class="js-optionCopyError o-messageBox c-messageError is-hidden">
	<div class="c-messageError__fadeOut"><?php esc_html_e("There was an error. Option not copied","dapre-cft") ?></div>
</div>