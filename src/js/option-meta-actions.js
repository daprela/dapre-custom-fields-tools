/* eslint-disable function-paren-newline */
// eslint-disable-next-line import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const renameOptionsButton = document.querySelector('.js-submitRenameOption');
const copyOptionsButton = document.querySelector('.js-submitCopyOption');
const restBase = 'options';
const renameBase = `${restBase}/rename`;
const copyBase = `${restBase}/copy`;

/* Refresh the rename section if the action has been completed successfully */
function refreshRenamePage(fields) {
  let lightbox;

  const oldOption = document.querySelector('.js-oldOptionName');
  const newOption = document.querySelector('.js-newOptionName');

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
    oldOption.value = '';
    // eslint-disable-next-line no-param-reassign
    newOption.value = '';
  }

  lightbox.setContent(fields.message);

  lightbox.open();

  spinnerOff();
  renameOptionsButton.disabled = false;
}

/* Gets the content of the form for the 'rename' action */
function getRenameForm(e) {
  e.preventDefault();
  spinnerOn();
  renameOptionsButton.disabled = true;

  const oldOptionName = document.querySelector('.js-oldOptionName').value;
  const newOptionName = document.querySelector('.js-newOptionName').value;

  /* Prepares the object for the Rest request */
  const rename = {
    oldOptionName,
    newOptionName,
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

  const currentOption = document.querySelector('.js-currentOptionName');

  if (fields.copied) {
    currentOption.value = '';

    lightbox = new Lightbox({
      openAnimation: 'fadein',
      closeAnimation: 'shrink',
    });
    lightbox.setTitle('Done');

    switch (copySelection) {
      case 'option':
        newOption = document.querySelector('.js-copyOptionToOptionName');
        checkboxCreate = document.querySelector('.js-copyOptionToOptionCreate');
        newOption.value = '';
        checkboxCreate.checked = false;
        break;
      case 'user field':
        userID = document.querySelector('.js-optToUserFieldID');
        userField = document.querySelector('.js-optToUserFieldName');
        checkboxCreate = document.querySelector('.js-optToUserFieldCreate');
        userID.value = '';
        userField.value = '';
        checkboxCreate.checked = false;
        break;
      case 'post field':
        postID = document.querySelector('.js-optToPostFieldID');
        postField = document.querySelector('.js-optToPostFieldName');
        checkboxCreate = document.querySelector('.js-optToPostFieldCreate');
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
  copyOptionsButton.disabled = false;
}

/* Gets the content of the form for the 'copy' action */
function getCopyForm(e) {
  e.preventDefault();
  spinnerOn();
  copyOptionsButton.disabled = true;

  let copy;
  let checkboxCreate;
  let newOption;
  let userID;
  let userField;
  let postID;
  let postField;

  const currentOption = document.querySelector('.js-currentOptionName').value;
  const copySelection = document.querySelector('input[type=radio][name=copy_option_to]:checked').value;

  /* Checks where we must copy the option to and prepares the appropriate object for the Rest request */
  switch (copySelection) {
    case 'option':
      newOption = document.querySelector('.js-copyOptionToOptionName').value;
      checkboxCreate = document.querySelector('.js-copyOptionToOptionCreate').checked;
      copy = {
        currentOption,
        copySelection,
        newOption,
        checkboxCreate,
      };
      break;
    case 'user field':
      userID = document.querySelector('.js-optToUserFieldID').value;
      userField = document.querySelector('.js-optToUserFieldName').value;
      checkboxCreate = document.querySelector('.js-optToUserFieldCreate').checked;
      copy = {
        currentOption,
        copySelection,
        userID,
        userField,
        checkboxCreate,
      };
      break;
    case 'post field':
      postID = document.querySelector('.js-optToPostFieldID').value;
      postField = document.querySelector('.js-optToPostFieldName').value;
      checkboxCreate = document.querySelector('.js-optToPostFieldCreate').checked;
      copy = {
        currentOption,
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

renameOptionsButton.addEventListener('click', getRenameForm, false);
copyOptionsButton.addEventListener('click', getCopyForm, true);
