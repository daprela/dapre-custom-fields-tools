/* eslint-disable function-paren-newline */
import { spinnerOn } from './functions.js';
import { spinnerOff } from './functions.js';
const { apiFetch } = wp;

const submitOptions = document.querySelector('.js-submitOptions');
const read = {};
const write = {};
const del = {};
const finishedWrite = false;
const finishedRead = false;
const finishedDel = false;
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
    let field = fields[index];
    let row = document.querySelector(`.js-optionFieldDataRow[data-index="${index}"]`);
    if (field.error === '' ) {
      row.classList.remove('is-error');
    } else {
      row.classList.add('is-error');
    }

    row.querySelector('.js-fieldAction[value="read"]').click();

    let fieldErrorMessage = row.querySelector('.js-fieldErrorMessage');
    if (field.fieldErrorClass === '' ) {
      fieldErrorMessage.classList.add('is-hidden');
    } else {
      fieldErrorMessage.classList.remove('is-hidden');
    }

    let emptyArrayCheckbox = row.querySelector('.js-emptyArray');
    emptyArrayCheckbox.checked = false;
    emptyArrayCheckbox.disabled = true;

    let dateStringCheckbox = row.querySelector('.js-dateString');
    dateStringCheckbox.checked = false;
    dateStringCheckbox.disabled = true;

    let metaFieldInputValue = row.querySelector('.js-metaFieldInputValue');
    metaFieldInputValue.value = '';
    metaFieldInputValue.disabled = true;

    let fieldCurrentValue = row.querySelector('.js-fieldCurrentValue');
    fieldCurrentValue.innerHTML = JSON.parse(field.currentValue);

    let currentValueDateToggle = row.querySelector('.js-curValueDateToggle');
    if (field.curValueDateToggle === 'is-visible' ) {
      currentValueDateToggle.classList.add('is-visible');
      currentValueDateToggle.classList.remove('is-hidden');
    } else {
      currentValueDateToggle.classList.remove('is-visible');
      currentValueDateToggle.classList.add('is-hidden');
    }

    let fieldPreviousValue = row.querySelector('.js-fieldPreviousValue');
    fieldPreviousValue.innerHTML = JSON.parse(field.previousValue);
  }
  spinnerOff();
}

function readData() {
  const readJSON = JSON.stringify(read);

  apiFetch({
    path: `${path}?names=${readJSON}`,
    method: 'GET',
    parse: false,
  })
    .then( (response) => {
      return response.json();
    })
    .then( (fields) => {
      refreshPage(fields);
    }
  );
}

function writeData() {
  const writeJSON = JSON.stringify(write);
  apiFetch({
    path: 'dapre-cft/v1/options',
    method: 'POST',
    body: writeJSON,
    parse: false,
  })
    .then(
      (response) =>
        // console.log(response);
        response,
    );
}

function deleteData() {
  const delJSON = JSON.stringify(del);
  apiFetch({
    path: 'dapre-cft/v1/options',
    method: 'DELETE',
    body: delJSON,
    parse: false,
  })
    .then(
      (response) =>
        // console.log(response);
        response,
    );
}

function getForm(e) {
  e.preventDefault();
  spinnerOn();

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
}

submitOptions.addEventListener('click', getForm, false);
