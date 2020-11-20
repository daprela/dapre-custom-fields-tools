/* eslint-disable function-paren-newline */
// eslint-disable-next-line import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const renamePostFieldsButton = document.querySelector('.js-submitRenamePostField');
const copyPostFieldsButton = document.querySelector('.js-submitCopyPostField');
const restBase = 'post_fields';
const renameBase = `${restBase}/rename`;
const copyBase = `${restBase}/copy`;

/* Refresh the rename section if the action has been completed successfully */
function refreshRenamePage(fields) {
  let lightbox;

  const postID = document.querySelector('.js-postFieldRenamePostID');
  const oldFieldName = document.querySelector('.js-oldPostFieldName');
  const newFieldName = document.querySelector('.js-newPostFieldName');

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
    postID.value = '';
    // eslint-disable-next-line no-param-reassign
    oldFieldName.value = '';
    // eslint-disable-next-line no-param-reassign
    newFieldName.value = '';
  }

  lightbox.setContent(fields.message);

  lightbox.open();

  spinnerOff();
  renamePostFieldsButton.disabled = false;
}

/* Gets the content of the form for the 'rename' action */
function getRenameForm(e) {
  e.preventDefault();
  spinnerOn();
  renamePostFieldsButton.disabled = true;

  const postID = document.querySelector('.js-postFieldRenamePostID').value;
  const oldFieldName = document.querySelector('.js-oldPostFieldName').value;
  const newFieldName = document.querySelector('.js-newPostFieldName').value;

  /* Prepares the object for the Rest request */
  const rename = {
    postID,
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
  let postID;
  let userField;
  let userID;
  let postField;
  let lightbox;

  const currentPostID = document.querySelector('.js-postFieldCopyPostID');
  const currentPostField = document.querySelector('.js-postFieldCopyFieldName');

  if (fields.copied) {
    currentPostID.value = '';
    currentPostField.value = '';

    lightbox = new Lightbox({
      openAnimation: 'fadein',
      closeAnimation: 'shrink',
    });
    lightbox.setTitle('Done');

    switch (copySelection) {
      case 'option':
        newOption = document.querySelector('.js-postFieldToOptionName');
        checkboxCreate = document.querySelector('.js-postFieldToOptionCreate');
        newOption.value = '';
        checkboxCreate.checked = false;
        break;
      case 'user field':
        userID = document.querySelector('.js-postFieldToUserFieldID');
        userField = document.querySelector('.js-postFieldToUserFieldName');
        checkboxCreate = document.querySelector('.js-postFieldToUserFieldCreate');
        userID.value = '';
        userField.value = '';
        checkboxCreate.checked = false;
        break;
      case 'post field':
        postID = document.querySelector('.js-postFieldToPostFieldID');
        postField = document.querySelector('.js-postFieldToPostFieldName');
        checkboxCreate = document.querySelector('.js-postFieldToPostFieldCreate');
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
  copyPostFieldsButton.disabled = false;
}

/* Gets the content of the form for the 'copy' action */
function getCopyForm(e) {
  e.preventDefault();
  spinnerOn();
  copyPostFieldsButton.disabled = true;

  let copy;
  let checkboxCreate;
  let newOption;
  let userID;
  let userField;
  let postID;
  let postField;

  const currentPostID = document.querySelector('.js-postFieldCopyPostID').value;
  const currentFieldName = document.querySelector('.js-postFieldCopyFieldName').value;
  const copySelection = document.querySelector('input[type=radio][name=copy_post_field_to]:checked').value;

  /* Checks where we must copy the user field to and prepares the appropriate object for the Rest request */
  switch (copySelection) {
    case 'option':
      newOption = document.querySelector('.js-postFieldToOptionName').value;
      checkboxCreate = document.querySelector('.js-postFieldToOptionCreate').checked;
      copy = {
        currentPostID,
        currentFieldName,
        copySelection,
        newOption,
        checkboxCreate,
      };
      break;
    case 'user field':
      userID = document.querySelector('.js-postFieldToUserFieldID').value;
      userField = document.querySelector('.js-postFieldToUserFieldName').value;
      checkboxCreate = document.querySelector('.js-postFieldToUserFieldCreate').checked;
      copy = {
        currentPostID,
        currentFieldName,
        copySelection,
        userID,
        userField,
        checkboxCreate,
      };
      break;
    case 'post field':
      postID = document.querySelector('.js-postFieldToPostFieldID').value;
      postField = document.querySelector('.js-postFieldToPostFieldName').value;
      checkboxCreate = document.querySelector('.js-postFieldToPostFieldCreate').checked;
      copy = {
        currentPostID,
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

renamePostFieldsButton.addEventListener('click', getRenameForm, false);
copyPostFieldsButton.addEventListener('click', getCopyForm, true);
