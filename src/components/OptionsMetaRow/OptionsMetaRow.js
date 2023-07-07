import React, { useCallback, useEffect, useState } from "react";
import styles from "./options-meta-row.module.scss";

import Arrow from "../Arrow";
import FieldName from "../FieldName.js";
import MetaFieldActions from "../MetaFieldActions.js";
import MetaFieldValueToAdd from "../MetaFieldValueToAdd.js";
import MetaFieldCurrentValue from "../MetaFieldCurrentValue.js";
import MetaFieldCurrentValueOptions from "../MetaFieldCurrentValueOptions.js";
import MetaFieldPreviousValue from "../MetaFieldPreviousValue.js";
import { isInteger } from "../../functions";

function OptionsMetaRow(props) {
  const {
    className,
    rowIndex,
    dataIndex,
    fieldName,
    errorClass,
    errorMessage,
    previousValue,
    disableWrite,
    disableDelete,
    arrowTitle,
    arrowContent,
    currentValue,
    rowChange: updateForm,
    resetPage,
    restoreEvent,
    arrowClick: arrowClickProp,
  } = props;

  const [action, setAction] = useState("read");
  const [addEmptyArray, setAddEmptyArray] = useState(false);
  const [valueToWrite, setValueToWrite] = useState("");
  const [fieldNameValue, setFieldNameValue] = useState("");
  const [currentValuePrinted, setCurrentValuePrinted] = useState("");
  const [classColor, setClassColor] = useState("-color-white");
  const [
    currentValueOptionsTimestampBackup,
    setCurrentValueOptionsTimestampBackup,
  ] = useState(0);
  const [currentValueOptionsDateBackup, setCurrentValueOptionsDateBackup] =
    useState("");

  /* Updates the form row to send upstream. */
  useEffect(() => {
    const row = {
      rowIndex,
      optionName: fieldNameValue,
      valueToWrite,
      index: dataIndex,
      emptyArray: addEmptyArray,
      action,
    };
    updateForm(row);
  }, [
    action,
    addEmptyArray,
    dataIndex,
    fieldNameValue,
    rowIndex,
    updateForm,
    valueToWrite,
  ]);

  /* Updates the field name value when the user changes it */
  const updateFieldNameValue = useCallback((value) => {
    setFieldNameValue(value);
  }, []);

  /* Initializes the field name value on first load */
  useEffect(() => {
    setFieldNameValue(fieldName);
  }, [fieldName]);

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
      if (
        parseInt(currentValuePrinted, 10) === currentValueOptionsTimestampBackup
      ) {
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
  }, [
    currentValueOptionsDateBackup,
    currentValueOptionsTimestampBackup,
    currentValuePrinted,
  ]);

  useEffect(() => {
    if (action === "read") {
      setClassColor("-color-white");
    }
    if (action === "write") {
      setClassColor("-color-blue");
    }
    if (action === "delete") {
      setClassColor("-color-orange");
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
      className={`${className} ${styles[errorClass]} ${styles[classColor]} ${styles.row_box}`}
      data-index={dataIndex}
    >
      <Arrow
        className="js-addRemoveOptionRow"
        title={arrowTitle}
        content={arrowContent}
        arrowClick={handleArrowClick}
      />
      <FieldName
        className="c-optionField__fieldName"
        errorClassName="c-optionField__fieldErrorContainer"
        errorMessageClassName="js-fieldErrorMessage c-optionField__fieldErrorMessage"
        inputClass="js-optionFieldName c-optionField__fieldInput"
        inputType="text"
        inputName="field_name"
        inputValue={fieldNameValue}
        fieldNameValue={updateFieldNameValue}
        errorMessage={errorMessage}
        action={action}
      />
      <MetaFieldActions
        className="c-optionField__fieldActions"
        dataIndex={dataIndex}
        onChange={updateAction}
        resetPage={resetPage}
        restoreEvent={restoreEvent}
        errorMessage={errorMessage}
        disableWrite={disableWrite}
        disableDelete={disableDelete}
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
        emptyArray={updateEmptyArrayChange}
        valueToAdd={updateValueToWrite}
        resetPage={resetPage}
      />
      <MetaFieldCurrentValue
        className="js-fieldCurrentValue c-optionField__fieldCurValue"
        currentValue={currentValuePrinted}
      />
      <MetaFieldCurrentValueOptions
        className="c-optionField__fieldCurValueOptions"
        currentValue={currentValue}
        dataIndex={dataIndex}
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
