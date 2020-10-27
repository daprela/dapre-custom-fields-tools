import React, { createRef, useEffect, useState } from 'react'

/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import Arrow from './Arrow.js';
import FieldName from './FieldName.js';
import MetaFieldActions from './MetaFieldActions.js';
import MetaFieldValueToAdd from './MetaFieldValueToAdd.js';
import MetaFieldCurrentValue from './MetaFieldCurrentValue.js';
import MetaFieldCurrentValueOptions from './MetaFieldCurrentValueOptions.js';
import MetaFieldPreviousValue from './MetaFieldPreviousValue.js';
import { isInteger } from '../functions';

function OptionsMetaRow(props) {
  const {
    className, dataIndex, fieldName, errorClass, errorMessage, previousValue, disableWrite,
    disableDelete, curValueDateToggle, arrowTitle, arrowContent, currentValue,
  } = props;

  const [action, setAction] = useState('read');
  const [currentValuePrinted, setCurrentValuePrinted] = useState('');
  const [classColor, setClassColor] = useState('-color-white');
  const [currentValueOptionsTimestampBackup, setCurrentValueOptionsTimestampBackup] = useState(0);
  const [currentValueOptionsDateBackup, setCurrentValueOptionsDateBackup] = useState('');

  function actionChange(e) {
    setAction(e.target.value);
  }

  /* Toggles between date string and timestamp when the current value is a valid date.
  * It's important to notice that this function can be called only when the current value is a valid date,
  * therefore we don't need to check. */
  function toggleDateCurrentValue() {
    /* Is the current value an integer (that could be interpreted as a timestamp)? */
    if (isInteger(currentValuePrinted)) {
      if (parseInt(currentValuePrinted, 10) === currentValueOptionsTimestampBackup) {
        // if the value hasn't changed use the date string saved
        setCurrentValuePrinted(currentValueOptionsDateBackup);
      } else {
        // if the value has changed re-generate the date string
        const timestamp = parseInt(currentValuePrinted, 10);
        setCurrentValueOptionsTimestampBackup(timestamp);
        const myDate = new Date(timestamp);
        setCurrentValuePrinted(myDate.toUTCString());
        setCurrentValueOptionsDateBackup(myDate.toUTCString());
      }
    /* If the current value is backed up it means that we also have its timestamp stored. Use that. */
    } else if (currentValuePrinted === currentValueOptionsDateBackup) {
      // if the date string hasn't changed use the timestamp saved
      setCurrentValuePrinted(parseInt(currentValueOptionsTimestampBackup, 10));
    } else {
      // if the date string has changed re-generate the timestamp
      const myDate = new Date(currentValuePrinted);
      setCurrentValueOptionsDateBackup(currentValuePrinted);
      setCurrentValuePrinted(myDate.valueOf());
      setCurrentValueOptionsTimestampBackup(myDate.valueOf());
    }
  }

  useEffect(() => {
    if (action === 'read') {
      setClassColor('-color-white');
    }
    if (action === 'write') {
      setClassColor('-color-blue');
    }
    if (action === 'delete') {
      setClassColor('-color-orange');
    }
  }, [action]);

  useEffect(() => {
    setCurrentValuePrinted(currentValue);
  }, [currentValue]);

  return (
    <div
      className={`${className} ${errorClass} ${classColor}`}
      data-index={dataIndex}
    >
      <Arrow
        className="js-addRemoveOptionRow c-addRemoveFieldRow c-addRemoveFieldRow__add"
        title={arrowTitle}
        content={arrowContent}
      />
      <FieldName
        className="c-optionField__fieldName"
        errorClassName="c-optionField__fieldErrorContainer"
        errorMessageClassName="js-fieldErrorMessage c-optionField__fieldErrorMessage"
        inputClass="js-optionFieldName c-optionField__fieldInput"
        inputType="text"
        inputName="field_name"
        inputValue={fieldName}
        errorMessage={errorMessage}
      />
      <MetaFieldActions
        className="c-optionField__fieldActions"
        dataIndex={dataIndex}
        onChange={actionChange}
      />
      <MetaFieldValueToAdd
        className="c-optionField__fieldValueToAdd"
        valueOptionsClass="c-optionField__fieldValueOptions"
        valueOptionsLabelClass="c-optionField__fieldValueOptionLabel"
        textAreaClass="js-metaFieldInputValue c-optionField__fieldValueToAddTextarea"
        disableWrite={disableWrite}
        disableDelete={disableDelete}
        dataIndex={dataIndex}
        action={action}
      />
      <MetaFieldCurrentValue
        className="js-fieldCurrentValue c-optionField__fieldCurValue"
        currentValue={currentValuePrinted}
      />
      <MetaFieldCurrentValueOptions
        className="c-optionField__fieldCurValueOptions"
        curValueDateToggle={curValueDateToggle}
        currentValue={currentValue}
        dataIndex={dataIndex}
        currentValueOptionsTimestampBackup={currentValueOptionsTimestampBackup}
        currentValueOptionsDateBackup={currentValueOptionsDateBackup}
        onChange={toggleDateCurrentValue}
      />
      <MetaFieldPreviousValue
        className="js-fieldPreviousValue c-optionField__fieldPrevValue"
        previousValue={previousValue}
      />
    </div>
  );
}

export default OptionsMetaRow;
