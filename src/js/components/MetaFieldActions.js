/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import React, { useEffect, useState } from 'react';

function MetaFieldActions({ className, dataIndex, onChange: onChangeProp }) {
  const [action, setAction] = useState('read');
  const [readChecked, setReadChecked] = useState(true);
  const [writeChecked, setWriteChecked] = useState(false);
  const [deleteChecked, setDeleteChecked] = useState(false);
  // const {
  //   className, dataIndex,
  // } = props;

  function actionSet(e) {
    setAction(e.target.value);
    onChangeProp(e);
  }

  useEffect(() => {
    if (action === 'read') {
      setReadChecked(true);
      setWriteChecked(false);
      setDeleteChecked(false);
    }
    if (action === 'write') {
      setReadChecked(false);
      setWriteChecked(true);
      setDeleteChecked(false);
    }
    if (action === 'delete') {
      setReadChecked(false);
      setWriteChecked(false);
      setDeleteChecked(true);
    }
  }, [action]);

  return (
    <div className={className}>
      <label>
        <input
          className="js-fieldAction"
          type="radio"
          name={`field_action[${dataIndex}]`}
          value="read"
          onChange={actionSet}
          checked={readChecked}
        />
        Read
      </label>
      <label>
        <input
          className="js-fieldAction"
          type="radio"
          name={`field_action[${dataIndex}]`}
          value="write"
          onChange={actionSet}
          checked={writeChecked}
        />
        Write
      </label>
      <label>
        <input
          className="js-fieldAction"
          type="radio"
          name={`field_action[${dataIndex}]`}
          value="delete"
          onChange={actionSet}
          checked={deleteChecked}
        />
        Delete
      </label>
    </div>
  );
}

export default MetaFieldActions;
