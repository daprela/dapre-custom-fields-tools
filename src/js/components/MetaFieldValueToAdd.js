/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import React, { useEffect, createRef, useState } from 'react';
import { isInteger, isDate } from '../functions.js';

function MetaFieldValueToAdd(props) {
  const [timeStampBackup, setTimeStampBackup] = useState(0);
  const [inputStringBackup, setInputStringBackup] = useState('');
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

  /* Manages the toggle date checkbox */
  function toggleDate() {
    /* Is the current value an integer (that could be interpreted as a timestamp)? */
    if (isInteger(textAreaRef.current.value)) {
      if (textAreaRef.current.value === timeStampBackup) {
        // if the value hasn't changed use the date string saved
        textAreaRef.current.value = inputStringBackup;
      } else {
        // if the value has changed re-generate the date string
        const timestamp = textAreaRef.current.value;
        setTimeStampBackup(timestamp);
        const myDate = new Date(parseInt(timestamp, 10));
        textAreaRef.current.value = myDate.toUTCString();
        setInputStringBackup(textAreaRef.current.value);
      }
    /* If the current value is backed up it means that we also have its timestamp stored. Use that. */
    } else if (textAreaRef.current.value === inputStringBackup) {
      // if the date string hasn't changed use the timestamp saved
      textAreaRef.current.value = timeStampBackup;
    } else {
      // if the date string has changed re-generate the timestamp
      const myDate = new Date(textAreaRef.current.value);
      const dateString = textAreaRef.current.value;
      setInputStringBackup(dateString);
      textAreaRef.current.value = myDate.valueOf();
      setTimeStampBackup(textAreaRef.current.value);
    }
  }

  function textAreaContent() {
    if (isDate(textAreaRef.current.value)) {
      toggleDateRef.current.disabled = false;
    } else {
      toggleDateRef.current.disabled = true;
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
              onChange={toggleDate}
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
      </div>
  );
}

export default MetaFieldValueToAdd;
