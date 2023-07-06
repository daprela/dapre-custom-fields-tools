import { nameSpace, spinnerOff } from './functions';

const { apiFetch } = wp;

const restBase = 'options';
const path = `${nameSpace}/${restBase}`;

/* Refresh the content of the meta rows after all read/write operations are finished */
export function refreshPage() {
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
export function readData(dataToRead) {
  if (dataToRead.length === 0) {
    finishedRead = true;
    return;
  }

  const readJSON = JSON.stringify(dataToRead);
  finishedRead = false;

  /* Launches the Rest request to read fields */
  apiFetch({
    path: `${path}?${restBase}=${readJSON}`,
    method: 'GET',
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
export function writeData(dataToWrite) {
  if (dataToWrite.length === 0) {
    finishedWrite = true;
    return;
  }

  const writeJSON = JSON.stringify(dataToWrite);
  finishedWrite = false;
  /* Launches the Rest request to write fields */
  apiFetch({
    path,
    method: 'POST',
    body: writeJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
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
export function deleteData(dataToDelete) {
  if (dataToDelete.length === 0) {
    finishedDel = true;
    return;
  }

  const delJSON = JSON.stringify(dataToDelete);
  finishedDel = false;

  /* Launches the Rest request to delete fields */
  apiFetch({
    path,
    method: 'DELETE',
    body: delJSON,
    parse: false,
    headers: {
      'Content-type': 'application/json',
    },
  })
    .then((response) => response.json())
    .then((fields) => {
      finishedDel = true;
      del = fields;
      refreshPage();
    });
}
