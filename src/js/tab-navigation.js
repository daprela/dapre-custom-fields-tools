const tab1 = document.querySelector('.js-tab_1');
const tab2 = document.querySelector('.js-tab_2');
const tab3 = document.querySelector('.js-tab_3');
const optionsForm = document.querySelector('.js-optionsForm');
const userFieldsForm = document.querySelector('.js-userFieldsForm');
const postFieldsForm = document.querySelector('.js-postFieldsForm');

const hideTab1 = function() {
  tab1.classList.remove('nav-tab-active');
  optionsForm.classList.add('is-hidden');
};

const hideTab2 = function() {
  tab2.classList.remove('nav-tab-active');
  userFieldsForm.classList.add('is-hidden');
};

const hideTab3 = function() {
  tab3.classList.remove('nav-tab-active');
  postFieldsForm.classList.add('is-hidden');
};

const switchToTab1 = function (e) {
  e.preventDefault();

  /* activate clicked tab and make section visible */
  tab1.classList.add('nav-tab-active');
  optionsForm.classList.remove('is-hidden');
  tab1.blur();

  /* deactivate other tabs and hides sections */
  hideTab2();
  hideTab3();
};

const switchToTab2 = function (e) {
  e.preventDefault();

  /* activate clicked tab and make section visible */
  tab2.classList.add('nav-tab-active');
  userFieldsForm.classList.remove('is-hidden');
  tab2.blur();

  /* deactivate other tabs and hides sections */
  hideTab1();
  hideTab3();
};

const switchToTab3 = function (e) {
  e.preventDefault();

  /* activate clicked tab and make section visible */
  tab3.classList.add('nav-tab-active');
  postFieldsForm.classList.remove('is-hidden');
  tab3.blur();

  /* deactivate other tabs and hides sections */
  hideTab1();
  hideTab2();
};

tab1.addEventListener('click', switchToTab1, false);
tab2.addEventListener('click', switchToTab2, false);
tab3.addEventListener('click', switchToTab3, false);
