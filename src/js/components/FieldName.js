import React from 'react';
/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import MetaFieldError from './MetaFieldError.js';
import MetaFieldNameInput from './MetaFieldNameInput.js';

function FieldName(props) {
  const {
    className, inputValue, inputName, inputType, inputClass, errorClassName, errorMessageClassName, errorMessage,
  } = props;
  return (
    <div className={className}>
      <MetaFieldError
        className={errorClassName}
        messageClassName={errorMessageClassName}
        errorMessage={errorMessage}
      />
      <MetaFieldNameInput
        className={inputClass}
        type={inputType}
        name={inputName}
        value={inputValue}
      />
    </div>
  );
}

export default FieldName;
