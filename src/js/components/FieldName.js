import React, {
  useEffect, createRef, useState, useCallback,
} from 'react';
/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */

function FieldName({
  className, inputValue, fieldNameValue: updateFieldName, inputName, inputType, inputClass, errorClassName, errorMessageClassName, errorMessage, action,
}) {
  const [fieldNameValue, setFieldNameValue] = useState('');
  const actionRef = createRef();

  const updateFieldNameValue = useCallback((e) => {
    setFieldNameValue(e.target.value);
    updateFieldName(e.target.value);
  }, [updateFieldName]);

  useEffect(() => {
    if (action === 'write' || action === 'delete') {
      actionRef.current.disabled = true;
    } else {
      actionRef.current.disabled = false;
    }
  }, [action, actionRef]);

  useEffect(() => {
    setFieldNameValue(inputValue);
  }, [inputValue]);

  return (
    <div className={className}>
      <div className={errorClassName}>
        <p
          className={errorMessageClassName}
        >
          {errorMessage}
        </p>
      </div>
      <input
        className={inputClass}
        type={inputType}
        name={inputName}
        value={fieldNameValue}
        ref={actionRef}
        onChange={updateFieldNameValue}
      />
    </div>
  );
}

export default FieldName;
