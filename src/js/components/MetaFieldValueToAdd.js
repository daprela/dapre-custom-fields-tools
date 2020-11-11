/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions,jsx-a11y/label-has-associated-control */
import React, {
  useEffect, createRef, useState, useCallback,
} from 'react';
import { isInteger, isDate } from '../functions.js';

function MetaFieldValueToAdd(props) {
  const [timeStampBackup, setTimeStampBackup] = useState(0);
  const [inputStringBackup, setInputStringBackup] = useState('');
  const emptyArrayRef = createRef();
  const toggleDateRef = createRef();
  const textAreaRef = createRef();

  const {
    className, valueOptionsClass, valueOptionsLabelClass, textAreaClass, dataIndex,
    action, emptyArray: emptyArrayChange, valueToAdd: updateValueToAdd, resetPage,
  } = props;

  useEffect(() => {
    if (resetPage) {
      textAreaRef.current.value = '';
      emptyArrayRef.current.checked = false;
      toggleDateRef.current.checked = false;
      emptyArrayChange(false);
      updateValueToAdd('');
    }
  }, [emptyArrayChange, emptyArrayRef, resetPage, textAreaRef, toggleDateRef, updateValueToAdd]);

  function emptyArrayCheckbox() {
    emptyArrayChange(emptyArrayRef.current.checked);
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
    // Sends upstream the updated value
    updateValueToAdd(textAreaRef.current.value);
  }

  const textAreaContent = useCallback(() => {
    // Sends upstream the updated value
    updateValueToAdd(textAreaRef.current.value);
    toggleDateRef.current.disabled = !isDate(textAreaRef.current.value);
  }, [textAreaRef, toggleDateRef, updateValueToAdd]);

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
  }, [action, emptyArrayRef, textAreaContent, textAreaRef, toggleDateRef]);

  /* TODO for some reasons, if I move this line under the function emptyArrayCheckbox (where it logically belongs)
      it doesn't work anymore. WHY? */
  useEffect(() => {
    textAreaRef.current.disabled = emptyArrayRef.current.checked;
  }, [emptyArrayRef, textAreaRef]);

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
      />
    </div>
  );
}

export default MetaFieldValueToAdd;
