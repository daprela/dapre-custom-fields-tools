/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import React, { useEffect, useRef, useState } from 'react';

function MetaFieldActions({
  className, dataIndex, onChange: onChangeProp, resetPage, restoreEvent, errorMessage, disableWrite, disableDelete,
}) {
  const [action, setAction] = useState('read');
  const [readChecked, setReadChecked] = useState(true);
  const [writeChecked, setWriteChecked] = useState(false);
  const [deleteChecked, setDeleteChecked] = useState(false);
  const readRef = useRef();
  const writeRef = useRef();
  const delRef = useRef();

  function actionSet(e) {
    setAction(e.target.value);
    onChangeProp(e);
  }

  useEffect(() => {
    if (resetPage) {
      readRef.current.click();
      restoreEvent();
    }
  }, [resetPage, restoreEvent]);

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

  useEffect(() => {
    if (disableWrite) {
      writeRef.current.disabled = true;
    } else {
      writeRef.current.disabled = false;
    }
    if (disableDelete) {
      delRef.current.disabled = true;
    } else {
      delRef.current.disabled = false;
    }

    if (errorMessage === 'This field does not exist.') {
      delRef.current.disabled = true;
    }

    if (errorMessage === 'This user does not exist.' || errorMessage === 'This post does not exist.') {
      writeRef.current.disabled = true;
      delRef.current.disabled = true;
    }
  }, [disableDelete, disableWrite, errorMessage]);

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
          ref={readRef}
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
          ref={writeRef}
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
          ref={delRef}
        />
        Delete
      </label>
    </div>
  );
}

export default MetaFieldActions;
