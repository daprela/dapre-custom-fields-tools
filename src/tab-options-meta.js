import React, { useState, useEffect, useCallback, createRef } from "react";
import OptionsMetaHeaders from "./components/OptionsMetaHeaders.js";
import OptionsMetaRow from "./components/OptionsMetaRow";
import { nameSpace, spinnerOff, spinnerOn } from "./functions.js";

const { apiFetch } = wp;

const restBase = "options";
const path = `${nameSpace}/${restBase}`;

function TabOptionsMeta() {
  const [rows, setRows] = useState([]);
  const [form, setForm] = useState([]);
  const [resetPage, setResetPage] = useState(false);
  const submitOptionsButtonRef = createRef();
  let read = [];
  let write = [];
  let del = [];
  let finishedRead = false;
  let finishedWrite = false;
  let finishedDel = false;

  /* Refresh the content of the meta rows after all read/write operations are finished */
  function refreshPage() {
    if (!finishedRead || !finishedWrite || !finishedDel) return;
    const newOptions = read.concat(write).concat(del);
    const sortedOptions = newOptions.sort((a, b) => {
      if (a.index < b.index) return -1;
      return 1;
    });
    setRows(sortedOptions);
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
  function submitOptions(options) {
    const readLocal = [];
    const writeLocal = [];
    const delLocal = [];
    let newRead = {};
    let newWrite = {};
    let newDel = {};

    options.forEach((option) => {
      if (option.action === "read") {
        newRead = {
          index: option.index,
          optionName: option.optionName,
        };
        readLocal.push(newRead);
      } else if (option.action === "write") {
        newWrite = {
          index: option.index,
          optionName: option.optionName,
          emptyArray: option.emptyArray,
          valueToWrite: option.valueToWrite,
        };
        writeLocal.push(newWrite);
      } else if (option.action === "delete") {
        newDel = {
          index: option.index,
          optionName: option.optionName,
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
    submitOptions(form);
  }

  /* Initializes the form at the first page load.
   * Gets the array containing the previous options stored in the DB */
  useEffect(() => {
    const readJSON = JSON.stringify("all");

    /* Launches the Rest request to read fields */
    apiFetch({
      path: `${path}?all_options=${readJSON}`,
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
            optionName: row.fieldName,
            emptyArray: "",
            action: "read",
            valueToWrite: "",
          };
        });
        setForm(formTemp);
        // After we get the previous options from the DB we must refresh them to get their current value.
        // The state variable 'form' is available only at the next refresh so in order to avoid passing an empty parameter
        // now we have to pass the current variable
        submitOptions(formTemp);
      });
  }, []);

  /* Called from downstream when the user manipulates the form fields.
   * This form is what is sent to the server when the user clicks the 'read/write values' button */
  const updateForm = useCallback(
    (formRow) => {
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
    },
    [form]
  );

  /* Regenerates the form array when meta rows are added or removed */
  function regenerateForm(newRows) {
    const newForm = new Array(newRows.length);
    newRows.forEach((row, index) => {
      newForm[index] = {
        index: row.index,
        optionName: row.optionName,
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
        fieldID: false,
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
      <div className="js-optionsMetaSection o-meta">
        <OptionsMetaHeaders className="c-optionsMetaHeaders" />
        {rows.map((row, index) => {
          let arrowTitle = "Add another row";
          let arrowContent = "+";
          if (index + 1 < rows.length) {
            arrowTitle = "Remove the next row";
            arrowContent = "-";
          }
          return (
            <OptionsMetaRow
              className="js-optionFieldDataRow"
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
              arrowClick={addRemoveMetaRows}
              rowChange={updateForm}
              resetPage={resetPage}
              restoreEvent={restoreEvent}
            />
          );
        })}
      </div>
      <input
        className="js-submitOptions c-metaSubmitButton button button-primary"
        type="submit"
        name="submit_options"
        value="Read/Write Values"
        ref={submitOptionsButtonRef}
        onClick={requestSubmit}
      />
    </>
  );
}

export default TabOptionsMeta;
