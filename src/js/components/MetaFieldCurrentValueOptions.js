/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */
import React, { useEffect, useState } from 'react';
import { isDate } from '../functions.js';

function MetaFieldCurrentValueOptions({
  className, dataIndex, currentValue, onChange,
}) {
  const [curValueDateToggle, setCurValueDateToggle] = useState('is-visible');

  useEffect(() => {
    if (isDate(currentValue)) {
      setCurValueDateToggle('is-visible');
    } else {
      setCurValueDateToggle('is-hidden');
    }
  }, [currentValue]);

  return (
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
          onChange={onChange}
        />
      </label>
    </div>
  );
}

export default MetaFieldCurrentValueOptions;
