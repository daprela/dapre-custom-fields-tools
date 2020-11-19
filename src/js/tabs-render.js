/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope */
import React from 'react';
import { render } from 'react-dom';
/* eslint-disable import/extensions */
import TabOptionsMeta from './tab-options-meta.js';
import TabUsersMeta from './tab-users-meta.js';

render(<TabOptionsMeta />, document.querySelector('#optionsMetaSection'));
render(<TabUsersMeta />, document.querySelector('#usersMetaSection'));
