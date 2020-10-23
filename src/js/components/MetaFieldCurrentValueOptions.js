/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope */
/* eslint-disable react/prop-types */
import React from 'react';

const MetaFieldCurrentValueOptions = ({ className, curValueDateToggle, dataIndex }) => (
  <div
    className={className}
  >
    <label
      className={`js-curValueDateToggle c-optionField__fieldCurValueOption ${curValueDateToggle}`}
    >
      <p>Toggle date string/timestamp</p>
      <input
        className="js-fieldDateStringCurValue"
        type="checkbox"
        name={`date_string_show[${dataIndex}]`}
        value=""
      />
    </label>
    <input
      className="js-fieldValueTimestampBackup"
      type="hidden"
      name="value-timestamp-backup"
      value=""
    />
    <input
      className="js-fieldValueStringBackup"
      type="hidden"
      name="value-string-backup"
      value=""
    />
  </div>
);

export default MetaFieldCurrentValueOptions;
