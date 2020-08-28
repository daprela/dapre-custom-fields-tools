/* eslint-disable function-paren-newline */
// eslint-disable import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const submitPostFieldsButton = document.querySelector('.js-submitPostFields');
const renamePostFieldsButton = document.querySelector('.js-submitRenamePostField');
const copyPostFieldsButton = document.querySelector('.js-submitCopyPostField');
let read = {};
let write = {};
let del = {};
let finishedWrite = false;
let finishedRead = false;
let finishedDel = false;
const restBase = 'post_fields';
const renameBase = `${restBase}/rename`;
const copyBase = `${restBase}/copy`;
const path = `${nameSpace}/${restBase}`;

/* Get the fields whose action is selected to 'write' */
function getWriteFields(row) {
  const { index } = row.dataset;
  const postID = row.querySelector(`input[name="post_id[${index}]"]`).value;
  const fieldName = row.querySelector(`input[name="field_name[${index}]"]`).value;
  const emptyArray = row.querySelector('.js-emptyArray').checked;
  const valueToWrite = row.querySelector('.js-metaFieldInputValue').value;

  write[index] = {
    index,
    postID,
    fieldName,
    emptyArray,
    valueToWrite,
  };
}

/* Get the fields whose action is selected to 'delete' */
function getDeleteFields(row) {
  const { index } = row.dataset;
  const postID = row.querySelector(`input[name="post_id[${index}]"]`).value;
  const fieldName = row.querySelector(`input[name="field_name[${index}]"]`).value;

  del[index] = {
    index,
    postID,
    fieldName,
  };
}

/* Get the fields whose action is selected to 'read' */
function readFields(row) {
  const { index } = row.dataset;
  const postID = row.querySelector(`input[name="post_id[${index}]"]`).value;
  const fieldName = row.querySelector(`input[name="field_name[${index}]"]`).value;

  read[index] = {
    index,
    postID,
    fieldName,
  };
}

/* Refresh the page of the meta fields with the data received */
function refreshPage(fields) {
  // eslint-disable-next-line no-restricted-syntax
  for (const index of Object.keys(fields)) {
    const field = fields[index];
    const row = document.querySelector(`.js-postFieldsDataRow[data-index="${index}"]`);
    // add/remove the red-dotted border
    if (field.error === '') {
      row.classList.remove('is-error');
    } else {
      row.classList.add('is-error');
    }

    row.querySelector('.js-fieldAction[value="read"]').click();

    row.querySelector(
      '.js-fieldAction[value="write"]').disabled = field.disableWrite
      === 'disabled';

    if (field.disableDelete === 'disabled') {
      row.querySelector('.js-fieldAction[value="delete"]').disabled = true;
    } else {
      row.querySelector('.js-fieldAction[value="delete"]').disabled = false;
    }

    // manages the error message
    const fieldErrorMessage = row.querySelector('.js-fieldErrorMessage');
    if (field.fieldErrorClass === '') {
      fieldErrorMessage.classList.add('is-hidden');
    } else {
      fieldErrorMessage.classList.remove('is-hidden');
      fieldErrorMessage.innerHTML = field.error;
    }

    // manages the empty array checkbox
    const emptyArrayCheckbox = row.querySelector('.js-emptyArray');
    emptyArrayCheckbox.checked = false;
    emptyArrayCheckbox.disabled = true;

    // manages the date string checkbox
    const dateStringCheckbox = row.querySelector('.js-dateString');
    dateStringCheckbox.checked = false;
    dateStringCheckbox.disabled = true;

    // input value box
    const metaFieldInputValue = row.querySelector('.js-metaFieldInputValue');
    metaFieldInputValue.value = '';
    metaFieldInputValue.disabled = true;

    // Current value
    const fieldCurrentValue = row.querySelector('.js-fieldCurrentValue');
    fieldCurrentValue.innerHTML = JSON.parse(field.currentValue);

    // Current value date-string option
    const currentValueDateToggle = row.querySelector('.js-curValueDateToggle');
    if (field.curValueDateToggle === 'is-visible') {
      currentValueDateToggle.classList.add('is-visible');
      currentValueDateToggle.classList.remove('is-hidden');
    } else {
      currentValueDateToggle.classList.remove('is-visible');
      currentValueDateToggle.classList.add('is-hidden');
    }

    // Previous value
    const fieldPreviousValue = row.querySelector('.js-fieldPreviousValue');
    fieldPreviousValue.innerHTML = JSON.parse(field.previousValue);
  }

  if (finishedWrite && finishedRead && finishedDel) {
    submitPostFieldsButton.disabled = false;
    submitPostFieldsButton.blur();

    spinnerOff();
    finishedWrite = false;
    finishedRead = false;
    finishedDel = false;
  }
}

/* Makes the API request for the 'read' action */
function readData() {
  if (Object.keys(read).length === 0) {
    finishedRead = true;
    return;
  }

  const readJSON = JSON.stringify(read);
  finishedRead = false;

  /* Launches the Rest request to read fields */
  apiFetch({
    path: `${path}?post_fields=${readJSON}`,
    method: 'GET',
    parse: false,
  })
    .then((response) => response.json())
    .then((fields) => {
      finishedRead = true;
      refreshPage(fields);
    });
}

/* Makes the API request for the 'write' action */
function writeData() {
  if (Object.keys(write).length === 0) {
    finishedWrite = true;
    return;
  }

  const writeJSON = JSON.stringify(write);
  finishedWrite = false;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'POST',
    body: writeJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      finishedWrite = true;
      refreshPage(fields);
    });
}

/* Makes the API request for the 'delete' action */
function deleteData() {
  if (Object.keys(del).length === 0) {
    finishedDel = true;
    return;
  }

  const delJSON = JSON.stringify(del);
  finishedDel = false;

  /* Launches the Rest request to delete fields */
  apiFetch({
    path,
    method: 'DELETE',
    body: delJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      finishedDel = true;
      refreshPage(fields);
    });
}

/* Gets the content of the form for the 'read|write|delete' actions */
function getMetaForm(e) {
  e.preventDefault();
  spinnerOn();
  submitPostFieldsButton.disabled = true;

  write = {};
  read = {};
  del = {};

  const rows = document.querySelectorAll('.js-postFieldsDataRow');

  /* Reads all the rows and fill in the request objects */
  // eslint-disable-next-line no-restricted-syntax
  for (const row of rows) {
    const fieldAction = row.querySelector('.js-fieldAction:checked').value;

    if (fieldAction === 'write') {
      getWriteFields(row);
    }

    if (fieldAction === 'delete') {
      getDeleteFields(row);
    }

    if (fieldAction === 'read') {
      readFields(row);
    }
  }

  /* Once all the rows have been read, launches the actions */
  readData();
  writeData();
  deleteData();
}

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

submitPostFieldsButton.addEventListener('click', getMetaForm, false);
renamePostFieldsButton.addEventListener('click', getRenameForm, false);
copyPostFieldsButton.addEventListener('click', getCopyForm, true);
