import React, { useEffect, useState } from 'react';

/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import Arrow from './Arrow.js';
import FieldName from './FieldName.js';
import MetaFieldActions from './MetaFieldActions.js';
import MetaFieldValueToAdd from './MetaFieldValueToAdd.js';
import MetaFieldCurrentValue from './MetaFieldCurrentValue.js';
import MetaFieldCurrentValueOptions from './MetaFieldCurrentValueOptions.js';
import MetaFieldPreviousValue from './MetaFieldPreviousValue.js';

function OptionsMetaRow(props) {
  const [action, setAction] = useState('read');
  const [classColor, setClassColor] = useState('-color-white');

  function actionChange(e) {
    setAction(e.target.value);
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

  const {
    className, dataIndex, fieldName, errorClass, errorMessage, currentValue, previousValue, disableWrite,
    disableDelete, curValueDateToggle, arrowTitle, arrowContent,
  } = props;

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
        currentValue={currentValue}
      />
      <MetaFieldCurrentValueOptions
        className="c-optionField__fieldCurValueOptions"
        curValueDateToggle={curValueDateToggle}
        dataIndex={dataIndex}
      />
      <MetaFieldPreviousValue
        className="js-fieldPreviousValue c-optionField__fieldPrevValue"
        previousValue={previousValue}
      />
    </div>
  );
}

export default OptionsMetaRow;
