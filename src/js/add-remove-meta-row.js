/* eslint-disable function-paren-newline */
// eslint-disable import/extensions
import { spinnerOn, spinnerOff, nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

export function addOptionArrows() {
  const optionRows = Array.from(document.querySelectorAll('.js-optionFieldDataRow'));

  function positionRow(optionRow, index) {
    const arrow = optionRow.querySelector('.js-addRemoveOptionRow');
    arrow.style.top = `${optionRow.offsetTop + optionRow.offsetHeight - 10}px`;
    if (index < optionRows.length - 1) {
      arrow.innerHTML = '-';
    } else {
      arrow.innerHTML = '+';
    }
  }

  optionRows.forEach(positionRow);
}

export function addUserArrows() {
  const userRows = Array.from(document.querySelectorAll('.js-userFieldsDataRow'));

  function positionRow(userRow, index) {
    const arrow = userRow.querySelector('.js-addRemoveUserRow');
    arrow.style.top = `${userRow.offsetTop + userRow.offsetHeight - 10}px`;
    if (index < userRows.length - 1) {
      arrow.innerHTML = '-';
    } else {
      arrow.innerHTML = '+';
    }
  }

  userRows.forEach(positionRow);
}

export function addPostArrows() {
  const postRows = Array.from(document.querySelectorAll('.js-postFieldsDataRow'));

  function positionRow(postRow, index) {
    const arrow = postRow.querySelector('.js-addRemovePostRow');
    arrow.style.top = `${postRow.offsetTop + postRow.offsetHeight - 10}px`;
    if (index < postRows.length - 1) {
      arrow.innerHTML = '-';
    } else {
      arrow.innerHTML = '+';
    }
  }

  postRows.forEach(positionRow);
}

function addArrows() {
  addOptionArrows();
  addUserArrows();
  addPostArrows();
}

window.addEventListener('load', addArrows, false);
