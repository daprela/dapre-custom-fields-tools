import React, {
  useCallback, useEffect, useState,
} from 'react';

/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import Arrow from './Arrow.js';
import FieldID from './FieldID.js';
import FieldName from './FieldName.js';
import MetaFieldActions from './MetaFieldActions.js';
import MetaFieldValueToAdd from './MetaFieldValueToAdd.js';
import MetaFieldCurrentValue from './MetaFieldCurrentValue.js';
import MetaFieldCurrentValueOptions from './MetaFieldCurrentValueOptions.js';
import MetaFieldPreviousValue from './MetaFieldPreviousValue.js';
import { isInteger } from '../functions';

function PostsMetaRow(props) {
  const {
    className, rowIndex, dataIndex, fieldID, fieldName, errorClass, errorMessage, previousValue, disableWrite,
    disableDelete, arrowTitle, arrowContent, currentValue, rowChange: updateForm, resetPage, restoreEvent, arrowClick: arrowClickProp,
  } = props;

  const [action, setAction] = useState('read');
  const [addEmptyArray, setAddEmptyArray] = useState(false);
  const [valueToWrite, setValueToWrite] = useState('');
  const [fieldIDValue, setFieldIDValue] = useState('');
  const [fieldNameValue, setFieldNameValue] = useState('');
  const [currentValuePrinted, setCurrentValuePrinted] = useState('');
  const [classColor, setClassColor] = useState('-color-white');
  const [currentValuePostsTimestampBackup, setCurrentValuePostsTimestampBackup] = useState(0);
  const [currentValuePostsDateBackup, setCurrentValuePostsDateBackup] = useState('');

  /* Updates the form row to send upstream. */
  useEffect(() => {
    const row = {
      rowIndex,
      fieldID: fieldIDValue,
      fieldName: fieldNameValue,
      valueToWrite,
      index: dataIndex,
      emptyArray: addEmptyArray,
      action,
    };
    updateForm(row);
  }, [action, addEmptyArray, dataIndex, fieldIDValue, fieldNameValue, rowIndex, updateForm, valueToWrite]);

  /* Updates the field name value when the user changes it */
  const updateFieldNameValue = useCallback((value) => {
    setFieldNameValue(value);
  }, []);

  const updateFieldIDValue = useCallback((value) => {
    setFieldIDValue(value);
  }, []);

  /* Initializes the field name value on first load */
  useEffect(() => {
    setFieldNameValue(fieldName);
  }, [fieldName]);

  /* Initializes the field ID on first load */
  useEffect(() => {
    setFieldIDValue(fieldID);
  }, [fieldID]);

  /* Updates the field value to write when the user changes it */
  const updateValueToWrite = useCallback((textAreaValue) => {
    setValueToWrite(textAreaValue);
  }, []);

  const updateEmptyArrayChange = useCallback((checkboxState) => {
    setAddEmptyArray(checkboxState);
  }, []);

  const updateAction = useCallback((e) => {
    setAction(e.target.value);
  }, []);

  /* Toggles between date string and timestamp when the current value is a valid date.
  * It's important to notice that this function can be called only when the current value is a valid date,
  * therefore we don't need to check. */
  const toggleDateCurrentValue = useCallback(() => {
    /* Is the current value an integer (that could be interpreted as a timestamp)? */
    if (isInteger(currentValuePrinted)) {
      if (parseInt(currentValuePrinted, 10) === currentValuePostsTimestampBackup) {
        // if the value hasn't changed use the date string saved
        setCurrentValuePrinted(currentValuePostsDateBackup);
      } else {
        // if the value has changed re-generate the date string
        const timestamp = parseInt(currentValuePrinted, 10);
        setCurrentValuePostsTimestampBackup(timestamp);
        const myDate = new Date(timestamp);
        setCurrentValuePrinted(myDate.toUTCString());
        setCurrentValuePostsDateBackup(myDate.toUTCString());
      }
    /* If the current value is backed up it means that we also have its timestamp stored. Use that. */
    } else if (currentValuePrinted === currentValuePostsDateBackup) {
      // if the date string hasn't changed use the timestamp saved
      setCurrentValuePrinted(parseInt(currentValuePostsTimestampBackup, 10));
    } else {
      // if the date string has changed re-generate the timestamp
      const myDate = new Date(currentValuePrinted);
      setCurrentValuePostsDateBackup(currentValuePrinted);
      setCurrentValuePrinted(myDate.valueOf());
      setCurrentValuePostsTimestampBackup(myDate.valueOf());
    }
  }, [currentValuePostsDateBackup, currentValuePostsTimestampBackup, currentValuePrinted]);

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

  function handleArrowClick(index, content) {
    arrowClickProp(index, content);
  }

  return (
    <div
      className={`${className} ${errorClass} ${classColor}`}
      data-index={dataIndex}
    >
      <Arrow
        className="js-addRemovePostRow c-addRemoveFieldRow c-addRemoveFieldRow__add"
        title={arrowTitle}
        content={arrowContent}
        arrowClick={handleArrowClick}
      />
      <FieldID
        className="c-metaField__fieldName"
        errorClassName="c-metaField__fieldErrorContainer"
        errorMessageClassName="js-fieldErrorMessage c-metaField__fieldErrorMessage"
        inputClass="js-postFieldName c-metaField__fieldInput"
        inputType="number"
        inputName="post_id"
        inputValue={fieldIDValue}
        fieldIDValue={updateFieldIDValue}
        action={action}
      />
      <FieldName
        className="c-metaField__fieldName"
        errorClassName="c-metaField__fieldErrorContainer"
        errorMessageClassName="js-postErrorMessage c-metaField__fieldErrorMessage"
        inputClass="js-metaFieldName c-metaField__fieldInput"
        inputType="text"
        inputName="field_name"
        inputValue={fieldNameValue}
        fieldNameValue={updateFieldNameValue}
        errorMessage={errorMessage}
        action={action}
      />
      <MetaFieldActions
        className="c-metaField__fieldActions"
        dataIndex={dataIndex}
        onChange={updateAction}
        resetPage={resetPage}
        restoreEvent={restoreEvent}
        errorMessage={errorMessage}
        disableWrite={disableWrite}
        disableDelete={disableDelete}
      />
      <MetaFieldValueToAdd
        className="c-metaField__fieldValueToAdd"
        valueOptionsClass="c-metaField__fieldValueOptions"
        valueOptionsLabelClass="c-metaField__fieldValueOptionLabel"
        textAreaClass="js-metaFieldInputValue c-metaField__fieldValueToAddTextarea"
        disableWrite={disableWrite}
        disableDelete={disableDelete}
        dataIndex={dataIndex}
        action={action}
        emptyArray={updateEmptyArrayChange}
        valueToAdd={updateValueToWrite}
        resetPage={resetPage}
      />
      <MetaFieldCurrentValue
        className="js-fieldCurrentValue c-metaField__fieldCurValue"
        currentValue={currentValuePrinted}
      />
      <MetaFieldCurrentValueOptions
        className="c-metaField__fieldCurValueOptions"
        currentValue={currentValue}
        dataIndex={dataIndex}
        onChange={toggleDateCurrentValue}
      />
      <MetaFieldPreviousValue
        className="js-fieldPreviousValue c-metaField__fieldPrevValue"
        previousValue={previousValue}
      />
    </div>
  );
}

export default PostsMetaRow;
