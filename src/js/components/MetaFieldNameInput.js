/* eslint-disable react/jsx-filename-extension,react/react-in-jsx-scope,react/prop-types,no-undef,react/prefer-stateless-function */
/* eslint-disable import/extensions */

function MetaFieldNameInput(props) {
  const {
    className, type, name, value,
  } = props;
  return (
    <input
      className={className}
      type={type}
      name={name}
      value={value}
    />
  );
}

export default MetaFieldNameInput;
