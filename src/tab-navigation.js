// eslint-disable-next-line import/extensions
import { refreshOptionArrows, refreshUserArrows, refreshPostArrows } from './add-remove-meta-row.js';

const tab1 = document.querySelector('.js-tab_1');
const tab2 = document.querySelector('.js-tab_2');
const tab3 = document.querySelector('.js-tab_3');
const optionsForm = document.querySelector('.js-optionsForm');
const userFieldsForm = document.querySelector('.js-userFieldsForm');
const postFieldsForm = document.querySelector('.js-postFieldsForm');

const hideOptionsTab = function () {
  tab1.classList.remove('nav-tab-active');
  optionsForm.classList.add('is-hidden');
};

const hideUsersTab = function () {
  tab2.classList.remove('nav-tab-active');
  userFieldsForm.classList.add('is-hidden');
};

const hidePostsTab = function () {
  tab3.classList.remove('nav-tab-active');
  postFieldsForm.classList.add('is-hidden');
};

const switchToOptionsTab = function (e) {
  e.preventDefault();

  /* activate clicked tab and make section visible */
  tab1.classList.add('nav-tab-active');
  optionsForm.classList.remove('is-hidden');
  tab1.blur();
  refreshOptionArrows();

  /* deactivate other tabs and hides sections */
  hideUsersTab();
  hidePostsTab();
};

const switchToUsersTab = function (e) {
  e.preventDefault();

  /* activate clicked tab and make section visible */
  tab2.classList.add('nav-tab-active');
  userFieldsForm.classList.remove('is-hidden');
  tab2.blur();
  refreshUserArrows();

  /* deactivate other tabs and hides sections */
  hideOptionsTab();
  hidePostsTab();
};

const switchToPostsTab = function (e) {
  e.preventDefault();

  /* activate clicked tab and make section visible */
  tab3.classList.add('nav-tab-active');
  postFieldsForm.classList.remove('is-hidden');
  tab3.blur();
  refreshPostArrows();

  /* deactivate other tabs and hides sections */
  hideOptionsTab();
  hideUsersTab();
};

tab1.addEventListener('click', switchToOptionsTab, false);
tab2.addEventListener('click', switchToUsersTab, false);
tab3.addEventListener('click', switchToPostsTab, false);
