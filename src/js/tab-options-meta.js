import React, { createRef, useState, useEffect } from 'react';
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

  useEffect(() => {
    const readJSON = JSON.stringify('all');

    /* Launches the Rest request to read fields */
    apiFetch({
      path: `${path}?all_options=${readJSON}`,
      method: 'GET',
      parse: false,
    })
      .then((response) => response.json())
      .then((fields) => {
        setRows(fields);
      });
  }, []);

  return (
    <>
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
            dataIndex={row.index}
            fieldName={row.fieldName}
            errorClass={row.rowErrorClass}
            errorMessage={row.error}
            currentValue={JSON.parse(row.currentValue)}
            previousValue={JSON.parse(row.previousValue)}
            disableWrite={row.disableWrite}
            disableDelete={row.disableDelete}
            curValueDateToggle={row.curValueDateToggle}
            arrowTitle={arrowTitle}
            arrowContent={arrowContent}
          />
        );
      })}
    </>
  );
}

export default TabOptionsMeta;
