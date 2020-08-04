/* eslint-disable function-paren-newline */
// eslint-disable import/extensions
import { spinnerOn } from './functions.js';
import { spinnerOff } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const submitOptions = document.querySelector('.js-submitOptions');
let read = {};
let write = {};
let del = {};
let finishedWrite = false;
let finishedRead = false;
let finishedDel = false;
const path = 'dapre-cft/v1/options';

function getWriteFields(row) {
  const { index } = row.dataset;
  const optionName = row.querySelector(`input[name="field_name[${index}]"]`).value;
  const emptyArray = row.querySelector('.js-emptyArray').checked;
  const valueToWrite = row.querySelector('.js-metaFieldInputValue').value;

  write[index] = {
    optionName,
    emptyArray,
    valueToWrite,
  };
}

function getDeleteFields(row) {
  const { index } = row.dataset;
  const optionName = row.querySelector(`input[name="field_name[${index}]"]`).value;

  del[index] = {
    optionName,
  };
}

function readFields(row) {
  const { index } = row.dataset;
  const optionName = row.querySelector(`input[name="field_name[${index}]"]`).value;

  read[index] = {
    optionName,
  };
}

function refreshPage(fields) {
  // eslint-disable-next-line no-restricted-syntax
  for (const index of Object.keys(fields)) {
    const field = fields[index];
    console.log(field);
    const row = document.querySelector(`.js-optionFieldDataRow[data-index="${index}"]`);
    // add/remove the red-dotted border
    if (field.error === '') {
      row.classList.remove('is-error');
    } else {
      row.classList.add('is-error');
    }

    row.querySelector('.js-fieldAction[value="read"]').click();

    if (field.disableWrite === 'disabled') {
      row.querySelector('.js-fieldAction[value="write"]').disabled = true;
    } else {
      row.querySelector('.js-fieldAction[value="write"]').disabled = false;
    }

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
    spinnerOff();
    finishedWrite = false;
    finishedRead = false;
    finishedDel = false;
  }
}

function readData() {
  if (Object.keys(read).length === 0) {
    finishedRead = true;
    return;
  }

  const readJSON = JSON.stringify(read);
  finishedRead = false;

  apiFetch({
    path: `${path}?names=${readJSON}`,
    method: 'GET',
    parse: false,
  })
    .then((response) => response.json())
    .then((fields) => {
      finishedRead = true;
      refreshPage(fields);
    });
}

function writeData() {
  if (Object.keys(write).length === 0) {
    finishedWrite = true;
    return;
  }

  const writeJSON = JSON.stringify(write);
  finishedWrite = false;

  apiFetch({
    path: 'dapre-cft/v1/options',
    method: 'POST',
    body: writeJSON,
    parse: false,
  })
    .then((response) => response.json())
    .then((fields) => {
      // console.log(fields);
      finishedWrite = true;
      refreshPage(fields);
    });
}

function deleteData() {
  if (Object.keys(del).length === 0) {
    finishedDel = true;
    return;
  }

  const delJSON = JSON.stringify(del);
  finishedDel = false;

  apiFetch({
    path: 'dapre-cft/v1/options',
    method: 'DELETE',
    body: delJSON,
    parse: false,
  })
    .then((response) => response.json())
    .then((fields) => {
      // console.log(fields);
      finishedDel = true;
      refreshPage(fields);
    });
}

function getForm(e) {
  e.preventDefault();
  spinnerOn();

  write = {};
  read = {};
  del = {};

  const rows = document.querySelectorAll('.js-optionFieldDataRow');

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
  readData();
  writeData();
  deleteData();
}

submitOptions.addEventListener('click', getForm, false);
