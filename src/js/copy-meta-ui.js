/* eslint-disable func-names */
// const { apiFetch } = wp;

const copyOptionSection = document.querySelector('.js-copyOptionTo');
const copyUserMetaSection = document.querySelector('.js-copyUserFieldTo');
const copyPostMetaSection = document.querySelector('.js-copyPostFieldTo');

/* Get elements in the option tab */
const optionToUserField = document.querySelector('.js-optionToUserField');
const optionToOption = document.querySelector('.js-optionToOption');
const optionToPostField = document.querySelector('.js-optionToPostField');

/* Get elements in the user fields tab */
const userFieldToUserField = document.querySelector('.js-userFieldToUserField');
const userFieldToOption = document.querySelector('.js-userFieldToOption');
const userFieldToPostField = document.querySelector('.js-userFieldToPostField');

/* Get elements in the post fields tab */
const postFieldToUserField = document.querySelector('.js-postFieldToUserField');
const postFieldToOption = document.querySelector('.js-postFieldToOption');
const postFieldToPostField = document.querySelector('.js-postFieldToPostField');

/**
 * Slide up a box
 * @param element Element or box to slide up
 */
function slideUp(element) {
  element.classList.remove('is-open');
}

/**
 * Slide down a box
 * @param element Element or box to slide down
 */
function slideDown(element) {
  element.classList.add('is-open');
}

/* Select where to copy the option field */
const copyOptionDestinations = function (e) {
  const element = e.target;

  if (element.value === 'option') {
    slideUp(optionToUserField);
    slideUp(optionToPostField);
    slideDown(optionToOption);
  }

  if (element.value === 'user field') {
    slideUp(optionToOption);
    slideUp(optionToPostField);
    slideDown(optionToUserField);
  }

  if (element.value === 'post field') {
    slideUp(optionToUserField);
    slideUp(optionToOption);
    slideDown(optionToPostField);
  }
};

/* Select where to copy the user field */
const copyUserFieldDestination = function (e) {
  const element = e.target;

  if (element.value === 'option') {
    slideUp(userFieldToUserField);
    slideUp(userFieldToPostField);
    slideDown(userFieldToOption);
  }

  if (element.value === 'user field') {
    slideUp(userFieldToOption);
    slideUp(userFieldToPostField);
    slideDown(userFieldToUserField);
  }

  if (element.value === 'post field') {
    slideUp(userFieldToOption);
    slideUp(userFieldToUserField);
    slideDown(userFieldToPostField);
  }
};

/**
 * Select where to copy the post field
 * @param e Top element that got the click
 */
const copyPostFieldDestination = function (e) {
  const element = e.target;

  if (element.value === 'option') {
    slideUp(postFieldToUserField);
    slideUp(postFieldToPostField);
    slideDown(postFieldToOption);
  }

  if (element.value === 'user field') {
    slideUp(postFieldToOption);
    slideUp(postFieldToPostField);
    slideDown(postFieldToUserField);
  }

  if (element.value === 'post field') {
    slideUp(postFieldToOption);
    slideUp(postFieldToUserField);
    slideDown(postFieldToPostField);
  }
};

copyOptionSection.addEventListener('click', copyOptionDestinations, false);
copyUserMetaSection.addEventListener('click', copyUserFieldDestination, false);
copyPostMetaSection.addEventListener('click', copyPostFieldDestination, false);
