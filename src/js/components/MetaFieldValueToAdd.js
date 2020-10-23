/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import { isInteger } from '../functions.js';
import React, { useEffect, createRef } from 'react';

function MetaFieldValueToAdd(props) {
  const emptyArrayRef = createRef();
  const toggleDateRef = createRef();
  const textAreaRef = createRef();

  const {
    className, valueOptionsClass, valueOptionsLabelClass, textAreaClass, dataIndex, action,
  } = props;

  function emptyArrayCheckbox() {
    if (emptyArrayRef.current.checked === true) {
      textAreaRef.current.disabled = true;
    } else {
      textAreaRef.current.disabled = false;
    }
  }

  function textAreaContent() {
    let date = new Date(textAreaRef.current.value);
    /* If the string starts with a number, parseInt extracts the number which is not what we want.
    * We need to check if the string is an integer first. */
    if (isInteger(textAreaRef.current.value)) {
      date = new Date(parseInt(textAreaRef.current.value, 10));
    }

    if (isNaN(date.valueOf())) {
      toggleDateRef.current.disabled = true;
    } else {
      toggleDateRef.current.disabled = false;
    }
  }

  useEffect(() => {
    if (action === 'read' || action === 'delete') {
      emptyArrayRef.current.disabled = true;
      toggleDateRef.current.disabled = true;
      textAreaRef.current.disabled = true;
    } else {
      emptyArrayRef.current.disabled = false;
      textAreaRef.current.disabled = false;
      textAreaContent();
    }
  }, [action]);

  return (
      <div className={className}>
        <div className={valueOptionsClass}>
          <label className={valueOptionsLabelClass}>
            <input
              className="js-fieldValueToAdd js-emptyArray"
              type="checkbox"
              name={`empty_array[${dataIndex}]`}
              value="empty_array"
              ref={emptyArrayRef}
              onChange={emptyArrayCheckbox}
            />
            <p
              title="Replace the field content with an empty array"
            >
              Add empty array
            </p>
          </label>
          <label className={valueOptionsLabelClass}>
            <input
              className="js-fieldValueToAdd js-dateString"
              type="checkbox"
              name={`date_string[${dataIndex}]`}
              value="date_string"
              ref={toggleDateRef}
            />
            <p>Toggle date string/timestamp</p>
          </label>
        </div>
        <textarea
          className={textAreaClass}
          name={`field_value[${dataIndex}]`}
          rows="2"
          ref={textAreaRef}
          onChange={textAreaContent}
        >
        </textarea>
        <input
          className="js-metaFieldInputTimestampBackup"
          type="hidden"
          name="input-timestamp-backup"
          value=""
        />
        <input
          className="js-metaFieldInputStringBackup"
          type="hidden"
          name="input-string-backup"
          value=""
        />
      </div>
  );
}

export default MetaFieldValueToAdd;
