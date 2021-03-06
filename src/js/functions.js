export const nameSpace = 'dapre-cft/v1';

/* eslint-disable no-param-reassign */
export const isNumber = function (o) {
  // eslint-disable-next-line no-restricted-globals
  return !isNaN(o - 0) && o !== null && o !== '' && o !== false;
};

/*
 * Checks if the content of a field is an integer >0
 * Useful to check if the content of an ID field is valid */
// eslint-disable-next-line no-unused-vars
export const isNormalInteger = function (str) {
  return /^([1-9]\d*)$/.test(str);
};

export const isPositiveInteger = function (str) {
  return /^([1-9]\d*)$/.test(str);
};

/*
 * Checks if the content of a field is an integer positive or negative
 * Useful to check timestamps */
export const isInteger = function (value) {
  return /^-?\d+$/.test(value);
};

/* Returns true if value is either a valid timestamp or a valid string date */
export const isDate = function (value) {
  if (isInteger(value)) {
    return true;
  }

  const date = new Date(value);

  if (isNaN(date.valueOf())) {
    return false;
  }

  return true;
};

export const spinnerOn = function () {
  const spinner = document.querySelector('.js-halfCircleSpinner');
  spinner.classList.remove('is-hidden');
};

export const spinnerOff = function () {
  const spinner = document.querySelector('.js-halfCircleSpinner');
  spinner.classList.add('is-hidden');
};
