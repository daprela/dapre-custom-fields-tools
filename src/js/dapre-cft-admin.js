(function ($) {
  $(() => {
    $(document).ready(() => {

      /* $('.half-circle-spinner').hide(); */

    });

    /* **************************************************************************** */

    /* AJAX functionality for options fields section */
    $('.js-submitOptions').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitOptions').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_submit_options_fields',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-optionsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          $('.js-optionsMetaSection').empty();
          $('.js-optionsMetaSection').append(response.data.html);
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitOptions').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    $('.js-submitRenameOption').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitRenameOption').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_rename_option',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-optionsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          if (response.data.renamed) {
            $('.js-optionRenameOK').show();
            $('.js-optionRenameOK').fadeOut(2000);
            const oldOpt = document.querySelector('.js-oldOptionName');
            oldOpt.value = '';

            const newOpt = document.querySelector('.js-newOptionName');
            newOpt.value = '';
          } else {
            $.colorbox({
              html: `<h4>${response.data.error}</h4>`,
              width: '300px',
            });
          }
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitRenameOption').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    /* Copy option section */
    $('.js-copyOptionTo').on('click', () => {
      if ($('input[type=radio][name=copy_option_to]:checked').val() === 'option to option') {
        $('.js-copyOptionToUserField').slideUp('slow');
        $('.js-copyOptionToPostField').slideUp('slow');
        $('.js-copyOptionToOption').slideDown('slow');
      }

      if ($('input[type=radio][name=copy_option_to]:checked').val() === 'option to user field') {
        console.log('option to user field');
        $('.js-copyOptionToPostField').slideUp('slow');
        $('.js-copyOptionToOption').slideUp('slow');
        $('.js-copyOptionToUserField').slideDown('slow');
      }

      if ($('input[type=radio][name=copy_option_to]:checked').val() === 'option to post field') {
        $('.js-copyOptionToUserField').slideUp('slow');
        $('.js-copyOptionToOption').slideUp('slow');
        $('.js-copyOptionToPostField').slideDown('slow');
      }
    });

    $('.js-submitCopyOption').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitCopyOption').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_copy_option',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-optionsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          if (response.data.copied) {
            $('.js-optionCopyOK').show();
            $('.js-optionCopyOK').fadeOut(2000);
            const oldOpt = document.querySelector('.js-currentOptionName');
            oldOpt.value = '';

            /* Block copy option to option */
            const newOpt = document.querySelector('.js-copyOptionToOptionName');
            newOpt.value = '';
            const createOption = document.querySelector('.js-copyOptionToOptionCreate');
            createOption.checked = false;

            /* Block copy option to user field */
            const optToUserFieldID = document.querySelector('.js-optToUserFieldID');
            optToUserFieldID.value = '';
            const optToUserFieldName = document.querySelector('.js-optToUserFieldName');
            optToUserFieldName.value = '';
            const optToUserFieldCreate = document.querySelector('.js-optToUserFieldCreate');
            optToUserFieldCreate.checked = false;

            /* Block copy option to post field */
            const optToPostFieldID = document.querySelector('.js-optToPostFieldID');
            optToPostFieldID.value = '';
            const optToPostFieldName = document.querySelector('.js-optToPostFieldName');
            optToPostFieldName.value = '';
            const optToPostFieldCreate = document.querySelector('.js-optToPostFieldCreate');
            optToPostFieldCreate.checked = false;
          } else {
            $.colorbox({
              html: `<h4>${response.data.error}</h4>`,
              width: '300px',
            });
          }
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitCopyOption').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    /** *************************************************************************** */

    /* AJAX functionality for user fields section */
    $('.js-submitUserFields').on('click', (e) => {
      $('.js-submitUserFields').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      const data = {
        action: 'dapre_submit_user_fields',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-userFieldsForm').serialize(),
      };

      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        console.log(response);
        if (response.success) {
          $('.js-userFieldsSection').empty();
          $('.js-userFieldsSection').append(response.data.html);
        }

        $('.js-submitUserFields').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    $('.js-submitRenameUserField').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitRenameUserField').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_rename_user_field',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-userFieldsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          if (response.data.renamed) {
            $('.user-fields-message-ok').show();
            $('.user-fields-message-ok').fadeOut(2000);
            const oldOpt = document.querySelector('.js-userFieldRenameFieldName');
            oldOpt.value = '';
            const oldID = document.querySelector('.js-userFieldRenameUserID');
            oldID.value = 0;

            const newOpt = document.querySelector('.js-newUserFieldName');
            newOpt.value = '';
          } else {
            $.colorbox({
              html: `<h4>${response.data.error}</h4>`,
              width: '300px',
            });
          }
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitRenameUserField').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    /* Copy user field section */
    $('.js-copyUserFieldTo').on('click', () => {
      if ($('input[type=radio][name=copy_user_field_to]:checked').val() === 'user field to option') {
        $('.js-copyUserFieldToUserField').slideUp('slow');
        $('.js-copyOptionToPostField').slideUp('slow');
        $('.js-copyUserFieldToOption').slideDown('slow');
      }

      if ($('input[type=radio][name=copy_user_field_to]:checked').val() === 'user field to user field') {
        $('.js-copyUserFieldToOption').slideUp('slow');
        $('.js-copyOptionToPostField').slideUp('slow');
        $('.js-copyUserFieldToUserField').slideDown('slow');
      }

      if ($('input[type=radio][name=copy_user_field_to]:checked').val() === 'user field to post field') {
        $('.js-copyUserFieldToUserField').slideUp('slow');
        $('.js-copyUserFieldToOption').slideUp('slow');
        $('.js-copyOptionToPostField').slideDown('slow');
      }
    });

    $('.js-submitCopyUserField').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitCopyUserField').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_copy_user_field',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-userFieldsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          if (response.data.copied) {
            $('.user-field-copy-ok').show();
            $('.user-field-copy-ok').fadeOut(2000);
            const oldUserFieldID = document.querySelector('.js-userFieldCopyUserID');
            oldUserFieldID.value = '';
            const oldUserFieldName = document.querySelector('.js-userFieldCopyFieldName');
            oldUserFieldName.value = '';

            /* Block copy user field to option */
            const newOpt = document.querySelector('.js-userFieldToOptionName');
            newOpt.value = '';
            const createOption = document.querySelector('.js-userFieldToOptionCreate');
            createOption.checked = false;

            /* Block copy user field to user field */
            const userFieldToUserFieldID = document.querySelector('.js-userFieldToUserFieldID');
            userFieldToUserFieldID.value = '';
            const userFieldToUserFieldName = document.querySelector('.js-userFieldToUserFieldName');
            userFieldToUserFieldName.value = '';
            const userFieldToUserFieldCreate = document.querySelector('.js-userFieldToUserFieldCreate');
            userFieldToUserFieldCreate.checked = false;

            /* Block copy user field to post field */
            const userFieldToPostFieldID = document.querySelector('.js-userFieldToPostFieldID');
            userFieldToPostFieldID.value = '';
            const userFieldToPostFieldName = document.querySelector('.js-userFieldToPostFieldName');
            userFieldToPostFieldName.value = '';
            const userFieldToPostFieldCreate = document.querySelector('.js-userFieldToPostFieldCreate');
            userFieldToPostFieldCreate.checked = false;
          } else {
            $.colorbox({
              html: `<h4>${response.data.error}</h4>`,
              width: '300px',
            });
          }
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitCopyUserField').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });
    /* End of AJAX for user field section */

    /** *************************************************************************** */

    /* AJAX functionality for post fields section */
    $('.js-submitPostFields').on('click', (e) => {
      $('.js-submitPostFields').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      const data = {
        action: 'dapre_submit_post_fields',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-postFieldsForm').serialize(),
      };

      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          $('.js-postFieldsSection').empty();
          $('.js-postFieldsSection').append(response.data.html);
        }

        $('.js-submitPostFields').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    $('.js-submitRenamePostField').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitRenamePostField').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_rename_post_field',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-postFieldsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          if (response.data.renamed) {
            $('.post-fields-message-ok').show();
            $('.post-fields-message-ok').fadeOut(2000);
            const oldOpt = document.querySelector('.js-postFieldRenameFieldName');
            oldOpt.value = '';
            const oldID = document.querySelector('.js-postFieldRenamePostID');
            oldID.value = 0;

            const newOpt = document.querySelector('.js-newPostFieldName');
            newOpt.value = '';
          } else {
            $.colorbox({
              html: `<h4>${response.data.error}</h4>`,
              width: '300px',
            });
          }
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitRenamePostField').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });

    /* Copy post field section */
    $('.js-copyPostFieldTo').on('click', () => {
      if ($('input[type=radio][name=copy_post_field_to]:checked').val() === 'post field to user field') {
        $('.js-copyPostFieldToOption').slideUp('slow');
        $('.js-copyOptionToPostField').slideUp('slow');
        $('.js-copyPostFieldToUserField').slideDown('slow');
      }

      if ($('input[type=radio][name=copy_post_field_to]:checked').val() === 'post field to option') {
        $('.js-copyOptionToPostField').slideUp('slow');
        $('.js-copyPostFieldToUserField').slideUp('slow');
        $('.js-copyPostFieldToOption').slideDown('slow');
      }

      if ($('input[type=radio][name=copy_post_field_to]:checked').val() === 'post field to post field') {
        $('.js-copyPostFieldToUserField').slideUp('slow');
        $('.js-copyPostFieldToOption').slideUp('slow');
        $('.js-copyOptionToPostField').slideDown('slow');
      }
    });

    $('.js-submitCopyPostField').on('click', (e) => {
      /* Disable the button and show the spinner */
      $('.js-submitCopyPostField').attr('disabled', 'disabled');
      $('.half-circle-spinner').show();

      e.preventDefault();

      /* Data to send back to the page */
      const data = {
        action: 'dapre_copy_post_field',
        // eslint-disable-next-line no-undef
        dapre_cft_nonce: DapreCftAjax.nonce,
        data: $('.js-postFieldsForm').serialize(),
      };

      /* Send data to the server, get the response and change the HTML */
      // eslint-disable-next-line no-undef
      $.post(DapreCftAjax.ajax_url, data, (response) => {
        if (response.success) {
          if (response.data.copied) {
            $('.post-field-copy-ok').show();
            $('.post-field-copy-ok').fadeOut(2000);
            const oldPostFieldID = document.querySelector('.js-postFieldCopyPostID');
            oldPostFieldID.value = '';
            const oldPostFieldName = document.querySelector('.js-postFieldCopyFieldName');
            oldPostFieldName.value = '';

            /* Block copy post field to option */
            const newOpt = document.querySelector('.js-postFieldToOptionName');
            newOpt.value = '';
            const createOption = document.querySelector('.js-postFieldToOptionCreate');
            createOption.checked = false;

            /* Block copy post field to user field */
            const postFieldToUserFieldID = document.querySelector('.js-postFieldToUserFieldID');
            postFieldToUserFieldID.value = '';
            const postFieldToUserFieldName = document.querySelector('.js-postFieldToUserFieldName');
            postFieldToUserFieldName.value = '';
            const postFieldToUserFieldCreate = document.querySelector('.js-postFieldToUserFieldCreate');
            postFieldToUserFieldCreate.checked = false;

            /* Block copy post field to post field */
            const postFieldToPostFieldID = document.querySelector('.js-postFieldToPostFieldID');
            postFieldToPostFieldID.value = '';
            const postFieldToPostFieldName = document.querySelector('.js-postFieldToPostFieldName');
            postFieldToPostFieldName.value = '';
            const postFieldToPostFieldCreate = document.querySelector('.js-postFieldToPostFieldCreate');
            postFieldToPostFieldCreate.checked = false;
          } else {
            $.colorbox({
              html: `<h4>${response.data.error}</h4>`,
              width: '300px',
            });
          }
        }

        /* Re-enable the button and hide the spinner */
        $('.js-submitCopyPostField').attr('disabled', false);
        $('.half-circle-spinner').hide();
      });
    });
    /* End of AJAX for post field section */

    /* **************************************************************************** */

    /* switch to tab 1 when clicked */
    $('.js-tab_1').on('click', (e) => {
      e.preventDefault();
      $('.js-tab_1').blur();

      /* activate clicked tab and make section visible */
      $('.js-tab_1').addClass('nav-tab-active');
      $('.js-optionsForm').removeClass('is-hidden');

      /* deactivate other tabs and hides sections */
      $('.js-tab_2').removeClass('nav-tab-active');
      $('.js-userFieldsForm').addClass('is-hidden');
      $('.js-tab_3').removeClass('nav-tab-active');
      $('.js-postFieldsForm').addClass('is-hidden');
    });

    /* switch to tab 2 when clicked */
    $('.js-tab_2').on('click', (e) => {
      e.preventDefault();
      $('.js-tab_2').blur();

      /* activate clicked tab and make section visible */
      $('.js-tab_2').addClass('nav-tab-active');
      $('.js-userFieldsForm').removeClass('is-hidden');

      /* deactivate other tabs and hides sections */
      $('.js-tab_1').removeClass('nav-tab-active');
      $('.js-optionsForm').addClass('is-hidden');
      $('.js-tab_3').removeClass('nav-tab-active');
      $('.js-postFieldsForm').addClass('is-hidden');
    });

    /* switch to tab 3 when clicked */
    $('.js-tab_3').on('click', (e) => {
      e.preventDefault();
      $('.js-tab_3').blur();

      /* activate clicked tab and make section visible */
      $('.js-tab_3').addClass('nav-tab-active');
      $('.js-postFieldsForm').removeClass('is-hidden');

      /* deactivate other tabs and hides sections */
      $('.js-tab_1').removeClass('nav-tab-active');
      $('.js-optionsForm').addClass('is-hidden');
      $('.js-tab_2').removeClass('nav-tab-active');
      $('.js-userFieldsForm').addClass('is-hidden');
    });
  });

  $('.js-inputCopyOptionToOption').change(() => {
    $('.js-copyOptionToOption').slideDown();
    $('.js-copyOptionToUserField').slideUp();
    $('.js-copyOptionToPostField').slideUp();
  });
}(jQuery));

function isNumber(o) {
  // eslint-disable-next-line no-restricted-globals
  return !isNaN(o - 0) && o !== null && o !== '' && o !== false;
}

/*
 * Checks if the content of a field is an integer >0
 * Useful to check if the content of an ID field is valid */
// eslint-disable-next-line no-unused-vars
function isNormalInteger(str) {
  return /^([1-9]\d*)$/.test(str);
}

// manage the toggle action radio buttons and option checkboxes
function toggleOptionAction(element) {
  const { index } = element.dataset;
  const row = document.querySelector(`.js-optionFieldDataRow_${index}`);
  const content = document.querySelector(`.js-optionFieldInputValue_${index}`);
  const toggleDateCheckbox = document.querySelector(`.js-optionDateString_${index}`);
  const arrayCheckbox = document.querySelector(`.js-optionEmptyArray_${index}`);

  if (element.type === 'checkbox') {
    if (element.classList.contains(`js-optionEmptyArray_${index}`)) {
      // if the user checked the empty array checkbox then disable the toggle date and return
      if (element.checked === true) {
        toggleDateCheckbox.checked = false;
        content.disabled = true;
      } else if (element.checked === false) {
        content.disabled = false;
      }
    }

    /* toggle between date string and timestamp representation of the input field content */
    if (element.classList.contains(`js-optionDateString_${index}`)) {
      content.disabled = false;
      arrayCheckbox.checked = false;
      // get the elements used to backup the values
      const dateTimestampBackup = document.querySelector(`.js-optionFieldInputTimestampBackup_${index}`);
      const dateStringBackup = document.querySelector(`.js-optionFieldInputStringBackup_${index}`);

      // if it is a number interpret as a timestamp
      if (isNumber(content.value)) {
        if (content.value === dateTimestampBackup.getAttribute('value')) {
          // if the value hasn't changed use the date string saved
          content.value = dateStringBackup.getAttribute('value');
        } else {
          // if the value has changed re-generate the date string
          const timestamp = content.value;
          dateTimestampBackup.setAttribute('value', timestamp);
          const dateInt = parseInt(timestamp, 10);
          const myDate = new Date(dateInt);
          content.value = myDate.toUTCString();
          dateStringBackup.setAttribute('value', content.value);
        }
      } else if (content.value !== null) {
        if (content.value === dateStringBackup.getAttribute('value')) {
          // if the date string hasn't changed use the timestamp saved
          content.value = dateTimestampBackup.getAttribute('value');
        } else {
          // if the date string has changed re-generate the timestamp
          const myDate = new Date(content.value);
          // eslint-disable-next-line no-restricted-globals
          if (isNaN(myDate.valueOf())) {
            alert('Please use a valid string date');
            toggleDateCheckbox.checked = false;
          } else {
            const dateString = content.value;
            dateStringBackup.setAttribute('value', dateString);
            content.value = myDate.valueOf();
            dateTimestampBackup.setAttribute('value', content.value);
          }
        }
      }
    }

    /* toggle between date string and timestamp representation of the field content */
    if (element.classList.contains(`js-optionDateStringCurValue_${index}`)) {
      const fieldContent = document.querySelector(`.js-optionFieldValue_${index}`);

      // get the elements used to backup the values
      const dateTimestampBackup = document.querySelector(`.js-optionFieldValueTimestampBackup_${index}`);
      const dateStringBackup = document.querySelector(`.js-optionFieldValueStringBackup_${index}`);

      // if it is a number interpret as a timestamp
      if (isNumber(fieldContent.innerText)) {
        if (dateStringBackup.getAttribute('value')) {
          // if there is a backup use it
          fieldContent.innerText = dateStringBackup.getAttribute('value');
        } else {
          const timestamp = fieldContent.innerText;

          // save the timestamp. Since the string is an approximation and loses the milliseconds
          // the backup guarantees that we get back the original timestamp
          dateTimestampBackup.setAttribute('value', timestamp);
          const dateInt = parseInt(fieldContent.innerText, 10);
          const myDate = new Date(dateInt);
          fieldContent.innerText = myDate.toUTCString();
          dateStringBackup.setAttribute('value', fieldContent.innerText);
        }
      } else if (dateTimestampBackup.getAttribute('value')) {
        fieldContent.innerText = dateTimestampBackup.getAttribute('value');
      } else {
        const myDate = new Date(fieldContent.innerText);
        // eslint-disable-next-line no-restricted-globals
        if (isNaN(myDate.valueOf())) {
          alert('Please use a valid string date');
          toggleDateCheckbox.checked = false;
        } else {
          const dateString = content.innerText;
          dateStringBackup.setAttribute('value', dateString);
          fieldContent.innerText = myDate.valueOf();
          dateTimestampBackup.setAttribute('value', fieldContent.innerText);
        }
      }
    }
  }

  if (element.type === 'radio') {
    if (element.value === 'read') {
      row.classList.add('-color-white');
      row.classList.remove('-color-blue');
      row.classList.remove('-color-orange');
      content.disabled = true;
      toggleDateCheckbox.disabled = true;
      arrayCheckbox.disabled = true;
    }

    if (element.value === 'write') {
      row.classList.add('-color-blue');
      row.classList.remove('-color-white');
      row.classList.remove('-color-orange');
      content.disabled = false;
      toggleDateCheckbox.disabled = false;
      arrayCheckbox.disabled = false;
    }

    if (element.value === 'delete') {
      row.classList.add('-color-orange');
      row.classList.remove('-color-blue');
      row.classList.remove('-color-white');
      content.disabled = true;
      toggleDateCheckbox.disabled = true;
      arrayCheckbox.disabled = true;
    }
  }
}

// manage the toggle action radio buttons
function toggleUserAction(element) {
  const { index } = element.dataset;
  const row = document.querySelector(`.js-userFieldsFieldDataRow_${index}`);
  const content = document.querySelector(`.js-userFieldInputValue_${index}`);
  const toggleDateCheckbox = document.querySelector(`.js-userDateString_${index}`);
  const arrayCheckbox = document.querySelector(`.js-userEmptyArray_${index}`);

  if (element.type === 'checkbox') {
    if (element.classList.contains(`js-userEmptyArray_${index}`)) {
      // if the user checked the empty array checkbox then disable the toggle date and return
      if (element.checked === true) {
        toggleDateCheckbox.checked = false;
        content.disabled = true;
      } else if (element.checked === false) {
        content.disabled = false;
      }
    }

    /* toggle between date string and timestamp representation of the input field content */
    if (element.classList.contains(`js-userDateString_${index}`)) {
      content.disabled = false;
      arrayCheckbox.checked = false;
      // get the elements used to backup the values
      const dateTimestampBackup = document.querySelector(`.js-userFieldInputTimestampBackup_${index}`);
      const dateStringBackup = document.querySelector(`.js-userFieldInputStringBackup_${index}`);

      // if it is a number interpret as a timestamp
      if (isNumber(content.value)) {
        if (content.value === dateTimestampBackup.getAttribute('value')) {
          // if the value hasn't changed use the date string saved
          content.value = dateStringBackup.getAttribute('value');
        } else {
          // if the value has changed re-generate the date string
          const timestamp = content.value;
          dateTimestampBackup.setAttribute('value', timestamp);
          const dateInt = parseInt(timestamp, 10);
          const myDate = new Date(dateInt);
          content.value = myDate.toUTCString();
          dateStringBackup.setAttribute('value', content.value);
        }
      } else if (content.value !== null) {
        if (content.value === dateStringBackup.getAttribute('value')) {
          // if the date string hasn't changed use the timestamp saved
          content.value = dateTimestampBackup.getAttribute('value');
        } else {
          // if the date string has changed re-generate the timestamp
          const myDate = new Date(content.value);
          // eslint-disable-next-line no-restricted-globals
          if (isNaN(myDate.valueOf())) {
            alert('Please use a valid string date');
            toggleDateCheckbox.checked = false;
          } else {
            const dateString = content.value;
            dateStringBackup.setAttribute('value', dateString);
            content.value = myDate.valueOf();
            dateTimestampBackup.setAttribute('value', content.value);
          }
        }
      }
    }

    /* toggle between date string and timestamp representation of the field content */
    if (element.classList.contains(`js-userDateStringCurValue_${index}`)) {
      const fieldContent = document.querySelector(`.js-userFieldValue_${index}`);

      // get the elements used to backup the values
      const dateTimestampBackup = document.querySelector(`.js-userFieldValueTimestampBackup_${index}`);
      const dateStringBackup = document.querySelector(`.js-userFieldValueStringBackup_${index}`);

      // if it is a number interpret as a timestamp
      if (isNumber(fieldContent.innerText)) {
        if (dateStringBackup.getAttribute('value')) {
          // if there is a backup use it
          fieldContent.innerText = dateStringBackup.getAttribute('value');
        } else {
          const timestamp = fieldContent.innerText;

          // save the timestamp. Since the string is an approximation and loses the milliseconds
          // the backup guarantees that we get back the original timestamp
          dateTimestampBackup.setAttribute('value', timestamp);
          const dateInt = parseInt(fieldContent.innerText, 10);
          const myDate = new Date(dateInt);
          fieldContent.innerText = myDate.toUTCString();
          dateStringBackup.setAttribute('value', fieldContent.innerText);
        }
      } else if (dateTimestampBackup.getAttribute('value')) {
        fieldContent.innerText = dateTimestampBackup.getAttribute('value');
      } else {
        const myDate = new Date(fieldContent.innerText);
        // eslint-disable-next-line no-restricted-globals
        if (isNaN(myDate.valueOf())) {
          alert('Please use a valid string date');
          toggleDateCheckbox.checked = false;
        } else {
          const dateString = content.innerText;
          dateStringBackup.setAttribute('value', dateString);
          fieldContent.innerText = myDate.valueOf();
          dateTimestampBackup.setAttribute('value', fieldContent.innerText);
        }
      }
    }
  }

  if (element.type === 'radio') {
    if (element.value === 'read') {
      row.classList.add('-color-white');
      row.classList.remove('-color-blue');
      row.classList.remove('-color-orange');
      content.disabled = true;
      toggleDateCheckbox.disabled = true;
      arrayCheckbox.disabled = true;
    }

    if (element.value === 'write') {
      row.classList.add('-color-blue');
      row.classList.remove('-color-white');
      row.classList.remove('-color-orange');
      content.disabled = false;
      toggleDateCheckbox.disabled = false;
      arrayCheckbox.disabled = false;
    }

    if (element.value === 'delete') {
      row.classList.add('-color-orange');
      row.classList.remove('-color-blue');
      row.classList.remove('-color-white');
      content.disabled = true;
      toggleDateCheckbox.disabled = true;
      arrayCheckbox.disabled = true;
    }
  }
}

// manage the toggle action radio buttons
function togglePostAction(element) {
  const { index } = element.dataset;
  const row = document.querySelector(`.js-postFieldsFieldDataRow_${index}`);
  const content = document.querySelector(`.js-postFieldInputValue_${index}`);
  const toggleDateCheckbox = document.querySelector(`.js-postDateString_${index}`);
  const arrayCheckbox = document.querySelector(`.js-postEmptyArray_${index}`);

  if (element.type === 'checkbox') {
    if (element.classList.contains(`js-postEmptyArray_${index}`)) {
      // if the user checked the empty array checkbox then disable the toggle date and return
      if (element.checked === true) {
        toggleDateCheckbox.checked = false;
        content.disabled = true;
      } else if (element.checked === false) {
        content.disabled = false;
      }
    }

    /* toggle between date string and timestamp representation of the input field content */
    if (element.classList.contains(`js-postDateString_${index}`)) {
      content.disabled = false;
      arrayCheckbox.checked = false;
      // get the elements used to backup the values
      const dateTimestampBackup = document.querySelector(`.js-postFieldInputTimestampBackup_${index}`);
      const dateStringBackup = document.querySelector(`.js-postFieldInputStringBackup_${index}`);

      // if it is a number interpret as a timestamp
      if (isNumber(content.value)) {
        if (content.value === dateTimestampBackup.getAttribute('value')) {
          // if the value hasn't changed use the date string saved
          content.value = dateStringBackup.getAttribute('value');
        } else {
          // if the value has changed re-generate the date string
          const timestamp = content.value;
          dateTimestampBackup.setAttribute('value', timestamp);
          const dateInt = parseInt(timestamp, 10);
          const myDate = new Date(dateInt);
          content.value = myDate.toUTCString();
          dateStringBackup.setAttribute('value', content.value);
        }
      } else if (content.value !== null) {
        if (content.value === dateStringBackup.getAttribute('value')) {
          // if the date string hasn't changed use the timestamp saved
          content.value = dateTimestampBackup.getAttribute('value');
        } else {
          // if the date string has changed re-generate the timestamp
          const myDate = new Date(content.value);
          // eslint-disable-next-line no-restricted-globals
          if (isNaN(myDate.valueOf())) {
            alert('Please use a valid string date');
            toggleDateCheckbox.checked = false;
          } else {
            const dateString = content.value;
            dateStringBackup.setAttribute('value', dateString);
            content.value = myDate.valueOf();
            dateTimestampBackup.setAttribute('value', content.value);
          }
        }
      }
    }

    /* toggle between date string and timestamp representation of the field content */
    if (element.classList.contains(`js-postDateStringCurValue_${index}`)) {
      const fieldContent = document.querySelector(`.js-postFieldValue_${index}`);

      // get the elements used to backup the values
      const dateTimestampBackup = document.querySelector(`.js-postFieldValueTimestampBackup_${index}`);
      const dateStringBackup = document.querySelector(`.js-postFieldValueStringBackup_${index}`);

      // if it is a number interpret as a timestamp
      if (isNumber(fieldContent.innerText)) {
        if (dateStringBackup.getAttribute('value')) {
          // if there is a backup use it
          fieldContent.innerText = dateStringBackup.getAttribute('value');
        } else {
          const timestamp = fieldContent.innerText;

          // save the timestamp. Since the string is an approximation and loses the milliseconds
          // the backup guarantees that we get back the original timestamp
          dateTimestampBackup.setAttribute('value', timestamp);
          const dateInt = parseInt(fieldContent.innerText, 10);
          const myDate = new Date(dateInt);
          fieldContent.innerText = myDate.toUTCString();
          dateStringBackup.setAttribute('value', fieldContent.innerText);
        }
      } else if (dateTimestampBackup.getAttribute('value')) {
        fieldContent.innerText = dateTimestampBackup.getAttribute('value');
      } else {
        const myDate = new Date(fieldContent.innerText);
        // eslint-disable-next-line no-restricted-globals
        if (isNaN(myDate.valueOf())) {
          alert('Please use a valid string date');
          toggleDateCheckbox.checked = false;
        } else {
          const dateString = content.innerText;
          dateStringBackup.setAttribute('value', dateString);
          fieldContent.innerText = myDate.valueOf();
          dateTimestampBackup.setAttribute('value', fieldContent.innerText);
        }
      }
    }
  }

  if (element.type === 'radio') {
    if (element.value === 'read') {
      row.classList.add('-color-white');
      row.classList.remove('-color-blue');
      row.classList.remove('-color-orange');
      content.disabled = true;
      toggleDateCheckbox.disabled = true;
      arrayCheckbox.disabled = true;
    }

    if (element.value === 'write') {
      row.classList.add('-color-blue');
      row.classList.remove('-color-white');
      row.classList.remove('-color-orange');
      content.disabled = false;
      toggleDateCheckbox.disabled = false;
      arrayCheckbox.disabled = false;
    }

    if (element.value === 'delete') {
      row.classList.add('-color-orange');
      row.classList.remove('-color-blue');
      row.classList.remove('-color-white');
      content.disabled = true;
      toggleDateCheckbox.disabled = true;
      arrayCheckbox.disabled = true;
    }
  }
}

/*
 * Called when the action radio button in the options screen is clicked
 *
 * @param {object} e The event object
 * @returns {undefined}
 */
const checkOptionEvent = function (e) {
  toggleOptionAction(e.target);
};

/*
 * Called when the action radio button in the users screen is clicked
 *
 * @param {object} e The event object
 * @returns {undefined}
 */
const checkUserEvent = function (e) {
  toggleUserAction(e.target);
};

/*
 * Called when the action radio button in the posts screen is clicked
 *
 * @param {object} e The event object
 * @returns {undefined}
 */
const checkPostEvent = function (e) {
  togglePostAction(e.target);
};

const checkEvents = function (e) {
  const optionSection = document.querySelector('.js-optionsMetaSection');
  optionSection.addEventListener('change', checkOptionEvent, false);

  const userSection = document.querySelector('.js-userFieldsSection');
  userSection.addEventListener('change', checkUserEvent, false);

  const postSection = document.querySelector('.js-postFieldsSection');
  postSection.addEventListener('change', checkPostEvent, false);

  const dateToggle = document.querySelector('.js-optionsMetaSection');
  dateToggle.addEventListener('change', checkOptionEvent, false);
};

window.addEventListener('load', checkEvents, false);
