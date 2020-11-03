import React, { useState, useEffect, useCallback } from 'react';
/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions,react/jsx-no-undef */
import OptionsMetaHeaders from './components/OptionsMetaHeaders.js';
import OptionsMetaRow from './components/OptionsMetaRow.js';
// import ButtonSubmitMeta from './components/ButtonSubmitMeta.js';
import { nameSpace } from './functions.js';

// eslint-disable-next-line no-undef
const { apiFetch } = wp;

const restBase = 'options';
const path = `${nameSpace}/${restBase}`;

function TabOptionsMeta() {
  const [rows, setRows] = useState([]);
  const [form, setForm] = useState([]);

  /* Initialize the form at first page load */
  useEffect(() => {
    const readJSON = JSON.stringify('all');

    /* Launches the Rest request to read fields */
    apiFetch({
      path: `${path}?all_options=${readJSON}`,
      method: 'GET',
      parse: false,
    })
      .then((response) => response.json())
      .then((metaFields) => {
        const formTemp = [];
        setRows(metaFields);
        // eslint-disable-next-line array-callback-return
        metaFields.map((row, index) => {
          formTemp[index] = {
            index: row.index,
            optionName: row.fieldName,
            emptyArray: '',
            action: 'read',
            valueToWrite: '',
          };
        });
        setForm(formTemp);
      });
  }, []);

  /* Called from downstream when the user writes or click the form.
  * this form is sent to the server to get updates when the user clicks the button */
  const updateForm = useCallback((formRow) => {
    const temp = {
      index: formRow.index,
      optionName: formRow.optionName,
      emptyArray: formRow.emptyArray,
      action: formRow.action,
      valueToWrite: formRow.valueToWrite,
    };
    const formTemp = form;
    formTemp[formRow.rowIndex] = temp;
    setForm(formTemp);
  }, [form]);

  return (
    <>
      <div className="js-optionsMetaSection o-meta">
        <OptionsMetaHeaders className="c-optionsMetaHeaders" />
        {rows.map((row, index) => {
          let arrowTitle = 'Add another row';
          let arrowContent = '+';
          if (index + 1 < rows.length) {
            arrowTitle = 'Remove the next row';
            arrowContent = '-';
          }
          return (
            <OptionsMetaRow
              className="js-optionFieldDataRow c-optionField"
              rowIndex={index}
              dataIndex={row.index}
              fieldName={row.fieldName}
              errorClass={row.rowErrorClass}
              errorMessage={row.error}
              currentValue={JSON.parse(row.currentValue)}
              previousValue={JSON.parse(row.previousValue)}
              disableWrite={row.disableWrite}
              disableDelete={row.disableDelete}
              arrowTitle={arrowTitle}
              arrowContent={arrowContent}
              rowChange={updateForm}
            />
          );
        })}
      </div>
      <input
        className="js-submitOptions c-metaSubmitButton button button-primary"
        type="submit"
        name="submit_options"
        value="Read/Write Values"
      />
    </>
  );
}

export default TabOptionsMeta;
