import React from 'react';
/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */

export default class MetaFieldError extends React.Component {
  render() {
    const {
      className, messageClassName, errorMessage,
    } = this.props;
    return (
      <div className={className}>
        <p
          className={messageClassName}
        >
          {errorMessage}
        </p>
      </div>
    );
  }
}
