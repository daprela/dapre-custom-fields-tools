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
