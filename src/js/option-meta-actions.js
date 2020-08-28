/* eslint-disable function-paren-newline */
// eslint-disable import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const submitOptionsButton = document.querySelector('.js-submitOptions');
const renameOptionsButton = document.querySelector('.js-submitRenameOption');
const copyOptionsButton = document.querySelector('.js-submitCopyOption');
let read = {};
let write = {};
let del = {};
let finishedWrite = false;
let finishedRead = false;
let finishedDel = false;
const restBase = 'options';
const renameBase = `${restBase}/rename`;
const copyBase = `${restBase}/copy`;
const path = `${nameSpace}/${restBase}`;

/* Get the fields whose action is selected to 'write' */
function getWriteFields(row) {
  const { index } = row.dataset;
  const optionName = row.querySelector(`input[name="field_name[${index}]"]`).value;
  const emptyArray = row.querySelector('.js-emptyArray').checked;
  const valueToWrite = row.querySelector('.js-metaFieldInputValue').value;

  write[index] = {
    index,
    optionName,
    emptyArray,
    valueToWrite,
  };
}

/* Get the fields whose action is selected to 'delete' */
function getDeleteFields(row) {
  const { index } = row.dataset;
  const optionName = row.querySelector(`input[name="field_name[${index}]"]`).value;

  del[index] = {
    index,
    optionName,
  };
}

/* Get the fields whose action is selected to 'read' */
function readFields(row) {
  const { index } = row.dataset;
  const optionName = row.querySelector(`input[name="field_name[${index}]"]`).value;

  read[index] = {
    index,
    optionName,
  };
}

/* Refresh the page of the meta fields with the data received */
function refreshPage(fields) {
  // eslint-disable-next-line no-restricted-syntax
  for (const index of Object.keys(fields)) {
    const field = fields[index];
    // console.log(field);
    const row = document.querySelector(`.js-optionFieldDataRow[data-index="${index}"]`);
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
    submitOptionsButton.disabled = false;
    submitOptionsButton.blur();

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
    path: `${path}?options=${readJSON}`,
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
  submitOptionsButton.disabled = true;

  write = {};
  read = {};
  del = {};

  const rows = document.querySelectorAll('.js-optionFieldDataRow');

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
      console.log(fields);
      refreshCopyPage(fields, copySelection);
    });
}

submitOptionsButton.addEventListener('click', getMetaForm, false);
renameOptionsButton.addEventListener('click', getRenameForm, false);
copyOptionsButton.addEventListener('click', getCopyForm, true);
