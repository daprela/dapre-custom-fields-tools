/* eslint-disable function-paren-newline */
// eslint-disable-next-line import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const renameUserFieldsButton = document.querySelector('.js-submitRenameUserField');
const copyUserFieldsButton = document.querySelector('.js-submitCopyUserField');
const restBase = 'user_fields';
const renameBase = `${restBase}/rename`;
const copyBase = `${restBase}/copy`;

/* Refresh the rename section if the action has been completed successfully */
function refreshRenamePage(fields) {
  let lightbox;

  const userID = document.querySelector('.js-userFieldRenameUserID');
  const oldFieldName = document.querySelector('.js-oldUserFieldName');
  const newFieldName = document.querySelector('.js-newUserFieldName');

  if (!fields.renamed) {
    lightbox = new Lightbox({
      openAnimation: 'jelly',
      closeAnimation: 'collapse',
    });
    lightbox.setTitle('ERROR');
  } else {
    lightbox = new Lightbox({
      openAnimation: 'fadein',
      closeAnimation: 'shrink',
    });
    lightbox.setTitle('Done');
    // eslint-disable-next-line no-param-reassign
    userID.value = '';
    // eslint-disable-next-line no-param-reassign
    oldFieldName.value = '';
    // eslint-disable-next-line no-param-reassign
    newFieldName.value = '';
  }

  lightbox.setContent(fields.message);

  lightbox.open();

  spinnerOff();
  renameUserFieldsButton.disabled = false;
}

/* Gets the content of the form for the 'rename' action */
function getRenameForm(e) {
  e.preventDefault();
  spinnerOn();
  renameUserFieldsButton.disabled = true;

  const userID = document.querySelector('.js-userFieldRenameUserID').value;
  const oldFieldName = document.querySelector('.js-oldUserFieldName').value;
  const newFieldName = document.querySelector('.js-newUserFieldName').value;

  /* Prepares the object for the Rest request */
  const rename = {
    userID,
    oldFieldName,
    newFieldName,
  };

  const renameJSON = JSON.stringify(rename);

  /* Launches the Rest request to rename fields */
  apiFetch({
    path: `${nameSpace}/${renameBase}`,
    method: 'POST',
    body: renameJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      refreshRenamePage(fields);
    });
}

/* Refresh the copy section if the action has been completed successfully */
function refreshCopyPage(fields, copySelection) {
  let checkboxCreate;
  let newOption;
  let userID;
  let userField;
  let postID;
  let postField;
  let lightbox;

  const currentUserID = document.querySelector('.js-userFieldCopyUserID');
  const currentUserField = document.querySelector('.js-userFieldCopyFieldName');

  if (fields.copied) {
    currentUserID.value = '';
    currentUserField.value = '';

    lightbox = new Lightbox({
      openAnimation: 'fadein',
      closeAnimation: 'shrink',
    });
    lightbox.setTitle('Done');

    switch (copySelection) {
      case 'option':
        newOption = document.querySelector('.js-userFieldToOptionName');
        checkboxCreate = document.querySelector('.js-userFieldToOptionCreate');
        newOption.value = '';
        checkboxCreate.checked = false;
        break;
      case 'user field':
        userID = document.querySelector('.js-userFieldToUserFieldID');
        userField = document.querySelector('.js-userFieldToUserFieldName');
        checkboxCreate = document.querySelector('.js-userFieldToUserFieldCreate');
        userID.value = '';
        userField.value = '';
        checkboxCreate.checked = false;
        break;
      case 'post field':
        postID = document.querySelector('.js-userFieldToPostFieldID');
        postField = document.querySelector('.js-userFieldToPostFieldName');
        checkboxCreate = document.querySelector('.js-userFieldToPostFieldCreate');
        postID.value = '';
        postField.value = '';
        checkboxCreate.checked = false;
        break;
      default:
    }
  } else {
    lightbox = new Lightbox({
      openAnimation: 'jelly',
      closeAnimation: 'collapse',
    });
    lightbox.setTitle('ERROR');
  }
  lightbox.setContent(fields.message);

  lightbox.open();

  spinnerOff();
  copyUserFieldsButton.disabled = false;
}

/* Gets the content of the form for the 'copy' action */
function getCopyForm(e) {
  e.preventDefault();
  spinnerOn();
  copyUserFieldsButton.disabled = true;

  let copy;
  let checkboxCreate;
  let newOption;
  let userID;
  let userField;
  let postID;
  let postField;

  const currentUserID = document.querySelector('.js-userFieldCopyUserID').value;
  const currentFieldName = document.querySelector('.js-userFieldCopyFieldName').value;
  const copySelection = document.querySelector('input[type=radio][name=copy_user_field_to]:checked').value;

  /* Checks where we must copy the user field to and prepares the appropriate object for the Rest request */
  switch (copySelection) {
    case 'option':
      newOption = document.querySelector('.js-userFieldToOptionName').value;
      checkboxCreate = document.querySelector('.js-userFieldToOptionCreate').checked;
      copy = {
        currentUserID,
        currentFieldName,
        copySelection,
        newOption,
        checkboxCreate,
      };
      break;
    case 'user field':
      userID = document.querySelector('.js-userFieldToUserFieldID').value;
      userField = document.querySelector('.js-userFieldToUserFieldName').value;
      checkboxCreate = document.querySelector('.js-userFieldToUserFieldCreate').checked;
      copy = {
        currentUserID,
        currentFieldName,
        copySelection,
        userID,
        userField,
        checkboxCreate,
      };
      break;
    case 'post field':
      postID = document.querySelector('.js-userFieldToPostFieldID').value;
      postField = document.querySelector('.js-userFieldToPostFieldName').value;
      checkboxCreate = document.querySelector('.js-userFieldToPostFieldCreate').checked;
      copy = {
        currentUserID,
        currentFieldName,
        copySelection,
        postID,
        postField,
        checkboxCreate,
      };
      break;
    default:
  }

  const copyJSON = JSON.stringify(copy);

  /* Launches the Rest request to copy fields */
  apiFetch({
    path: `${nameSpace}/${copyBase}`,
    method: 'POST',
    body: copyJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      refreshCopyPage(fields, copySelection);
    });
}

renameUserFieldsButton.addEventListener('click', getRenameForm, false);
copyUserFieldsButton.addEventListener('click', getCopyForm, true);
