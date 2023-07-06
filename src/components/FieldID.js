/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
import React, {
  useEffect, createRef, useState, useCallback,
} from 'react';

function FieldID({
  className, inputValue, fieldIDValue: updateFieldID, inputName, inputType, inputClass, errorClassName, errorMessageClassName, errorMessage, action,
}) {
  const [fieldIDValue, setFieldIDValue] = useState('');
  const actionRef = createRef();

  const updateFieldIDValue = useCallback((e) => {
    setFieldIDValue(e.target.value);
    updateFieldID(e.target.value);
  }, [updateFieldID]);

  useEffect(() => {
    if (action === 'write' || action === 'delete') {
      actionRef.current.disabled = true;
    } else {
      actionRef.current.disabled = false;
    }
  }, [action, actionRef]);

  useEffect(() => {
    setFieldIDValue(inputValue);
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
        step="1"
        min="0"
        name={inputName}
        value={fieldIDValue}
        ref={actionRef}
        onChange={updateFieldIDValue}
      />
    </div>
  );
}

export default FieldID;
