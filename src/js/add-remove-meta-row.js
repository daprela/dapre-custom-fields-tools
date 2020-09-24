/* eslint-disable function-paren-newline */
// eslint-disable import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const optionSection = document.querySelector('.js-optionsMetaSection');
const userMetaSection = document.querySelector('.js-userFieldsSection');
const postMetaSection = document.querySelector('.js-postFieldsSection');

export function refreshOptionArrows() {
  const optionRows = Array.from(document.querySelectorAll('.js-optionFieldDataRow'));

  function positionRow(optionRow, index) {
    const arrow = optionRow.querySelector('.js-addRemoveOptionRow');
    arrow.style.top = `${optionRow.offsetTop + optionRow.offsetHeight - 10}px`;
    if (index < optionRows.length - 1) {
      arrow.innerHTML = '-';
      arrow.title = 'Remove the next row';
    } else {
      arrow.innerHTML = '+';
      arrow.title = 'Add another row';
    }
  }

  optionRows.forEach(positionRow);
}

export function refreshUserArrows() {
  const userRows = Array.from(document.querySelectorAll('.js-userFieldsDataRow'));

  function positionRow(userRow, index) {
    const arrow = userRow.querySelector('.js-addRemoveUserRow');
    arrow.style.top = `${userRow.offsetTop + userRow.offsetHeight - 10}px`;
    if (index < userRows.length - 1) {
      arrow.innerHTML = '-';
      arrow.title = 'Remove the next row';
    } else {
      arrow.innerHTML = '+';
      arrow.title = 'Add another row';
    }
  }

  userRows.forEach(positionRow);
}

export function refreshPostArrows() {
  const postRows = Array.from(document.querySelectorAll('.js-postFieldsDataRow'));

  function positionRow(postRow, index) {
    const arrow = postRow.querySelector('.js-addRemovePostRow');
    arrow.style.top = `${postRow.offsetTop + postRow.offsetHeight - 10}px`;
    if (index < postRows.length - 1) {
      arrow.innerHTML = '-';
      arrow.title = 'Remove the next row';
    } else {
      arrow.innerHTML = '+';
      arrow.title = 'Add another row';
    }
  }

  postRows.forEach(positionRow);
}

function addArrows() {
  refreshOptionArrows();
  refreshUserArrows();
  refreshPostArrows();
}

function removeOptionRow(elementRow, path) {
  const submitOptionsButton = document.querySelector('.js-submitOptions');
  const elementToRemove = elementRow.nextElementSibling;

  elementToRemove.remove();
  refreshOptionArrows();

  const rows = document.querySelectorAll('.js-optionFieldDataRow');

  const options = [];

  // eslint-disable-next-line no-restricted-syntax
  for (const row of rows) {
    const { index } = row.dataset;
    options.push(index);
  }
  const removeJSON = JSON.stringify(options);
  spinnerOn();
  submitOptionsButton.disabled = true;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'DELETE',
    body: removeJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      submitOptionsButton.disabled = false;
      submitOptionsButton.blur();
      spinnerOff();
      if (fields.error) {

      }
    });
}

function addOptionRow(elementRow, path) {
  const table = elementRow.parentElement;
  const oldRow = document.querySelector('.js-optionFieldDataRow[data-index="0"]');
  const newRow = oldRow.cloneNode(true);

  newRow.dataset.index = Number(elementRow.dataset.index) + 1;

  const optionName = newRow.querySelector('.js-optionFieldName');
  optionName.value = '';
  optionName.name = `field_name[${newRow.dataset.index}]`;

  // start removing the content of the previous row
  newRow.classList.remove('is-error');
  newRow.querySelector('.js-fieldAction[value="read"]').disabled = false;
  newRow.querySelector('.js-fieldAction[value="write"]').disabled = true;
  newRow.querySelector('.js-fieldAction[value="delete"]').disabled = true;

  newRow.querySelector('.js-fieldAction[value="read"]').name = `field_action[${newRow.dataset.index}]`;
  newRow.querySelector('.js-fieldAction[value="write"]').name = `field_action[${newRow.dataset.index}]`;
  newRow.querySelector('.js-fieldAction[value="delete"]').name = `field_action[${newRow.dataset.index}]`;

  // manages the error message
  const fieldErrorMessage = newRow.querySelector('.js-fieldErrorMessage');
  fieldErrorMessage.innerHTML = '';
  fieldErrorMessage.classList.add('is-hidden');

  // manages the empty array checkbox
  const emptyArrayCheckbox = newRow.querySelector('.js-emptyArray');
  emptyArrayCheckbox.checked = false;
  emptyArrayCheckbox.disabled = true;
  emptyArrayCheckbox.name = `empty_array[${newRow.dataset.index}]`;

  // manages the date string checkbox
  const dateStringCheckbox = newRow.querySelector('.js-dateString');
  dateStringCheckbox.checked = false;
  dateStringCheckbox.disabled = true;
  dateStringCheckbox.name = `date_string[${newRow.dataset.index}]`;

  // input value box
  const metaFieldInputValue = newRow.querySelector('.js-metaFieldInputValue');
  metaFieldInputValue.value = '';
  metaFieldInputValue.disabled = true;
  metaFieldInputValue.name = `field_value[${newRow.dataset.index}]`;

  // Current value
  const fieldCurrentValue = newRow.querySelector('.js-fieldCurrentValue');
  fieldCurrentValue.innerHTML = '';

  // Current value date-string option
  const currentValueDateToggle = newRow.querySelector('.js-curValueDateToggle');
  currentValueDateToggle.classList.remove('is-visible');
  currentValueDateToggle.classList.add('is-hidden');

  const currentValueDateToggleCheckbox = newRow.querySelector('.js-fieldDateStringCurValue');
  currentValueDateToggleCheckbox.name = `date_string_show[${newRow.dataset.index}]`;

  // Previous value
  const fieldPreviousValue = newRow.querySelector('.js-fieldPreviousValue');
  fieldPreviousValue.innerHTML = '';

  table.appendChild(newRow);
  refreshOptionArrows();

  const submitOptionsButton = document.querySelector('.js-submitOptions');

  const addJSON = JSON.stringify(newRow.dataset.index);
  spinnerOn();
  submitOptionsButton.disabled = true;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'POST',
    body: addJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      submitOptionsButton.disabled = false;
      submitOptionsButton.blur();
      spinnerOff();
      if (fields.error) {

      }
    });
}

function removeUserRow(elementRow, path) {
  const submitUserButton = document.querySelector('.js-submitUserFields');
  const elementToRemove = elementRow.nextElementSibling;

  elementToRemove.remove();
  refreshUserArrows();

  const rows = document.querySelectorAll('.js-userFieldsDataRow');

  const metaFields = [];

  // eslint-disable-next-line no-restricted-syntax
  for (const row of rows) {
    const { index } = row.dataset;
    metaFields.push(index);
  }
  const removeJSON = JSON.stringify(metaFields);
  spinnerOn();
  submitUserButton.disabled = true;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'DELETE',
    body: removeJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      submitUserButton.disabled = false;
      submitUserButton.blur();
      spinnerOff();
      if (fields.error) {

      }
    });
}

function addUserRow(elementRow, path) {
  const table = elementRow.parentElement;
  const oldRow = document.querySelector('.js-userFieldsDataRow[data-index="0"]');
  const newRow = oldRow.cloneNode(true);

  newRow.dataset.index = Number(elementRow.dataset.index) + 1;

  const fieldID = newRow.querySelector('.js-userFieldID');
  fieldID.value = '';
  fieldID.name = `user_id[${newRow.dataset.index}]`;

  const fieldName = newRow.querySelector('.js-userFieldName');
  fieldName.value = '';
  fieldName.name = `field_name[${newRow.dataset.index}]`;

  // start removing the content of the previous row
  newRow.classList.remove('is-error');
  newRow.querySelector('.js-fieldAction[value="read"]').disabled = false;
  newRow.querySelector('.js-fieldAction[value="write"]').disabled = true;
  newRow.querySelector('.js-fieldAction[value="delete"]').disabled = true;

  newRow.querySelector('.js-fieldAction[value="read"]').name = `field_action[${newRow.dataset.index}]`;
  newRow.querySelector('.js-fieldAction[value="write"]').name = `field_action[${newRow.dataset.index}]`;
  newRow.querySelector('.js-fieldAction[value="delete"]').name = `field_action[${newRow.dataset.index}]`;

  // manages the error message
  const fieldErrorMessage = newRow.querySelector('.js-fieldErrorMessage');
  fieldErrorMessage.innerHTML = '';
  fieldErrorMessage.classList.add('is-hidden');

  // manages the empty array checkbox
  const emptyArrayCheckbox = newRow.querySelector('.js-emptyArray');
  emptyArrayCheckbox.checked = false;
  emptyArrayCheckbox.disabled = true;
  emptyArrayCheckbox.name = `empty_array[${newRow.dataset.index}]`;

  // manages the date string checkbox
  const dateStringCheckbox = newRow.querySelector('.js-dateString');
  dateStringCheckbox.checked = false;
  dateStringCheckbox.disabled = true;
  dateStringCheckbox.name = `date_string[${newRow.dataset.index}]`;

  // input value box
  const metaFieldInputValue = newRow.querySelector('.js-metaFieldInputValue');
  metaFieldInputValue.value = '';
  metaFieldInputValue.disabled = true;
  metaFieldInputValue.name = `field_value[${newRow.dataset.index}]`;

  // Current value
  const fieldCurrentValue = newRow.querySelector('.js-fieldCurrentValue');
  fieldCurrentValue.innerHTML = '';

  // Current value date-string option
  const currentValueDateToggle = newRow.querySelector('.js-curValueDateToggle');
  currentValueDateToggle.classList.remove('is-visible');
  currentValueDateToggle.classList.add('is-hidden');

  const currentValueDateToggleCheckbox = newRow.querySelector('.js-fieldDateStringCurValue');
  currentValueDateToggleCheckbox.name = `date_string_show[${newRow.dataset.index}]`;

  // Previous value
  const fieldPreviousValue = newRow.querySelector('.js-fieldPreviousValue');
  fieldPreviousValue.innerHTML = '';

  table.appendChild(newRow);
  refreshUserArrows();

  const submitUserButton = document.querySelector('.js-submitUserFields');

  const addJSON = JSON.stringify(newRow.dataset.index);
  spinnerOn();
  submitUserButton.disabled = true;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'POST',
    body: addJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      submitUserButton.disabled = false;
      submitUserButton.blur();
      spinnerOff();
      if (fields.error) {

      }
    });
}

function removePostRow(elementRow, path) {
  const submitPostButton = document.querySelector('.js-submitPostFields');
  const elementToRemove = elementRow.nextElementSibling;

  elementToRemove.remove();
  refreshPostArrows();

  const rows = document.querySelectorAll('.js-postFieldsDataRow');

  const metaFields = [];

  // eslint-disable-next-line no-restricted-syntax
  for (const row of rows) {
    const { index } = row.dataset;
    metaFields.push(index);
  }
  const removeJSON = JSON.stringify(metaFields);
  spinnerOn();
  submitPostButton.disabled = true;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'DELETE',
    body: removeJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      submitPostButton.disabled = false;
      submitPostButton.blur();
      spinnerOff();
      if (fields.error) {

      }
    });
}

function addPostRow(elementRow, path) {
  const table = elementRow.parentElement;
  const oldRow = document.querySelector('.js-postFieldsDataRow[data-index="0"]');
  const newRow = oldRow.cloneNode(true);

  newRow.dataset.index = Number(elementRow.dataset.index) + 1;

  const fieldID = newRow.querySelector('.js-postFieldID');
  fieldID.value = '';
  fieldID.name = `post_id[${newRow.dataset.index}]`;

  const fieldName = newRow.querySelector('.js-postFieldName');
  fieldName.value = '';
  fieldName.name = `field_name[${newRow.dataset.index}]`;

  // start removing the content of the previous row
  newRow.classList.remove('is-error');
  newRow.querySelector('.js-fieldAction[value="read"]').disabled = false;
  newRow.querySelector('.js-fieldAction[value="write"]').disabled = true;
  newRow.querySelector('.js-fieldAction[value="delete"]').disabled = true;

  newRow.querySelector('.js-fieldAction[value="read"]').name = `field_action[${newRow.dataset.index}]`;
  newRow.querySelector('.js-fieldAction[value="write"]').name = `field_action[${newRow.dataset.index}]`;
  newRow.querySelector('.js-fieldAction[value="delete"]').name = `field_action[${newRow.dataset.index}]`;

  // manages the error message
  const fieldErrorMessage = newRow.querySelector('.js-fieldErrorMessage');
  fieldErrorMessage.innerHTML = '';
  fieldErrorMessage.classList.add('is-hidden');

  // manages the empty array checkbox
  const emptyArrayCheckbox = newRow.querySelector('.js-emptyArray');
  emptyArrayCheckbox.checked = false;
  emptyArrayCheckbox.disabled = true;
  emptyArrayCheckbox.name = `empty_array[${newRow.dataset.index}]`;

  // manages the date string checkbox
  const dateStringCheckbox = newRow.querySelector('.js-dateString');
  dateStringCheckbox.checked = false;
  dateStringCheckbox.disabled = true;
  dateStringCheckbox.name = `date_string[${newRow.dataset.index}]`;

  // input value box
  const metaFieldInputValue = newRow.querySelector('.js-metaFieldInputValue');
  metaFieldInputValue.value = '';
  metaFieldInputValue.disabled = true;
  metaFieldInputValue.name = `field_value[${newRow.dataset.index}]`;

  // Current value
  const fieldCurrentValue = newRow.querySelector('.js-fieldCurrentValue');
  fieldCurrentValue.innerHTML = '';

  // Current value date-string option
  const currentValueDateToggle = newRow.querySelector('.js-curValueDateToggle');
  currentValueDateToggle.classList.remove('is-visible');
  currentValueDateToggle.classList.add('is-hidden');

  const currentValueDateToggleCheckbox = newRow.querySelector('.js-fieldDateStringCurValue');
  currentValueDateToggleCheckbox.name = `date_string_show[${newRow.dataset.index}]`;

  // Previous value
  const fieldPreviousValue = newRow.querySelector('.js-fieldPreviousValue');
  fieldPreviousValue.innerHTML = '';

  table.appendChild(newRow);
  refreshPostArrows();

  const submitPostButton = document.querySelector('.js-submitPostFields');

  const addJSON = JSON.stringify(newRow.dataset.index);
  spinnerOn();
  submitPostButton.disabled = true;

  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'POST',
    body: addJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      submitPostButton.disabled = false;
      submitPostButton.blur();
      spinnerOff();
      if (fields.error) {

      }
    });
}

function checkOptionEvent(e) {
  const element = e.target;

  const restBase = 'options';
  const updateBase = `${restBase}/update`;
  const path = `${nameSpace}/${updateBase}`;

  if (!element.classList.contains('js-addRemoveOptionRow')) {
    return;
  }

  const elementRow = element.parentElement;

  if (element.innerText === '-') {
    removeOptionRow(elementRow, path);
  } else {
    addOptionRow(elementRow, path);
  }
}

function checkUserEvent(e) {
  const element = e.target;

  const restBase = 'user_fields';
  const updateBase = `${restBase}/update`;
  const path = `${nameSpace}/${updateBase}`;

  if (!element.classList.contains('js-addRemoveUserRow')) {
    return;
  }

  const elementRow = element.parentElement;

  if (element.innerText === '-') {
    removeUserRow(elementRow, path);
  } else {
    addUserRow(elementRow, path);
  }
}

function checkPostEvent(e) {
  const element = e.target;

  const restBase = 'post_fields';
  const updateBase = `${restBase}/update`;
  const path = `${nameSpace}/${updateBase}`;

  if (!element.classList.contains('js-addRemovePostRow')) {
    return;
  }

  const elementRow = element.parentElement;

  if (element.innerText === '-') {
    removePostRow(elementRow, path);
  } else {
    addPostRow(elementRow, path);
  }
}

window.addEventListener('load', addArrows, false);
optionSection.addEventListener('click', checkOptionEvent, false);
userMetaSection.addEventListener('click', checkUserEvent, false);
postMetaSection.addEventListener('click', checkPostEvent, false);
