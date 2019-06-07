(function($) {
	'use strict';
	
	$(function() {
		
		$(document).ready(function() {
			
			//$('.half-circle-spinner').hide();	
			
		});
		
/******************************************************************************/
	
		/* AJAX functionality for options fields section */
		$('#submit-options').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#submit-options').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_submit_options_fields',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-options' ).serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					$("#options-section").empty();
					$("#options-section").append(response.data.html);
				}
				
				/* Re-enable the button and hide the spinner */
				$('#submit-options').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		
		$('#rename-option-btn').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#rename-option-btn').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_rename_option',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-options').serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					
					if (response.data.renamed) {
						$(".options-message-ok").show();
						$(".options-message-ok").fadeOut(2000);
						let oldOpt = document.querySelector('#old-option-name' );
						oldOpt.value = '';

						let newOpt = document.querySelector('#new-option-name' );
						newOpt.value = '';
					} else {
						$.colorbox( {
							html:"<h4>" + response.data.error + "</h4>", 
							width:"300px"
						});
					}
						
				}
				
				/* Re-enable the button and hide the spinner */
				$('#rename-option-btn').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		
		/* Copy option section */
		$("input[name='copy_option_to']").on('click', function(){
			if ( "copy_option_to_option" == $(this).attr("id") ) {
				$("#copy-option-to-user-field").slideUp("slow");
				$("#copy-option-to-post-field").slideUp("slow");
				$("#copy-option-to-option").slideDown("slow");
			}
			
			if ( "copy_option_to_user_field" == $(this).attr("id") ) {
				$("#copy-option-to-post-field").slideUp("slow");
				$("#copy-option-to-option").slideUp("slow");
				$("#copy-option-to-user-field").slideDown("slow");
			}
			
			if ( "copy_option_to_post_field" == $(this).attr("id") ) {
				$("#copy-option-to-user-field").slideUp("slow");
				$("#copy-option-to-option").slideUp("slow");
				$("#copy-option-to-post-field").slideDown("slow");
			}
				
		});
		
		$('#copy-option-btn').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#copy-option-btn').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_copy_option',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-options').serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					
					if (response.data.copied) {
						$(".option-copy-ok").show();
						$(".option-copy-ok").fadeOut(2000);
						let oldOpt = document.querySelector('#current_option_name' );
						oldOpt.value = '';

						/* Block copy option to option */
						let newOpt = document.querySelector('#opt_to_option_name' );
						newOpt.value = '';
						let createOption = document.querySelector('#opt_to_option_create' );
						createOption.checked = false;
						
						/* Block copy option to user field */
						let optToUserFieldID = document.querySelector('#opt_to_user_field_id' );
						optToUserFieldID.value = '';
						let optToUserFieldName = document.querySelector('#opt_to_user_field_name' );
						optToUserFieldName.value = '';
						let optToUserFieldCreate = document.querySelector('#opt_to_user_field_create' );
						optToUserFieldCreate.checked = false;
						
						/* Block copy option to post field */
						let optToPostFieldID = document.querySelector('#opt_to_post_field_id' );
						optToPostFieldID.value = '';
						let optToPostFieldName = document.querySelector('#opt_to_post_field_name' );
						optToPostFieldName.value = '';
						let optToPostFieldCreate = document.querySelector('#opt_to_post_field_create' );
						optToPostFieldCreate.checked = false;
						
					} else {
						$.colorbox( {
							html:"<h4>" + response.data.error + "</h4>", 
							width:"300px"
						});
					}
						
				}
				
				/* Re-enable the button and hide the spinner */
				$('#copy-option-btn').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		
/******************************************************************************/
		
		/* AJAX functionality for user fields section */
		$('#submit-user-fields').on('click', function(e) {
			
			$('#submit-user-fields').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			let data = {
						 action: 'dapre_submit_user_fields',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-user-fields').serialize()
			};
			
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					$("#user-fields-section").empty();
					$("#user-fields-section").append(response.data.html);
				}
					
				$('#submit-user-fields').attr("disabled", false);
				$('.half-circle-spinner').hide();
			});
		});
		
		$('#rename-user-field-btn').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#rename-user-field-btn').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_rename_user_field',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-user-fields').serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					
					if (response.data.renamed) {
						$(".user-fields-message-ok").show();
						$(".user-fields-message-ok").fadeOut(2000);
						let oldOpt = document.querySelector('#old-user-field-name' );
						oldOpt.value = '';
						let oldID = document.querySelector('#old-user-field-id' );
						oldID.value = 0;

						let newOpt = document.querySelector('#new-user-field-name' );
						newOpt.value = '';
					} else {
						$.colorbox( {
							html:"<h4>" + response.data.error + "</h4>", 
							width:"300px"
						});
					}
						
				}
				
				/* Re-enable the button and hide the spinner */
				$('#rename-user-field-btn').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		
		/* Copy user field section */
		$("input[name='copy_user_field_to']").on('click', function(){
			if ( "copy_user_field_to_user_field" == $(this).attr("id") ) {
				$("#copy-user-field-to-option").slideUp("slow");
				$("#copy-user-field-to-post-field").slideUp("slow");
				$("#copy-user-field-to-user-field").slideDown("slow");
			}
			
			if ( "copy_user_field_to_option" == $(this).attr("id") ) {
				$("#copy-user-field-to-post-field").slideUp("slow");
				$("#copy-user-field-to-user-field").slideUp("slow");
				$("#copy-user-field-to-option").slideDown("slow");
			}
			
			if ( "copy_user_field_to_post_field" == $(this).attr("id") ) {
				$("#copy-user-field-to-user-field").slideUp("slow");
				$("#copy-user-field-to-option").slideUp("slow");
				$("#copy-user-field-to-post-field").slideDown("slow");
			}
				
		});
		
		$('#copy-user-field-btn').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#copy-user-field-btn').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_copy_user_field',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-user-fields').serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					
					if (response.data.copied) {
						$(".user-field-copy-ok").show();
						$(".user-field-copy-ok").fadeOut(2000);
						let oldUserFieldID = document.querySelector('#current_user_field_id' );
						oldUserFieldID.value = '';
						let oldUserFieldName = document.querySelector('#current_user_field_name' );
						oldUserFieldName.value = '';

						/* Block copy user field to option */
						let newOpt = document.querySelector('#userfield_to_option_name' );
						newOpt.value = '';
						let createOption = document.querySelector('#userfield_to_option_create' );
						createOption.checked = false;
						
						/* Block copy user field to user field */
						let userFieldToUserFieldID = document.querySelector('#userfield_to_user_field_id' );
						userFieldToUserFieldID.value = '';
						let userFieldToUserFieldName = document.querySelector('#userfield_to_user_field_name' );
						userFieldToUserFieldName.value = '';
						let userFieldToUserFieldCreate = document.querySelector('#userfield_to_user_field_create' );
						userFieldToUserFieldCreate.checked = false;
						
						/* Block copy user field to post field */
						let userFieldToPostFieldID = document.querySelector('#userfield_to_post_field_id' );
						userFieldToPostFieldID.value = '';
						let userFieldToPostFieldName = document.querySelector('#userfield_to_post_field_name' );
						userFieldToPostFieldName.value = '';
						let userFieldToPostFieldCreate = document.querySelector('#userfield_to_post_field_create' );
						userFieldToPostFieldCreate.checked = false;
						
					} else {
						$.colorbox( {
							html:"<h4>" + response.data.error + "</h4>", 
							width:"300px"
						});
					}
				}
				
				/* Re-enable the button and hide the spinner */
				$('#copy-user-field-btn').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		/* End of AJAX for user field section */
		
/******************************************************************************/

		/* AJAX functionality for post fields section */
		$('#submit-post-fields').on('click', function(e) {
			
			$('#submit-post-fields').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			let data = {
						 action: 'dapre_submit_post_fields',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-post-fields').serialize()
			};
			
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					$("#post-fields-section").empty();
					$("#post-fields-section").append(response.data.html);
				}
				
				$('#submit-post-fields').attr("disabled", false);
				$('.half-circle-spinner').hide();
			});
		});
		
		$('#rename-post-field-btn').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#rename-post-field-btn').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_rename_post_field',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-post-fields').serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					
					if (response.data.renamed) {
						$(".post-fields-message-ok").show();
						$(".post-fields-message-ok").fadeOut(2000);
						let oldOpt = document.querySelector('#old-post-field-name' );
						oldOpt.value = '';
						let oldID = document.querySelector('#old-post-field-id' );
						oldID.value = 0;

						let newOpt = document.querySelector('#new-post-field-name' );
						newOpt.value = '';
					} else {
						$.colorbox( {
							html:"<h4>" + response.data.error + "</h4>", 
							width:"300px"
						});
					}
						
				}
				
				/* Re-enable the button and hide the spinner */
				$('#rename-post-field-btn').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		
		/* Copy post field section */
		$("input[name='copy_post_field_to']").on('click', function(){
			if ( "copy_post_field_to_user_field" == $(this).attr("id") ) {
				$("#copy-post-field-to-option").slideUp("slow");
				$("#copy-post-field-to-post-field").slideUp("slow");
				$("#copy-post-field-to-user-field").slideDown("slow");
			}
			
			if ( "copy_post_field_to_option" == $(this).attr("id") ) {
				$("#copy-post-field-to-post-field").slideUp("slow");
				$("#copy-post-field-to-user-field").slideUp("slow");
				$("#copy-post-field-to-option").slideDown("slow");
			}
			
			if ( "copy_post_field_to_post_field" == $(this).attr("id") ) {
				$("#copy-post-field-to-user-field").slideUp("slow");
				$("#copy-post-field-to-option").slideUp("slow");
				$("#copy-post-field-to-post-field").slideDown("slow");
			}
				
		});
		
		$('#copy-post-field-btn').on('click', function(e) {
			
			/* Disable the button and show the spinner */
			$('#copy-post-field-btn').attr("disabled", "disabled");
			$('.half-circle-spinner').show();

			e.preventDefault();
			
			/* Data to send back to the page */
			let data = {
						 action: 'dapre_copy_post_field',
				dapre_cft_nonce: DapreCftAjax.nonce,
						   data: $('#form-post-fields').serialize()
			};
			
			/* Send data to the server, get the response and change the HTML */
			$.post(DapreCftAjax.ajax_url, data, function(response) {
				
				if (response.success) {
					
					if (response.data.copied) {
						$(".post-field-copy-ok").show();
						$(".post-field-copy-ok").fadeOut(2000);
						let oldPostFieldID = document.querySelector('#current_post_field_id' );
						oldPostFieldID.value = '';
						let oldPostFieldName = document.querySelector('#current_post_field_name' );
						oldPostFieldName.value = '';

						/* Block copy post field to option */
						let newOpt = document.querySelector('#postfield_to_option_name' );
						newOpt.value = '';
						let createOption = document.querySelector('#postfield_to_option_create' );
						createOption.checked = false;
						
						/* Block copy post field to user field */
						let postFieldToUserFieldID = document.querySelector('#postfield_to_user_field_id' );
						postFieldToUserFieldID.value = '';
						let postFieldToUserFieldName = document.querySelector('#postfield_to_user_field_name' );
						postFieldToUserFieldName.value = '';
						let postFieldToUserFieldCreate = document.querySelector('#postfield_to_user_field_create' );
						postFieldToUserFieldCreate.checked = false;
						
						/* Block copy post field to post field */
						let postFieldToPostFieldID = document.querySelector('#postfield_to_post_field_id' );
						postFieldToPostFieldID.value = '';
						let postFieldToPostFieldName = document.querySelector('#postfield_to_post_field_name' );
						postFieldToPostFieldName.value = '';
						let postFieldToPostFieldCreate = document.querySelector('#postfield_to_post_field_create' );
						postFieldToPostFieldCreate.checked = false;
						
					} else {
						$.colorbox( {
							html:"<h4>" + response.data.error + "</h4>", 
							width:"300px"
						});
					}
				}
				
				/* Re-enable the button and hide the spinner */
				$('#copy-post-field-btn').attr("disabled", false);
				$('.half-circle-spinner').hide();
				
			});
		});
		/* End of AJAX for post field section */

/******************************************************************************/

		/* switch to tab 1 when clicked */
		$("#tab_1").on('click', function(e) {

			e.preventDefault();
			$("#tab_1").blur();
			
			/* activate clicked tab and make section visible */
			$("#tab_1").addClass("nav-tab-active");
			$("#options").removeClass("hide");
			
			/* deactivate other tabs and hides sections */
			$("#tab_2").removeClass("nav-tab-active");
			$("#user-fields").addClass("hide");
			$("#tab_3").removeClass("nav-tab-active");
			$("#post-fields").addClass("hide");
			
		});
		
		/* switch to tab 2 when clicked */
		$("#tab_2").on('click', function(e) {

			e.preventDefault();
			$("#tab_2").blur();
			
			/* activate clicked tab and make section visible */
			$("#tab_2").addClass("nav-tab-active");
			$("#user-fields").removeClass("hide");
			
			/* deactivate other tabs and hides sections */
			$("#tab_1").removeClass("nav-tab-active");
			$("#options").addClass("hide");
			$("#tab_3").removeClass("nav-tab-active");
			$("#post-fields").addClass("hide");
			
		});
		
		/* switch to tab 3 when clicked */
		$("#tab_3").on('click', function(e) {

			e.preventDefault();
			$("#tab_3").blur();
			
			/* activate clicked tab and make section visible */
			$("#tab_3").addClass("nav-tab-active");
			$("#post-fields").removeClass("hide");
			
			/* deactivate other tabs and hides sections */
			$("#tab_1").removeClass("nav-tab-active");
			$("#options").addClass("hide");
			$("#tab_2").removeClass("nav-tab-active");
			$("#user-fields").addClass("hide");
			
		});
	});
	
	$("#copy_option_to_option").change( function(){
		$("#copy-option-to-option").slideDown();
		$("#copy-option-to-user-field").slideUp();
		$("#copy-option-to-post-field").slideUp();
	});
	
}) (jQuery);

// manage the toggle action radio buttons and option checkboxes
function toggleOptionAction(element) {
	
	let index = element.dataset.index;
	let row = document.querySelector('#option_field_data_row_' + index );
	let content = document.querySelector('#option_field_input_value_' + index );
	let toggleDateCheckbox = document.querySelector('#option_date_string_' + index );
	let arrayCheckbox = document.querySelector('#option_empty_array_' + index );

	if ( element.type === 'checkbox' ) {
		if ( element.id === 'option_empty_array_' + index ) {
			// if the user checked the empty array checkbox then disable the toggle date and return
			if ( element.checked === true ) {
				toggleDateCheckbox.checked = false;
				content.disabled = true;
			} else if (element.checked === false) {
				content.disabled = false;
			}
		}

		/* toggle between date string and timestamp representation of the input field content */
		if ( element.id === 'option_date_string_' + index ) {
			content.disabled = false;
			arrayCheckbox.checked = false;
			// get the elements used to backup the values
			let dateTimestampBackup = document.querySelector('#option_field_input_timestamp_backup_' + index );
			let dateStringBackup = document.querySelector('#option_field_input_string_backup_' + index );

			// if it is a number interpret as a timestamp
			if( isNumber(content.value) ) {
				if ( content.value === dateTimestampBackup.getAttribute('value') ) {
					// if the value hasn't changed use the date string saved
					content.value = dateStringBackup.getAttribute('value');
				} else {
					// if the value has changed re-generate the date string
					let timestamp = content.value;
					dateTimestampBackup.setAttribute('value', timestamp);
					let dateInt = parseInt(timestamp);
					let myDate = new Date(dateInt);
					content.value = myDate.toUTCString();
					dateStringBackup.setAttribute('value', content.value);
				}
			} else if ( content.value !== null ) {
				if ( content.value === dateStringBackup.getAttribute('value') ) {
					// if the date string hasn't changed use the timestamp saved
					content.value = dateTimestampBackup.getAttribute('value');
				} else {
					// if the date string has changed re-generate the timestamp
					let myDate = new Date(content.value);
					if ( isNaN(myDate.valueOf()) ) {
						alert('Please use a valid string date');
						checkbox.checked = false;
					} else {
						let dateString = content.value;
						dateStringBackup.setAttribute('value', dateString);
						content.value = myDate.valueOf();
						dateTimestampBackup.setAttribute('value', content.value);
					}
				}
			}
		}

		/* toggle between date string and timestamp representation of the field content */
		if ( element.id === 'option_date_string_show_' + index ) {
			let fieldContent = document.querySelector('#option_field_value_' + index );

			// get the elements used to backup the values
			let dateTimestampBackup = document.querySelector('#option_field_value_timestamp_backup_' + index );
			let dateStringBackup = document.querySelector('#option_field_value_string_backup_' + index );

			// if it is a number interpret as a timestamp
			if ( isNumber(fieldContent.innerText) ) {
				if ( dateStringBackup.getAttribute('value') ) {
					// if there is a backup use it
					fieldContent.innerText = dateStringBackup.getAttribute('value');
				} else {
					let timestamp = fieldContent.innerText;

					// save the timestamp. Since the string is an approximation and loses the milliseconds 
					// the backup guarantees that we get back the original timestamp
					dateTimestampBackup.setAttribute('value', timestamp);
					let dateInt = parseInt(fieldContent.innerText);
					let myDate = new Date(dateInt);
					fieldContent.innerText = myDate.toUTCString();
					dateStringBackup.setAttribute('value', fieldContent.innerText);
				}
			} else {
				if ( dateTimestampBackup.getAttribute('value') ) {
					fieldContent.innerText = dateTimestampBackup.getAttribute('value');
				} else {
					let myDate = new Date(fieldContent.innerText);
					if ( isNaN(myDate.valueOf()) ) {
						alert('Please use a valid string date');
						checkbox.checked = false;
					} else {
						let dateString = content.innerText;
						dateStringBackup.setAttribute('value', dateString);
						fieldContent.innerText = myDate.valueOf();
						dateTimestampBackup.setAttribute('value', fieldContent.innerText);
					}
				}
			}
		}
	}

	if ( element.type === 'radio' ) {
		if ( element.value === 'read' ) {
			row.classList.add("read");
			row.classList.remove("write");
			row.classList.remove("delete");
			content.disabled = true;
			toggleDateCheckbox.disabled = true;
			arrayCheckbox.disabled = true;
		}

		if ( element.value === 'write' ) {
			row.classList.add("write");
			row.classList.remove("read");
			row.classList.remove("delete");
			content.disabled = false;
			toggleDateCheckbox.disabled = false;
			arrayCheckbox.disabled = false;
		}

		if ( element.value === 'delete' ) {
			row.classList.add("delete");
			row.classList.remove("write");
			row.classList.remove("read");
			content.disabled = true;
			toggleDateCheckbox.disabled = true;
			arrayCheckbox.disabled = true;
		}
	}
}

// manage the toggle action radio buttons
function toggleUserAction(element) {
	
	let index = element.dataset.index;
	let row = document.querySelector('#user_field_data_row_' + index );
	let content = document.querySelector('#user_field_input_value_' + index );
	let toggleDateCheckbox = document.querySelector('#user_date_string_' + index );
	let arrayCheckbox = document.querySelector('#user_empty_array_' + index );

	if ( element.type === 'checkbox' ) {
		if ( element.id === 'user_empty_array_' + index ) {
			// if the user checked the empty array checkbox then disable the toggle date and return
			if ( element.checked === true ) {
				toggleDateCheckbox.checked = false;
				content.disabled = true;
			} else if (element.checked === false) {
				content.disabled = false;
			}
		}

		/* toggle between date string and timestamp representation of the input field content */
		if ( element.id === 'user_date_string_' + index ) {
			content.disabled = false;
			arrayCheckbox.checked = false;
			// get the elements used to backup the values
			let dateTimestampBackup = document.querySelector('#user_field_input_timestamp_backup_' + index );
			let dateStringBackup = document.querySelector('#user_field_input_string_backup_' + index );

			// if it is a number interpret as a timestamp
			if( isNumber(content.value) ) {
				if ( content.value === dateTimestampBackup.getAttribute('value') ) {
					// if the value hasn't changed use the date string saved
					content.value = dateStringBackup.getAttribute('value');
				} else {
					// if the value has changed re-generate the date string
					let timestamp = content.value;
					dateTimestampBackup.setAttribute('value', timestamp);
					let dateInt = parseInt(timestamp);
					let myDate = new Date(dateInt);
					content.value = myDate.toUTCString();
					dateStringBackup.setAttribute('value', content.value);
				}
			} else if ( content.value !== null ) {
				if ( content.value === dateStringBackup.getAttribute('value') ) {
					// if the date string hasn't changed use the timestamp saved
					content.value = dateTimestampBackup.getAttribute('value');
				} else {
					// if the date string has changed re-generate the timestamp
					let myDate = new Date(content.value);
					if ( isNaN(myDate.valueOf()) ) {
						alert('Please use a valid string date');
						checkbox.checked = false;
					} else {
						let dateString = content.value;
						dateStringBackup.setAttribute('value', dateString);
						content.value = myDate.valueOf();
						dateTimestampBackup.setAttribute('value', content.value);
					}
				}
			}
		}

		/* toggle between date string and timestamp representation of the field content */
		if ( element.id === 'user_date_string_show_' + index ) {
			let fieldContent = document.querySelector('#user_field_value_' + index );

			// get the elements used to backup the values
			let dateTimestampBackup = document.querySelector('#user_field_value_timestamp_backup_' + index );
			let dateStringBackup = document.querySelector('#user_field_value_string_backup_' + index );

			// if it is a number interpret as a timestamp
			if ( isNumber(fieldContent.innerText) ) {
				if ( dateStringBackup.getAttribute('value') ) {
					// if there is a backup use it
					fieldContent.innerText = dateStringBackup.getAttribute('value');
				} else {
					let timestamp = fieldContent.innerText;

					// save the timestamp. Since the string is an approximation and loses the milliseconds
					// the backup guarantees that we get back the original timestamp
					dateTimestampBackup.setAttribute('value', timestamp);
					let dateInt = parseInt(fieldContent.innerText);
					let myDate = new Date(dateInt);
					fieldContent.innerText = myDate.toUTCString();
					dateStringBackup.setAttribute('value', fieldContent.innerText);
				}
			} else {
				if ( dateTimestampBackup.getAttribute('value') ) {
					fieldContent.innerText = dateTimestampBackup.getAttribute('value');
				} else {
					let myDate = new Date(fieldContent.innerText);
					if ( isNaN(myDate.valueOf()) ) {
						alert('Please use a valid string date');
						checkbox.checked = false;
					} else {
						let dateString = content.innerText;
						dateStringBackup.setAttribute('value', dateString);
						fieldContent.innerText = myDate.valueOf();
						dateTimestampBackup.setAttribute('value', fieldContent.innerText);
					}
				}
			}
		}
	}

	if ( element.type === 'radio' ) {
		if ( element.value === 'read' ) {
			row.classList.add("read");
			row.classList.remove("write");
			row.classList.remove("delete");
			content.disabled = true;
			toggleDateCheckbox.disabled = true;
			arrayCheckbox.disabled = true;
		}

		if ( element.value === 'write' ) {
			row.classList.add("write");
			row.classList.remove("read");
			row.classList.remove("delete");
			content.disabled = false;
			toggleDateCheckbox.disabled = false;
			arrayCheckbox.disabled = false;
		}

		if ( element.value === 'delete' ) {
			row.classList.add("delete");
			row.classList.remove("write");
			row.classList.remove("read");
			content.disabled = true;
			toggleDateCheckbox.disabled = true;
			arrayCheckbox.disabled = true;
		}
	}
}

// manage the toggle action radio buttons
function togglePostAction(element) {
	
	let index = element.dataset.index;
	let row = document.querySelector('#post_field_data_row_' + index );
	let content = document.querySelector('#post_field_input_value_' + index );
	let toggleDateCheckbox = document.querySelector('#post_date_string_' + index );
	let arrayCheckbox = document.querySelector('#post_empty_array_' + index );

	if ( element.type === 'checkbox' ) {
		if ( element.id === 'post_empty_array_' + index ) {
			// if the user checked the empty array checkbox then disable the toggle date and return
			if ( element.checked === true ) {
				toggleDateCheckbox.checked = false;
				content.disabled = true;
			} else if (element.checked === false) {
				content.disabled = false;
			}
		}

		/* toggle between date string and timestamp representation of the input field content */
		if ( element.id === 'post_date_string_' + index ) {
			content.disabled = false;
			arrayCheckbox.checked = false;
			// get the elements used to backup the values
			let dateTimestampBackup = document.querySelector('#post_field_input_timestamp_backup_' + index );
			let dateStringBackup = document.querySelector('#post_field_input_string_backup_' + index );

			// if it is a number interpret as a timestamp
			if( isNumber(content.value) ) {
				if ( content.value === dateTimestampBackup.getAttribute('value') ) {
					// if the value hasn't changed use the date string saved
					content.value = dateStringBackup.getAttribute('value');
				} else {
					// if the value has changed re-generate the date string
					let timestamp = content.value;
					dateTimestampBackup.setAttribute('value', timestamp);
					let dateInt = parseInt(timestamp);
					let myDate = new Date(dateInt);
					content.value = myDate.toUTCString();
					dateStringBackup.setAttribute('value', content.value);
				}
			} else if ( content.value !== null ) {
				if ( content.value === dateStringBackup.getAttribute('value') ) {
					// if the date string hasn't changed use the timestamp saved
					content.value = dateTimestampBackup.getAttribute('value');
				} else {
					// if the date string has changed re-generate the timestamp
					let myDate = new Date(content.value);
					if ( isNaN(myDate.valueOf()) ) {
						alert('Please use a valid string date');
						checkbox.checked = false;
					} else {
						let dateString = content.value;
						dateStringBackup.setAttribute('value', dateString);
						content.value = myDate.valueOf();
						dateTimestampBackup.setAttribute('value', content.value);
					}
				}
			}
		}

		/* toggle between date string and timestamp representation of the field content */
		if ( element.id === 'post_date_string_show_' + index ) {
			let fieldContent = document.querySelector('#post_field_value_' + index );

			// get the elements used to backup the values
			let dateTimestampBackup = document.querySelector('#post_field_value_timestamp_backup_' + index );
			let dateStringBackup = document.querySelector('#post_field_value_string_backup_' + index );

			// if it is a number interpret as a timestamp
			if ( isNumber(fieldContent.innerText) ) {
				if ( dateStringBackup.getAttribute('value') ) {
					// if there is a backup use it
					fieldContent.innerText = dateStringBackup.getAttribute('value');
				} else {
					let timestamp = fieldContent.innerText;

					// save the timestamp. Since the string is an approximation and loses the milliseconds
					// the backup guarantees that we get back the original timestamp
					dateTimestampBackup.setAttribute('value', timestamp);
					let dateInt = parseInt(fieldContent.innerText);
					let myDate = new Date(dateInt);
					fieldContent.innerText = myDate.toUTCString();
					dateStringBackup.setAttribute('value', fieldContent.innerText);
				}
			} else {
				if ( dateTimestampBackup.getAttribute('value') ) {
					fieldContent.innerText = dateTimestampBackup.getAttribute('value');
				} else {
					let myDate = new Date(fieldContent.innerText);
					if ( isNaN(myDate.valueOf()) ) {
						alert('Please use a valid string date');
						checkbox.checked = false;
					} else {
						let dateString = content.innerText;
						dateStringBackup.setAttribute('value', dateString);
						fieldContent.innerText = myDate.valueOf();
						dateTimestampBackup.setAttribute('value', fieldContent.innerText);
					}
				}
			}
		}
	}

	if ( element.type === 'radio' ) {
		if (element.value === 'read') {
			row.classList.add("read");
			row.classList.remove("write");
			row.classList.remove("delete");
			content.disabled = true;
			toggleDateCheckbox.disabled = true;
			arrayCheckbox.disabled = true;
		}

		if (element.value === 'write') {
			row.classList.add("write");
			row.classList.remove("read");
			row.classList.remove("delete");
			content.disabled = false;
			toggleDateCheckbox.disabled = false;
			arrayCheckbox.disabled = false;
		}

		if (element.value === 'delete') {
			row.classList.add("delete");
			row.classList.remove("write");
			row.classList.remove("read");
			content.disabled = true;
			toggleDateCheckbox.disabled = true;
			arrayCheckbox.disabled = true;
		}
	}
}

function isNumber (o) {
	return ! isNaN (o-0) && o !== null && o !== "" && o !== false;
}

/* 
 * Checks if the content of a field is an integer >0 
 * Useful to check if the content of an ID field is valid */
function isNormalInteger(str) {
    return /^([1-9]\d*)$/.test(str);
}

/*
 * Called when the action radio button in the options screen is clicked
 * 
 * @param {object} e The event object
 * @returns {undefined}
 */
let checkOptionEvent = function(e) {
	
	toggleOptionAction(e.target);
};

/*
 * Called when the action radio button in the users screen is clicked
 * 
 * @param {object} e The event object
 * @returns {undefined}
 */
let checkUserEvent = function(e) {
	
	toggleUserAction(e.target);
};

/*
 * Called when the action radio button in the posts screen is clicked
 * 
 * @param {object} e The event object
 * @returns {undefined}
 */
let checkPostEvent = function(e) {
	
	togglePostAction(e.target);
};

checkEvents = function() {
	
	let optionSection = document.getElementById('options-section');
	optionSection.addEventListener('change', checkOptionEvent, false);

	let userSection = document.getElementById('user-fields-section');
	userSection.addEventListener('change', checkUserEvent, false);
	
	let postSection = document.getElementById('post-fields-section');
	postSection.addEventListener('change', checkPostEvent, false);

	let dateToggle = document.getElementById('options-section');
	dateToggle.addEventListener('change', checkOptionEvent, false);
};

window.addEventListener('load', checkEvents, false);