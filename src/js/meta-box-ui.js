import { isNumber } from './functions.js';

const { apiFetch } = wp;

const optionSection = document.querySelector('.js-optionsMetaSection');
const optionMetaSubmit = document.querySelector('.js-submitOptions');
const userMetaSection = document.querySelector('.js-userFieldsSection');
const userMetaSubmit = document.querySelector('.js-submitUserFields');
const postMetaSection = document.querySelector('.js-postFieldsSection');
const postMetaSubmit = document.querySelector('.js-submitPostFields');

// Change the color of the row field according to the option chosen
const changeFieldColor = function(element) {
  const row = element.parentNode.parentNode.parentNode;
  const content = row.querySelector(`.js-metaFieldInputValue`);
  const toggleDateCheckbox = row.querySelector(`.js-emptyArray`);
  const arrayCheckbox = row.querySelector(`.js-dateString`);

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

const newValueOptions = function(element) {
  const inputBox = element.parentNode.parentNode.parentNode;
  const toggleDateCheckbox = inputBox.querySelector(`.js-dateString`);
  const arrayCheckbox = inputBox.querySelector(`.js-emptyArray`);
  const content = inputBox.querySelector(`.js-metaFieldInputValue`);

  if( element.value === 'empty_array' ) {
    // if the user checked the empty array checkbox then disable the toggle date and return
    if (element.checked === true) {
      toggleDateCheckbox.checked = false;
      content.disabled = true;
    } else if (element.checked === false) {
      content.disabled = false;
    }
  }

  if( element.value === 'date_string' ) {
    content.disabled = false;
    arrayCheckbox.checked = false;
    // get the elements used to backup the values
    const dateTimestampBackup = document.querySelector(`.js-metaFieldInputTimestampBackup`);
    const dateStringBackup = document.querySelector(`.js-metaFieldInputStringBackup`);

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
}

/* Toggle between date string and timestamp representation of the current field content */
const curValueDateString = function(element) {
  const row = element.parentNode.parentNode.parentNode;
  const fieldContent = row.querySelector(`.js-fieldCurrentValue`);
  const toggleDateCheckbox = row.querySelector(`.js-fieldDateStringCurValue`);

  // get the elements used to backup the values
  const dateTimestampBackup = row.querySelector(`.js-fieldValueTimestampBackup`);
  const dateStringBackup = row.querySelector(`.js-fieldValueStringBackup`);

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


const checkEvent = function(e) {
  e.preventDefault();

  const element = e.target;

  /* Manage the action option */
  if ( element.classList.contains('js-fieldAction') ) {
    changeFieldColor(element);
  }

  /* Manage the checkboxes with the value options */
  if ( element.classList.contains('js-fieldValueToAdd') ) {
    newValueOptions(element);
  }

  /* Manage the current value checkbox */
  if ( element.classList.contains('js-fieldDateStringCurValue') ) {
    curValueDateString(element);
  }

}

const getOptions = function(e) {
  e.preventDefault();

}

function fetchData() {
  apiFetch({
    path: 'dapre-cft/v1/options?name=daprela',
    method: 'GET',
  })
    .then(
      (response) => {
        console.log(response);
        return response;
      },
    )
    .then((option) => console.log(option));
}

optionSection.addEventListener('change', checkEvent, false);
userMetaSection.addEventListener('change', checkEvent, false);
postMetaSection.addEventListener('change', checkEvent, false);

optionMetaSubmit.addEventListener('submit', getOptions, false);
userMetaSubmit.addEventListener('submit', getOptions, false);
postMetaSubmit.addEventListener('submit', getOptions, false);
