import React, { useState, useEffect, useCallback, createRef } from "react";
import UsersMetaHeaders from "./components/UsersMetaHeaders.js";
import UsersMetaRow from "./components/UsersMetaRow";
import { nameSpace, spinnerOff, spinnerOn } from "./functions.js";

const { apiFetch } = wp;

const restBase = "user_fields";
const path = `${nameSpace}/${restBase}`;

function TabUsersMeta() {
  const [rows, setRows] = useState([]);
  const [form, setForm] = useState([]);
  const [resetPage, setResetPage] = useState(false);
  const submitUsersButtonRef = createRef();
  let read = [];
  let write = [];
  let del = [];
  let finishedRead = false;
  let finishedWrite = false;
  let finishedDel = false;

  /* Refresh the content of the meta rows after all read/write operations are finished */
  function refreshPage() {
    if (!finishedRead || !finishedWrite || !finishedDel) return;
    const newUsersMeta = read.concat(write).concat(del);
    const sortedUsers = newUsersMeta.sort((a, b) => {
      if (a.index < b.index) return -1;
      return 1;
    });
    setRows(sortedUsers);
    setResetPage(true);
    spinnerOff();
  }

  /* Makes the API request for the 'read' action */
  function readData(dataToRead) {
    if (dataToRead.length === 0) {
      finishedRead = true;
      return;
    }

    const readJSON = JSON.stringify(dataToRead);
    finishedRead = false;

    /* Launches the Rest request to read fields */
    apiFetch({
      path: `${path}?${restBase}=${readJSON}`,
      method: "GET",
      parse: false,
    })
      .then((response) => response.json())
      .then((fields) => {
        finishedRead = true;
        read = fields;
        refreshPage();
      });
  }

  /* Makes the API request for the 'write' action */
  function writeData(dataToWrite) {
    if (dataToWrite.length === 0) {
      finishedWrite = true;
      return;
    }

    const writeJSON = JSON.stringify(dataToWrite);
    finishedWrite = false;
    /* Launches the Rest request to write fields */
    apiFetch({
      path,
      method: "POST",
      body: writeJSON,
      parse: false,
      headers: {
        "Content-type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((fields) => {
        finishedWrite = true;
        write = fields;
        refreshPage();
      });
  }

  /* Makes the API request for the 'delete' action */
  function deleteData(dataToDelete) {
    if (dataToDelete.length === 0) {
      finishedDel = true;
      return;
    }

    const delJSON = JSON.stringify(dataToDelete);
    finishedDel = false;

    /* Launches the Rest request to delete fields */
    apiFetch({
      path,
      method: "DELETE",
      body: delJSON,
      parse: false,
      headers: {
        "Content-type": "application/json",
      },
    })
      .then((response) => response.json())
      .then((fields) => {
        finishedDel = true;
        del = fields;
        refreshPage();
      });
  }

  /* Called when the submit button is clicked. Launches the read, write and delete operations */
  function submitUsersMeta(users) {
    const readLocal = [];
    const writeLocal = [];
    const delLocal = [];
    let newRead = {};
    let newWrite = {};
    let newDel = {};

    users.forEach((user) => {
      if (user.action === "read") {
        newRead = {
          index: user.index,
          userID: user.userID,
          fieldName: user.fieldName,
        };
        readLocal.push(newRead);
      } else if (user.action === "write") {
        newWrite = {
          index: user.index,
          userID: user.userID,
          fieldName: user.fieldName,
          emptyArray: user.emptyArray,
          valueToWrite: user.valueToWrite,
        };
        writeLocal.push(newWrite);
      } else if (user.action === "delete") {
        newDel = {
          index: user.index,
          userID: user.userID,
          fieldName: user.fieldName,
        };
        delLocal.push(newDel);
      }
    });

    spinnerOn();

    finishedRead = false;
    readData(readLocal);

    finishedWrite = false;
    writeData(writeLocal);

    finishedDel = false;
    deleteData(delLocal);
  }

  function requestSubmit(e) {
    e.preventDefault();
    submitUsersMeta(form);
  }

  /* Initializes the form at the first page load.
   * Gets the array containing the previous options stored in the DB */
  useEffect(() => {
    const readJSON = JSON.stringify("all");

    /* Launches the Rest request to read fields */
    apiFetch({
      path: `${path}?all_fields=${readJSON}`,
      method: "GET",
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
            userID: row.fieldID,
            fieldName: row.fieldName,
            emptyArray: "",
            action: "read",
            valueToWrite: "",
          };

          if (!row.fieldID || !row.fieldName) {
            row.disableWrite = true;
            row.disableDelete = true;
          }
        });
        setForm(formTemp);
        // After we get the previous options from the DB we must refresh them to get their current value.
        // The state variable 'form' is available only at the next refresh so in order to avoid passing an empty parameter
        // now we have to pass the current variable
        submitUsersMeta(formTemp);
      });
  }, []);

  /* Called from downstream when the user manipulates the form fields.
   * This form is what is sent to the server when the user clicks the 'read/write values' button */
  const updateForm = useCallback(
    (formRow) => {
      const temp = {
        index: formRow.index,
        userID: formRow.fieldID,
        fieldName: formRow.fieldName,
        emptyArray: formRow.emptyArray,
        action: formRow.action,
        valueToWrite: formRow.valueToWrite,
      };
      const formTemp = form;
      formTemp[formRow.rowIndex] = temp;
      setForm(formTemp);
    },
    [form]
  );

  /* Regenerates the form array when meta rows are added or removed */
  function regenerateForm(newRows) {
    const newForm = new Array(newRows.length);
    newRows.forEach((row, index) => {
      newForm[index] = {
        index: row.index,
        userID: row.fieldID,
        fieldName: row.fieldName,
        emptyArray: row.emptyArray,
        action: row.action,
        valueToWrite: row.valueToWrite,
      };
    });
    setForm(newForm);
  }

  /* After a REST request is made to the backend, the state variable 'resetPage' restores */
  function restoreEvent() {
    setResetPage(false);
  }

  function addRemoveMetaRows(index, content) {
    const updateBase = `${restBase}/update`;
    const newPath = `${nameSpace}/${updateBase}`;

    if (content === "+") {
      const lastElement = rows[rows.length - 1];
      const newIndex = parseInt(lastElement.index, 10) + 1;
      const newRow = {
        fieldID: 0,
        fieldName: "",
        index: newIndex,
        currentValueDateToggle: "is-hidden",
        currentValue: JSON.stringify(""),
        disableDelete: true,
        disableWrite: true,
        error: "",
        previousValue: JSON.stringify(""),
        rowErrorClass: "",
      };
      const addJSON = JSON.stringify(newIndex);
      spinnerOn();
      /* TODO submitOptionsButtonRef is empty: WHY??? */
      // submitOptionsButtonRef.current.disabled = true;

      /* Launches the Rest request to write fields */
      apiFetch({
        path: newPath,
        method: "POST",
        body: addJSON,
        parse: false,
        headers: {
          "Content-type": "application/json",
        },
      })
        .then((response) => response.json())
        .then((fields) => {
          // submitOptionsButtonRef.current.disabled = false;
          // submitOptionsButtonRef.current.blur();
          spinnerOff();
          if (!fields.error) {
            setRows([...rows, newRow]);
            regenerateForm([...rows, newRow]);
          }
        });
    } else {
      // Remove the selected row from the array
      const rowIndex = rows.findIndex(
        (row) => row.index === parseInt(index, 10)
      );
      rows.splice(rowIndex, 1);

      const removeJSON = JSON.stringify(rows);
      spinnerOn();
      // submitUserButton.disabled = true;

      /* Launches the Rest request to write fields */
      apiFetch({
        path: newPath,
        method: "DELETE",
        body: removeJSON,
        parse: false,
        headers: {
          "Content-type": "application/json",
        },
      })
        .then((response) => response.json())
        .then((fields) => {
          // submitUserButton.disabled = false;
          // submitUserButton.blur();
          spinnerOff();
          if (!fields.error) {
            setRows([...rows]);
            regenerateForm(rows);
          }
        });
    }
  }

  return (
    <>
      <div className="js-userFieldsSection o-meta">
        <UsersMetaHeaders className="c-metaFieldsHeaders" />
        {rows.map((row, index) => {
          let arrowTitle = "Add another row";
          let arrowContent = "+";
          if (index + 1 < rows.length) {
            arrowTitle = "Remove the next row";
            arrowContent = "-";
          }
          return (
            <UsersMetaRow
              className="js-userFieldsDataRow"
              rowIndex={index}
              dataIndex={row.index}
              fieldID={row.fieldID}
              fieldName={row.fieldName}
              errorClass={row.rowErrorClass}
              errorMessage={row.error}
              currentValue={JSON.parse(row.currentValue)}
              previousValue={JSON.parse(row.previousValue)}
              disableWrite={row.disableWrite}
              disableDelete={row.disableDelete}
              arrowTitle={arrowTitle}
              arrowContent={arrowContent}
              arrowClick={addRemoveMetaRows}
              rowChange={updateForm}
              resetPage={resetPage}
              restoreEvent={restoreEvent}
            />
          );
        })}
      </div>
      <input
        className="js-submitUserFields c-metaSubmitButton button button-primary"
        type="submit"
        name="submit_user_fields"
        value="Read/Write Values"
        ref={submitUsersButtonRef}
        onClick={requestSubmit}
      />
    </>
  );
}

export default TabUsersMeta;
